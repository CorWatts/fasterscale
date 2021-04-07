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
        'only' => ['index', 'view', 'questions', 'report', 'history'],
        'rules' => [
          [
            'actions' => ['index', 'view', 'questions', 'report', 'history'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
        ],
        ];
    }
    public function actionIndex()
    {
        $form = Yii::$container->get(\site\models\CheckinForm::class);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (!$form->compileBehaviors()) {
                return $this->redirect(['view']);
            }

            // we only store one data set per day, so wipe out previously saved ones
            $form->deleteToday();
            $form->save();

            return $this->redirect(['questions']);
        }

        $behaviors  = Yii::$container->get(BehaviorInterface::class)::$behaviors;
        $custom = \common\models\CustomBehavior::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all();
        return $this->render('index', [
        'categories'    => Yii::$container->get(CategoryInterface::class)::$categories,
        'model'         => $form,
        'behaviorsList' => AH::index($behaviors, null, "category_id"),
        'customList' => AH::index($custom, null, "category_id")
        ]);
    }

    public function actionQuestions()
    {
        $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
        $date = Yii::$container->get(TimeInterface::class)->getLocalDate();

        $user_behaviors = $user_behavior->getUserBehaviorsWithCategory($date);
        if (count($user_behaviors) === 0) {
            return $this->redirect(['view']);
        }

        $form = Yii::$container->get(\site\models\QuestionForm::class);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            // we only store one data set per day so clear out any previously saved ones
            $form->deleteToday(Yii::$app->user->id);

            $behaviors = $user_behavior->findAll($form->getUserBehaviorIds());
            if ($result = $form->saveAnswers(Yii::$app->user->id, $behaviors)) {
                if (Yii::$app->user->identity->send_email) {
                    if (Yii::$app->user->identity->sendEmailReport($date)) {
                        Yii::$app->session->setFlash('success', 'Your check-in is complete. A notification has been sent to your report partners.');
                    } else {
                        Yii::$app->session->setFlash('success', 'Your check-in is complete.');
                    }
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
        $date = $time->validate($date);
        $dt = $time->parse($date);
        list($start, $end) = $time->getUTCBookends($date);

        $user          = Yii::$container->get(UserInterface::class);
        $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
        $categories    = Yii::$container->get(CategoryInterface::class)::$categories;
        $question      = Yii::$container->get(\common\interfaces\QuestionInterface::class);

        $user_behaviors = $user_behavior->getByDate(Yii::$app->user->id, $date);
        $form = Yii::$container->get(\site\models\CheckinForm::class);

        $form_behaviors = $form->mergeWithDefault($user_behaviors);
        $form->setBehaviors($user_behaviors);

        $raw_pie_data = $user_behavior::decorate($user_behavior->getBehaviorsWithCounts($dt));
        $answer_pie = $user_behavior->getBehaviorsByCategory($raw_pie_data);

        return $this->render('view', [
        'model'              => $form,
        'actual_date'        => $date,
        'categories'         => $categories,
        'behaviorsList'      => $form_behaviors,
        'past_checkin_dates' => $user_behavior->getPastCheckinDates(),
        'answer_pie'         => $answer_pie,
        'questions'          => $question->getByUser(Yii::$app->user->id, $date),
        'isToday'            => $time->getLocalDate() === $date,
        ]);
    }

    public function actionReport()
    {
        /* Pie Chart data */
        $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
        $user_rows     = $user_behavior::decorate($user_behavior->getBehaviorsWithCounts(5));
        $raw_pie_data  = $user_behavior::decorate($user_behavior->getBehaviorsWithCounts());
        $answer_pie    = $user_behavior->getBehaviorsByCategory($raw_pie_data);

        $pie_data   = [
        "labels"   => array_column($answer_pie, "name"),
        "datasets" => [[
          "data"                 => array_map('intval', array_column($answer_pie, "count")),
          "backgroundColor"      => array_column($answer_pie, "color"),
          "hoverBackgroundColor" => array_column($answer_pie, "highlight"),
        ]]
        ];

        /* Bar Chart data */
        ['labels' => $labels, 'datasets' => $datasets] = $this->history(30);

        return $this->render('report', [
        'top_behaviors' => $user_rows,
        'pie_data' => $pie_data,
        'bar_dates' => $labels,
        'bar_datasets' => $datasets,
        ]);
    }

    public function actionHistory($period)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $period = intval($period);
        $whitelist_periods = [30, 90, 180];
        $period = in_array($period, $whitelist_periods) ? $period : 30;
        return $this->history($period);
    }

    private function history($period)
    {
        $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
        $category      = Yii::$container->get(CategoryInterface::class);

        $checkins = $user_behavior->getCheckInBreakdown($period);

        $accum = [];
        foreach ($checkins as $date => $cats) {
            for ($i = 1; $i <= 7; $i++) {
                $accum[$i][] = array_key_exists($i, $cats) ? $cats[$i]['count'] : [];
            }
        }

        $bar_datasets = [];
        foreach ($accum as $idx => $data) {
            $bar_datasets[] = [
            'label' => ($category::getCategories())[$idx],
            'backgroundColor' => $category::$colors[$idx]['color'],
            'data' => $data
            ];
        }
        return [
        'labels' => array_keys($checkins),
        'datasets' => $bar_datasets,
        ];
    }
}
