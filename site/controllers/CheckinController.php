<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\models\User;
use common\models\UserOption;
use common\models\Question;
use site\models\CheckinForm;
use site\models\QuestionForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
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
      // delete the old data, we only store one data set per day
      $options = array_merge((array)$form->options1, (array)$form->options2, (array)$form->options3, (array)$form->options4, (array)$form->options5, (array)$form->options6, (array)$form->options7);
      $options = array_filter($options); // strip out false values
      $form->compiled_options = $options;

      if(sizeof($form->compiled_options) === 0) {
        return $this->redirect(['checkin/view'], 200);
      }

      $date = User::getLocalDate();
      $utc_start_time = User::convertLocalTimeToUTC($date." 00:00:00");
      $utc_end_time = User::convertLocalTimeToUTC($date." 23:59:59");
      UserOption::deleteAll("user_id=:user_id 
        AND date > :start_date 
        AND date < :end_date", 
[
  "user_id" => Yii::$app->user->id, 
  ':start_date' => $utc_start_time, 
  ":end_date" => $utc_end_time
]
             );

$form->save();

// delete cached scores
Yii::$app->cache->delete("scores_of_last_month_".Yii::$app->user->id."_".User::getLocalDate());

Yii::$app->session->setFlash('success', 'Answer the questions below to compete your checkin.');
return $this->redirect(['checkin/questions'], 200);
    } else {
      $categories = Category::find()->asArray()->all();
      $options = Option::find()->asArray()->all();
      $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");
      return $this->render('index', [
        'categories' => $categories,
        'model' => $form,
        'optionsList' => $optionsList
      ]);
    }
  }

  public function actionQuestions()
  {
    $user_options = UserOption::getUserOptionsWithCategory(User::getLocalDate("UTC"), true);
    if(!$user_options)
      return $this->redirect(['checkin/view'], 200);

    $form = new QuestionForm();
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      $date = User::getLocalDate();
      $utc_start_time = User::convertLocalTimeToUTC($date." 00:00:00");
      $utc_end_time = User::convertLocalTimeToUTC($date." 23:59:59");
      Question::deleteAll("user_id=:user_id 
        AND date > :start_date 
        AND date < :end_date", 
[
  "user_id" => Yii::$app->user->id, 
  ':start_date' => $utc_start_time, 
  ":end_date" => $utc_end_time
]
            );
$result = $form->saveAnswers();

$user = User::findOne([
  'status' => User::STATUS_ACTIVE,
  'email' => Yii::$app->user->identity->email,
]);
$score = UserOption::calculateScoreByUTCRange($utc_start_time, $utc_end_time);
if(!is_null($user->email_threshold) && $score > $user->email_threshold) {
  $user->sendEmailReport($user->getLocalDate());
  Yii::$app->session->setFlash('warning', 'Your checkin is complete. A notification has been sent to your report partners because of your high score. Reach out to them!');
} else {
  Yii::$app->session->setFlash('success', 'Your emotions have been logged!');
}

if($result) {
  return $this->redirect(['checkin/view'], 200);
}
    }

    return $this->render('questions', [
      'model' => $form,
      'options' => $user_options
    ]);	

  }

  public function actionView($date = null)
  {
    if(is_null($date))
      $date = User::getLocalDate();

    $past_checkin_dates = UserOption::getPastCheckinDates();
    $questions = User::getUserQuestions($date);
    $user_options = User::getUserOptions($date);
    //var_dump($user_options); exit();

    $form = new CheckinForm();
    foreach($user_options as $category_id => $category_data) {
      foreach($category_data['options'] as $option) {
        $attribute = "options$category_id";
        $form->{$attribute}[] = $option['id'];
      }
    }   

    $categories = Category::find()->asArray()->all();

    $options = Option::find()->asArray()->all();
    $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

    $utc_start_time = User::convertLocalTimeToUTC($date." 00:00:00");
    $utc_end_time = User::convertLocalTimeToUTC($date." 23:59:59");
    $utc_date = User::convertLocalTimeToUTC($date);
    $score = UserOption::calculateScoreByUTCRange($utc_start_time, $utc_end_time);

    return $this->render('view', [
      'model' => $form,
      'categories' => $categories, 
      'optionsList' => $optionsList, 
      'actual_date' => $date, 
      'utc_date' => $utc_date, 
      'score' => $score, 
      'past_checkin_dates' => $past_checkin_dates,
      'questions' => $questions
    ]);
  }

  public function actionReport() {
    $query = new Query;
    $query->params = [":user_id" => Yii::$app->user->id];
    $query->select("o.id as id, o.name as name, c.name as category, COUNT(o.id) as count")
      ->from('user_option_link l')
      ->join("INNER JOIN", "option o", "l.option_id = o.id")
      ->join("INNER JOIN", "category c", "o.category_id = c.id")
      ->groupBy('o.id, l.user_id, c.name')
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

    return $this->render('report', [
      'top_options' => $user_rows,
      'answer_pie' => $answer_pie,
      'scores' => $scores
    ]);
  }
}
