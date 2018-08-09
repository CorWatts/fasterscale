<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\EditProfileForm;

class EditProfileFormTest extends \Codeception\Test\Unit {

  public function testLoadUser() {
    $user = $this->getUser();
    $form = new \site\models\EditProfileForm($user);
    $form->loadUser();
    expect('timezone should be equal', $this->assertEquals($form->timezone, $user->timezone));
    expect('email_threshold should be equal', $this->assertEquals($form->email_threshold, $user->email_threshold));
    expect('partner_email1 should be equal', $this->assertEquals($form->partner_email1, $user->partner_email1));
    expect('partner_email2 should be equal', $this->assertEquals($form->partner_email2, $user->partner_email2));
    expect('partner_email3 should be equal', $this->assertEquals($form->partner_email3, $user->partner_email3));
    expect('expose_graph should be equal', $this->assertEquals($form->expose_graph, $user->expose_graph));
    expect('send_email should be true', $this->assertTrue($form->send_email));

    $user->email_threshold = null;
    $form->loadUser();
    expect('send_email should be false', $this->assertFalse($form->send_email));

    $user->partner_email1 = null;
    $user->partner_email2 = null;
    $user->partner_email3 = null;
    $form->loadUser();
    expect('send_email should be false', $this->assertFalse($form->send_email));
  }

  private function getUser() {
    $user = $this->getmockbuilder('\common\models\user')
      ->disableoriginalconstructor()
      ->setmethods(['getisnewrecord', 'attributes', 'save', 'findbypasswordresettoken', 'removepasswordresettoken'])
      ->getmock();
    $user->method('attributes')->willReturn([
      'timezone',
      'email_threshold',
      'partner_email1',
      'partner_email2',
      'partner_email3',
      'expose_graph',
    ]);
    $user->timezone = 'America/Los_Angeles';
    $user->email_threshold = 10;
    $user->partner_email1 = 'partner1@email.com';
    $user->partner_email2 = 'partner2@email.com';
    $user->partner_email3 = 'partner3@email.com';
    $user->expose_graph = false;
    return $user;
  }
}
