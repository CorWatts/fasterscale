<?php

namespace site\controllers;

use Yii;
use common\interfaces\UserInterface;
use common\interfaces\UserBehaviorInterface;
use common\interfaces\CategoryInterface;
use common\interfaces\BehaviorInterface;
use common\interfaces\TimeInterface;
use yii\di\Container;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use common\components\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use yii\helpers\ArrayHelper as AH;

class CheckinController extends Controller
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

      return $this->redirect(['questions']);
    } else {
      $behaviors  = Yii::$container->get(BehaviorInterface::class)::$behaviors;
      return $this->render('index', [
        'categories'    => Yii::$container->get(CategoryInterface::class)::$categories,
        'model'         => $form,
        'behaviorsList' => AH::index($behaviors, null, "category_id")
      ]);
    }
  }

  public function actionQuestions()
  {
    $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
    $date = Yii::$container->get(TimeInterface::class)->getLocalDate();

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
    $time = Yii::$container->get(TimeInterface::class);
    $dt = $time->parse($date, $time->now());
    $date = $dt->format('Y-m-d');
    list($start, $end) = $time->getUTCBookends($date);

    $user          = Yii::$container->get(UserInterface::class);
    $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
    $categories    = Yii::$container->get(CategoryInterface::class)::$categories;
    $behaviors     = Yii::$container->get(BehaviorInterface::class)::$behaviors;

    $form = Yii::$container->get(\site\models\CheckinForm::class);
    $form->setBehaviors($user->getUserBehaviors($date));

    return $this->render('view', [
      'model'              => $form,
      'actual_date'        => $date,
      'categories'         => $categories,
      'behaviorsList'      => AH::index($behaviors, 'name', "category_id"),
      'score'              => $user_behavior->getDailyScore($date),
      'past_checkin_dates' => $user_behavior->getPastCheckinDates(),
      'answer_pie'         => $user_behavior->getBehaviorsByCategory($dt),
      'questions'          => $user->getUserQuestions($date),
      'isToday'            => $time->getLocalDate() === $date,
    ]);
  }

  public function actionReport() {
    $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
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
