<?php

namespace common\models;

use Yii;
use common\models\UserOption;
use common\models\User;
use common\components\Time;
use yii\db\Query;
use yii\helpers\ArrayHelper as AH;
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
    return $this->hasOne(User::className(), ['id' => 'user_id']);
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

  public static function getUserOptionsWithCategory($checkin_date) {
    list($start, $end) = Time::getUTCBookends($checkin_date);

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

  public static function getDailyScore($date = null) {
    // default to today's score
    if(is_null($date)) $date = Time::getLocalDate();

    list($start, $end) = Time::getUTCBookends($date);
    $score = UserOption::calculateScoreByUTCRange($start, $end);
    return reset($score) ?: 0; // get first array item
  }

  public static function getBehaviorsByDate($start, $end) {
      $uo = UserOption::find()
        ->select(['id', 'user_id', 'option_id', 'date'])
        ->where(
          "user_id=:user_id AND date > :start_date AND date < :end_date",
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

  public static function calculateScore($selected_opts) {
    if(!!!$selected_opts) {
      return [];
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

      foreach(Option::getAllOptionsByCategory() as $options) {
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
    $graph->title->Set(Yii::$app->user->identity->email . "'s scores for the last month");
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

  public static function getTopBehaviors($limit = 5) {
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

  public static function getBehaviorsByCategory() {
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
      if($option = Option::getOption('id', $o['option_id'])) {
        $o['option'] = $option;
        if($with_category) {
          $o['option']['category'] = Category::getCategory('id', $o['option']['category_id']);
        }
      }
    }
    return $uo;
  }

  public static function decorateWithCategory(Array $uo) {
    return self::decorate($uo, true);
  }
}
