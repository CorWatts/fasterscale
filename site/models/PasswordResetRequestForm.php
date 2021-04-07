<?php

namespace site\models;

use yii\base\Model;
use common\interfaces\UserInterface;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    private $user;

    public function __construct(UserInterface $user, $config = [])
    {
        $this->user = $user;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ['email', 'filter', 'filter' => 'strtolower'],
        ['email', 'filter', 'filter' => 'trim'],
        ['email', 'required'],
        ['email', 'email'],
        ];
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = $this->user::find()->where([
        'email' => $this->email,
        ])->one();

        if ($user) {
            if (!$user->isTokenCurrent($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                return \Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . \Yii::$app->name)
                ->send();
            }
        }
        return false;
    }
}
