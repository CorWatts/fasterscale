<?php

namespace site\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use common\components\Controller;
use yii\filters\VerbFilter;
use common\components\AccessControl;

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
        'class' => AccessControl::class,
        'rules' => [
          [
            'actions' => ['index', 'blog', 'error', 'privacy', 'terms', 'about', 'captcha', 'contact', 'faq'],
            'allow' => true,
          ],
          [
            'actions' => ['login', 'signup', 'reset-password', 'request-password-reset', 'verify-email'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout', 'welcome'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionBlog()
  {
    $time = Yii::$container->get(\common\interfaces\TimeInterface::class); 
    $key = "index_blog_".$time->getLocalDate('UTC');
    $posts = Yii::$app->cache->get($key);
    if($posts === false) {
      $posts = \Yii::$app->getModule('blog')
                            ->fetch()
                            ->parse()
                            ->results;
    }
    Yii::$app->cache->set($key, $posts, 60*60*24);

    return $this->render('blog', ['posts'=>$posts]);
  }

  public function actionLogin()
  {
    $model = Yii::$container->get(\common\models\LoginForm::class);
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
    $model = new \site\models\ContactForm();
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
    $model = Yii::$container->get(\site\models\SignupForm::class);
    if($model->load(Yii::$app->request->post()) && $model->validate()) {
      $model->signup();
      Yii::$app->session->setFlash('success', 'We have sent a verification email to the email address you provided. Please check your inbox and follow the instructions to verify your account.');
      return $this->redirect('/',302);
    }

    return $this->render('signup', [
      'model' => $model,
    ]);
  }

  public function actionRequestPasswordReset()
  {
    $model = Yii::$container->get(\site\models\PasswordResetRequestForm::class);
    if($model->load(Yii::$app->request->post()) && $model->validate()) {
      if(!$model->sendEmail()) {
        $ip = Yii::$app->getRequest()->getUserIP() ?: "UNKNOWN";
        Yii::warning("$ip has tried to reset the password for ".$model->email);
      }

      Yii::$app->session->setFlash('success', 'If there is an account with the submitted email address you will receive further instructions in your email inbox.');
      return $this->goHome();
    }

    return $this->render('requestPasswordResetToken', [
      'model' => $model,
    ]);
  }

  public function actionResetPassword($token)
  {
    try {
      $model = Yii::$container->get(\site\models\ResetPasswordForm::class, [$token]);
    } catch (\yii\base\InvalidParamException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }

    if ($model->load(Yii::$app->request->post())
        && $model->validate()
        && $model->resetPassword()) {
      Yii::$app->session->setFlash('success', 'New password was saved.');
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

    $user = Yii::$container->get(\common\interfaces\UserInterface::class)
              ->findByVerifyEmailToken($token);
    if (!$user) {
      throw new BadRequestHttpException("Wrong or expired email verification token. If you aren't sure why this error occurs perhaps you've already verified your account. Please try logging in.");
    }

    if($user->isTokenConfirmed($user->verify_email_token)) {
      Yii::$app->session->setFlash('success', 'Your account has already been verified. Please log in.');
      return $this->redirect('/login',302);
    } else if (Yii::$app->getUser()->login($user)) {
      $user->confirmVerifyEmailToken();
      $user->save();
      Yii::$app->session->setFlash('success', 'Your account has been verified. Please continue with your check-in.');
      return $this->redirect('/welcome',302);
    }
  }

  public function actionPrivacy()
  {
    return $this->render('privacy');
  }

  public function actionTerms()
  {
    return $this->render('terms');
  }
}
