<?php
namespace site\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Question;
use site\models\PasswordResetRequestForm;
use site\models\ResetPasswordForm;
use site\models\SignupForm;
use site\models\EditProfileForm;
use site\models\DeleteAccountForm;
use site\models\ContactForm;
use common\models\User;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;
use League\Csv\Writer;

/**
 * Site controller
 */
class SiteController extends Controller
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'actions' => ['index', 'error', 'privacy', 'terms', 'about', 'captcha', 'contact', 'faq'],
            'allow' => true,
          ],
          [
            'actions' => ['login', 'signup', 'reset-password', 'request-password-reset', 'verify-email'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout', 'welcome', 'delete-account', 'profile', 'export'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
          'deleteAccount' => ['post'],
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

  public function actionIndex()
  {
    $key = "index_blog_".\common\components\Time::getLocalDate('UTC');
    $posts = Yii::$app->cache->get($key);
    if($posts === false) {
      $posts = \Yii::$app->getModule('blog')
                            ->fetch()
                            ->parse()
                            ->results;
    }
    Yii::$app->cache->set($key, $posts, 60*60*24);

    return $this->render('index', ['posts'=>$posts]);
  }

  public function actionLogin()
  {
    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    } else {
      return $this->render('login', [
        'model' => $model,
      ]);
    }
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();
    return $this->goHome();
  }

  public function actionContact()
  {
    $model = new ContactForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if($model->sendEmail(Yii::$app->params['adminEmail'])) {
        Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
      } else {
        Yii::$app->session->setFlash('error', 'There was an error sending email.');
      }

      return $this->refresh();
    } else {
      return $this->render('contact', [
        'model' => $model,
      ]);
    }
  }

  public function actionAbout()
  {
    return $this->render('about');
  }

  public function actionFaq()
  {
    return $this->render('faq');
  }

  public function actionWelcome()
  {
    return $this->render('welcome');
  }

  public function actionSignup()
  {
    $model = new SignupForm();
    if($model->load(Yii::$app->request->post())) {
      $user = $model->signup();
      Yii::$app->getSession()->setFlash('success', 'We have sent a verification email to the email address you provided. Please check your inbox and follow the instructions to verify your account.');
      return $this->redirect('/',302);
    }

    return $this->render('signup', [
      'model' => $model,
    ]);
  }

  public function actionRequestPasswordReset()
  {
    $model = new PasswordResetRequestForm();
    if($model->load(Yii::$app->request->post()) && $model->validate()) {
      if(!$model->sendEmail()) {
        $ip = Yii::$app->getRequest()->getUserIP() ?: "UNKNOWN";
        Yii::warning("$ip has tried to reset the password for ".$model->email);
      }

      Yii::$app->getSession()->setFlash('success', 'If there is an account with the submitted email address you will receive further instructions in your email inbox.');
      return $this->goHome();
    }

    return $this->render('requestPasswordResetToken', [
      'model' => $model,
    ]);
  }

  public function actionResetPassword($token)
  {
    try {
      $model = new ResetPasswordForm($token);
    } catch (InvalidParamException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }

    if ($model->load(Yii::$app->request->post())
        && $model->validate()
        && $model->resetPassword()) {
      Yii::$app->getSession()->setFlash('success', 'New password was saved.');
      return $this->goHome();
    }

    return $this->render('resetPassword', [
      'model' => $model,
    ]);
  }

  public function actionVerifyEmail($token)
  {
    if (empty($token) || !is_string($token)) {
      throw new BadRequestHttpException('Email verification token cannot be blank.');
    }

    $user = User::findByVerifyEmailToken($token);
    if (!$user) {
      throw new BadRequestHttpException("Wrong or expired email verification token. If you aren't sure why this error occurs perhaps you've already verified your account. Please try logging in.");
    }

    if (Yii::$app->getUser()->login($user)) {
      $user->removeVerifyEmailToken();
      $user->save();
      Yii::$app->getSession()->setFlash('success', 'Your account has been verified. Please continue with your check-in.');
      return $this->redirect('/welcome',302);
    }
  }

  public function actionProfile()
  {
    $editProfileForm = new EditProfileForm();

    if (Yii::$app->request->isAjax && $editProfileForm->load($_POST))
    {
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

    $deleteAccountForm = new DeleteAccountForm();
    return $this->render('profile', [
      'profile' => $editProfileForm,
      'delete' => $deleteAccountForm
    ]);
  }

  public function actionDeleteAccount()
  {
    $model = new DeleteAccountForm();

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if($model->deleteAccount()) {
        $this->redirect(['site/index']);
      } else {
        Yii::$app->getSession()->setFlash('error', 'Wrong password!');
      }
    } else {
      Yii::$app->getSession()->setFlash('error', 'Request must be a POST.');
    }

    $this->redirect(Yii::$app->request->getReferrer());
  }

  public function actionPrivacy()
  {
    return $this->render('privacy');
  }

  public function actionTerms()
  {
    return $this->render('terms');
  }

  public function actionExport()
  {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=fsa-data-export-".Yii::$app->user->identity->email."-".date('Ymd').".csv");

    $data = Yii::$app->user->identity->getExportData();
    $fp = fopen('php://output', 'w');

    $header = [
      'Date',
      'Option',
      'Category',
      Question::$QUESTIONS[1],
      Question::$QUESTIONS[2],
      Question::$QUESTIONS[3],
    ];

    fputcsv($fp, $header);
    foreach($data as $row) {
      fputcsv($fp, $row);
    }
    fclose($fp);

    die;
  }
}
