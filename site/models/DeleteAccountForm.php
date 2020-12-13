<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * edit profile form
 */
class DeleteAccountForm extends Model
{
    public $password;
    private $user;

    /**
     * Creates a form model
     *
     * @param  object                          $user
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct(\common\interfaces\UserInterface $user, $config = [])
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
      ['password', 'required'],
      ['password', 'string', 'min' => 6],
    ];
    }

    public function attributeLabels()
    {
        return [
      'password' => 'Password'
    ];
    }

    /**
     * saves user's profile info.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function deleteAccount()
    {
        if ($this->validate() && $this->user->validatePassword($this->password)) {
            $this->user->sendDeleteNotificationEmail();
            $this->user->delete();
            return true;
        }

        return false;
    }
}
