<?php

namespace common\models;

use Yii;
use \common\interfaces\TimeInterface;
use \common\interfaces\BehaviorInterface;
use \common\interfaces\UserBehaviorInterface;
use \common\components\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper as AH;
use \DateTime;
use \DateTimeZone;
use yii\db\Expression;

/**
 * This is the model class for table "user_behavior_link".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $behavior_id
 * @property integer $category_id
 * @property string $date
 *
 * @property Behavior $user
 */
class UserBehavior extends ActiveRecord implements UserBehaviorInterface
{
  private $time;
  private $behavior;

  public function __construct(BehaviorInterface $behavior, TimeInterface $time, $config = []) {
    $this->behavior = $behavior;
    $this->time = $time;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'user_behavior_link';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['user_id', 'behavior_id', 'category_id', 'date'], 'required'],
      [['user_id', 'behavior_id', 'category_id'], 'integer'],
      //[['date'], 'string']
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id'        => 'ID',
      'date'      => 'Date',
      'user_id'   => 'User ID',
      'behavior_id' => 'Behavior ID',
      'category_id' => 'Category ID',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
  }

  public function getPastCheckinDates() {
    $past_checkin_dates = [];
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("date")
      ->from('user_behavior_link l')
      ->groupBy('date, user_id')
      ->having('user_id = :user_id');
    $temp_dates = $query->all();
    foreach($temp_dates as $temp_date) {
      $past_checkin_dates[] = $this->time->convertUTCToLocal($temp_date['date']);
    }

    return $past_checkin_dates;
  }

  public function getUserBehaviorsWithCategory($checkin_date) {
    list($start, $end) = $this->time->getUTCBookends($checkin_date);

    $query = new Query;
    $query->select('*')
      ->from('user_behavior_link')
      ->orderBy('behavior_id')
      ->where("user_id=:user_id
          AND date > :start_date
          AND date < :end_date",
      [
        ":user_id" => Yii::$app->user->id, 
        ":start_date" => $start, 
        ":end_date" => $end
      ]);

    $user_behaviors = self::decorate($query->all());
    return AH::map($user_behaviors, 'id', function($a) { return $a['behavior']['name']; }, function($b) {return $b['behavior']['category_id']; });
  }

  public function getCheckinBreakdown(int $period = 30) {
    $datetimes = $this->time->getDateTimesInPeriod($period);
    $key = "checkins_".Yii::$app->user->id."_{$period}_".$this->time->getLocalDate();
    $checkins = Yii::$app->cache->get($key);

    if($checkins === false) {
      $checkins = [];
      foreach($datetimes as $datetime) {
        $behaviors = self::decorate($this->getBehaviorsWithCounts($datetime));
        $checkins[$datetime->format('Y-m-d')] = $this->getBehaviorsByCategory($behaviors);
      }

      Yii::$app->cache->set($key, $checkins, 60*60*24);
    }

    return $checkins;
  }

  /**
   * @param integer $limit the desired number of behaviors
   * @return array a list of the most-selected behaviors
   */
  public function getTopBehaviors(int $limit = 5) {
    return self::decorate($this->getBehaviorsWithCounts($limit));
  }

  /**
   * @param array $decorated_behaviors an array of behaviors ran through self::decorate()
   * @return array a list of categories and the number of selected behaviors in each category
   */
  public function getBehaviorsByCategory(array $decorated_behaviors) {
    $arr = array_reduce($decorated_behaviors, function($acc, $row) {
      $cat_id = $row['category_id'];
      if(array_key_exists($cat_id, $acc)) {
        $acc[$cat_id]['count'] += $row['count'];
      } else {
        $acc[$cat_id] = [
          'name'      => $row['category']['name'],
          'count'     => $row['count'],
          'color'     => \common\models\Category::$colors[$cat_id]['color'],
          'highlight' => \common\models\Category::$colors[$cat_id]['highlight'],
        ];
      }
      return $acc;
    }, []);
    ksort($arr);
    return $arr;
  }

  /*
   * @param integer|\DateTime $limit a limit for the number of behaviors to
   * return if an integer is supplied. If a \DateTime object is given instead,
   * then it adds a start and end date range in the WHERE clause of the query.
   * @return array a list of the user's behaviors with a count of each behavior's
   * occurence, sorted in descending order of occurrence.
   */
  public function getBehaviorsWithCounts($limit = null) {
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("user_id, behavior_id, category_id, COUNT(id) as count")
      ->from('user_behavior_link')
      ->groupBy('behavior_id, category_id, user_id')
      ->having('user_id = :user_id')
      ->orderBy('count DESC');

    if($limit instanceof \DateTime) {
      list($start, $end) = $this->time->getUTCBookends($limit->format('Y-m-d'));
      $query->params += [':start_date' => $start, ':end_date' => $end];
      $query->where('user_id=:user_id AND date > :start_date AND date <= :end_date');
    } else if(is_int($limit)) {
      $query->limit($limit);
    }
    return $query->all();
  }

  /**
   * Returns an array of behaviors selected by the given user on the given date.
   * NOTE: this function is designed to return data in the same format that is returned by using yii\helpers\ArrayHelper::index(\common\models\Behavior::$behaviors, 'name', "category_id"). This facilitates generation of the CheckinForm and allows us to easily merge these two datasets together.
   * @param integer $user_id
   * @param string|null $local_date
   * @return array
   */
  public function getByDate(int $user_id, $local_date = null) {
    if(is_null($local_date)) $local_date = $this->time->getLocalDate();

    $behaviors = $this->getBehaviorData($user_id, $local_date);
    $behaviors = self::decorate($behaviors);
    return $this->parseBehaviorData($behaviors);
  }

  /**
   * Adds the respective Category and Behavior to each UserBehavior in the given array.
   * @param array of UserBehaviors
   * @return an array of decorated UserBehaviors, each with an added Category and Behavior
   */
  public static function decorate(array $uo) {
    foreach($uo as &$o) {
      $behavior = \common\models\Behavior::getBehavior('id', $o['behavior_id']);
      $category = \common\models\Category::getCategory('id', $o['category_id']);
      // if this behavior does not have a valid behavior or category, something
      // is weird and we don't want to do this halfway.
      if($behavior && $category) {
        $o['behavior'] = $behavior;
        $o['category'] = $category;
      }
    }
    return $uo;
  }

  // TODO: this should probably be a private method...but unit testing is hard
  public function getBehaviorData(int $user_id, $local_date) {
    list($start, $end) = $this->time->getUTCBookends($local_date);

    return $this->find()
      ->where("user_id=:user_id
      AND date > :start_date
      AND date < :end_date",
    [
      "user_id" => Yii::$app->user->id,
      ':start_date' => $start,
      ":end_date" => $end
    ])
    ->asArray()
    ->all();
  }

  // TODO: this should probably be a private method...but unit testing is hard
  public function parseBehaviorData($behaviors) {
    if(!$behaviors) return [];

    $bhvrs_by_cat = [];
    foreach($behaviors as $behavior) {
      $indx = $behavior['category_id'];

      $bhvrs_by_cat[$indx][$behavior['behavior']['name']] = [
        "id" => $behavior['behavior_id'],
        "name"=>$behavior['behavior']['name']];
    }

    return $bhvrs_by_cat;
  }
}
