<?php
namespace site\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper as AH;
use common\interfaces\BehaviorInterface;

/**
 * Checkin form
 */
class CheckinForm extends Model
{
    public $behaviors1;
    public $behaviors2;
    public $behaviors3;
    public $behaviors4;
    public $behaviors5;
    public $behaviors6;
    public $behaviors7;
    public $custom_behaviors = [];

    public $compiled_behaviors = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      [
        [
          'behaviors1',
          'behaviors2',
          'behaviors3',
          'behaviors4',
          'behaviors5',
          'behaviors6',
          'behaviors7'
        ],
        'validateBehaviors'],
    ];
    }

    public function attributeLabels()
    {
        return [
      'behaviors1' => 'Restoration',
      'behaviors2' => 'Forgetting Priorities',
      'behaviors3' => 'Anxiety',
      'behaviors4' => 'Speeding Up',
      'behaviors5' => 'Ticked Off',
      'behaviors6' => 'Exhausted',
      'behaviors7' => 'Relapsed/Moral Failure'
    ];
    }

    public function setBehaviors($behaviors)
    {
        foreach ($behaviors as $category_id => $bhvr_set) {
            $attribute = "behaviors$category_id";
            $this->$attribute = [];
            foreach ($bhvr_set as $behavior) {
                $this->{$attribute}[] = $behavior['id'];
            }
        }
    }

    public function validateBehaviors($attribute, $params)
    {
        if (!$this->hasErrors()) {
            foreach ($this->$attribute as $behavior) {
                if (!is_numeric($behavior) && !preg_match('/[0-9]{1,2}-custom/', $behavior)) {
                    $this->addError($attribute, 'One of your behaviors is not an integer!');
                }
            }
        }
    }

    /*
     * compileBehaviors()
     *
     * Takes every behavior in all of the local behaviors[1-7] variables,
     * which can include custom behaviors as well as regular behaviors, and
     * splits them out into two local array variables.
     *
     * It returns true or false depending on whether or not there are any behaviors.
     *
     * @return boolean whether or not there were behaviors to compile
     *
     */
    public function compileBehaviors()
    {
        $behaviors = array_merge(
            (array)$this->behaviors1,
            (array)$this->behaviors2,
            (array)$this->behaviors3,
            (array)$this->behaviors4,
            (array)$this->behaviors5,
            (array)$this->behaviors6,
            (array)$this->behaviors7
        );

        $clean_behaviors = array_filter($behaviors); // strip out false values

        // if there are no selected behaviors just return false now
        if (sizeof($clean_behaviors) === 0) {
            return false;
        }

        $self = $this;
        array_walk($clean_behaviors, function ($bhvr) use (&$self) {
            if (preg_match('/.*-custom/', $bhvr)) {
                $self->custom_behaviors[] = $bhvr;
            } else {
                $self->compiled_behaviors[] = $bhvr;
            }
        });

        return true;
    }

    public function deleteToday()
    {
        $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
        $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);

        $date = $time->getLocalDate();
        list($start, $end) = $time->getUTCBookends($date);

        $user_behavior->deleteAll(
            "user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date",
            [
        "user_id" => Yii::$app->user->id,
        ':start_date' => $start,
        ":end_date" => $end
      ]
        );

        // delete cached behaviors
        array_map(function ($period) use ($time) {
            $key = "checkins_".Yii::$app->user->id."_{$period}_".$time->getLocalDate();
            Yii::$app->cache->delete($key);
        }, [30, 90, 180]);
    }

    /**
     * Takes an array of the default behaivors and given behaviors from the user and returns them merged together.
     * This is used for the generation of the CheckinForm behavior list.
     * @param array $user_behaviors an array of UserBehaviors indexed by the category. Typically just the result of self::$getByDate().
     * @return array the two supplied arrays merged together
     */
    public function mergeWithDefault(array $user_behaviors)
    {
        $behaviors = AH::index(Yii::$container->get(BehaviorInterface::class)::$behaviors, 'name', "category_id");
        array_walk($behaviors, function (&$bhvrs, $cat_id) use ($user_behaviors) {
            if (array_key_exists($cat_id, $user_behaviors)) {
                $bhvrs = AH::merge($bhvrs, $user_behaviors[$cat_id]);
            }
        });
        return $behaviors;
    }

    /*
     * getCustomBehaviors()
     *
     * takes the array of strings ($this->custom_behaviors) that
     * looks like ['3-custom', '7-custom'] and converts it to an
     * integer array like [3, 7].
     *
     * @return Array of integers, ids of selected custom behaviors
     */
    public function getCustomBehaviors()
    {
        return array_map(function ($cs) {
            return (int)explode('-', $cs)[0];
        }, $this->custom_behaviors);
    }

    public function save()
    {
        if (empty($this->compiled_behaviors) && empty($this->custom_behaviors)) {
            // maybe we haven't compiled the behaviors yet
            $exit = !!!$this->compileBehaviors();
            if ($exit) {
                return false;
            }
        }

        $custom_bhvrs = \common\models\CustomBehavior::find()
      ->where([
      'user_id' => Yii::$app->user->id,
      'id' => (array)$this->getCustomBehaviors()
    ])
    ->asArray()
    ->all();

        $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);

        $rows = [];
        // this can be condensed....but we are optimizing for readability
        foreach ($this->compiled_behaviors as $behavior_id) {
            $behavior_id = (int)$behavior_id;
            $behavior = \common\models\Behavior::getBehavior('id', $behavior_id);
            $category_id = $behavior['category_id'];
            $temp = [
        Yii::$app->user->id,
        (int)$behavior_id,
        (int)$category_id,
        null,
        new Expression("now()::timestamp")
      ];
            $rows[] = $temp;
        }

        foreach ($custom_bhvrs as $cb) {
            $temp = [
        Yii::$app->user->id,
        null,
        (int)$cb['category_id'],
        $cb['name'],
        new Expression("now()::timestamp")
      ];
            $rows[] = $temp;
        }

        // this batchInsert() method properly escapes the inputted data
        Yii::$app
      ->db
      ->createCommand()
      ->batchInsert(
          $user_behavior->tableName(),
          ['user_id', 'behavior_id', 'category_id', 'custom_behavior', 'date'],
          $rows
      )->execute();

        // if the user has publicised their check-in graph, create the image
        if (Yii::$app->user->identity->expose_graph) {
            $checkins_last_month = $user_behavior->getCheckInBreakdown();

            if ($checkins_last_month) {
                Yii::$container
          ->get(\common\components\Graph::class, [Yii::$app->user->identity])
          ->create($checkins_last_month, true);
            }
        }
    }
}
