<?php

namespace tests\codeception\common\unit\models;

use \DateTime;
use \DateTimeZone;
use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\models\UserOption;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class TimeTest extends TestCase
{
    use Specify;

    public function setUp()
    {
        parent::setUp();

        Yii::configure(Yii::$app, [
            'components' => [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'tests\codeception\common\unit\FakeUser',
                ],
            ],
        ]);

        $identity = new \tests\codeception\common\unit\FakeUser();
        $identity->timezone = "America/Los_Angeles";

        // logs in the user 
        Yii::$app->user->setIdentity($identity);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetLocalTime()
    {
      $this->specify('getLocalTime should function correctly', function () {
        expect('getLocalTime should work with user\'s set time', $this->assertEquals(Time::getLocalTime(), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s")));
        expect('getLocalTime should work with a custom timezone', $this->assertEquals(Time::getLocalTime("UTC"), (new DateTime("now"))->format("Y-m-d H:i:s")));
      });
    }

    public function testConvertLocalToUTC()
    {
      $this->specify('convertLocalToUTC should function correctly', function () {
        $la_tz = (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s");

        expect('convertLocalToUTC should convert a Los Angeles tz to UTC with the included time', $this->assertEquals(Time::convertLocalToUTC($la_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a Los Angeles tz to UTC without the included time', $this->assertEquals(Time::convertLocalToUTC($la_tz, false), (new DateTime("now"))->format("Y-m-d")));
        // with UTC
        Yii::$app->user->identity->timezone = "UTC";
        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");
        expect('convertLocalToUTC should convert a UTC tz correctly with the included time', $this->assertEquals(Time::convertLocalToUTC($utc_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a UTC tz correctly without the included time', $this->assertEquals(Time::convertLocalToUTC($utc_tz, false), (new DateTime("now"))->format("Y-m-d")));
      });
    }

    public function testConvertUTCToLocal()
    {
      $this->specify('convertUTCToLocal should function correctly', function () {
        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");

        expect('convertUTCToLocal should convert a UTC tz to Los Angeles with the included time', $this->assertEquals(Time::convertUTCToLocal($utc_tz), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d H:i:s")));
        expect('convertUTCToLocal should convert a UTC tz to Los Angeles without the included time', $this->assertEquals(Time::convertUTCToLocal($utc_tz, false), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d")));
        // with UTC
        Yii::$app->user->identity->timezone = "UTC";
        $utc_tz = (new DateTime("now"))->format("Y-m-d H:i:s");
        expect('convertLocalToUTC should convert a UTC tz correctly with the included time', $this->assertEquals(Time::convertLocalToUTC($utc_tz), (new DateTime("now"))->format("Y-m-d H:i:s")));
        expect('convertLocalToUTC should convert a UTC tz correctly without the included time', $this->assertEquals(Time::convertLocalToUTC($utc_tz, false), (new DateTime("now"))->format("Y-m-d")));
      });
    }

    public function testGetLocalDate()
    {
      $this->specify('getLocalDate should function correctly', function () {
        expect("getLocalDate should correctly get the user's local date", $this->assertEquals(Time::getLocalDate(), (new DateTime("now", new DateTimeZone("America/Los_Angeles")))->format("Y-m-d")));
        expect("getLocalDate should correctly get the local date of a specified timezone", $this->assertEquals(Time::getLocalDate("UTC"), (new DateTime("now", new DateTimeZone("UTC")))->format("Y-m-d")));
      });
    }

    public function testAlterLocalDate()
    {
      $this->specify('alterLocalDate should function correctly', function() {
        expect('alterLocalDate should add one day to the local time', $this->assertEquals(Time::alterLocalDate('2016-05-01 00:00:00', '+1 day'), (new DateTime("2016-05-01 00:00:00 +1 day"))->format("Y-m-d")));
        expect('alterLocalDate should subtract one week to the local time and change the year correctly', $this->assertEquals(Time::alterLocalDate('2016-01-01 04:03:00', '-1 week'), (new DateTime("2016-01-01 04:03:00 -1 week"))->format("Y-m-d")));
      });
    }

    public function testGetUTCBookends()
    {
      $this->specify('getUTCBookends should function correctly', function() {
        expect('getUTCBookends should return false if there is a space in the time string', $this->assertFalse(Time::getUTCBookends('2016-05-30 00:00:00')));
        expect('getUTCBookends should return false if there is a space at the start of the time string', $this->assertFalse(Time::getUTCBookends(' 2016-05-30 00:00:00')));
        expect('getUTCBookends should return false if there is a space at the end of the time string', $this->assertFalse(Time::getUTCBookends('2016-05-30 00:00:00 ')));
        expect('getUTCBookends should return UTC bookend times from the Los_Angeles tz', $this->assertEquals(Time::getUTCBookends('2016-05-30'), ['2016-05-30 07:00:00', '2016-05-31 06:59:59']));
        // with UTC
        Yii::$app->user->identity->timezone = "UTC";
        expect('getUTCBookends should return UTC bookend times from UTC tz', $this->assertEquals(Time::getUTCBookends('2016-05-30'), ['2016-05-30 00:00:00', '2016-05-30 23:59:59']));
      });
    }
}
