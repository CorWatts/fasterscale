<?php

namespace site\tests\unit\models;

use Yii;

class SignupFormTest extends \Codeception\Test\Unit
{
  use \Codeception\Specify;

  private $vals = [
    'email'           => 'fake@email.com',
    'password'        => 'password',
    'timezone'        => "America/Los_Angeles",
    'email_threshold' => 60,
    'partner_email1'  => 'partner1@email.com',
    'partner_email2'  => 'partner2@email.com',
    'partner_email3'  => 'partner3@email.com',
  ];

  private $whitelist = [
    'email',
    'timezone',
    'partner_email1',
    'partner_email2',
    'partner_email3'
  ];

  public function setUp() {
    $this->container = new \yii\di\Container;
    $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
    $this->container->set('common\interfaces\UserOptionInterface', '\site\tests\_support\MockUserOption');
    $this->container->set('common\interfaces\TimeInterface', function () {
      return new \common\components\Time('America/Los_Angeles');
    });

    $user_option = $this->container->get('common\interfaces\UserOptionInterface');
    $time        = $this->container->get('common\interfaces\TimeInterface');

    $question = $this->getMockBuilder('\common\models\Question')
      ->setMethods(['save', 'attributes'])
      ->getMock();

    $this->user = $this->getMockBuilder('\common\models\User')
      ->setConstructorArgs([$user_option, $question, $time])
      ->setMethods(['save', 'attributes', 'findByEmail', 'setPassword', 'generateAuthKey', 'generateVerifyEmailToken', 'sendSignupNotificationEmail', 'sendVerifyEmail'])
      ->getMock();
    $this->user->method('save')->willReturn(true);
    $this->user->method('attributes')->willReturn([
      'id',
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

    parent::setUp();
  }

  protected function tearDown() {
    $this->user = null;
    parent::tearDown();
  }

  public function testSignup() {
    $container = new yii\di\Container;
    $form = $container->get('\site\models\SignupForm', [$this->user]);
    foreach($this->vals as $key => $val) {
      $form->$key = $val;
    }
    $form->send_email = true;

    expect('signup should return a newly-saved user when the user does not already exist', $this->assertArraySubset(array_intersect_key($this->vals, array_flip($this->whitelist)), $form->signup()->attributes)); // check what attributes does

    $container = new yii\di\Container;
    $this->user->method('findByEmail')->willReturn($this->user);
    $this->user->verify_email_token = 'a_token_confirmed';
    $form = $container->get('\site\models\SignupForm', [$this->user]);
    foreach($this->vals as $key => $val) {
      $form->$key = $val;
    }
    $form->send_email = true;
    expect('signup should return null when the user already exists and is confirmed', $this->assertNull($form->signup()));

  }

  public function testSetFields() {
    $container = new yii\di\Container;
    $form = $container->get('\site\models\SignupForm', [$this->user]);

    $form = $this->setAttrs($form, $this->vals);
    $form->send_email = true;

    $result = $form->setFields($this->user);
    $this->specify('populate should set the values of the user model from the form', function() use ($result) {
      foreach($this->whitelist as $attr) {
        $this->assertEquals($this->vals[$attr], $result->$attr);
      }
    });
  }

  private function setAttrs($form, Array $vals) {
    foreach($vals as $key => $val) {
      $form->$key = $val;
    }
    return $form;
  }
}
