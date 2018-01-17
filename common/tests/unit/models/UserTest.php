<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;
use common\models\User;

date_default_timezone_set('UTC');

/**
 * User test
 */

class UserTest extends \Codeception\Test\Unit {
  use Specify;

  private $user;

	public $questionData = [
	[
		'id' => 641,
		'user_id' => 2,
		'option_id' => 13,
		'user_option_id' => 821,
		'question' => 1,
		'answer' => 'alsgn',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 13,
			'name' => 'less time/energy for God, meetings, and church',
			'category_id' => 2,
		],
	],
	[
		'id' => 642,
		'user_id' => 2,
		'option_id' => 13,
		'user_option_id' => 821,
		'question' => 2,
		'answer' => 'loiun',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 13,
			'name' => 'less time/energy for God, meetings, and church',
			'category_id' => 2,
		],
	],
	[
		'id' => 643,
		'user_id' => 2,
		'option_id' => 13,
		'user_option_id' => 821,
		'question' => 3,
		'answer' => 'liun',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 13,
			'name' => 'less time/energy for God, meetings, and church',
			'category_id' => 2,
		],
	],
	[
		'id' => 644,
		'user_id' => 2,
		'option_id' => 29,
		'user_option_id' => 823,
		'question' => 1,
		'answer' => 'ljnb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 29,
			'name' => 'using profanity',
			'category_id' => 3,
		],
	],
	[
		'id' => 645,
		'user_id' => 2,
		'option_id' => 29,
		'user_option_id' => 823,
		'question' => 2,
		'answer' => 'liunb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 29,
			'name' => 'using profanity',
			'category_id' => 3,
		],
	],
	[
		'id' => 646,
		'user_id' => 2,
		'option_id' => 29,
		'user_option_id' => 823,
		'question' => 3,
		'answer' => 'ilub ',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 29,
			'name' => 'using profanity',
			'category_id' => 3,
		],
	],
	[
		'id' => 647,
		'user_id' => 2,
		'option_id' => 48,
		'user_option_id' => 825,
		'question' => 1,
		'answer' => 'liub',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 48,
			'name' => 'workaholic',
			'category_id' => 4,
		],
	],
	[
		'id' => 648,
		'user_id' => 2,
		'option_id' => 48,
		'user_option_id' => 825,
		'question' => 2,
		'answer' => 'liub',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 48,
			'name' => 'workaholic',
			'category_id' => 4,
		],
	],
	[
		'id' => 649,
		'user_id' => 2,
		'option_id' => 48,
		'user_option_id' => 825,
		'question' => 3,
		'answer' => 'liub ',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 48,
			'name' => 'workaholic',
			'category_id' => 4,
		],
	],
	[
		'id' => 650,
		'user_id' => 2,
		'option_id' => 89,
		'user_option_id' => 828,
		'question' => 1,
		'answer' => 'liub',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 89,
			'name' => 'obsessive (stuck) thoughts',
			'category_id' => 5,
		],
	],
	[
		'id' => 651,
		'user_id' => 2,
		'option_id' => 89,
		'user_option_id' => 828,
		'question' => 2,
		'answer' => 'liuby',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 89,
			'name' => 'obsessive (stuck) thoughts',
			'category_id' => 5,
		],
	],
	[
		'id' => 652,
		'user_id' => 2,
		'option_id' => 89,
		'user_option_id' => 828,
		'question' => 3,
		'answer' => 'uiylb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 89,
			'name' => 'obsessive (stuck) thoughts',
			'category_id' => 5,
		],
	],
	[
		'id' => 653,
		'user_id' => 2,
		'option_id' => 111,
		'user_option_id' => 829,
		'question' => 1,
		'answer' => 'liub',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 111,
			'name' => 'seeking out old unhealthy people and places',
			'category_id' => 6,
		],
	],
	[
		'id' => 654,
		'user_id' => 2,
		'option_id' => 111,
		'user_option_id' => 829,
		'question' => 2,
		'answer' => 'liuyb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 111,
			'name' => 'seeking out old unhealthy people and places',
			'category_id' => 6,
		],
	],
	[
		'id' => 655,
		'user_id' => 2,
		'option_id' => 111,
		'user_option_id' => 829,
		'question' => 3,
		'answer' => 'iuyb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 111,
			'name' => 'seeking out old unhealthy people and places',
			'category_id' => 6,
		],
	],
	[
		'id' => 656,
		'user_id' => 2,
		'option_id' => 122,
		'user_option_id' => 831,
		'question' => 1,
		'answer' => 'iuyb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 122,
			'name' => 'returning to the place you swore you would never go again',
			'category_id' => 7,
		],
	],
	[
		'id' => 657,
		'user_id' => 2,
		'option_id' => 122,
		'user_option_id' => 831,
		'question' => 2,
		'answer' => 'iuyb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 122,
			'name' => 'returning to the place you swore you would never go again',
			'category_id' => 7,
		],
	],
	[
		'id' => 658,
		'user_id' => 2,
		'option_id' => 122,
		'user_option_id' => 831,
		'question' => 3,
		'answer' => 'liuyb',
		'date' => '2016-09-10 19:27:43',
		'option' => 
		[
			'id' => 122,
			'name' => 'returning to the place you swore you would never go again',
			'category_id' => 7,
		],
	],
];
public $userQuestions = [
	13 => [
		'question' => [
			'id' => 13,
			'title' => 'less time/energy for God, meetings, and church',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'alsgn',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'loiun',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liun',
			],
		],
	],
	29 => [
		'question' => [
			'id' => 29,
			'title' => 'using profanity',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'ljnb',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liunb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'ilub ',
			],
		],
	],
	48 => [
		'question' => [
			'id' => 48,
			'title' => 'workaholic',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liub',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liub ',
			],
		],
	],
	89 => [
		'question' => [
			'id' => 89,
			'title' => 'obsessive (stuck) thoughts',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liuby',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'uiylb',
			],
		],
	],
	111 => [
		'question' => [
			'id' => 111,
			'title' => 'seeking out old unhealthy people and places',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'liub',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'liuyb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'iuyb',
			],
		],
	],
	122 => [
		'question' => [
			'id' => 122,
			'title' => 'returning to the place you swore you would never go again',
		],
		'answers' => [
			[
				'title' => 'How does it affect me? How do I act and feel?',
				'answer' => 'iuyb',
			], [
				'title' => 'How does it affect the important people in my life?',
				'answer' => 'iuyb',
			], [
				'title' => 'Why do I do this? What is the benefit for me?',
				'answer' => 'liuyb',
			],
		],
	],
];

public $optionData = [
	[
		'id' => 820,
		'user_id' => 2,
		'option_id' => 7,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 7,
			'name' => 'making eye contact',
			'category_id' => 1,
			'category' => [
				'id' => 1,
				'name' => 'Restoration',
				'weight' => '0',
			],
		],
	], [
		'id' => 821,
		'user_id' => 2,
		'option_id' => 13,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 13,
			'name' => 'less time/energy for God, meetings, and church',
			'category_id' => 2,
			'category' => [
				'id' => 2,
				'name' => 'Forgetting Priorities',
				'weight' => '0.016',
			],
		],
	], [
		'id' => 822,
		'user_id' => 2,
		'option_id' => 18,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 18,
			'name' => 'changes in goals',
			'category_id' => 2,
			'category' => [
				'id' => 2,
				'name' => 'Forgetting Priorities',
				'weight' => '0.016',
			],
		],
	], [
		'id' => 823,
		'user_id' => 2,
		'option_id' => 29,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 29,
			'name' => 'using profanity',
			'category_id' => 3,
			'category' => [
				'id' => 3,
				'name' => 'Anxiety',
				'weight' => '0.032',
			],
		],
	], [
		'id' => 824,
		'user_id' => 2,
		'option_id' => 41,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 41,
			'name' => 'co-dependent rescuing',
			'category_id' => 3,
			'category' => [
				'id' => 3,
				'name' => 'Anxiety',
				'weight' => '0.032',
			],
		],
	], [
		'id' => 825,
		'user_id' => 2,
		'option_id' => 48,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 48,
			'name' => 'workaholic',
			'category_id' => 4,
			'category' => [
				'id' => 4,
				'name' => 'Speeding Up',
				'weight' => '0.064',
			],
		],
	], [
		'id' => 826,
		'user_id' => 2,
		'option_id' => 72,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 72,
			'name' => 'black and white, all or nothing thinking',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 827,
		'user_id' => 2,
		'option_id' => 79,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 79,
			'name' => 'blaming',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 828,
		'user_id' => 2,
		'option_id' => 89,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 89,
			'name' => 'obsessive (stuck) thoughts',
			'category_id' => 5,
			'category' => [
				'id' => 5,
				'name' => 'Ticked Off',
				'weight' => '0.128',
			],
		],
	], [
		'id' => 829,
		'user_id' => 2,
		'option_id' => 111,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 111,
			'name' => 'seeking out old unhealthy people and places',
			'category_id' => 6,
			'category' => [
				'id' => 6,
				'name' => 'Exhausted',
				'weight' => '0.256',
			],
		],
	], [
		'id' => 830,
		'user_id' => 2,
		'option_id' => 118,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 118,
			'name' => 'not returning phone calls',
			'category_id' => 6,
			'category' => [
				'id' => 6,
				'name' => 'Exhausted',
				'weight' => '0.256',
			],
		],
	], [
		'id' => 831,
		'user_id' => 2,
		'option_id' => 122,
		'date' => '2016-09-10 19:26:04',
		'option' => [
			'id' => 122,
			'name' => 'returning to the place you swore you would never go again',
			'category_id' => 7,
			'category' => [
				'id' => 7,
				'name' => 'Relapse/Moral Failure',
				'weight' => '0.512',
			],
		],
	],
];

public $userOptions = [
	1 => [
		'category_name' => 'Restoration',
		'options' => [
			[
				'id' => 7,
				'name' => 'making eye contact',
			],
		],
	],
	2 => [
		'category_name' => 'Forgetting Priorities',
		'options' => [
			[
				'id' => 13,
				'name' => 'less time/energy for God, meetings, and church',
			], [
				'id' => 18,
				'name' => 'changes in goals',
			],
		],
  ],
  3 => [
		'category_name' => 'Anxiety',
		'options' => [
			[
				'id' => 29,
				'name' => 'using profanity',
			], [
				'id' => 41,
				'name' => 'co-dependent rescuing',
			],
		],
	],
	4 => [
		'category_name' => 'Speeding Up',
		'options' => [
			[
				'id' => 48,
				'name' => 'workaholic',
			],
		],
	],
	5 => [
		'category_name' => 'Ticked Off',
		'options' => [
			[
				'id' => 72,
				'name' => 'black and white, all or nothing thinking',
			], [
				'id' => 79,
				'name' => 'blaming',
			], [
				'id' => 89,
				'name' => 'obsessive (stuck) thoughts',
			],
		],
	],
	6 => [
		'category_name' => 'Exhausted',
		'options' => [
			[
				'id' => 111,
				'name' => 'seeking out old unhealthy people and places',
			], [
				'id' => 118,
				'name' => 'not returning phone calls',
			],
		],
	],
	7 => [
		'category_name' => 'Relapse/Moral Failure',
		'options' => [
			[
				'id' => 122,
				'name' => 'returning to the place you swore you would never go again',
			],
		],
	],
];

public $exportData = [
    [
      'id' => 485,
      'date' => '2017-07-29 10:40:29',
      'option_id' => 59,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'option' => [
        'id' => 59,
        'name' => 'repetitive, negative thoughts',
        'category_id' => 4,
        'category' => [
          'id' => 4,
          'name' => 'Speeding Up',
          'weight' => 4,
        ],
      ],
    ], [
      'id' => 487,
      'date' => '2017-07-29 10:40:29',
      'option_id' => 106,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'option' => [
        'id' => 106,
        'name' => 'tired',
        'category_id' => 6,
        'category' => [
          'id' => 6,
          'name' => 'Exhausted',
          'weight' => 8,
        ],
      ],
    ], [
      'id' => 488,
      'date' => '2017-07-29 10:40:29',
      'option_id' => 125,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'option' => [
        'id' => 125,
        'name' => 'out of control',
        'category_id' => 7,
        'category' => [
          'id' => 7,
          'name' => 'Relapse/Moral Failure',
          'weight' => 10,
        ],
      ],
    ], [
      'id' => 486,
      'date' => '2017-07-29 10:40:29',
      'option_id' => 89,
      'question1' => 'q1',
      'question2' => 'q2',
      'question3' => 'q3',
      'option' => [
        'id' => 89,
        'name' => 'obsessive (stuck) thoughts',
        'category_id' => 5,
        'category' => [
          'id' => 5,
          'name' => 'Ticked Off',
          'weight' => 6,
        ],
      ],
    ]
  ];

  public function setUp() {
    $this->container = new \yii\di\Container;
    $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
    $this->container->set('common\interfaces\UserOptionInterface', '\site\tests\_support\MockUserOption');
    $this->container->set('common\interfaces\TimeInterface', function () {
      return new \common\components\Time('America/Los_Angeles');
    });

    $user_option = $this->container->get('common\interfaces\UserOptionInterface');
    $time        = $this->container->get('common\interfaces\TimeInterface');

    $question = $this->getMockBuilder('\common\models\Question')
      ->setMethods(['save', 'attributes'])
      ->getMock();

    $this->user = $this->getMockBuilder('\common\models\User')
      ->setConstructorArgs([$user_option, $question, $time])
      ->setMethods(['save', 'attributes'])
      ->getMock();
    $this->user->method('save')->willReturn(true);
    $this->user->method('attributes')->willReturn([
      'id',
      'password_hash',
      'password_reset_token',
      'verify_email_token',
      'email',
      'auth_key',
      'role',
      'status',
      'created_at',
      'updated_at',
      'password',
      'timezone',
      'email_threshold',
      'partner_email1',
      'partner_email2',
      'partner_email3',
    ]);

    parent::setUp();
  }

  protected function tearDown() {
    $this->user = null;
    parent::tearDown();
  }

  public function testParseQuestionData() {
    $this->specify('parseQuestionData should function correctly', function () {
      expect('parseQuestionData should return the correct structure with expected data', $this->assertEquals($this->user->parseQuestionData($this->questionData), $this->userQuestions));
      expect('parseQuestionData should return empty with the empty set', $this->assertEmpty($this->user->parseQuestionData([])));
    });
  }

  public function testParseOptionData() {
    $this->specify('parseOptionData should function correctly', function () {
      expect('parseOptionData should return the correct structure with expected data', $this->assertEquals($this->user->parseOptionData($this->optionData), $this->userOptions));
      expect('parseOptionData should return empty with the empty set', $this->assertEmpty($this->user->parseOptionData([])));
    });
  }

  public function testIsPartnerEnabled() {
    $this->specify('isPartnerEnabled should function correctly', function () {
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is null', $this->assertFalse($this->user->isPartnerEnabled()));

      $this->user->email_threshold = 10;
      expect('isPartnerEnabled should return false when no partners are set and email_threshold is a positive integer', $this->assertFalse($this->user->isPartnerEnabled()));

      $this->user->partner_email3 = 'hello@partner3.com';
      $this->user->email_threshold = null;
      expect('isPartnerEnabled should return false when a partner is set and email_threshold is null', $this->assertFalse($this->user->isPartnerEnabled()));

      $this->user->email_threshold = 10;
      expect('isPartnerEnabled should return true when at one partner is set and email_threshold is a positive integer', $this->assertTrue($this->user->isPartnerEnabled()));

      $this->user->partner_email1 = 'hello@partner1.com';
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is a positive integer', $this->assertTrue($this->user->isPartnerEnabled()));

      $this->user->email_threshold = 0;
      expect('isPartnerEnabled should return true when two partners are set and email_threshold is 0', $this->assertTrue($this->user->isPartnerEnabled()));

      $this->user->email_threshold = -7;
      expect('isPartnerEnabled should return false when two partners are set and email_threshold is a negative number', $this->assertFalse($this->user->isPartnerEnabled()));
    });
  }

  public function testIsOverThreshold() {
    $this->specify('isOverThreshold should function correctly', function () {
      expect('isOverThreshold should return false with no partners enabled', $this->assertFalse($this->user->isOverThreshold(5)));

      $this->user->email_threshold = 10;
      $this->user->partner_email1 = 'hello@hello.com';
      expect('isOverThreshold should return false if partners enabled but not over threshold', $this->assertFalse($this->user->isOverThreshold(5)));
      expect('isOverThreshold should return false if partners enabled and equal to but not over threshold', $this->assertFalse($this->user->isOverThreshold(10)));

      expect('isOverThreshold should return true if partners enabled and over threshold', $this->assertTrue($this->user->isOverThreshold(15)));
    });
  }

  public function testIsTokenCurrent() {
    $this->specify('isTokenCurrent should function correctly', function () {
      $good_token = \Yii::$app
                      ->getSecurity()
                      ->generateRandomString() . '_' . time();
      expect('isTokenCurrent should return true if the token is still current/alive', $this->assertTrue($this->user->isTokenCurrent($good_token)));
      $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
      $bad_token = \Yii::$app
                      ->getSecurity()
                      ->generateRandomString() . '_' . (time() - $expire - 1); // subtract the expiration time and a little more from the current time
      expect('isTokenCurrent should return false if the token is expired', $this->assertFalse($this->user->isTokenCurrent($bad_token)));
    });
  }

  public function testIsTokenConfirmed() {
      expect('isTokenConfirmed should return true if the token has been confirmed', $this->assertTrue($this->user->isTokenConfirmed('token123_confirmed')));
      expect('isTokenConfirmed should return false if the token has not been confirmed', $this->assertFalse($this->user->isTokenConfirmed('token123')));
      expect('isTokenConfirmed should return false if the token has not been confirmed', $this->assertFalse($this->user->isTokenConfirmed('token123_not_blah')));
  }

  public function testConfirmVerifyEmailToken() {
    $this->user->verify_email_token = 'hello_world';
    $this->user->confirmVerifyEmailToken();
    expect('confirmVerifyEmailToken should append User::CONFIRMED_STRING to the end of the verify_email_token property', $this->assertEquals($this->user->verify_email_token, 'hello_world'.$this->user::CONFIRMED_STRING));
  }

  public function testIsVerified() {
      $this->user->verify_email_token = null;
      expect('isVerified should return true if the token is null', $this->assertTrue($this->user->isVerified()));
      $this->user->verify_email_token = '';
      expect('isVerified should return false if the token is the empty string', $this->assertFalse($this->user->isVerified()));
      $this->user->verify_email_token = 'this_looks_truthy';
      expect('isVerified should return false if the token is still present', $this->assertFalse($this->user->isVerified()));
  }

  public function testRemoveVerifyEmailToken() {
      $this->user->verify_email_token = 'faketoken_1234';
      $this->user->removeVerifyEmailToken();
      expect('removeVerifyEmailToken should set the verify_email_token to be null', $this->assertNull($this->user->verify_email_token));
  }

  public function testGetIdHash() {
    $hash = 'iegYQgiPZUF48kk5bneuPn9_6ZOZhkMEGJ6Y8yICgKc';

    $this->user->id = 12345;
    $this->user->created_at = "2017-12-31 23:59:59";
    expect('getIdHash should return a url-safe string', $this->assertEquals($this->user->getidHash(), $hash));

    $this->user->created_at = "2018-01-01 00:00:00";
    expect('getIdHash should return a DIFFERENT url-safe string for different params', $this->assertNotEquals($this->user->getidHash(), $hash));
  }

  public function testCleanExportData() {
    // need this for the convertUTCToLocal call
    Yii::configure(Yii::$app, [
      'components' => [
        'user' => [
          'class' => 'yii\web\User',
          'identityClass' => 'common\tests\unit\FakeUser',
        ],
      ],
    ]);
    $identity = new \common\tests\unit\FakeUser();
    $identity->timezone = "America/Los_Angeles";
    // logs in the user 
    Yii::$app->user->setIdentity($identity);

    expect('cleanExportData should clean and mutate the queried data to be suitable for downloading', $this->assertEquals([
      [
        'date' => '2017-07-29 03:40:29',
        'option' => 'repetitive, negative thoughts',
        'category' => 'Speeding Up',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'option' => 'tired',
        'category' => 'Exhausted',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'option' => 'out of control',
        'category' => 'Relapse/Moral Failure',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ], [
        'date' => '2017-07-29 03:40:29',
        'option' => 'obsessive (stuck) thoughts',
        'category' => 'Ticked Off',
        'question1' => 'q1',
        'question2' => 'q2',
        'question3' => 'q3',
      ]
    ], $this->user->cleanExportData($this->exportData)));
  }
}
