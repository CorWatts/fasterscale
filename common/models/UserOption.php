<?php

namespace common\models;

use Yii;
use \common\interfaces\TimeInterface;
use \common\interfaces\OptionInterface;
use \common\interfaces\UserOptionInterface;
use \common\components\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper as AH;
use \DateTime;
use \DateTimeZone;
use yii\db\Expression;

/**
 * This is the model class for table "user_option_link".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $option_id
 * @property string $date
 *
 * @property Option $user
 */
class UserOption extends ActiveRecord implements UserOptionInterface
{
  private $time;
  private $option;

  public function __construct(OptionInterface $option, TimeInterface $time, $config = []) {
    $this->option = $option;
    $this->time = $time;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'user_option_link';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['user_id', 'option_id', 'date'], 'required'],
      [['user_id', 'option_id'], 'integer'],
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
      'option_id' => 'Option ID',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
  }

  public function getPastCheckinDates()
  {
    $past_checkin_dates = [];
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("date")
      ->from('user_option_link l')
      ->groupBy('date, user_id')
      ->having('user_id = :user_id');
    $temp_dates = $query->all();
    foreach($temp_dates as $temp_date) {
      $past_checkin_dates[] = $this->time->convertUTCToLocal($temp_date['date']);
    }

    return $past_checkin_dates;
  }

  public function getUserOptionsWithCategory($checkin_date) {
    list($start, $end) = $this->time->getUTCBookends($checkin_date);

    $query = new Query;
    $query->select('*')
      ->from('user_option_link')
      ->orderBy('option_id')
      ->where("user_id=:user_id
          AND date > :start_date
          AND date < :end_date",
      [
        ":user_id" => Yii::$app->user->id, 
        ":start_date" => $start, 
        ":end_date" => $end
      ]);

    $user_options = self::decorateWithCategory($query->all());
    return AH::map($user_options, 'id', function($a) { return $a['option']['name']; }, function($b) {return $b['option']['category_id']; });
  }

  public function getDailyScore($date = null) {
    // default to today's score
    if(is_null($date)) $date = $this->time->getLocalDate();

    list($start, $end) = $this->time->getUTCBookends($date);
    $score = $this->calculateScoreByUTCRange($start, $end);
    return reset($score) ?: 0; // get first array item
  }

  public function getBehaviorsByDate($start, $end) {
      $uo = $this->find()
        ->select(['id', 'user_id', 'option_id', 'date'])
        ->where(
          "user_id=:user_id AND date > :start_date AND date <= :end_date",
          ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end]
        )
        ->orderBy('date')
        ->asArray()
        ->all();

      return self::decorate($uo, false);
  }

  /**
   * @date date string in yyyy-mm-dd format
   * @return int score
   */
  public function calculateScoreByUTCRange($start, $end) {
    $behaviors = $this->getBehaviorsByDate($start, $end);
    $scores = $this->calculateScore($behaviors);

    return $scores;
  }

  public function calculateScoresOfLastMonth() {
    $key = "scores_of_last_month_".Yii::$app->user->id."_".$this->time->getLocalDate();
    $scores = Yii::$app->cache->get($key);
    if($scores === false) {
      $scores = [];

      $dt = new DateTime("now", new DateTimeZone("UTC"));
      $end = $dt->format("Y-m-d H:i:s");
      $start = $dt->modify("-1 month")->format("Y-m-d H:i:s");

      $behaviors = $this->getBehaviorsByDate($start, $end);
      $scores = $this->calculateScore($behaviors);

      Yii::$app->cache->set($key, $scores, 60*60*24);
    }

    return $scores;
  }

  public function calculateScore($usr_bhvrs, $all_cats = null) {
    if(!!!$usr_bhvrs) return [];
    if(!!!$all_cats)  $all_cats = $this->option->getCategories();

    $usr_bhvrs = array_reduce($usr_bhvrs, function($carry, $bhvr) {
      $date = $this->time->convertUTCToLocal($bhvr['date']);
      $carry[$date][] = $bhvr['option'];
      return $carry;
    }, []);

    $scores = [];
    foreach($usr_bhvrs as $date => $bhvrs) {
      $picked = array_reduce($bhvrs, function($carry, $bhvr) {
        $carry[$bhvr['category_id']][] = $bhvr['id'];
        return $carry;
      }, []);

      $grades_sum = array_sum($this->_getCatGrades($picked, $all_cats));
      $scores[$date] = intval(floor($grades_sum));
    }

    return $scores;
  }

  private function _getCatGrades($picked, $all_cats = null) {
    if(!!!$all_cats) $all_cats = $this->option->getCategories();

    return array_reduce($all_cats, function($carry, $cat) use($picked) {
      if(array_key_exists($cat['category_id'], $picked)) {
        $count = count($picked[$cat['category_id']]);
        $prcnt_2x = ($count / $cat['option_count']) * 2;
        // because we're doubling the % we want to ensure we don't take more than 100%
        $carry[$cat['category_id']] = $cat['weight'] * min($prcnt_2x, 1);
      } else {
        $carry[$cat['category_id']] = 0;
      }
      return $carry;
    }, []);
  }

  public function getTopBehaviors($limit = 5) {
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("user_id, option_id, COUNT(id) as count")
      ->from('user_option_link')
      ->groupBy('option_id, user_id')
      ->having('user_id = :user_id')
      ->orderBy('count DESC')
      ->limit($limit);
    return self::decorateWithCategory($query->all(), false);
  }

  public function getBehaviorsByCategory() {
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("user_id, option_id, COUNT(id) as count")
      ->from('user_option_link')
      ->groupBy('option_id, user_id')
      ->having('user_id = :user_id')
      ->orderBy('count DESC');

    return array_values(array_reduce(self::decorateWithCategory($query->all(), false), function($acc, $row) {
      $cat_id = $row['option']['category']['id'];
      if(array_key_exists($cat_id, $acc)) {
        $acc[$cat_id]['count'] += $row['count'];
      } else {
        $acc[$cat_id] = [
          'name' => $row['option']['category']['name'],
          'count' => $row['count'],
        ];
      }
      return $acc;
    }, []));
  }

  public static function decorate(Array $uo, $with_category = false) {
    foreach($uo as &$o) {
      if($option = \common\models\Option::getOption('id', $o['option_id'])) {
        $o['option'] = $option;
        if($with_category) {
          $o['option']['category'] = \common\models\Category::getCategory('id', $o['option']['category_id']);
        }
      }
    }
    return $uo;
  }

  public static function decorateWithCategory(Array $uo) {
    return self::decorate($uo, true);
  }
}
