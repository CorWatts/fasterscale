<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * change email form
 */
class ChangeEmailForm extends Model {
  public $desired_email;
  private $user;

  /**
   * Creates a form model
   *
   * @param  object                          $user
   * @param  array                           $config name-value pairs that will be used to initialize the object properties
   */
  public function __construct(\common\interfaces\UserInterface $user, $config = []) {
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      ['desired_email', 'required'],
      ['desired_email', 'filter', 'filter' => 'trim'],
      ['desired_email', 'filter', 'filter' => 'strtolower'],
      ['desired_email', 'email'],
    ];
  }

  public function attributeLabels() {
    return [
      'desired_email' => 'New Email',
      ];
  }

  /**
   * Sets verification tokens and sends verification emails
   *
   * @return Boolean whether or not the operation was successful
   */
  public function changeEmail() {
    // check that the desired_email is not already the email of another user
    $user = $this->user->findByEmail($this->desired_email);
    // if it is, we do nothing and return true, don't leak the data
    if(!$user) {
      $this->user->desired_email = $this->desired_email;
      $this->user->generateChangeEmailToken();
      $this->user->save();

      $this->sendChangeEmailEmail();
    }
    return true;
  }

  private function sendChangeEmailEmail() {
    Yii::$app->mailer->compose('changeEmail', [
      'current_email' => $this->user->email,
      'desired_email' => $this->desired_email,
      'token'         => $this->user->change_email_token,
    ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
    ->setSubject(Yii::$app->name . ' -- your requested email change')
    ->setTo($this->desired_email)
    ->send();
  }
}
