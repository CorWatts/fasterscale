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
    public $verifyCode;

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
            
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
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
            $user->save();
            return $user;
        }

        return null;
    }
}
