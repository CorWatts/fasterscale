<?php

namespace common\components;

use Yii;

class Controller extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (
            $this->enableCsrfValidation
            && Yii::$app->getErrorHandler()->exception === null
            && !Yii::$app->getRequest()->validateCsrfToken()
        ) {
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
        'class' => 'yii\captcha\CaptchaAction',
        ],
        ];
    }
}
