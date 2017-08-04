<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\interfaces\UserInterface;
use common\interfaces\UserOptionInterface;
use common\Interfaces\TimeInterface;
use yii\di\Container;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use yii\helpers\ArrayHelper as AH;

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
    $form = Yii::$container->get('\site\models\CheckinForm');
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      $form->compiled_options = $form->compileOptions();

      if(sizeof($form->compiled_options) === 0) {
        return $this->redirect(['view']);
      }

      // we only store one data set per day, so wipe out previously saved ones
      $form->deleteToday();
      $form->save();

      // delete cached scores
      $time = Yii::$container->get('common\interfaces\TimeInterface');
      Yii::$app->cache->delete("scores_of_last_month_".$time->getLocalDate());
      return $this->redirect(['questions']);

    } else {
      return $this->render('index', [
        'categories'  => Category::$categories,
        'model'       => $form,
        'optionsList' => AH::index(Option::$options, null, "category_id")
      ]);
    }
  }

  public function actionQuestions()
  {
    $user_option = Yii::$container->get('common\interfaces\UserOptionInterface');
    $date = Yii::$container
      ->get('common\interfaces\TimeInterface')
      ->getLocalDate();
    $user_options = $user_option->getUserOptionsWithCategory($date);
    if(count($user_options) === 0) {
      return $this->redirect(['view']);
    }

    $form = Yii::$container->get('\site\models\QuestionForm');
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      // we only store one data set per day so clear out any previously saved ones
      $form->deleteToday();

      $behaviors = $user_option->findAll($form->getUserBehaviorIds());
      if($result = $form->saveAnswers($behaviors)) {
        $user = Yii::$container->get('common\interfaces\UserInterface');
        $user = $user->findOne([
          'status' => $user::STATUS_ACTIVE,
          'email' => Yii::$app->user->identity->email,
        ]);

        $score = $user_option->getDailyScore();
        if($user->isOverThreshold($score)) {
          $user->sendEmailReport($date);
          Yii::$app->session->setFlash('warning', 'Your check-in is complete. A notification has been sent to your report partners because of your high score. Reach out to them!');
        } else {
          Yii::$app->session->setFlash('success', 'Your behaviors and processing questions have been logged!');
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
    if(is_null($date)) {
      $date = Yii::$container->get('common\interfaces\TimeInterface')->getLocalDate();
    }

    list($start, $end) = Yii::$container->get('common\interfaces\TimeInterface')->getUTCBookends($date);
    $user = Yii::$container->get('common\interfaces\UserInterface');
    $user_option = Yii::$container->get('common\interfaces\UserOptionInterface');

    $form = Yii::$container->get('\site\models\CheckinForm');
    $form->setOptions($user->getUserOptions($date));

    return $this->render('view', [
      'model'              => $form,
      'actual_date'        => $date,
      'categories'         => Category::$categories,
      'optionsList'        => AH::index(Option::$options, 'name', "category_id"),
      'score'              => $user_option->getDailyScore($date),
      'past_checkin_dates' => $user_option->getPastCheckinDates(),
      'questions'          => $user->getUserQuestions($date),
    ]);
  }

  public function actionReport() {
    $user_option = Yii::$container->get('common\interfaces\UserOptionInterface');
    $user_rows  = $user_option->getTopBehaviors();
    $answer_pie = $user_option->getBehaviorsByCategory();
    $scores     = $user_option->calculateScoresOfLastMonth();

    return $this->render('report', [
      'top_options' => $user_rows,
      'answer_pie' => $answer_pie,
      'scores' => $scores
    ]);
  }
}
