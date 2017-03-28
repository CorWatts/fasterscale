<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
  public $username;
  public $password;
  public $rememberMe = true;

  private $_user = false;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      // username and password are both required
      [['username', 'password'], 'required'],
      ['username', 'string'],
      // rememberMe must be a boolean value
      ['rememberMe', 'boolean'],
      // password is validated by validatePassword()
      ['password', 'validatePassword'],
    ];
  }

  /**
   * Validates the password.
   * This method serves as the inline validation for password.
   */
  public function validatePassword()
  {
    if (!$this->hasErrors()) {
      $user = $this->getUser();
      if (!$user || !$user->validatePassword($this->password)) {
        $this->addError('password', 'Incorrect username or password.');
      }
    }
  }

  /**
   * Logs in a user using the provided username and password.
   *
   * @return boolean whether the user is logged in successfully
   */
  public function login()
  {
    if($this->validate()) {
      if($this->getUser()->isVerified()) {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
      } else {
        Yii::$app->getSession()->setFlash('warning', 'You must verify your account before you can proceed. Please check your email inbox for a verification email and follow the instructions.');
      }
    }
    return false;
  }

  /**
   * Finds user by [[username]]
   *
   * @return User|null
   */
  public function getUser()
  {
    if ($this->_user === false) {
      $this->_user = User::findByUsername($this->username);
    }

    return $this->_user;
  }
}
