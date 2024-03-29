<?php
namespace site\models;

use yii\base\Model;
use Yii;
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
    public $send_email = false;
    public $email_category = 4; // default to "Speeding Up"
    public $partner_email1;
    public $partner_email2;
    public $partner_email3;

    private $user;
    private $categories;

    public function __construct(UserInterface $user, $config = [])
    {
        $this->user = $user;
        $this->categories = \common\models\Category::getCategories();
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      //['email_category', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'filter', 'filter' => 'strtolower'],
      ['email', 'required'],
      ['email', 'email'],

      ['password', 'required'],
      ['password', 'string', 'min' => 8],

      ['timezone', 'required'],
      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'   => \DateTimeZone::listIdentifiers()],

      // captcha needs to be entered correctly
      ['captcha', 'captcha', 'caseSensitive' => false, 'skipOnEmpty' => !!YII_ENV_TEST],

      ['send_email', 'boolean'],
      ['email_category', 'integer'],
      ['email_category', 'in', 'range'=>array_keys($this->categories)],
      [['partner_email1', 'partner_email2', 'partner_email3'], 'email'],
      [
        ['partner_email1', 'email_category'],
        'required',
        'when' => function ($model) {
            return $model->send_email;
        },
        'message' => "If you've elected to send email reports, at least one partner email must be set.",
        "whenClient" => 'function(attribute, value) {
          return $("#signupform-send_email").is("checked");
        }'
      ]
    ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
      'partner_email1' => "Partner Email #1",
      'partner_email2' => "Partner Email #2",
      'partner_email3' => "Partner Email #3",
      //'send_email' => 'Send an email when I complete a check-in'
      'send_email'     => 'Automatically send an email when I select behaviors at or above a specific category',
      'email_category' => 'The email threshold category is',
    ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $user = $this->user->findByEmail($this->email);
        if (!$user) {
            // this is a brand new user
            $this->user = $this->setFields($this->user);
            $this->user->save();

            if(\Yii::$app->params['sendSignupNotification']) {
                $this->user->sendSignupNotificationEmail();
            }
            $this->user->sendVerifyEmail();

            return $this->user;
        } else {
            /*
             * this is a user that for whatever reason is trying to sign up again
             * with the same email address.
             */
            if (!$user->isTokenConfirmed()) {
                /*
                 * they've never verified their account. We don't care if their
                 * verification token is current or expired. We're resetting their
                 * account and resending their verification email.
                 */
                $this->setFields($user);
                $user->save();
                $user->sendVerifyEmail();
                return $user;
            } else {
                /*
                 * they've already confirmed their account and are a full user, so skip
                 * all this
                 */
            }
        }
        return null;
    }

    public function setFields($user)
    {
        $user->send_email = $this->send_email;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->timezone = $this->timezone;
        $user->generateAuthKey();
        $user->generateVerifyEmailToken();

        if ($user->send_email) {
            $user->send_email = true;
            $user->email_category = $this->email_category;
            $user->partner_email1 = $this->partner_email1;
            $user->partner_email2 = $this->partner_email2;
            $user->partner_email3 = $this->partner_email3;
        } else {
            $user->send_email = false;
        }

        return $user;
    }
}
