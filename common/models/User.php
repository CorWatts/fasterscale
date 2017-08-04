<?php
namespace common\models;

use yii;
use yii\base\NotSupportedException;
use yii\db\Query;
use yii\web\IdentityInterface;
use \common\components\ActiveRecord;
use \common\interfaces\UserInterface;
use \common\interfaces\UserOptionInterface;
use \common\interfaces\QuestionInterface;
use \common\interfaces\TimeInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verify_email_token
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
class User extends ActiveRecord implements IdentityInterface, UserInterface
{
  const STATUS_DELETED = 0;
  const STATUS_ACTIVE = 10;

  const ROLE_USER = 10;

  const CONFIRMED_STRING = '_confirmed';

  public $user_option;
  public $question;
  public $time;

  public function __construct(UserOptionInterface $user_option, QuestionInterface $question, TimeInterface $time, $config = []) {
    $this->user_option = $user_option;
    $this->question = $question;
    $this->time = $time;
    parent::__construct($config);
  }

  public function afterFind() {
    $this->time = new \common\components\Time($this->timezone);
    parent::afterFind();
  }

  public function afterRefresh() {
    $this->time = new \common\components\Time($this->timezone);
    parent::afterRefresh();
  }

  //public function afterSave() {
  //  $this->time = new \common\components\Time($this->timezone);
  //  parent::afterSave();
  //}

  /**
   * @inheritdoc
   */

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
      $this->partner_email1,
      $this->partner_email2,
      $this->partner_email3,
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
   * Finds user by email
   *
   * @param  string      $email
   * @return static|null
   */
  public function findByEmail($email)
  {
    return $this->findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
  }

  /**
   * Finds user by password reset token
   *
   * @param  string      $token password reset token
   * @return static|null
   */
  public function findByPasswordResetToken($token)
  {
    if(!$this->isTokenCurrent($token)) {
      return null;
    }

    return $this->findOne([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ]);
  }

  /**
   * Finds user by email verification token
   *
   * @param  string      $token email verification token
   * @return static|null
   */
  public function findByVerifyEmailToken($token)
  {
    if($this->isTokenConfirmed($token)) return null;

    $user = $this->findOne([
      'verify_email_token' => [$token, $token . self::CONFIRMED_STRING],
      'status' => self::STATUS_ACTIVE,
    ]);

    if($user) {
      if(!$this->isTokenConfirmed($token) &&
         !$this->isTokenCurrent($token, 'user.verifyAccountTokenExpire')) {
        return null;
      }
    }

    return $user;
  }

  /**
   * Finds out if a token is current or expired
   *
   * @param  string      $token verification token
   * @param  string      $paramPath Yii app param path
   * @return boolean
   */
  public function isTokenCurrent($token, String $paramPath = 'user.passwordResetTokenExpire') {
    $expire = \Yii::$app->params[$paramPath];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    if ($timestamp + $expire < time()) {
      // token expired
      return false;
    }
    return true;
  }

  /*
   * Checks if $token ends with the $match string
   *
   * @param string    $token verification token (the haystack)
   * @param string    $match the needle to search for
   */
  public function isTokenConfirmed($token = null, String $match = self::CONFIRMED_STRING) {
    if(is_null($token)) $token = $this->verify_email_token;
    return substr($token, -strlen($match)) === $match;
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

  public function isVerified() {
    if(is_null($this->verify_email_token)) {
      // for old users who verified their accounts before the addition of
      // '_confirmed' to the token
      return true;
    } else {
      return !!$this->verify_email_token && $this->isTokenConfirmed($this->verify_email_token);
    }
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
   * Generates email verification token
   */
  public function generateVerifyEmailToken()
  {
    $this->verify_email_token = Yii::$app
      ->getSecurity()
      ->generateRandomString() . '_' . time();
  }

  public function confirmVerifyEmailToken()
  {
    $this->verify_email_token .= self::CONFIRMED_STRING;
  }

  /**
   * Removes email verification token
   */
  public function removeVerifyEmailToken()
  {
    $this->verify_email_token = null;
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
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
    $this->password_reset_token = null;
  }

  public function sendEmailReport($date) {
    if(!$this->isPartnerEnabled()) return false; // no partner emails set

    list($start, $end) = $this->time->getUTCBookends($date);

    $messages = [];
    foreach($this->getPartnerEmails() as $email) {
      if($email) {
        $messages[] = Yii::$app->mailer->compose('checkinReport', [
          'user'          => $this,
          'email'         => $email,
          'date'          => $date,
          'user_options'  => $this->getUserOptions($date),
          'questions'     => $this->getUserQuestions($date),
          'chart_content' => $this->user_option->generateScoresGraph(),
          'categories'    => \common\models\Category::$categories,
          'score'         => $this->user_option->calculateScoreByUTCRange($start, $end),
          'options_list'  => \common\models\Option::$options,
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->email." has scored high in The Faster Scale App")
        ->setTo($email);
      }
    }

    return Yii::$app->mailer->sendMultiple($messages);
  }

  public function getExportData() {
    $query = (new Query)
      ->select(
      'l.id,        
       l.date      AS "date",
       l.option_id AS "option_id",
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
      ->join("LEFT JOIN", "question q", "l.id = q.user_option_id")
      ->where('l.user_id=:user_id', ["user_id" => Yii::$app->user->id])
      ->groupBy('l.id,
          l.date,
          "question1",
          "question2",
          "question3"')
      ->orderBy('l.date DESC');
   $data = $this->user_option::decorateWithCategory($query->all());
   return $this->cleanExportData($data);

/* Plaintext Query
SELECT l.id,
       l.date      AS "date",
       l.option_id AS "option_id",
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
          AND q1.user_option_id = l.id) AS "question3"
FROM   user_option_link l
       LEFT JOIN question q
         ON l.id = q.user_option_id
WHERE  l.user_id = 1
GROUP  BY l.id,
          l.date,
          "question1",
          "question2",
          "question3",
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

  public function sendVerifyEmail() {
    return \Yii::$app->mailer->compose('verifyEmail', ['user' => $this])
      ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
      ->setTo($this->email)
      ->setSubject('Please verify your '.\Yii::$app->name .' account')
      ->send();
  }

  public function sendDeleteNotificationEmail() {
    if($this->isPartnerEnabled()) return false; // no partner emails set

    $messages = [];
    foreach(array_merge([$this->email], $this->getPartnerEmails()) as $email) {
      if($email) {
        $messages[] = Yii::$app->mailer->compose('partnerDeleteNotification', [
          'user' => $this,
          'email' => $email
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->email." has delete their The Faster Scale App account")
        ->setTo($email);
      }
    }

    return Yii::$app->mailer->sendMultiple($messages);
  }

  public function getUserQuestions($local_date = null) {
    if(is_null($local_date)) $local_date = $this->time->getLocalDate();
    $questions = $this->getQuestionData($local_date);
    return $this->parseQuestionData($questions);
  }

  public function getUserOptions($local_date = null) {
    if(is_null($local_date)) $local_date = $this->time->getLocalDate();

    $options = $this->getOptionData($local_date);
    $options = $this->user_option::decorateWithCategory($options);
    return $this->parseOptionData($options);
  }

  public function parseQuestionData($questions) {
    if(!$questions) return [];

    $question_answers = [];
    foreach($questions as $question) {
      $option = $question['option'];

      $question_answers[$option['id']]['question'] = [
        "id" => $option['id'],
        "title" => $option['name']
      ];

      $question_answers[$option['id']]["answers"][] = [
        "title" => $this->question::$QUESTIONS[$question['question']],
        "answer" => $question['answer']
      ];
    }

    return $question_answers;
  }
 
  public function parseOptionData($options) {
    if(!$options) return [];

    $opts_by_cat = [];
    foreach($options as $option) {
      $indx = $option['option']['category_id'];

      $opts_by_cat[$indx]['category_name'] = $option['option']['category']['name'];
      $opts_by_cat[$indx]['options'][] = [
        "id" => $option['option_id'],
        "name"=>$option['option']['name']];
    }

    return $opts_by_cat;
  }

  public function getQuestionData($local_date) {
    list($start, $end) = $this->time->getUTCBookends($local_date);

    $questions = $this->question->find()
      ->where("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
    [
      "user_id" => Yii::$app->user->id, 
      ':start_date' => $start, 
      ":end_date" => $end
    ])
    ->asArray()
    ->all();

    $questions = $this->user_option::decorate($questions);

    return $questions;
  }

  public function getOptionData($local_date) {
    list($start, $end) = $this->time->getUTCBookends($local_date);

    return $this->user_option->find()
      ->where("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
    [
      "user_id" => Yii::$app->user->id, 
      ':start_date' => $start, 
      ":end_date" => $end
    ])
    ->asArray()
    ->all();
  }

  public function isPartnerEnabled() {
    if((is_integer($this->email_threshold)
       && $this->email_threshold >= 0)
         && ($this->partner_email1
           || $this->partner_email2
           || $this->partner_email3)) {
      return true;
    }
    return false;
  }

  public function isOverThreshold($score) {
    if(!$this->isPartnerEnabled()) return false;

    $threshold = $this->email_threshold;

    return (!is_null($threshold) && $score > $threshold)
            ? true
            : false;
  }

  public function cleanExportData($data) {
   $order = array_flip(["date", "option", "category", "question1", "question2", "question3"]);

   $ret = array_map(
     function($row) use ($order) {
       // change timestamp to local time (for the user)
       $row['date'] = $this->time->convertUTCToLocal($row['date'], true);
       
       // clean up things we don't need
       $row['category'] = $row['option']['category']['name'];
       $row['option'] = $row['option']['name'];
       unset($row['id']);
       unset($row['option_id']);

       // sort the array into a sensible order
       uksort($row, function($a, $b) use ($order) {
        return $order[$a] <=> $order[$b];
       });
       return $row;
     }, 
     $data
   );
   return $ret;
  }
}
