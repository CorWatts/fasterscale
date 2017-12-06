<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserOptionTest extends \Codeception\Test\Unit {
  use Specify;

  public $singleSimpleBehaviorNoOption = [
    [
      'id' => 396,
      'user_id' => 2,
      'option_id' => 107,
      'date' => '2016-06-17 04:12:43',
    ],
  ]; 

  public $badSingleSimpleBehaviorNoOption = [
    [
      'id' => 396,
      'user_id' => 2,
      'option_id' => 99999,
      'date' => '2016-06-17 04:12:43',
    ],
  ]; 
  public function setUp() {
    // pull in test data
    $data = require(__DIR__.'/../data/checkinData.php');
    $this->singleBhvr = $data['singleBhvr'];
    $this->manyBhvrs = $data['manyBhvrs'];
    $this->allBhvrs = $data['allBhvrs'];
    $this->multipleDates = $data['multipleDates'];

    $this->container = new \yii\di\Container;
    $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
    $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');
    $this->container->set('common\interfaces\OptionInterface', 'common\models\Option');
    $this->container->set('common\interfaces\TimeInterface', function () {
        return new \common\components\Time('America/Los_Angeles');
      });

    $time = $this->container->get('common\interfaces\TimeInterface');
    $option = $this->container->get('common\interfaces\OptionInterface');
    $user = $this->container->get('common\interfaces\UserInterface');

    $this->user_option = $this
                            ->getMockBuilder("common\models\UserOption")
                            ->setConstructorArgs([$option, $time])
                            ->setMethods(["getIsNewRecord", "save", "getBehaviorsByDate"])
                            ->getMock();
    parent::setUp();
  }

  protected function tearDown() {
    $this->user_option = null;
    parent::tearDown();
  }

  public function testCalculateScore() {
    $this->specify('calculateScore should function correctly', function () {
      expect('calculateScore should return the empty set when null is passed', $this->assertEmpty($this->user_option->calculateScore(null)));

      expect('calculateScore should return the empty set with no selected options', $this->assertEmpty($this->user_option->calculateScore([])));
      
      expect('calculateScore should work with a single date item and simple behaviors', $this->assertEquals(['2016-06-16T21:12:43-07:00' => 1], $this->user_option->calculateScore($this->singleBhvr)));
      
      expect('calculateScore should work with a single date item and complex behaviors', $this->assertEquals(['2016-06-20T21:08:36-07:00' => 43], $this->user_option->calculateScore($this->manyBhvrs)));

      expect('calculateScore should max out at 100', $this->assertEquals(['2016-08-03T22:57:53-07:00' => 100], $this->user_option->calculateScore($this->allBhvrs)));

      
      expect('calculateScore should work with multiple dates', $this->assertEquals([
          '2016-08-03T22:57:53-07:00' => 100,
          '2016-07-15T21:16:58-07:00' => 0,
          '2016-07-16T20:24:35-07:00' => 0,
          '2016-07-17T15:15:26-07:00' => 0,
          '2016-07-29T19:49:41-07:00' => 97
        ], $this->user_option->calculateScore($this->multipleDates)));
    });
  }

  public function testDecorate() {
    expect('decorate should add Option data to an array of UserOptions', $this->assertEquals($this->user_option->decorate($this->singleSimpleBehaviorNoOption),
                    [['id' => 396,
                      'user_id' => 2,
                      'option_id' => 107,
                      'date' => '2016-06-17 04:12:43',
                      'option' => [
                        'id' => 107,
                        'name' => 'numb',
                        'category_id' => 6,
                      ]]]));

    expect('decorate SHOULD NOT add Option data when the provided option_id is invalid',
      $this->assertEquals(
        $this->user_option->decorate($this->badSingleSimpleBehaviorNoOption),
        [['id' => 396,
          'user_id' => 2,
          'option_id' => 99999,
          'date' => '2016-06-17 04:12:43']]));
  }

  public function testDecorateWithCategory() {
    expect('decorate should add Option data and Category data to an array of UserOptions',
      $this->assertEquals(
        $this->user_option->decorateWithCategory($this->singleSimpleBehaviorNoOption),
         [['id' => 396,
           'user_id' => 2,
           'option_id' => 107,
           'date' => '2016-06-17 04:12:43',
           'option' => [
             'id' => 107,
             'name' => 'numb',
             'category_id' => 6,
             'category' => [
               'id' => 6,
               'name' => 'Exhausted',
               'weight' => 8
             ]
           ]]]));
  }

  public function testGetDailyScore() {
    // getBehaviorsByDate() is called internally by getDailyScore()
    $this
      ->user_option
      ->method('getBehaviorsByDate')
      ->willReturnOnConsecutiveCalls([], $this->manyBhvrs);

    expect('getDailyScore should return the score for the day given',
      $this->assertEquals($this->user_option->getDailyScore(),
      0));
    
    expect('getDailyScore should return the score for the day given',
      $this->assertEquals($this->user_option->getDailyScore('2017-08-01'),
      43));
  }
}
