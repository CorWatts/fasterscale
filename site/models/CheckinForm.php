<?php
namespace site\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use common\interfaces\UserBehaviorInterface;
use common\interfaces\TimeInterface;

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

  public $compiled_behaviors;

  private $user_behavior;
  private $time;

  public function __construct(UserBehaviorInterface $user_behavior, TimeInterface $time, $config = []) {
    $this->user_behavior = $user_behavior;
    $this->time = $time;
    parent::__construct($config);
  }

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

  public function attributeLabels() {
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

  public function setBehaviors($behaviors) {
    foreach($behaviors as $category_id => $category_data) {
      $attribute = "behaviors$category_id";
			$this->$attribute = [];
      foreach($category_data['behaviors'] as $behavior) {
        $this->{$attribute}[] = $behavior['id'];
      }
    }   
  }

  public function validateBehaviors($attribute, $params) {
    if (!$this->hasErrors()) {
      foreach($this->$attribute as $behavior) {
        if(!is_numeric($behavior)) {
          $this->addError($attribute, 'One of your behaviors is not an integer!');
        }
      }
    }
  }

  public function compileBehaviors() {
    $behaviors = array_merge((array)$this->behaviors1,
                           (array)$this->behaviors2,
                           (array)$this->behaviors3,
                           (array)$this->behaviors4,
                           (array)$this->behaviors5,
                           (array)$this->behaviors6,
                           (array)$this->behaviors7);

    return array_filter($behaviors); // strip out false values
  }

  public function deleteToday() {
    $date = $this->time->getLocalDate();
    list($start, $end) = $this->time->getUTCBookends($date);
    $this->user_behavior->deleteAll("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
      [
        "user_id" => Yii::$app->user->id, 
        ':start_date' => $start, 
        ":end_date" => $end
      ]
    );

    // delete cached scores
    $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
    array_map(function($period) {
      $key = "scores_of_last_month_".Yii::$app->user->id."_{$period}_".$this->time->getLocalDate();
      Yii::$app->cache->delete($key);
    }, [30, 90, 180]);
  }

  public function save() {
    if(empty($this->compiled_behaviors)) {
      $this->commpiled_behaviors = $this->compileBehaviors();
    }

    $rows = [];
    foreach($this->compiled_behaviors as $behavior_id) {
      $temp = [
        Yii::$app->user->id,
        (int)$behavior_id,
        new Expression("now()::timestamp")
      ];
      $rows[] = $temp;
    }

    Yii::$app
      ->db
      ->createCommand()
      ->batchInsert(
        $this->user_behavior->tableName(),
        ['user_id', 'behavior_id', 'date'],
        $rows
      )->execute();

    // if the user has publicised their score graph, create the image
    if(Yii::$app->user->identity->expose_graph) {
      $checkins_last_month = $this->user_behavior->getCheckInBreakdown();

      if($checkins_last_month) {
        Yii::$container
          ->get(\common\components\Graph::class, [Yii::$app->user->identity])
          ->create($checkins_last_month, true);
      }
    }
  }
}
