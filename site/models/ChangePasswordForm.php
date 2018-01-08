<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * change password form
 */
class ChangePasswordForm extends Model
{
  public $old_password;
  public $new_password;
  private $user;

  /**
   * Creates a form model
   *
   * @param  object                          $user
   * @param  array                           $config name-value pairs that will be used to initialize the object properties
   */
  public function __construct(\common\interfaces\UserInterface $user, $config = []) {
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['old_password', 'string', 'min' => 6],
      ['new_password', 'string', 'min' => 8],
      [['old_password', 'new_password'], 'required'],
      ['old_password', 'validatePassword'],
    ];
  }

  public function attributeLabels() {
    return [
      'old_password' => 'Current Password',
      'new_password' => 'New Password',
      ];
  }

  /**
   * Validates the password.
   * This method serves as the inline validation for password.
   */
  public function validatePassword()
  {
    if (!$this->hasErrors()) {
      if (!$this->user->validatePassword($this->old_password)) {
        $this->addError('old_password', 'Incorrect email or password.');
      }
    }
  }

  /**
   * changes user's password.
   *
   * @return Boolean whether or not the save was successful
   */
  public function changePassword()
  {
    $this->user->setPassword($this->new_password);
    return $this->user->save();
  }
}
