<?php

namespace common\components;

use Yii;

class Controller extends \yii\web\Controller {
  /**
   * {@inheritdoc}
   */
  public function beforeAction($action)
  {
    if ($this->enableCsrfValidation
      && Yii::$app->getErrorHandler()->exception === null
      && !Yii::$app->getRequest()->validateCsrfToken()) {

      Yii::$app->session->setFlash('error', 'Your security token has expired. Please retry your submission.');
      $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
      return false;
    }

    return parent::beforeAction($action);
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
         'class' => 'yii\web\ErrorAction',
       ],
      'captcha' => [
        'class' => 'juliardi\captcha\CaptchaAction',
        //'length' => 5, // captcha character count
        //'width' => 150, // width of generated captcha image
        //'height' => 40, // height of generated captcha image
        //'quality' => 100, // quality of generated captcha image
      ],
    ];
  }
}