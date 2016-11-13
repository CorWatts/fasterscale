<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;
use common\models\UserOption;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class UserOptionTest extends \Codeception\Test\Unit {
  use Specify;

  public $allBehaviors = [
    [
      'category_id' => 1,
      'name' => 'Restoration',
      'weight' => 0,
      'option_count' => 10,
    ], [
      'category_id' => 2,
      'name' => 'Forgetting Priorities',
      'weight' => 1,
      'option_count' => 17,
    ], [
      'category_id' => 3,
      'name' => 'Anxiety',
      'weight' => 2,
      'option_count' => 17,
    ], [
      'category_id' => 4,
      'name' => 'Speeding Up',
      'weight' => 4,
      'option_count' => 23,
    ], [
      'category_id' => 5,
      'name' => 'Ticked Off',
      'weight' => 6,
      'option_count' => 24,
    ], [
      'category_id' => 6,
      'name' => 'Exhausted',
      'weight' => 8,
      'option_count' => 28,
    ], [
      'category_id' => 7,
      'name' => 'Relapse/Moral Failure',
      'weight' => 10,
      'option_count' => 11,
    ]
  ];

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

  public function setUp() {
    parent::setUp();

    Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'common\unit\FakeUser',
        ],
      ],
    ]);

    $identity = new \common\unit\FakeUser();
    $identity->timezone = "America/Los_Angeles";

    // logs in the user 
    Yii::$app->user->setIdentity($identity);
  }

  protected function tearDown() {
    parent::tearDown();
  }

  public function testCalculateScore() {
    $this->specify('calculateScore should function correctly', function () {
      expect('calculateScore should return the empty set when null is passed', $this->assertEmpty(UserOption::calculateScore(null, $this->allBehaviors)));

      expect('calculateScore should return the empty set with no selected options', $this->assertEmpty(UserOption::calculateScore([], $this->allBehaviors)));
      
      expect('calculateScore should work with a single date item and simple behaviors', $this->assertEquals(UserOption::calculateScore($this->singleSimpleBehaviors, $this->allBehaviors), ['2016-06-16 21:12:43' => 29]));
      
      expect('calculateScore should work with a single date item and complex behaviors', $this->assertEquals(UserOption::calculateScore($this->singleComplexBehaviors, $this->allBehaviors), ['2016-06-20 21:08:36' => 233]));
    });
  }
}
