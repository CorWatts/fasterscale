<?php

namespace tests\codeception\common\unit\models;

use \DateTime;
use \DateTimeZone;
use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\components\Time;

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
}
