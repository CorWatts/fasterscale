<?php

namespace site\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
  public $name;
  public $email;
  public $subject;
  public $body;
  public $verifyCode;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      // name, email, subject and body are required
      [['name', 'email', 'subject', 'body'], 'required'],
      // email has to be a valid email address
      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'filter', 'filter' => 'strtolower'],
      ['email', 'email'],
      // verifyCode needs to be entered correctly
      ['verifyCode', 'captcha', 'when' => function() {
        return Yii::$app->user->isGuest;
      }, 'skipOnEmpty' => !!YII_ENV_TEST],
    ];
  }

  /**
   * @inheritdoc
   * @codeCoverageIgnore
   */
  public function attributeLabels()
  {
    return [
      'verifyCode' => 'Verification Code',
    ];
  }

  /**
   * Sends an email to the specified email address using the information collected by this model.
   *
   * @param  string  $email the target email address
   * @return boolean whether the email was sent
   */
  public function sendEmail($email)
  {
    return Yii::$app->mailer->compose()
      ->setTo($email)
      ->setFrom([$this->email => $this->name])
      ->setSubject("[FSA Contact] ".$this->subject)
      ->setTextBody($this->body)
      ->send();
  }
}
