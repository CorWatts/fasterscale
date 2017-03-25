<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;
use Codeception\Util\Stub;
use common\models\User;
use common\models\LoginForm;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class LoginFormTest extends \Codeception\Test\Unit {
  use Specify;

  private function mockUser($validatePassword = true) {
    $user = $this->getMockBuilder('\common\models\User')
      ->setMethods(['save', 'login', 'attributes', 'validatePassword', 'isVerified'])
      ->getMock();
    $user->method('save')->willReturn(true);
    $user->method('validatePassword')->willReturn($validatePassword);
    $user->method('login')->willReturn('LOGGING IN');
    $user->method('isVerified')->willReturn(true);
    $user->method('attributes')->willReturn([
      'id',
      'username',
      'password_hash',
      'password_reset_token',
      'verify_email_token',
      'email',
      'auth_key',
      'role',
      'status',
      'created_at',
      'updated_at',
      'password',
      'timezone',
      'email_threshold',
      'partner_email1',
      'partner_email2',
      'partner_email3',
    ]);

    return $user;
  }

  private function mockForm($user) {
    $form = Stub::make('\common\models\LoginForm', [
      '_user' => $user,
    ]);
    return $form;
  }

  public function setUp() {
    $this->user = $this->mockUser();
    $this->form = $this->mockForm($this->user);
    parent::setUp();

    Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => '\common\tests\unit\FakeWebUser',
          'identityClass' => '\common\tests\unit\FakeUser',
        ],
      ],
    ]);

    $identity = new \common\tests\unit\FakeUser();
    $identity->timezone = "America/Los_Angeles";
  }


  public function testGetUser() {
    $this->specify('getUser should function correctly', function() {
      expect('getUser should return the user if the _user attr on the form object is already set', $this->assertInstanceOf('\common\models\User', $this->form->getUser()));
    });
  }

  public function testValidatePassword() {
    $this->specify('validatePassword should function correctly', function() {
      expect('validatePassword should always return null', $this->assertNull($this->form->validatePassword()));
      expect('validatePassword should by default in this test class not set errors', $this->assertEmpty($this->form->getErrors()));

      $this->user = $this->mockUser(false);
      $this->form = $this->mockForm($this->user);
      $this->form->validatePassword();
      expect('validatePassword should set errors on the model if something goes wrong', $this->assertNotEmpty($this->form->getErrors('password')));
    });
  }
  public function testLogin() {
    $this->specify('login should function correctly', function() {
      $this->form->email = 'login@email.com';
      $this->form->password = 'hunter2';
      expect('login should return true if able to log in user', $this->assertTrue($this->form->login()));
    });
  }
}
