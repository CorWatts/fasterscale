<?php
namespace common\models;

use yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \DateTime;
use \DateTimeZone;

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
			->where("(username=:username_or_email OR email=:username_or_email) AND status=:status", ['username_or_email' => $username_or_email, 'status' => self::STATUS_ACTIVE])
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
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
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

    public static function convertLocalTimeToUTC($local_timestamp) {
        $timestamp = new DateTime($local_timestamp, new DateTimeZone(Yii::$app->user->identity->timezone));
        $timestamp->setTimeZone(new DateTimeZone("UTC"));
        return $timestamp->format("Y-m-d H:i:s");
    }

    public static function convertUTCToLocalDate($utc_timestamp) {
        $timestamp = new DateTime($utc_timestamp, new DateTimeZone("UTC"));
        $timestamp->setTimeZone(new DateTimeZone(Yii::$app->user->identity->timezone));
        return $timestamp->format("Y-m-d");
    }

    public static function getLocalTime($timezone = null) {
	if($timezone === null)
	    $timezone = Yii::$app->user->identity->timezone;

        $timestamp = new DateTime("now", new DateTimeZone($timezone));
        return $timestamp->format("Y-m-d H:i:s");
    }

    public static function getLocalDate($timezone = null) {
	if($timezone === null)
	    $timezone = Yii::$app->user->identity->timezone;

        $timestamp = new DateTime("now", new DateTimeZone($timezone));
        return $timestamp->format("Y-m-d");
    }

    public static function alterLocalDate($date, $modifier) {
        $new_date = new DateTime("$date $modifier", new DateTimeZone(Yii::$app->user->identity->timezone));
        return $new_date->format("Y-m-d");
    }

    public function sendEmailReport($date) {
        $utc_start_time = User::convertLocalTimeToUTC($date." 00:00:00");
        $utc_end_time = User::convertLocalTimeToUTC($date." 23:59:59");
        $utc_date = User::convertLocalTimeToUTC($date);

        if(!is_null($this->email_threshold) && !$this->partner_email1 && !$this->partner_email2 && !$this->partner_email3)
          return false; // they don't have their partner emails set

            $score = UserOption::calculateScoreByUTCRange($utc_start_time, $utc_end_time);

        $questions = User::getUserQuestions($date);
            $user_options = User::getUserOptions($date);
        //var_dump($user_options[1]); exit();
            

            $categories = Category::find()->asArray()->all();

            $options = Option::find()->asArray()->all();
            $options_list = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

        $messages = [];
        foreach([$this->partner_email1, $this->partner_email2, $this->partner_email3] as $email) {
          if($email) {
            $messages[] = Yii::$app->mailer->compose('checkinReport', [
              'user' => $this,
              'categories' => $categories, 
              'options_list' => $options_list, 
              'user_options' => $user_options,
              'date' => $date, 
              'score' => $score, 
              'questions' => $questions,
              'email' => $email
            ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setReplyTo($this->email)
            ->setSubject($this->username." has scored high in The Faster Scale App")
            ->setTo($email);
          }
        }

        return Yii::$app->mailer->sendMultiple($messages);
    }

    public function sendSignupNotificationEmail() {
        return \Yii::$app->mailer->compose('signupNotification')
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
            ->setTo(\Yii::$app->params['adminEmail'])
            ->setSubject('A new user has signed up for '.\Yii::$app->name)
            ->send();
    }

	  public static function getUserQuestions($local_date) {
      if(is_null($local_date))
          $local_date = User::getLocalDate();

      $utc_start_time = User::convertLocalTimeToUTC($local_date." 00:00:00");
      $utc_end_time = User::convertLocalTimeToUTC($local_date." 23:59:59");
      $utc_date = User::convertLocalTimeToUTC($local_date);

      $questions = Question::find()
          ->where("user_id=:user_id 
              AND date > :start_date 
              AND date < :end_date", 
              [
                  "user_id" => Yii::$app->user->id, 
                  ':start_date' => $utc_start_time, 
                  ":end_date" => $utc_end_time
              ])
          ->with('option')
          ->all();

		if($questions) {
			$organized_question_answers = [];
			foreach($questions as $question) {
				$question_data = [
					"id" => $question->option->id,
					"title" => $question->option->name
				];

				$question_answer = [
					"title" => Question::$QUESTIONS[$question->question],
					"answer" => $question->answer
				];

				$organized_question_answers[$question->option->id]['question'] = $question_data;
				$organized_question_answers[$question->option->id]["answers"][] = $question_answer;
			}
			return $organized_question_answers;
		}

		return [];
	}

	public static function getUserOptions($local_date) {
        if(is_null($local_date))
            $local_date = User::getLocalDate();

        $utc_start_time = User::convertLocalTimeToUTC($local_date." 00:00:00");
        $utc_end_time = User::convertLocalTimeToUTC($local_date." 23:59:59");
        $utc_date = User::convertLocalTimeToUTC($local_date);

        $user_options = UserOption::find()
            ->where("user_id=:user_id 
                AND date > :start_date 
                AND date < :end_date", 
                [
                    "user_id" => Yii::$app->user->id, 
                    ':start_date' => $utc_start_time, 
                    ":end_date" => $utc_end_time
                ])
            ->with('option', 'option.category')
            ->asArray()
            ->all();

		if($user_options) {
			foreach($user_options as $option) {
				$user_options_by_category[$option['option']['category_id']]['category_name'] = $option['option']['category']['name'];
				$user_options_by_category[$option['option']['category_id']]['options'][] = ["id" => $option['option_id'], "name"=>$option['option']['name']];
			}

			return $user_options_by_category;
		}

		return [];
	}
}
