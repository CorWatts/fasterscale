<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;
use \DateTimeZone;

/**
 * Signup form
 */
class SignupForm extends Model
{
  public $username;
  public $email;
  public $password;
  public $timezone = "America/Los_Angeles"; // default
  public $captcha;
  public $send_email;
  public $email_threshold;
  public $partner_email1;
  public $partner_email2;
  public $partner_email3;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['username', 'filter', 'filter' => 'trim'],
      ['username', 'required'],
      ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
      ['username', 'string', 'min' => 2, 'max' => 255],

      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'required'],
      ['email', 'email'],
      ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

      ['password', 'required'],
      ['password', 'string', 'min' => 6],

      ['timezone', 'required'],
      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],

      // captcha needs to be entered correctly
      ['captcha', 'captcha', 'caseSensitive' => false],

      ['send_email', 'boolean'],
      ['email_threshold', 'integer'],
      ['email_threshold', 'required', 'when'=> function($model) {
        return $model->send_email;
      }, 'message' => "If you've elected to send email reports, you must set a threshold.", "whenClient" => "function(attribute, value) {
        return $('#signupform-send_email').is(':checked');
  }"],
    [['partner_email1', 'partner_email2', 'partner_email3'], 'email'],
    [['partner_email1'], 'required', 'when' => function($model) {
      return $model->send_email;
    }, 'message' => "If you've elected to send email reports, at least one partner email must be set.", "whenClient" => "function(attribute, value) {
      return $('#signupform-send_email').is(':checked');
  }"]
  ];
  }

  public function attributeLabels() {
    return [
      'partner_email1' => "Partner Email #1",
      'partner_email2' => "Partner Email #2",
      'partner_email3' => "Partner Email #3",
      'send_email' => 'Send an email when I score above a certain threshold'
    ];
  }

  /**
   * Signs user up.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function signup()
  {
    if ($this->validate()) {
      $user = new User();
      $user->username = $this->username;
      $user->email = $this->email;
      $user->setPassword($this->password);
      $user->timezone = $this->timezone;
      $user->generateAuthKey();

      if($this->send_email) {
        $user->email_threshold = $this->email_threshold;
        $user->partner_email1  = $this->partner_email1;
        $user->partner_email2  = $this->partner_email2;
        $user->partner_email3  = $this->partner_email3;
      }
      $user->save();
      return $user;
    }

    return null;
  }
}
