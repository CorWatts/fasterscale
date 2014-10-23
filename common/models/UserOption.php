<?php

namespace common\models;

use Yii;
use common\models\UserOption;
use common\models\User;
use yii\db\Query;
use \DateTime;
use \DateTimeZone;

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
    public function calculateScoreByUTCRange($start, $end)
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

    public function calculateScoresOfLastMonth() {
        $scoresByMonth = [];

        $start = new DateTime("now - 1 month", new DateTimeZone("UTC"));
        $start = $start->format("Y-m-d H:i:s");
        $end = new DateTime("now", new DateTimeZone("UTC"));
        $end = $end->format("Y-m-d H:i:s");

        $user_options = UserOption::find()->select(['id', 'user_id', 'option_id', 'date(date)'])->where("user_id=:user_id AND date > :start_date AND date < :end_date", ["user_id" => Yii::$app->user->id, ':start_date' => $start, ":end_date" => $end])->orderBy('date(date)')->with('option')->asArray()->all();

        $options_by_date = [];
        foreach($user_options as $user_option) {
            $options_by_date[$user_option['date']][] = $user_option['option'];
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

        return $scoresByMonth;
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
            $past_checkin_dates[] = User::convertUTCToLocalDate($temp_date['date']);
        }

        return $past_checkin_dates;
    }
}
