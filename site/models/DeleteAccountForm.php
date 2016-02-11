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

    public function attributeLabels() {
        return [
        ];
    }

    /**
     * saves user's profile info.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function deleteAccount()
    {
      if ($this->validate() && Yii::$app->user->identity->validatePassword($this->password)) {
        Yii::$app->user->identity->sendDeleteNotificationEmail();
        Yii::$app->user->identity->delete();
        return true;
      }

        return false;
    }
}
