<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\models\User;
use common\models\UserOption;
use common\models\Question;
use common\components\Time;
use site\models\CheckinForm;
use site\models\QuestionForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;

class CheckinController extends \yii\web\Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['index', 'view', 'questions', 'report'],
        'rules' => [
          [
            'actions' => ['index', 'view', 'questions', 'report'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }
  public function actionIndex() {
    $form = new CheckinForm();
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      $form->compiled_options = $form->compileOptions();

      if(sizeof($form->compiled_options) === 0) {
        return $this->redirect(['view']);
      }

      // we only store one data set per day, so wipe out previously saved ones
      $form->deleteToday();
      $form->save();

      // delete cached scores
      Yii::$app->cache->delete("scores_of_last_month_".Yii::$app->user->id."_".Time::getLocalDate());
      Yii::$app->session->setFlash('success', 'Answer the questions below to compete your checkin.');
      return $this->redirect(['questions']);

    } else {
      $categories = Category::find()->asArray()->all();

      return $this->render('index', [
        'categories' => $categories,
        'model' => $form,
        'optionsList' =>  Option::getAllOptions(),
      ]);
    }
  }

  public function actionQuestions()
  {
    $date = Time::getLocalDate();
    $user_options = UserOption::getUserOptionsWithCategory($date, true);
    if(count($user_options) === 0) {
      return $this->redirect(['view']);
    }

    $form = new QuestionForm();
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      // we only store one data set per day so clear out any previously saved ones
      $form->deleteToday();

      if($result = $form->saveAnswers()) {
        $user = User::findOne([
          'status' => User::STATUS_ACTIVE,
          'email' => Yii::$app->user->identity->email,
        ]);

        $score = UserOption::getDailyScore();
        if($user->isOverThreshold($score)) {
          $user->sendEmailReport($date);
          Yii::$app->session->setFlash('warning', 'Your checkin is complete. A notification has been sent to your report partners because of your high score. Reach out to them!');
        } else {
          Yii::$app->session->setFlash('success', 'Your emotions have been logged!');
        }

        return $this->redirect(['view']);
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
      $date = Time::getLocalDate();

    list($start, $end) = Time::getUTCBookends($date);

    $form = new CheckinForm();
    $form->setOptions(User::getUserOptions($date));

    return $this->render('view', [
      'model'              => $form,
      'actual_date'        => $date,
      'categories'         => Category::find()->asArray()->all(),
      'optionsList'        => Option::getAllOptions(),
      'score'              => UserOption::getDailyScore($date),
      'past_checkin_dates' => UserOption::getPastCheckinDates(),
      'questions'          => User::getUserQuestions($date),
    ]);
  }

  public function actionReport() {
    $user_rows = UserOption::getTopBehaviors();
    $answer_pie = UserOption::getBehaviorsByCategory();

    $scores = UserOption::calculateScoresOfLastMonth();

    return $this->render('report', [
      'top_options' => $user_rows,
      'answer_pie' => $answer_pie,
      'scores' => $scores
    ]);
  }
}
