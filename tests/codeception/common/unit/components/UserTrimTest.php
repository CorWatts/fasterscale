<?php

namespace tests\codeception\common\unit\components;

use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\components\UserTrim;

/**
 * Time test
 */

class UserTrimTest extends TestCase {
  use Specify;

  public function setUp() {
    parent::setUp();
  }

  protected function tearDown() {
    parent::tearDown();
  }

  public function testIsPartnerEnabled() {
    $mock = new \stdClass();
    $mock->email_threshold = null;
    $mock->partner_email1 = null;
    $mock->partner_email2 = null;
    $mock->partner_email3 = null;
    $user = new UserTrim($mock);

    $this->specify('isPartnerEnabled should function correctly', function () use ($user, $mock) {
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is null', $this->assertFalse($user->isPartnerEnabled()));

      $mock->email_threshold = 10;
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is a positive integer', $this->assertFalse($user->isPartnerEnabled()));

      $mock->partner_email3 = 'hello@partner3.com';
      $mock->email_threshold = null;
      expect('isPartnerEnabled should return false when a partner is set and email_threshold is null', $this->assertFalse($user->isPartnerEnabled()));

      $mock->email_threshold = 10;
      expect('isPartnerEnabled should return true when at one partner is set and email_threshold is a positive integer', $this->assertTrue($user->isPartnerEnabled()));

      $mock->partner_email1 = 'hello@partner1.com';
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is a positive integer', $this->assertTrue($user->isPartnerEnabled()));

      $mock->email_threshold = 0;
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is 0', $this->assertTrue($user->isPartnerEnabled()));

      $mock->email_threshold = -7;
      expect('isPartnerEnabled should return false when two partners are set and email_threshold is a negative number', $this->assertFalse($user->isPartnerEnabled()));
    });
  }
}
