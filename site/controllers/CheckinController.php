<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Behavior;
use common\interfaces\UserInterface;
use common\interfaces\UserBehaviorInterface;
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
        'class' => AccessControl::class,
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
    $form = Yii::$container->get(\site\models\CheckinForm::class);
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      $form->compiled_behaviors = $form->compileBehaviors();

      if(sizeof($form->compiled_behaviors) === 0) {
        return $this->redirect(['view']);
      }

      // we only store one data set per day, so wipe out previously saved ones
      $form->deleteToday();
      $form->save();

      // delete cached scores
      $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
      $key = "scores_of_last_month_".Yii::$app->user->id."_".$time->getLocalDate();
      Yii::$app->cache->delete($key);

      // if the user has publicised their score graph, create the image
      if(Yii::$app->user->identity->expose_graph) {
        $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);
        $scores_last_month = $user_behavior->calculateScoresOfLastMonth();
        if($scores_last_month) {
          Yii::$container
            ->get(\common\components\Graph::class)
            ->create($scores_last_month, true);
        }
      }

      return $this->redirect(['questions']);
    } else {
      $category = Yii::$container->get(\common\interfaces\CategoryInterface::class);
      $behavior = Yii::$container->get(\common\interfaces\BehaviorInterface::class);
      return $this->render('index', [
        'categories'    => $category::$categories,
        'model'         => $form,
        'behaviorsList' => AH::index($behavior::$behaviors, null, "category_id")
      ]);
    }
  }

  public function actionQuestions()
  {
    $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);
    $date = Yii::$container->get(\common\interfaces\TimeInterface::class)->getLocalDate();

    $user_behaviors = $user_behavior->getUserBehaviorsWithCategory($date);
    if(count($user_behaviors) === 0) {
      return $this->redirect(['view']);
    }

    $form = Yii::$container->get(\site\models\QuestionForm::class);
    if ($form->load(Yii::$app->request->post()) && $form->validate()) {
      // we only store one data set per day so clear out any previously saved ones
      $form->deleteToday();

      $behaviors = $user_behavior->findAll($form->getUserBehaviorIds());
      if($result = $form->saveAnswers($behaviors)) {

        $score = $user_behavior->getDailyScore();
        if(Yii::$app->user->identity->isOverThreshold($score)) {
          Yii::$app->user->identity->sendEmailReport($date);
          Yii::$app->session->setFlash('warning', 'Your check-in is complete. A notification has been sent to your report partners because of your high score. Reach out to them!');
        } else {
          Yii::$app->session->setFlash('success', 'Your behaviors and processing questions have been logged!');
        }

        return $this->redirect(['view']);
      }
    }

    return $this->render('questions', [
      'model' => $form,
      'behaviors' => $user_behaviors
    ]);	
  }

  public function actionView(string $date = null)
  {
    $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
    if(is_null($date)) {
      $date = $time->getLocalDate();
    } else if($dt = $time->parse($date)) {
      $date = $dt->format('Y-m-d');
    } else {
      $date = $time->getLocalDate();
    }
    list($start, $end) = $time->getUTCBookends($date);

    $user          = Yii::$container->get(\common\interfaces\UserInterface::class);
    $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);
    $category      = Yii::$container->get(\common\interfaces\CategoryInterface::class);
    $behavior      = Yii::$container->get(\common\interfaces\BehaviorInterface::class);

    $form = Yii::$container->get(\site\models\CheckinForm::class);
    $form->setBehaviors($user->getUserBehaviors($date));

    return $this->render('view', [
      'model'              => $form,
      'actual_date'        => $date,
      'categories'         => $category::$categories,
      'behaviorsList'      => AH::index($behavior::$behaviors, 'name', "category_id"),
      'score'              => $user_behavior->getDailyScore($date),
      'past_checkin_dates' => $user_behavior->getPastCheckinDates(),
      'questions'          => $user->getUserQuestions($date),
      'isToday'            => $time->getLocalDate() === $date,
    ]);
  }

  public function actionReport() {
    $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);
    $user_rows  = $user_behavior->getTopBehaviors();
    $answer_pie = $user_behavior->getBehaviorsByCategory();
    $scores     = $user_behavior->calculateScoresOfLastMonth();

    return $this->render('report', [
      'top_behaviors' => $user_rows,
      'answer_pie' => $answer_pie,
      'scores' => $scores
    ]);
  }
}
