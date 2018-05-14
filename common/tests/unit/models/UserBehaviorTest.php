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

  public function testRules() {
    expect('rules', $this->assertEquals($this->user_behavior->rules(), [
      [['user_id', 'behavior_id', 'date'], 'required'],
      [['user_id', 'behavior_id'], 'integer'],
    ]));
  }

  public function testAttributeLabels() {
    expect('attributeLabels', $this->assertEquals($this->user_behavior->attributelabels(), [
      'id'        => 'ID',
      'date'      => 'Date',
      'user_id'   => 'User ID',
      'behavior_id' => 'Behavior ID',
    ]));
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
             ]
           ]]]));
  }

  public function testGetBehaviorsByCategory() {
    expect('getBehaviorsByCategory to return the empty array if the user has not logged any behaviors', $this->assertEquals([], $this->user_behavior->getBehaviorsByCategory($this->user_behavior::decorateWithCategory([]))));
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
    ], $this->user_behavior->getBehaviorsByCategory($this->user_behavior::decorateWithCategory([
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
      ]))));
  }

  public function testGetCheckinBreakdown() {
    Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'common\tests\unit\FakeUser',
        ],
      ],
    ]);
    $identity = new \common\tests\unit\FakeUser();
    $tz = 'America/Los_Angeles';
    $identity->timezone = $tz;
    // logs in the user -- we use Yii::$app->user->id in getCheckinBreakdown()
    Yii::$app->user->setIdentity($identity);

    /* get mocked Time */
    $tz = new \DateTimeZone($tz);
    $date_range = new \DatePeriod(new \DateTime('2019-02-03', $tz),
                                  new \DateInterval('P1D'),
                                  new \DateTime('2019-03-05', $tz));
    $time = $this
        ->getMockBuilder("common\components\Time")
        ->setConstructorArgs(['America/Los_Angeles'])
        ->setMethods(['getDateTimesInPeriod'])
        ->getMock();
    $time
      ->method('getDateTimesInPeriod')
      ->willReturn($date_range);

    $behavior = new \common\models\Behavior();

    $this->user_behavior = $this
                            ->getMockBuilder("common\models\UserBehavior")
                            ->setConstructorArgs([$behavior, $time])
                            ->setMethods(['getIsNewRecord', 'save', 'getBehaviorsByDate', 'getBehaviorsWithCounts'])
                            ->getMock();

    $bhvrs = require(__DIR__.'/../data/behaviorsWithCounts.php');
    $expected = require(__DIR__.'/../data/expected_getCheckinBreakdown.php');
    $this->user_behavior->method('getBehaviorsWithCounts')->willReturn(...$bhvrs);
		expect('asdf', $this->assertEquals($expected, $this->user_behavior->getCheckinBreakdown()));
  }
}
