<?php
namespace site\controllers;


use Yii;
use common\models\Question;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use League\Csv\Writer;
use yii\web\BadRequestHttpException;

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
            'actions' => ['index', 'error', 'delete-account', 'change-password', 'request-change-email', 'export'],
            'allow'   => true,
            'roles'   => ['@'],
          ], [
            'actions' => [ 'change-email', ],
            'allow'   => true,
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
    $changeEmailForm    = Yii::$container->get(\site\models\ChangeEmailForm::class, [Yii::$app->user->identity]);
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
      'change_email'    => $changeEmailForm,
      'delete'          => $deleteAccountForm,
      'graph_url'       => $graph->getUrl(Yii::$app->user->identity->getIdHash()),
    ]);
  }

  public function actionDeleteAccount() {
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

  public function actionChangePassword() {
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

  public function actionRequestChangeEmail() {
    $model = Yii::$container->get(\site\models\ChangeEmailForm::class, [Yii::$app->user->identity]);

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      $model->changeEmail();
      Yii::$app->getSession()->setFlash('success', "We've sent an email to your requested email address to confirm. Please click on the verification link to continue.");
    }

    $this->redirect(['profile/index']);
  }

  public function actionChangeEmail(string $token) {
    $user = \common\models\User::findByChangeEmailToken($token);
    if($user) {
      $validator = new \yii\validators\EmailValidator();
      if($validator->validate($user->desired_email, $error)) {
        $user->removeChangeEmailToken();
        $user->email = $user->desired_email;
        $user->desired_email = null;
        $user->save();
        if(!Yii::$app->user->isGuest) {
          Yii::$app->user->logout();
          Yii::$app->session->setFlash('success', 'Your email address was successfully changed. For security, we\'ve logged you out.');
        } else {
          Yii::$app->session->setFlash('success', 'Your email address was successfully changed.');
        }
        return $this->goHome();
      } else {
        // desired_email failed validation. Something sneaky might be happening here
        Yii::warning("ProfileController::actionChangeEmail() User({$user->id}) desired_email failed validation. Something weird is going on.");
        return $this->goHome();
      }
    } else {
      // no user was found with that token
      throw new BadRequestHttpException("Wrong or expired change email token. If you aren't sure why this error occurs perhaps you've already confirmed your new email address. Please try logging in with it.");
    }
  }

  public function actionExport() {
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
