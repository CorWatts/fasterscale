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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.', 'filter' => "id <> ".Yii::$app->user->id],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.', 'filter' => "id <> ".Yii::$app->user->id],

            ['password', 'string', 'min' => 6],

            ['timezone', 'required'],
            ['timezone', 'string', 'min' => 2, 'max' => 255],
            ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],
        ];
    }

/**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function profile()
    {
        if ($this->validate()) {
            $user = User::findOne(Yii::$app->user->id);
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
	    $user->timezone = $this->timezone;
            $user->save();
            return $user;
        }

        return null;
    }
}
