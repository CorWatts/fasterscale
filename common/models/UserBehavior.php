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
      [['user_id', 'behavior_id', 'date'], 'required'],
      [['user_id', 'behavior_id'], 'integer'],
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

    $user_behaviors = self::decorateWithCategory($query->all());
    return AH::map($user_behaviors, 'id', function($a) { return $a['behavior']['name']; }, function($b) {return $b['behavior']['category_id']; });
  }

  public function getBehaviorsByDate($start, $end) {
      $uo = $this->find()
        ->select(['id', 'user_id', 'behavior_id', 'date'])
        ->where(
          "user_id=:user_id AND date > :start_date AND date <= :end_date",
          ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end]
        )
        ->orderBy('date')
        ->asArray()
        ->all();

      return self::decorate($uo, false);
  }

  public function getCheckinBreakdown(int $period = 30) {
    $datetimes = $this->time->getDateTimesInPeriod($period);
    $key = "checkins_".Yii::$app->user->id."_{$period}_".$this->time->getLocalDate();
    $checkins = Yii::$app->cache->get($key);

    if($checkins === false) {
      $checkins = [];
      foreach($datetimes as $datetime) {
        $behaviors = self::decorateWithCategory($this->getBehaviorsWithCounts($datetime));
        $checkins[$datetime->format('Y-m-d')] = $this->getBehaviorsByCategory($behaviors);
      }

      Yii::$app->cache->set($key, $checkins, 60*60*24);
    }

    return $checkins;
  }

  /**
   * Returns a list of the most-selected behaviors
   * @param integer $limit the desired number of behaviors
   */
  public function getTopBehaviors(int $limit = 5) {
    return self::decorateWithCategory($this->getBehaviorsWithCounts($limit));
  }

  /**
   * Returns a list of categories and the number of selected behaviors in each category
   * @param array $decorated_behaviors an array of behaviors ran through self::decorateWithCategory()
   */
  public function getBehaviorsByCategory(array $decorated_behaviors) {
    $arr = array_reduce($decorated_behaviors, function($acc, $row) {
      $cat_id = $row['behavior']['category']['id'];
      if(array_key_exists($cat_id, $acc)) {
        $acc[$cat_id]['count'] += $row['count'];
      } else {
        $acc[$cat_id] = [
          'name'      => $row['behavior']['category']['name'],
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

  public static function decorate(array $uo, $with_category = false) {
    foreach($uo as &$o) {
      if($behavior = \common\models\Behavior::getBehavior('id', $o['behavior_id'])) {
        $o['behavior'] = $behavior;
        if($with_category) {
          $o['behavior']['category'] = \common\models\Category::getCategory('id', $o['behavior']['category_id']);
        }
      }
    }
    return $uo;
  }

  public static function decorateWithCategory(array $uo) {
    return self::decorate($uo, true);
  }

  /*
   * Returns a list of the user's behaviors with a count of each behavior's
   * occurence, sorted in descending order of occurrence.
   * @param integer|\DateTime $limit a limit for the number of behaviors to return if an integer is supplied. If a \DateTime object is given, then it returns the 
   */
  public function getBehaviorsWithCounts($limit = null) {
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("user_id, behavior_id, COUNT(id) as count")
      ->from('user_behavior_link')
      ->groupBy('behavior_id, user_id')
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
}
