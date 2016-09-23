<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;
use \DateTimeZone;

/**
 * edit profile form
 */
class EditProfileForm extends Model
{
  public $username;
  public $email;
  public $password;
  public $timezone;
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
      [
        'username',
        'unique',
        'targetClass' => '\common\models\User',
        'message' => 'This
        username has already been taken.',
        'filter' => "id <> ".Yii::$app->user->id
      ],
      ['username', 'string', 'min' => 2, 'max' => 255],
      ['username', 'required'],

      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'email'],
      ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.', 'filter' => "id <> ".Yii::$app->user->id],

      ['password', 'string', 'min' => 6],

      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],

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
   * saves user's profile info.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function saveProfile()
  {
    if ($this->validate()) {
      $user = User::findOne(Yii::$app->user->id);

      if($this->username)
        $user->username = $this->username;
      if($this->email)
        $user->email = $this->email;
      if($this->password)
        $user->setPassword($this->password);
      if($this->timezone)
        $user->timezone = $this->timezone;
      if($this->send_email) {
        $user->email_threshold = $this->email_threshold;
        $user->partner_email1 = $this->partner_email1;
        $user->partner_email2 = $this->partner_email2;
        $user->partner_email3 = $this->partner_email3;
      } else {
        $user->email_threshold = null;
        $user->partner_email1 = null;
        $user->partner_email2 = null;
        $user->partner_email3 = null;
      }
      $user->save();

      return $user;
    }

    return null;
  }

  public function loadUser() {
    $user = User::findOne(Yii::$app->user->id);

    $this->username = $user->username;
    $this->email = $user->email;
    $this->timezone = $user->timezone;
    $this->email_threshold = $user->email_threshold;
    $this->partner_email1 = $user->partner_email1;
    $this->partner_email2 = $user->partner_email2;
    $this->partner_email3 = $user->partner_email3;
    $this->send_email = (isset($user->email_threshold) && array_filter($user->getPartnerEmails));
  }
}
