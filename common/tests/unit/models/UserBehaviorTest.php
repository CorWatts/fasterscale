<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserBehaviorTest extends \Codeception\Test\Unit {
  use Specify;

  public $singleSimpleBehaviorNoBehavior = [
    [
      'id' => 396,
      'user_id' => 2,
      'behavior_id' => 107,
      'date' => '2016-06-17 04:12:43',
    ],
  ]; 

  public $badSingleSimpleBehaviorNoBehavior = [
    [
      'id' => 396,
      'user_id' => 2,
      'behavior_id' => 99999,
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
    $this->container->set('common\interfaces\BehaviorInterface', 'common\models\Behavior');
    $this->container->set('common\interfaces\TimeInterface', function () {
        return new \common\components\Time('America/Los_Angeles');
      });

    $time = $this->container->get('common\interfaces\TimeInterface');
    $behavior = $this->container->get('common\interfaces\BehaviorInterface');
    $user = $this->container->get('common\interfaces\UserInterface');

    $this->user_behavior = $this
                            ->getMockBuilder("common\models\UserBehavior")
                            ->setConstructorArgs([$behavior, $time])
                            ->setMethods(['getIsNewRecord', 'save', 'getBehaviorsByDate', 'getBehaviorsWithCounts'])
                            ->getMock();
    parent::setUp();
  }

  protected function tearDown() {
    $this->user_behavior = null;
    parent::tearDown();
  }

  public function testCalculateScore() {
    $this->specify('calculateScore should function correctly', function () {
      expect('calculateScore should return the empty set when null is passed', $this->assertEmpty($this->user_behavior->calculateScore(null)));

      expect('calculateScore should return the empty set with no selected behaviors', $this->assertEmpty($this->user_behavior->calculateScore([])));
      
      expect('calculateScore should work with a single date item and simple behaviors', $this->assertEquals(['2016-06-16T21:12:43-07:00' => 1], $this->user_behavior->calculateScore($this->singleBhvr)));
      
      expect('calculateScore should work with a single date item and complex behaviors', $this->assertEquals(['2016-06-20T21:08:36-07:00' => 43], $this->user_behavior->calculateScore($this->manyBhvrs)));

      expect('calculateScore should max out at 100', $this->assertEquals(['2016-08-03T22:57:53-07:00' => 100], $this->user_behavior->calculateScore($this->allBhvrs)));

      
      expect('calculateScore should work with multiple dates', $this->assertEquals([
          '2016-08-03T22:57:53-07:00' => 100,
          '2016-07-15T21:16:58-07:00' => 0,
          '2016-07-16T20:24:35-07:00' => 0,
          '2016-07-17T15:15:26-07:00' => 0,
          '2016-07-29T19:49:41-07:00' => 97
        ], $this->user_behavior->calculateScore($this->multipleDates)));
    });
  }

  public function testDecorate() {
    expect('decorate should add Behavior data to an array of UserBehaviors', $this->assertEquals($this->user_behavior->decorate($this->singleSimpleBehaviorNoBehavior),
                    [['id' => 396,
                      'user_id' => 2,
                      'behavior_id' => 107,
                      'date' => '2016-06-17 04:12:43',
                      'behavior' => [
                        'id' => 107,
                        'name' => 'numb',
                        'category_id' => 6,
                      ]]]));

    expect('decorate SHOULD NOT add Behavior data when the provided behavior_id is invalid',
      $this->assertEquals(
        $this->user_behavior->decorate($this->badSingleSimpleBehaviorNoBehavior),
        [['id' => 396,
          'user_id' => 2,
          'behavior_id' => 99999,
          'date' => '2016-06-17 04:12:43']]));
  }

  public function testDecorateWithCategory() {
    expect('decorate should add Behavior data and Category data to an array of UserBehaviors',
      $this->assertEquals(
        $this->user_behavior->decorateWithCategory($this->singleSimpleBehaviorNoBehavior),
         [['id' => 396,
           'user_id' => 2,
           'behavior_id' => 107,
           'date' => '2016-06-17 04:12:43',
           'behavior' => [
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
      ->user_behavior
      ->method('getBehaviorsByDate')
      ->willReturnOnConsecutiveCalls([], $this->manyBhvrs);

    expect('getDailyScore should return the score for the day given',
      $this->assertEquals($this->user_behavior->getDailyScore(),
      0));
    
    expect('getDailyScore should return the score for the day given',
      $this->assertEquals($this->user_behavior->getDailyScore('2017-08-01'),
      43));
  }

  public function testGetTopBehaviors() {
    $this
      ->user_behavior
      ->method('getBehaviorsWithCounts')
      ->willReturnOnConsecutiveCalls([], [
        [
          'user_id' => 1,
          'behavior_id' => 1,
          'count' => 5
        ], [
          'user_id' => 1,
          'behavior_id' => 2,
          'count' => 4
        ], [
          'user_id' => 1,
          'behavior_id' => 3,
          'count' => 3
        ]
      ]);

    expect('getTopBehaviors to return the empty array if the user has not logged any behaviors', $this->assertEquals([], $this->user_behavior->getTopBehaviors()));
    expect('getTopBehaviors to return embelished data corresponding to the most commonly selected behaviors', $this->assertEquals(
      [[
        'user_id' => 1,
        'behavior_id' => 1,
        'count' => 5,
        'behavior' => [
          'id' => 1,
          'name' => 'no current secrets',
          'category_id' => 1,
          'category' => [
            'id' => 1,
            'weight' => 0,
            'name' => 'Restoration',
          ],
        ],
      ], [
        'user_id' => 1,
        'behavior_id' => 2,
        'count' => 4,
        'behavior' => [
          'id' => 2,
          'name' => 'resolving problems',
          'category_id' => 1,
          'category' => [
            'id' => 1,
            'weight' => 0,
            'name' => 'Restoration',
          ],
        ],
      ], [
        'user_id' => 1,
        'behavior_id' => 3,
        'count' => 3,
        'behavior' => [
          'id' => 3,
          'name' => 'identifying fears and feelings',
          'category_id' => 1,
          'category' => [
            'id' => 1,
            'weight' => 0,
            'name' => 'Restoration',
          ],
        ],
      ]], $this->user_behavior->getTopBehaviors()));
  }

  public function testGetBehaviorsByCategory() {
    $this
      ->user_behavior
      ->method('getBehaviorsWithCounts')
      ->willReturnOnConsecutiveCalls([], [
        [
          'user_id' => 1,
          'behavior_id' => 1,
          'count' => 5
        ], [
          'user_id' => 1,
          'behavior_id' => 20,
          'count' => 4
        ], [
          'user_id' => 1,
          'behavior_id' => 50,
          'count' => 3
        ]
      ]);

    expect('getBehaviorsByCategory to return the empty array if the user has not logged any behaviors', $this->assertEquals([], $this->user_behavior->getBehaviorsByCategory()));
    expect('getBehaviorsByCategory to return the empty array if the user has not logged any behaviors', $this->assertEquals([
      1 => [
        'name' => 'Restoration',
        'count' => 5,
        'color' => '#008000',
        'highlight' => '#199919'
      ],
      2 => [
        'name' => 'Forgetting Priorities',
        'count' => 4,
        'color' => '#4CA100',
        'highlight' => '#61B219'
      ],
      4 => [
        'name' => 'Speeding Up',
        'count' => 3,
        'color' => '#E5E500',
        'highlight' => '#E5E533'
      ]
    ], $this->user_behavior->getBehaviorsByCategory()));
  }
}
