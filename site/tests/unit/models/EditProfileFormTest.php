<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\EditProfileForm;

class EditProfileFormTest extends \Codeception\Test\Unit {
  public $graph;
  public $values = [
      'timezone' => 'America/Los_Angeles',
      'send_email' => true,
      'email_category' => 4,
      'partner_email1' => 'partner1@example.com',
      'partner_email2' => 'partner2@example.com',
      'partner_email3' => 'partner3@example.com',
      'expose_graph' => true,
    ];

  public function setUp() {
    $this->graph = $this->getMockBuilder(common\components\Graph::class)
      ->setMethods(['create', 'destroy'])
      ->getMock();
    parent::setUp();
  }

  public function testLoadUser() {
    $user = $this->getUser();
    $form = new \site\models\EditProfileForm($user);
    $form->loadUser();
    expect('timezone should be equal', $this->assertEquals($form->timezone, $user->timezone));
    expect('partner_email1 should be equal', $this->assertEquals($form->partner_email1, $user->partner_email1));
    expect('partner_email2 should be equal', $this->assertEquals($form->partner_email2, $user->partner_email2));
    expect('partner_email3 should be equal', $this->assertEquals($form->partner_email3, $user->partner_email3));
    expect('expose_graph should be equal', $this->assertEquals($form->expose_graph, $user->expose_graph));
    expect('send_email should be true', $this->assertTrue($form->send_email));

    $user->send_email = false;
    $form->loadUser();
    expect('send_email should be false', $this->assertFalse($form->send_email));

    $user->partner_email1 = null;
    $user->partner_email2 = null;
    $user->partner_email3 = null;
    $form->loadUser();
    expect('send_email should be false', $this->assertFalse($form->send_email));
  }

  public function testSaveProfile() {
    $user = $this->getUser();
    $form = new \site\models\EditProfileForm($user);
    $form->loadUser();

    // Set up dependency injections
    Yii::$container
      ->set(\common\interfaces\TimeInterface::class, function () { return new \common\components\Time('UTC'); });
    Yii::$container
      ->set(\common\components\Graph::class, function () { return $this->graph; });
    Yii::$container
      ->set(\common\interfaces\UserBehaviorInterface::class, function () { return new FakeUserBehavior(); });

    // Actually begin testing
    $form->attributes = $this->values;
    expect('saveProfile should return the user', $this->assertEquals($user, $form->saveProfile()));
    expect('saveProfile should set the user\'s attributes to be the form values', $this->assertEquals($this->values, $user->attributes));

    $form->send_email = 'not_a_boolean';
    expect('saveProfile should return the user with the partner-related settings equal to null', $this->assertNull($form->saveProfile()));

    $null_vals = [
      'partner_email1'  => null,
      'partner_email2'  => null,
      'partner_email3'  => null,
      'send_email'      => false,
      'email_category'  => 4,
      'timezone'        => 'America/Los_Angeles',
      'expose_graph'    => true,
    ];
    $form->send_email = false;
    $ret = $form->saveProfile();
    expect('saveProfile should return the user with the partner-related settings equal to null', $this->assertEquals($null_vals, $user->attributes));

    $this->graph
      ->expects($this->once())
      ->method('destroy');
    $form->expose_graph = false;
    $ret = $form->saveProfile();
    expect('if form->expose_graph is set to false, destroy the graph and set user->expose_graph to false', $this->assertFalse($ret->expose_graph));

    $this->graph
      ->expects($this->once())
      ->method('create');
    $form->expose_graph = true;
    $ret = $form->saveProfile();
    expect('if form->expose_graph is set to true, create the graph and set user->expose_graph to true', $this->assertTrue($ret->expose_graph));

    $form->send_email = 'not_a_boolean';
    expect('saveProfile should return null if the form validation fails', $this->assertNull($form->saveProfile()));
  }

  private function getUser() {
    $user = $this->getmockbuilder('\common\models\User')
      ->disableoriginalconstructor()
      ->setmethods(['getisnewrecord', 'attributes', 'save', 'findbypasswordresettoken', 'removepasswordresettoken', 'getid'])
      ->getmock();
    $user->method('attributes')->willReturn([
      'timezone',
      'partner_email1',
      'partner_email2',
      'partner_email3',
      'send_email',
      'email_category',
      'expose_graph',
    ]);
    $user->timezone = 'America/Los_Angeles';
    $user->partner_email1 = 'partner1@example.com';
    $user->partner_email2 = 'partner2@example.com';
    $user->partner_email3 = 'partner3@example.com';
    $user->send_email = true;
    $user->email_category = 4;
    $user->expose_graph = true;
    return $user;
  }
}

class FakeUserBehavior {
  public $scores = true;
  public function getCheckInBreakdown() { return $this->scores; }
}
