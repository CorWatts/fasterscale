<?php

namespace common\models;

use Yii;
use common\models\UserOption;
use common\models\User;
use common\components\Time;
use yii\db\Query;
use \DateTime;
use \DateTimeZone;
use yii\db\Expression;
use \SVGGraph;

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
class UserOption extends \yii\db\ActiveRecord
{
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
      'id' => 'ID',
      'user_id' => 'User ID',
      'option_id' => 'Option ID',
      'date' => 'Date',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getOption()
  {
    return $this->hasOne(Option::className(), ['id' => 'option_id']);
  }

  public static function getPastCheckinDates()
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
      $past_checkin_dates[] = Time::convertUTCToLocal($temp_date['date'], false);
    }

    return $past_checkin_dates;
  }

  public static function getUserOptionsWithCategory($checkin_date, $exclude_1s = false) {
    list($start, $end) = Time::getUTCBookends($checkin_date);

    $query = new Query;
    $query->select('l.id as user_option_id, c.id as category_id, c.name as category_name, o.id as option_id, o.name as option_name')
      ->from('user_option_link l')
      ->innerJoin('option o', 'l.option_id=o.id')
      ->innerJoin('category c', 'o.category_id=c.id')
      ->orderBy('c.id')
      ->where("l.user_id=:user_id
          AND l.date > :start_date
          AND l.date < :end_date",
      [
        ":user_id" => Yii::$app->user->id, 
        ":start_date" => $start, 
        ":end_date" => $end
      ]);

    if($exclude_1s)
      $query->andWhere("c.id <> 1");

    $user_options = $query->all();
    return $user_options;
  }

  public static function getBehaviorsByDate($start, $end) {

      $user_options = UserOption::find()
        ->select(['id', 'user_id', 'option_id', 'date'])
        ->where(
          "user_id=:user_id AND date > :start_date AND date < :end_date",
          ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end]
        )
        ->orderBy('date')
        ->with('option')
        ->asArray()
        ->all();

      return $user_options;
  }

  /**
   * @date date string in yyyy-mm-dd format
   * @return int score
   */
  public static function calculateScoreByUTCRange($start, $end) {
    $behaviors = self::getBehaviorsByDate($start, $end);
    $scores = self::calculateScore($behaviors);

    return $scores;
  }

  public static function calculateScoresOfLastMonth() {
    $key = "scores_of_last_month_".Yii::$app->user->id."_".Time::getLocalDate();
    $scores = Yii::$app->cache->get($key);
    if($scores === false) {
      $scores = [];

      $dt = new DateTime("now", new DateTimeZone("UTC"));
      $end = $dt->format("Y-m-d H:i:s");
      $start = $dt->modify("-1 month")->format("Y-m-d H:i:s");

      $behaviors = self::getBehaviorsByDate($start, $end);
      $scores = self::calculateScore($behaviors);

      Yii::$app->cache->set($key, $scores, 60*60*24);
    }

    return $scores;
  }

  public static function calculateScore($selected_opts, $all_opts = null) {
    if(!!!$selected_opts) {
      return [];
    }

    if(!!!$all_opts) {
      $all_opts = Option::getAllOptionsByCategory();
    }

    $local_opts = [];
    foreach($selected_opts as $user_option) {
      $local_opts[Time::convertUTCToLocal($user_option['date'])][] = $user_option['option'];
    }

    $scores = [];
    foreach($local_opts as $date => $options) {
      foreach($options as $option) {
        $options_by_category[$option['category_id']][] = $option['id'];
      }

      foreach($all_opts as $options) {
        if(array_key_exists($options['category_id'], $options_by_category)) {
          $stats[$options['category_id']] = $options['weight'] * (count($options_by_category[$options['category_id']]) / $options['option_count']);
        } else {
          $stats[$options['category_id']] = 0;
        }
      }

      $sum = 0;
      $count = 0;
      foreach($stats as $stat) {
        $sum += $stat;

        if($stat > 0)
          $count += 1;
      }

      unset($stats);
      unset($options_by_category);

      $avg = ($count > 0) ? $sum / $count : 0;

      $scores[$date] = round($avg * 100);
    }

    return $scores;
  }

  public static function generateScoresGraph() {
    $values = UserOption::calculateScoresOfLastMonth();

    $settings = array(
      'back_colour'       => '#fff',    'stroke_colour'      => 'blue',
      'back_stroke_width' => 0,         'back_stroke_colour' => '#eee',
      'axis_colour'       => '#333',    'axis_overlap'       => 2,
      'axis_font'         => 'Georgia', 'axis_font_size'     => 10,
      'grid_colour'       => '#666',    'label_colour'       => '#000',
      'pad_right'         => 20,        'pad_left'           => 20,
      'link_base'         => '/',       'link_target'        => '_top',
      'fill_under'        => true,
      'fill_opacity'      => 0.5,
      'marker_size'       => 3,			'auto_fit'			 => true,
      'marker_type'       => array('circle', 'square'),
      'marker_colour'     => array('blue'),
      'show_grid'         => true
    );


    $graph = new SVGGraph(600, 300, $settings);

    $graph->Values($values);
    return $graph->Fetch('LineGraph');
  }
}
