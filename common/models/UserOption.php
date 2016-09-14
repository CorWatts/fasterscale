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
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

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
      return UserOption::find()
        ->select(['id', 'user_id', 'option_id', 'date'])
        ->where(
          "user_id=:user_id AND date > :start_date AND date < :end_date",
          ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end]
        )
        ->orderBy('date')
        ->with('option')
        ->asArray()
        ->all();
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
    $scores = array_values($values);
    $dates = array_map(function($date) {
      return (new \DateTime($date))->format('M j, Y');
    }, array_keys($values));

    $graph = new Graph\Graph(800, 600);
    //$graph->SetImgFormat('jpeg',0);
    $graph->SetImgFormat('png');
    //$graph->SetScale('textlin',0,100);
    $graph->img->SetImgFormat('png');
    $graph->img->SetMargin(60, 60, 40, 160);
    $graph->img->SetAntiAliasing();
    $graph->SetScale("textlin");
    $graph->SetShadow();
    $graph->title->Set(Yii::$app->user->identity->username . "'s Scores for the last month");
    $graph->title->SetFont(FF_ARIAL, FS_BOLD, 20);
    // Use 20% "grace" to get slightly larger scale then min/max of data
    $graph->yscale->SetGrace(10); // remove when new score formula is released
    // Set the angle for the labels to 90 degrees
    $graph->xaxis->SetLabelAngle(45);
    $graph->xaxis->SetTickLabels($dates);
    $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 15);
    $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 15);
    $p1 = new Plot\LinePlot($scores);
    $p1->SetColor("#37b98f");
    $p1->SetFillColor("#92d1b5");
    $p1->mark->SetWidth(8);
    $p1->SetCenter();
    $graph->Add($p1);
    $img = $graph->Stroke(_IMG_HANDLER);

    ob_start();
    imagepng($img);
    $img_data = ob_get_clean();


    return $img_data;
  }
}
