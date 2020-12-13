<?php
namespace site\controllers;

use Yii;
use common\components\Controller;
use common\models\CustomBehavior;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use kartik\grid\GridView;
use kartik\grid\EditableColumnAction;

/**
 * Custom Behavior controller
 */
class CustomBehaviorController extends Controller
{
    public function behaviors()
    {
        return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'actions' => ['create', 'update', 'delete'],
            'allow'   => true,
            'roles'   => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'create' => ['post'],
          'update' => ['post'],
          'delete' => ['post'],
        ],
      ],
    ];
    }

    public function actions()
    {
        return array_replace_recursive(parent::actions(), [
      'update' => [  // identifier for your editable column action
        'class'           => EditableColumnAction::className(), // action class name
        'modelClass'      => CustoMBehavior::className(), // the model for the record being edited
        'scenario'        => CustomBehavior::SCENARIO_DEFAULT, // model scenario assigned before validation & update
        'showModelErrors' => true, // show model validation errors after save
        'errorOptions'    => ['header' => ''], // error summary HTML options
        'postOnly'        => true,
        'ajaxOnly'        => true,
        'findModel'       => function ($id, $action) {
            $model = $this->findModel($id);
            if ($model !== null) {
                return $model;
            }
            throw new NotFoundHttpException('The specified behavior does not exist.');
        },
      ]
    ]);
    }

    public function actionCreate()
    {
        $form = Yii::$container->get(\site\models\CustomBehaviorForm::class, [Yii::$app->user->identity]);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            return $this->redirect(['profile/index']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($errors = $form->getErrorSummary(true)) {
            return $errors;
        } else {
            return ["success" => false, "message" => "Unknown Error"];
        }
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->renderContent($model->getGridView());
    }

    protected function findModel($id)
    {
        $model = CustomBehavior::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
