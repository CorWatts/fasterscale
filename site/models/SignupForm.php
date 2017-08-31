<?php
namespace site\models;

use yii\base\Model;
use Yii;
use \DateTimeZone;
use \common\interfaces\UserInterface;

/**
 * Signup form
 */
class SignupForm extends Model
{
  public $email;
  public $password;
  public $timezone = "America/Los_Angeles"; // default
  public $captcha;
  public $send_email;
  public $email_threshold;
  public $partner_email1;
  public $partner_email2;
  public $partner_email3;

  private $user;

  public function __construct(UserInterface $user, $config = []) {
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'required'],
      ['email', 'email'],

      ['password', 'required'],
      ['password', 'string', 'min' => 6],

      ['timezone', 'required'],
      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],

      // captcha needs to be entered correctly
      ['captcha', 'captcha', 'caseSensitive' => false, 'skipOnEmpty' => !!YII_ENV_TEST],

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
  public function signup() {
    $user_maybe = $this->user->findByEmail($this->email);
    if(!$user_maybe) {
      // this is a brand new user
      $this->user = $this->setFields($this->user);
      $this->user->save();

      $this->user->sendSignupNotificationEmail();
      $this->user->sendVerifyEmail();

      return $this->user;
    } else {
      /*
       * this is a user that for whatever reason is trying to sign up again
       * with the same email address.
       */
      if(!$this->user->isTokenConfirmed() && !$this->user->isTokenCurrent($this->user->verify_email_token, 'user.verifyAccountTokenExpire')) {
        /*
         * they've never verified their account and their verification token
         * is expired. We're resetting their account and resending their
         * verification email.
         */
        $this->setFields($this->user);
        $this->user->save();

        $this->user->generateVerifyEmailToken();
        $this->user->sendVerifyEmail();
      } else {
        /*
         * they've already confirmed their account and are a full user, so skip
         * all this
         *   OR
         * their token is still current and live and they should
         * click the link in their email.
         */
      }
    }
    return null;
  }

  public function setFields($user) {
      $user->email = $this->email;
      $user->setPassword($this->password);
      $user->timezone = $this->timezone;
      $user->generateAuthKey();
      $user->generateVerifyEmailToken();

      if($this->send_email) {
        $user->email_threshold = $this->email_threshold;
        $user->partner_email1  = $this->partner_email1;
        $user->partner_email2  = $this->partner_email2;
        $user->partner_email3  = $this->partner_email3;
      }
      return $user;
  }
}
