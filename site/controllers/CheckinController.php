<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\models\User;;
use common\models\UserOption;
use site\models\CheckinForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\VarDumper;
use \DateTime;
use \DateTimeZone;

class CheckinController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $form = new CheckinForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $options = array_merge((array)$form->options1, (array)$form->options2, (array)$form->options3, (array)$form->options4, (array)$form->options5, (array)$form->options6, (array)$form->options7);
            $options = array_filter($options);

            // delete the old data, we only store one data set per day
            if(sizeof($options) > 0) {
                UserOption::deleteAll('user_id=:user_id AND date(date)=now()::date', [':user_id' => Yii::$app->user->id]);
            }

            // delete cached scores
            Yii::$app->cache->delete("scores_of_last_month_".Yii::$app->user->id."_".User::getLocalDate());

            foreach($options as $option_id) {
                $user_option = new UserOption;
                $user_option->option_id = $option_id;
                $user_option->user_id = Yii::$app->user->id;
                //$user_option->date = date('Y-m-d H:i:s');
                $user_option->date = new Expression("now()::timestamp");
                $user_option->save();
            }
            Yii::$app->session->setFlash('success', 'Your emotions have been logged!');
            return $this->redirect(['checkin/view'], 200);
        } else {
            $categories = Category::find()->asArray()->all();
            $options = Option::find()->asArray()->all();
            $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");
            return $this->render('index', ['categories' => $categories, 'model' => $form, 'optionsList' => $optionsList]);
        }
    }

    public function actionView($date = null)
    {
        if(is_null($date))
            $date = \common\models\User::getLocalDate();

        $utc_start_time = \common\models\User::convertLocalTimeToUTC($date." 00:00:00");
        $utc_end_time = \common\models\User::convertLocalTimeToUTC($date." 23:59:59");
        $utc_date = \common\models\User::convertLocalTimeToUTC($date);
        $form = new CheckinForm();

        $past_checkin_dates = UserOption::getPastCheckinDates();
        $user_options = UserOption::find()->where("user_id=:user_id AND date > :start_date AND date < :end_date", ["user_id" => Yii::$app->user->id, ':start_date' => $utc_start_time, ":end_date" => $utc_end_time])->with('option')->asArray()->all();
        foreach($user_options as $option) {                                                                                                                         
                $user_options_by_category[$option['option']['category_id']][] = $option['option_id'];
                $attribute = "options".$option['option']['category_id'];
                $form->{$attribute}[] = $option['option_id'];
            }   


        $categories = Category::find()->asArray()->all();

        $options = Option::find()->asArray()->all();
        $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

        $score = UserOption::calculateScoreByUTCRange($utc_start_time, $utc_end_time);

        return $this->render('view', ['model' => $form, 'categories' => $categories, 'optionsList' => $optionsList, 'actual_date' => $date, 'utc_date' => $utc_date, 'score' => $score, 'past_checkin_dates' => $past_checkin_dates]);
    }

    public function actionReport() {
        $query = new Query;
        $query->params = [":user_id" => Yii::$app->user->id];
        $query->select("o.id as id, o.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->groupBy('o.id, l.user_id')
            ->having('l.user_id = :user_id')
            ->orderBy('count DESC')
            ->limit(5);
        $user_rows = $query->all();

        $query2 = new Query;
        $query2->params = [":user_id" => Yii::$app->user->id];
        $query2->select("c.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->join("INNER JOIN", "category c", "o.category_id = c.id")
            ->groupBy('c.id, c.name, l.user_id')
            ->having('l.user_id = :user_id');
        $answer_pie = $query2->all();

       $scores = UserOption::calculateScoresOfLastMonth();

        return $this->render('report', ['top_options' => $user_rows, 'answer_pie' => $answer_pie, 'scores' => $scores]);
    }
}
