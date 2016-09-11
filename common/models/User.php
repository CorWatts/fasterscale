<?php
namespace common\models;

use yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use common\components\Time;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $timezone
 * @property integer $email_threshold
 * @property string $partner_email1
 * @property string $partner_email2
 * @property string $partner_email3
 */
class User extends ActiveRecord implements IdentityInterface
{
  const STATUS_DELETED = 0;
  const STATUS_ACTIVE = 10;

  const ROLE_USER = 10;

  public $decorator;

  /**
   * @inheritdoc
   */

  public function __call($name, $args) {
    if(is_object($this->decorator)) {
      return $this->decorator->$name($this, $args);
    }
    throw new NotSupportedException("'$name' is not implemented.");
  }

  public function __construct($decorator = null) {
    $decorator = $decorator ?: new \common\components\UserTrim($this);
    $this->decorate($decorator);
    parent::__construct();
  }

  public function decorate($decorator) {
    $this->decorator = $decorator;
  }

  public function behaviors()
  {
    return [
      'timestamp' => [
        'class' => 'yii\behaviors\TimestampBehavior',
        'attributes' => [
          ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
          ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
        ],
      ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['status', 'default', 'value' => self::STATUS_ACTIVE],
      ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

      ['role', 'default', 'value' => self::ROLE_USER],
      ['role', 'in', 'range' => [self::ROLE_USER]],
    ];
  }

  public function getPartnerEmails() {
    return [
      $this->partner_email1
      , $this->partner_email2
      , $this->partner_email3
    ];
  }

  /**
   * @inheritdoc
   */
  public static function findIdentity($id)
  {
    return static::findOne($id);
  }

  /**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

  /**
   * Finds user by username
   *
   * @param  string      $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
    return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
  }

  /**
   * Finds user by username or password
   *
   * @param  string      $username_or_password
   * @return static|null
   */
  public static function findByUsernameOrEmail($username_or_email)
  {
    return static::find()
      ->where("
        (
          username=:username_or_email 
          OR email=:username_or_email
        ) AND status=:status", [
          'username_or_email' => $username_or_email,
          'status' => self::STATUS_ACTIVE
        ])
      ->one();
  }
  /**
   * Finds user by password reset token
   *
   * @param  string      $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token)
  {
    $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    if ($timestamp + $expire < time()) {
      // token expired
      return null;
    }

    return static::findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  /**
   * @inheritdoc
   */
  public function getId()
  {
    return $this->getPrimaryKey();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey()
  {
    return $this->auth_key;
  }

  public function getTimezone() {
    return $this->timezone;
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey)
  {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param  string  $password password to validate
   * @return boolean if password provided is valid for current user
   */
  public function validatePassword($password)
  {
    return Yii::$app
      ->getSecurity()
      ->validatePassword($password, $this->password_hash);
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    $this->password_hash = Yii::$app
      ->getSecurity()
      ->generatePasswordHash($password);
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey()
  {
    $this->auth_key = Yii::$app
      ->getSecurity()
      ->generateRandomString();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken()
  {
    $this->password_reset_token = Yii::$app
      ->getSecurity()
      ->generateRandomString() . '_' . time();
  }

  /**
   * Finds out if password reset token is valid
   *
   * @param string $token password reset token
   * @return boolean
   */
  public static function isPasswordResetTokenValid($token)
  {
    if (empty($token)) {
      return false;
    }
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    return $timestamp + $expire >= time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
    $this->password_reset_token = null;
  }

  public function sendEmailReport($date) {
    list($start, $end) = Time::getUTCBookends($date);

    $utc_date = Time::convertLocalToUTC($date);

    if(!$this->isPartnerEnabled()) return false; // they don't have their partner emails set

    $score = UserOption::calculateScoreByUTCRange($start, $end);

    $questions = User::getUserQuestions($date);
    $user_options = User::getUserOptions($date);

    $categories = Category::find()->asArray()->all();

    $options = Option::find()->asArray()->all();
    $options_list = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

    $messages = [];
    foreach($this->getPartnerEmails() as $email) {
      if($email) {
        $messages[] = Yii::$app->mailer->compose('checkinReport', [
          'user' => $this,
          'categories' => $categories, 
          'options_list' => $options_list, 
          'user_options' => $user_options,
          'date' => $date, 
          'score' => $score, 
          'questions' => $questions,
          'email' => $email,
          'chartContent' => UserOption::generateScoresGraph()
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->username." has scored high in The Faster Scale App")
        ->setTo($email);
      }
    }

    return Yii::$app->mailer->sendMultiple($messages);
  }

  public function getExportData() {
   $query = new Query;
    $query->select(
      'l.date  AS "date",
       o.name  AS "option",
       c.name  AS "category", 
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 1
          AND q1.user_option_id = l.id) AS "question1",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 2
          AND q1.user_option_id = l.id) AS "question2",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 3
          AND q1.user_option_id = l.id) AS "question3"')
      ->from('user_option_link l')
      ->join("INNER JOIN", "option o", "l.option_id = o.id")
      ->join("INNER JOIN", "category c", "o.category_id = c.id")
      ->join("LEFT JOIN", "question q", "l.id = q.user_option_id")
      ->where('l.user_id=:user_id', ["user_id" => Yii::$app->user->id])
      ->groupBy('l.id,
          l.date,
          o.name,
          c.name,
          "question1",
          "question2",
          "question3"')
      ->orderBy('l.date DESC');
    $data = $query->all();

    $data = array_map(
      function($row) {
        $row['date'] = Time::convertUTCToLocal($row['date'], true);
        return $row;
      }, 
      $data
    );

    return $data;

/* Plaintext Query
SELECT l.id,
       l.DATE  AS "date",
       o.name  AS "option",
       c.name  AS "category",
       1       AS "Question1",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 1
          AND q1.user_option_id = l.id) AS "Answer1",
       2       AS "Question2",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 2
          AND q1.user_option_id = l.id) AS "Answer2",
       3       AS "Question3",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 3
          AND q1.user_option_id = l.id) AS "Answer3"
FROM   user_option_link l
       join OPTION o
         ON l.option_id = o.id
       join category c
         ON o.category_id = c.id
       join question q
         ON l.id = q.user_option_id
WHERE  l.user_id = 1
GROUP  BY l.id,
          l.date,
          o.name,
          c.name,
          "Question1",
          "Answer1",
          "Question2",
          "Answer2",
          "Question3",
          "Answer3"
ORDER  BY l.date DESC;
*/
  }

  public function sendSignupNotificationEmail() {
    return \Yii::$app->mailer->compose('signupNotification')
      ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
      ->setTo(\Yii::$app->params['adminEmail'])
      ->setSubject('A new user has signed up for '.\Yii::$app->name)
      ->send();
  }

  public function sendDeleteNotificationEmail() {
    if($this->isPartnerEnabled())
      return false; // they don't have their partner emails set

    $messages = [];
    foreach(array_merge($this->email, $this->getPartnerEmails()) as $email) {
      if($email) {
        $messages[] = Yii::$app->mailer->compose('partnerDeleteNotification', [
          'user' => $this,
          'email' => $email
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->username." has delete their The Faster Scale App account")
        ->setTo($email);
      }
    }

    return Yii::$app->mailer->sendMultiple($messages);
  }

  public static function getQuestionData($local_date) {
    list($start, $end) = Time::getUTCBookends($local_date);

    $questions = Question::find()
      ->where("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
    [
      "user_id" => Yii::$app->user->id, 
      ':start_date' => $start, 
      ":end_date" => $end
    ])
    ->with('option')
    ->all();

    return $questions;
  }

  public static function getUserQuestions($local_date = null, $questions = null) {
    if(is_null($questions)) {
      if(is_null($local_date)) $local_date = Time::getLocalDate();
      $questions = self::getQuestionData($local_date);
    }
    if(!$questions) return [];

    $question_answers = [];
    foreach($questions as $question) {
      $option = $question->option;

      $question_answers[$option->id]['question'] = [
        "id" => $option->id,
        "title" => $option->name
      ];

      $question_answers[$option->id]["answers"][] = [
        "title" => $question->question_text,
        "answer" => $question->answer
      ];
    }

    return $question_answers;
  }

  public static function getOptionData($local_date) {
    list($start, $end) = Time::getUTCBookends($local_date);

    return UserOption::find()
      ->where("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
    [
      "user_id" => Yii::$app->user->id, 
      ':start_date' => $start, 
      ":end_date" => $end
    ])
    ->with('option', 'option.category')
    ->asArray()
    ->all();
  }

  public static function getUserOptions($local_date = null, $options = null) {
    if(is_null($options)) {
      if(is_null($local_date)) $local_date = Time::getLocalDate();
      $options = self::getOptionData($local_date);
    }
    if(!$options) return [];

    $opts_by_cat = [];
    foreach($options as $option) {
      $indx = $option['option']['category_id'];

      $opts_by_cat[$indx]['category_name'] = $option['option']['category']['name'];
      $opts_by_cat[$indx]['options'][] = [
        "id" => $option['option_id'],
        "name"=>$option['option']['name']];
    }

    return array_values($opts_by_cat);
  }
}
