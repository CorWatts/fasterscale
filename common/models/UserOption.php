<?php

namespace common\models;

use Yii;
use common\models\UserOption;
use common\models\User;
use yii\db\Query;
use \DateTime;
use \DateTimeZone;
use yii\db\Expression;
use JpGraph\JpGraph;

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

    /**
     * @date date string in yyyy-mm-dd format
     * @return int score
     */
    public static function calculateScoreByUTCRange($start, $end)
    {
        $user_options = UserOption::find()->where("user_id=:user_id AND date > :start_date AND date < :end_date", ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end])->with('option')->asArray()->all();

        $score = 0;

        if($user_options) {
            foreach($user_options as $option) {
                $user_options_by_category[$option['option']['category_id']][] = $option['option_id'];
            }

            $category_options = Option::getAllOptionsByCategory();

            foreach($category_options as $options) {
                if(array_key_exists($options['category_id'], $user_options_by_category))
                    $stats[$options['category_id']] = $category_options[$options['category_id']]['weight'] * (count($user_options_by_category[$options['category_id']]) / $options['option_count']);
                else
                    $stats[$options['category_id']] = 0;
            }

            $sum = 0;
            $count = 0;
            foreach($stats as $stat) {
                $sum += $stat;

                if($stat > 0)
                    $count += 1;
            }

            $avg = ($count > 0) ? $sum / $count : 0;

            $score = round($avg * 100);
        }

        return $score;
    }

    public static function calculateScoresOfLastMonth() {
	$key = "scores_of_last_month_".Yii::$app->user->id."_".User::getLocalDate();
	$scoresByMonth = Yii::$app->cache->get($key);
	if($scoresByMonth === false) {
		$scoresByMonth = [];

		$start = new DateTime("now - 1 month", new DateTimeZone("UTC"));
		$start = $start->format("Y-m-d H:i:s");
		$end = new DateTime("now", new DateTimeZone("UTC"));
		$end = $end->format("Y-m-d H:i:s");

		$user_options = UserOption::find()->select(['id', 'user_id', 'option_id', 'date'])->where("user_id=:user_id AND date > :start_date AND date < :end_date", ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end])->orderBy('date')->with('option')->asArray()->all();

		$options_by_date = [];
		foreach($user_options as $user_option) {
		    $options_by_date[\common\models\User::convertUTCToLocalDate($user_option['date'])][] = $user_option['option'];
		}

		$category_options = Option::getAllOptionsByCategory();

		foreach($options_by_date as $date => $options) {
		    foreach($options as $option) {
			$options_by_category[$option['category_id']][] = $option['id'];
		    }

		    foreach($category_options as $options) {
			if(array_key_exists($options['category_id'], $options_by_category))
			    $stats[$options['category_id']] = $category_options[$options['category_id']]['weight'] * (count($options_by_category[$options['category_id']]) / $options['option_count']);
			else
			    $stats[$options['category_id']] = 0;
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

		    $scoresByMonth[$date] = round($avg * 100);
		}
		Yii::$app->cache->set($key, $scoresByMonth, 60*60*24);
	}

        return $scoresByMonth;
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
            $past_checkin_dates[] = User::convertUTCToLocalDate($temp_date['date']);
        }

        return $past_checkin_dates;
    }

    public static function getUserOptionsWithCategory($checkin_date, $exclude_1s = false) {
        $query = new Query;
        $query->select('l.id as user_option_id, c.id as category_id, c.name as category_name, o.id as option_id, o.name as option_name')
            ->from('user_option_link l')
            ->innerJoin('option o', 'l.option_id=o.id')
            ->innerJoin('category c', 'o.category_id=c.id')
            ->orderBy('c.id')
            ->where(['l.user_id' => Yii::$app->user->id, 'date(l.date)' => $checkin_date]);
        if($exclude_1s)
            $query->andWhere("c.id <> 1");

        $user_options = $query->all();
        return $user_options;
    }

	public static function generateScoresGraph() {
		$values = UserOption::calculateScoresOfLastMonth();

		JpGraph::load();
		JpGraph::module("line");

		ob_start();

		$data = array_values($values);

		// Setup the graph
		$graph = new \Graph(600,500);
		$graph->SetScale("textlin");

		$theme_class=new \UniversalTheme;

		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);
		$graph->title->Set('Last Month\'s Scores');
		$graph->SetBox(false);

		$graph->img->SetAntiAliasing();

		$graph->yaxis->HideZeroLabel();
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");
		$graph->xaxis->SetTickLabels(array_keys($values));
		$graph->xaxis->SetLabelAngle(90);
		$graph->xgrid->SetColor('#E3E3E3');

		// Create the first line
		$p1 = new \LinePlot($data);
		$graph->Add($p1);
		$p1->SetColor("#6495ED");
		$p1->SetLegend('Scores');

		$graph->legend->SetFrameWeight(1);

		// Output line
		$graph->Stroke();

		$img = ob_get_contents();
		ob_end_clean();

		return $img;
	}
}
