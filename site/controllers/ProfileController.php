<?php
namespace site\controllers;


use Yii;
use common\models\Question;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use League\Csv\Writer;

/**
 * Profile controller
 */
class ProfileController extends Controller {
  /**
   * @inheritdoc
   */
  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'actions' => ['index', 'error', 'delete-account', 'change-password', 'export'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'deleteAccount' => ['post'],
          'changePassword' => ['post'],
        ],
      ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
      ],
    ];
  }

  public function actionIndex() {
    $editProfileForm    = Yii::$container->get(\site\models\EditProfileForm::class, [Yii::$app->user->identity]);
    $changePasswordForm = Yii::$container->get(\site\models\ChangePasswordForm::class, [Yii::$app->user->identity]);
    $deleteAccountForm  = Yii::$container->get(\site\models\DeleteAccountForm::class, [Yii::$app->user->identity]);
    $graph              = Yii::$container->get(\common\components\Graph::class, [Yii::$app->user->identity]);

    if (Yii::$app->request->isAjax && $editProfileForm->load($_POST)) {
      Yii::$app->response->format = 'json';
      return \yii\widgets\ActiveForm::validate($editProfileForm);
    }
    $editProfileForm->loadUser();

    if ($editProfileForm->load(Yii::$app->request->post())) {
      $saved_user = $editProfileForm->saveProfile();
      if($saved_user) {
        Yii::$app->getSession()->setFlash('success', 'New profile data saved!');
      }
    }

    return $this->render('index', [
      'profile'         => $editProfileForm,
      'change_password' => $changePasswordForm,
      'delete'          => $deleteAccountForm,
      'graph_url'       => $graph->getUrl(Yii::$app->user->identity->getIdHash()),
    ]);
  }

  public function actionDeleteAccount()
  {
    $model = Yii::$container->get(\site\models\DeleteAccountForm::class, [Yii::$app->user->identity]);

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if($model->deleteAccount()) {
        $this->redirect(['site/index']);
      } else {
        Yii::$app->getSession()->setFlash('error', 'Wrong password!');
      }
    }

    $this->redirect(Yii::$app->request->getReferrer());
  }

  public function actionChangePassword()
  {
    $model = Yii::$container->get(\site\models\ChangePasswordForm::class, [Yii::$app->user->identity]);

    if ($model->load(Yii::$app->request->post())) {
      if($model->validate() && $model->changePassword()) {
        Yii::$app->getSession()->setFlash('success', 'Password successfully changed');
      } else {
        Yii::$app->getSession()->setFlash('error', 'Wrong password!');
      }
    }

    $this->redirect(['profile/index']);
  }

  public function actionExport()
  {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=fsa-data-export-".Yii::$app->user->identity->email."-".date('Ymd').".csv");

    $reader = Yii::$app->user->identity->getExportData();
    $fp = fopen('php://output', 'w');

    $header = [
      'Date',
      'Behavior',
      'Category',
      Question::$QUESTIONS[1],
      Question::$QUESTIONS[2],
      Question::$QUESTIONS[3],
    ];

    fputcsv($fp, $header);
    $user_behavior = Yii::$container->get(\common\interfaces\UserBehaviorInterface::class);
    while($row = $reader->read()) {
      $row = $user_behavior::decorateWithCategory([$row]);
      $row = Yii::$app->user->identity->cleanExportData($row);
      fputcsv($fp, $row[0]);
    }
    fclose($fp);

    die;
  }
}
