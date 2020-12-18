<?php
namespace common\models;

use yii;
use yii\base\NotSupportedException;
use yii\db\Query;
use yii\web\IdentityInterface;
use \common\components\ActiveRecord;
use \common\interfaces\UserInterface;
use \common\interfaces\UserBehaviorInterface;
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
 * @property boolean $send_email
 * @property integer $email_category
 * @property string $partner_email1
 * @property string $partner_email2
 * @property string $partner_email3
 * @property boolean $expose_graph
 * @property string $desired_email
 * @property string $change_emaiL_token
 */
class User extends ActiveRecord implements IdentityInterface, UserInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 10;

    public const CONFIRMED_STRING = '_confirmed';

    public $user_behavior;
    public $time;

    private $export_order = [
    "date" => 0,
    "behavior" => 1,
    "category" => 2,
    "question1" => 3,
    "question2" => 4,
    "question3" => 5,
  ];

    public function __construct(UserBehaviorInterface $user_behavior, TimeInterface $time, $config = [])
    {
        $this->time = $time;
        parent::__construct($config);
    }

    public function afterFind()
    {
        $this->time->timezone = $this->timezone;
        parent::afterFind();
    }

    public function afterRefresh()
    {
        $this->time->timezone = $this->timezone;
        parent::afterRefresh();
    }

    //public function afterSave() {
    //  $this->time = new \common\components\Time($this->timezone);
    //  parent::afterSave();
    //}

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */

    public function behaviors()
    {
        return [
      'timestamp' => [
        'class' => yii\behaviors\TimestampBehavior::class,
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

    public function getPartnerEmails()
    {
        return [
      $this->partner_email1,
      $this->partner_email2,
      $this->partner_email3,
    ];
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
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
        return $this->find()->where(['email' => $email, 'status' => self::STATUS_ACTIVE])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public function findByPasswordResetToken($token)
    {
        if (!$this->isTokenCurrent($token)) {
            return null;
        }

        return $this->find()->where([
      'password_reset_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ])->one();
    }

    /**
     * Finds user by email verification token
     *
     * @param  string      $token email verification token
     * @return static|null
     */
    public function findByVerifyEmailToken($token)
    {
        if ($this->isTokenConfirmed($token)) {
            return null;
        }

        $user = $this->find()->where([
      'verify_email_token' => [$token, $token . self::CONFIRMED_STRING],
      'status' => self::STATUS_ACTIVE,
    ])->one();

        if ($user) {
            if (!$this->isTokenConfirmed($token) &&
         !$this->isTokenCurrent($token, 'user.verifyAccountTokenExpire')) {
                return null;
            }
        }

        return $user;
    }

    /**
     * Finds user by email change token
     *
     * @param  string      $token email change token
     * @return static|null
     */
    public function findByChangeEmailToken($token)
    {
        $user = static::find()->where([
      'change_email_token' => $token,
      'status' => self::STATUS_ACTIVE,
    ])->one();

        if ($user) {
            if (!$user->isTokenCurrent($token, 'user.verifyAccountTokenExpire')) {
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
    public function isTokenCurrent($token, String $paramPath = 'user.passwordResetTokenExpire')
    {
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
    public function isTokenConfirmed($token = null, String $match = self::CONFIRMED_STRING)
    {
        if (is_null($token)) {
            $token = $this->verify_email_token;
        }
        return substr($token, -strlen($match)) === $match;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function isVerified()
    {
        if (is_null($this->verify_email_token)) {
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
        $this->verify_email_token = $this->getRandomVerifyString();
    }

    /**
     * Confirms email verification token
     */
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
     * Generates email change tokens
     */
    public function generateChangeEmailToken()
    {
        $this->change_email_token = $this->getRandomVerifyString();
    }

    /**
     * Removes change email token
     */
    public function removeChangeEmailToken()
    {
        $this->change_email_token = null;
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
        $this->password_reset_token = $this->getRandomVerifyString();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /*
     * sendEmailReport()
     *
     * @param $date String a date string in YYYY-mm-dd format. The desired check-in date to send an email report of. Normally just today.
     * @return boolean whether or not it succeeds. It will return false if the user's specified criteria are not met (or if the user did not select any behaviors for the given day)
     *
     * This is the function that sends email reports. It can send an email report
     * for whichever `$date` is passed in. It checks if the user's specified
     * criteria are met before it sends any email. It sends email to every
     * partner email address the user has set.
     */
    public function sendEmailReport($date)
    {
        if (!$this->send_email) {
            return false;
        } // no partner emails set
        list($start, $end) = $this->time->getUTCBookends($date);

        $user_behavior = Yii::$container->get(UserBehaviorInterface::class);
        $checkins_last_month = $user_behavior->getCheckInBreakdown();

        // we should only proceed with sending the email if the user
    // scored above their set email threshold (User::email_category)
    $this_checkin     = $checkins_last_month[$date]; // gets the check-in
    if (!$this_checkin) {
        return false;
    }                // sanity check
    $highest_cat_data = end($this_checkin);          // gets the data for the highest category from the check-in
    if (!$highest_cat_data) {
        return false;
    }             // another sanity check
    $highest_cat_idx  = key($this_checkin);          // gets the key of the highest category

    // if the highest category they reached today was less than
        // the category threshold they have set, don't send the email
        if ($highest_cat_idx < $this->email_category) {
            return false;
        }

        $user_behaviors = $user_behavior->getByDate(Yii::$app->user->id, $date);

        $question = Yii::$container->get(\common\interfaces\QuestionInterface::class);
        $user_questions = $question->getByUser(Yii::$app->user->id, $date);

        $graph = Yii::$container
      ->get(\common\components\Graph::class)
      ->create($checkins_last_month);

        $messages = [];
        foreach ($this->getPartnerEmails() as $email) {
            if ($email) {
                $messages[] = Yii::$app->mailer->compose('checkinReport', [
          'user'           => $this,
          'email'          => $email,
          'date'           => $date,
          'user_behaviors' => $user_behaviors,
          'questions'      => $user_questions,
          'chart_content'  => $graph,
          'categories'     => \common\models\Category::$categories,
          'behaviors_list' => \common\models\Behavior::$behaviors,
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->email." has completed a Faster Scale check-in")
        ->setTo($email);
            }
        }

        return Yii::$app->mailer->sendMultiple($messages);
    }

    public function getExportData()
    {
        $query = (new Query)
      ->select(
          'l.id,        
       l.date      AS "date",
       l.custom_behavior AS "custom_behavior",
       l.behavior_id AS "behavior_id",
       l.category_id AS "category_id",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 1
          AND q1.user_behavior_id = l.id) AS "question1",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 2
          AND q1.user_behavior_id = l.id) AS "question2",
       (SELECT q1.answer
        FROM question q1
        WHERE q1.question = 3
          AND q1.user_behavior_id = l.id) AS "question3"'
      )
      ->from('user_behavior_link l')
      ->join("LEFT JOIN", "question q", "l.id = q.user_behavior_id")
      ->where('l.user_id=:user_id', ["user_id" => Yii::$app->user->id])
      ->groupBy('l.id,
          l.date,
          "question1",
          "question2",
          "question3"')
      ->orderBy('l.date DESC');

        return $query
      ->createCommand()
      ->query();

        /* Plaintext Query
        SELECT l.id,
               l.date      AS "date",
               l.custom_behavior AS "custom_behavior",
               l.behavior_id AS "behavior_id",
               (SELECT q1.answer
                FROM question q1
                WHERE q1.question = 1
                  AND q1.user_behavior_id = l.id) AS "question1",
               (SELECT q1.answer
                FROM question q1
                WHERE q1.question = 2
                  AND q1.user_behavior_id = l.id) AS "question2",
               (SELECT q1.answer
                FROM question q1
                WHERE q1.question = 3
                  AND q1.user_behavior_id = l.id) AS "question3"
        FROM   user_behavior_link l
               LEFT JOIN question q
                 ON l.id = q.user_behavior_id
        WHERE  l.user_id = 1
        GROUP  BY l.id,
                  l.date,
                  l.custom_behavior,
                  "question1",
                  "question2",
                  "question3",
        ORDER  BY l.date DESC;
        */
    }

    public function sendSignupNotificationEmail()
    {
        return \Yii::$app->mailer->compose('signupNotification')
      ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
      ->setTo(\Yii::$app->params['adminEmail'])
      ->setSubject('A new user has signed up for '.\Yii::$app->name)
      ->send();
    }

    public function sendVerifyEmail()
    {
        return \Yii::$app->mailer->compose('verifyEmail', ['user' => $this])
      ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
      ->setTo($this->email)
      ->setSubject('Please verify your '.\Yii::$app->name .' account')
      ->send();
    }

    public function sendDeleteNotificationEmail()
    {
        $messages = [];
        foreach (array_merge([$this->email], $this->getPartnerEmails()) as $email) {
            if ($email) {
                $messages[] = Yii::$app->mailer->compose('deleteNotification', [
          'user' => $this,
          'email' => $email
        ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        ->setReplyTo($this->email)
        ->setSubject($this->email." has deleted their The Faster Scale App account")
        ->setTo($email);
            }
        }

        return Yii::$app->mailer->sendMultiple($messages);
    }

    public function cleanExportRow($row)
    {
        // change timestamp to local time (for the user)
        $row['date'] = $this->time->convertUTCToLocal($row['date'], false);

        // clean up things we don't need
        $row['category'] = $row['category']['name'];
        if (array_key_exists('behavior', $row)) {
            $row['behavior'] = $row['behavior']['name'];
        } else {
            $row['behavior'] = $row['custom_behavior'];
        }
        unset($row['id']);
        unset($row['behavior_id']);
        unset($row['category_id']);
        unset($row['custom_behavior']);

        // sort the array into a sensible order
        uksort($row, function ($a, $b) {
            return $this->export_order[$a] <=> $this->export_order[$b];
        });
        return $row;
    }

    /*
     * getIdHash()
     *
     * @return String a user-identifying hash
     *
     * After generating the hash, we run it through a url-safe base64 encoding to
     * shorten it. This generated string is currently used as an identifier in
     * URLs, so the shorter the better. the url-safe version has been ripped from
     * https://secure.php.net/manual/en/function.base64-encode.php#103849
     *
     * It does NOT take into account the user's email address. The email address
     * is changeable by the user. If that was used for this function, the
     * returned hash would change when the user updates their email. That would
     * obviously not be desirable.
     */
    public function getIdHash()
    {
        return rtrim(
            strtr(
          base64_encode(
            hash('sha256', $this->id."::".$this->created_at, true)
        ),
          '+/',
          '-_'
      ),
            '='
        );
    }

    /*
     * getRandomVerifyString()
     *
     * @return String a randomly generated string with a timestamp appended
     *
     * This is generally used for verification purposes: verifying an email, password change, or email address change.
     */
    private function getRandomVerifyString()
    {
        return Yii::$app
      ->getSecurity()
      ->generateRandomString() . '_' . time();
    }
}
