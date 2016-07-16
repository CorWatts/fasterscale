<?php

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\models\User;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserTest extends TestCase {
  use Specify;

  public function setUp() {
    parent::setUp();

    Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'tests\codeception\common\unit\FakeUser',
        ],
      ],
    ]);

    $identity = new User();
    $identity->timezone = "America/Los_Angeles";

    // logs in the user 
    Yii::$app->user->setIdentity($identity);
  }

  protected function tearDown() {
    parent::tearDown();
  }

  public function testIsPartnerEnabled() {
    $this->specify('isPartnerEnabled should function correctly', function () {
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is null', $this->assertFalse(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->email_threshold = 10;
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is a positive integer', $this->assertFalse(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->partner_email3 = 'hello@partner3.com';
      Yii::$app->user->identity->email_threshold = null;
      expect('isPartnerEnabled should return false when a partner is set and email_threshold is null', $this->assertFalse(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->email_threshold = 10;
      expect('isPartnerEnabled should return true when at one partner is set and email_threshold is a positive integer', $this->assertTrue(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->partner_email1 = 'hello@partner1.com';
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is a positive integer', $this->assertTrue(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->email_threshold = 0;
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is 0', $this->assertTrue(Yii::$app->user->identity->isPartnerEnabled()));

      Yii::$app->user->identity->email_threshold = -7;
      expect('isPartnerEnabled should return false when two partners are set and email_threshold is a negative number', $this->assertFalse(Yii::$app->user->identity->isPartnerEnabled()));
    });
  }
}
