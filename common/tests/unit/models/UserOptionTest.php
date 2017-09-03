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

  public $singleSimpleBehaviors = [
    [
      'id' => 396,
      'user_id' => 2,
      'option_id' => 107,
      'date' => '2016-06-17 04:12:43',
      'option' => [
        'id' => 107,
        'name' => 'numb',
        'category_id' => 6,
      ],
    ],
  ]; 

  public $singleComplexBehaviors = [
    [
      'id' => 397,
      'user_id' => 2,
      'option_id' => 2,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 2,
        'name' => 'resolving problems',
        'category_id' => 1,
      ],
    ], [
      'id' => 398,
      'user_id' => 2,
      'option_id' => 7,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 7,
        'name' => 'making eye contact',
        'category_id' => 1,
      ],
    ], [
      'id' => 399,
      'user_id' => 2,
      'option_id' => 13,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 13,
        'name' => 'less time/energy for God, meetings, and church',
        'category_id' => 2,
      ],
    ], [
      'id' => 400,
      'user_id' => 2,
      'option_id' => 19,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 19,
        'name' => 'flirting',
        'category_id' => 2,
      ],
    ], [
      'id' => 401,
      'user_id' => 2,
      'option_id' => 31,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 31,
        'name' => 'being resentful',
        'category_id' => 3,
      ],
    ], [
      'id' => 402,
      'user_id' => 2,
      'option_id' => 41,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 41,
        'name' => 'co-dependent rescuing',
        'category_id' => 3,
      ],
    ], [
      'id' => 403,
      'user_id' => 2,
      'option_id' => 64,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 64,
        'name' => 'too much caffeine',
        'category_id' => 4,
      ],
    ], [
      'id' => 404,
      'user_id' => 2,
      'option_id' => 95,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 95,
        'name' => 'panicked',
        'category_id' => 6,
      ],
    ], [
      'id' => 405,
      'user_id' => 2,
      'option_id' => 97,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 97,
        'name' => 'hopeless',
        'category_id' => 6,
      ],
    ], [
      'id' => 406,
      'user_id' => 2,
      'option_id' => 111,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 111,
        'name' => 'seeking out old unhealthy people and places',
        'category_id' => 6,
      ],
    ], [
      'id' => 407,
      'user_id' => 2,
      'option_id' => 113,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 113,
        'name' => 'people are angry with you',
        'category_id' => 6,
      ],
    ], [
      'id' => 408,
      'user_id' => 2,
      'option_id' => 122,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 122,
        'name' => 'returning to the place you swore you would never go again',
        'category_id' => 7,
      ],
    ], [
      'id' => 409,
      'user_id' => 2,
      'option_id' => 123,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 123,
        'name' => 'giving up',
        'category_id' => 7,
      ],
    ], [
      'id' => 410,
      'user_id' => 2,
      'option_id' => 124,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 124,
        'name' => 'giving in',
        'category_id' => 7,
      ],
    ], [
      'id' => 411,
      'user_id' => 2,
      'option_id' => 125,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 125,
        'name' => 'out of control',
        'category_id' => 7,
      ],
    ], [
      'id' => 412,
      'user_id' => 2,
      'option_id' => 126,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 126,
        'name' => 'lost in your addiction',
        'category_id' => 7,
      ],
    ], [
      'id' => 413,
      'user_id' => 2,
      'option_id' => 127,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 127,
        'name' => 'lying to yourself and others',
        'category_id' => 7,
      ],
    ], [
      'id' => 414,
      'user_id' => 2,
      'option_id' => 128,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 128,
        'name' => 'feeling you just can\'t manage without your coping behavior, at least for now',
        'category_id' => 7,
      ],
    ], [
      'id' => 415,
      'user_id' => 2,
      'option_id' => 129,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 129,
        'name' => 'shame',
        'category_id' => 7,
      ],
    ], [
      'id' => 416,
      'user_id' => 2,
      'option_id' => 130,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 130,
        'name' => 'condemnation',
        'category_id' => 7,
      ],
    ], [
      'id' => 417,
      'user_id' => 2,
      'option_id' => 131,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 131,
        'name' => 'guilt',
        'category_id' => 7,
      ],
    ], [
      'id' => 418,
      'user_id' => 2,
      'option_id' => 132,
      'date' => '2016-06-21 04:08:36',
      'option' => [
        'id' => 132,
        'name' => 'aloneness',
        'category_id' => 7,
      ],
    ],
  ];

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
    $this->container = new \yii\di\Container;
    $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
    $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');
    $this->container->set('common\interfaces\TimeInterface', function () {
        return new \common\components\Time('America/Los_Angeles');
      });

    $time = $this->container->get('common\interfaces\TimeInterface');
    $user = $this->container->get('common\interfaces\UserInterface');

    $this->user_option = $this
                            ->getMockBuilder("common\models\UserOption")
                            ->setConstructorArgs([$time])
                            ->setMethods(array("getIsNewRecord", "save"))
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
      
      expect('calculateScore should work with a single date item and simple behaviors', $this->assertEquals(['2016-06-16T21:12:43-07:00' => 29], $this->user_option->calculateScore($this->singleSimpleBehaviors)));
      
      expect('calculateScore should work with a single date item and complex behaviors', $this->assertEquals(['2016-06-20T21:08:36-07:00' => 233], $this->user_option->calculateScore($this->singleComplexBehaviors)));
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
}
