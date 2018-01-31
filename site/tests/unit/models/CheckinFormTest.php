<?php

namespace site\tests\unit\models;

use Yii;

class CheckinFormTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

		public $behaviors = [
			1 => [
				'category_name' => 'Restoration',
				'behaviors' => [
					0 => [
						'id' => 7,
						'name' => 'making eye contact',
					],
				],
			],
			2 => [
				'category_name' => 'Forgetting Priorities',
				'behaviors' => [
					0 => [
						'id' => 12,
						'name' => 'bored',
					],
					1 => [
						'id' => 13,
						'name' => 'less time/energy for God, meetings, and church',
					],
					2 => [
						'id' => 17,
						'name' => 'isolating yourself',
					],
					3 => [
						'id' => 18,
						'name' => 'changes in goals',
					],
				],
			],
			3 => [
				'category_name' => 'Anxiety',
				'behaviors' => [
					0 => [
						'id' => 28,
						'name' => 'worry',
					],
					1 => [
						'id' => 38,
						'name' => 'fantasy',
					],
					2 => [
						'id' => 46,
						'name' => 'using over-the-counter medication for pain, sleep, and weight control',
					],
				],
			],
			4 => [
				'category_name' => 'Speeding Up',
				'behaviors' => [
					0 => [
						'id' => 47,
						'name' => 'super busy',
					],
					1 => [
						'id' => 56,
						'name' => 'binge eating (usually at night]',
					],
					2 => [
						'id' => 62,
						'name' => 'dramatic mood swings',
					],
				],
			],
			5 => [
				'category_name' => 'Ticked Off',
				'behaviors' => [
					0 => [
						'id' => 78,
						'name' => 'increased isolation',
					],
					1 => [
						'id' => 79,
						'name' => 'blaming',
					],
				],
			],
			6 => [
				'category_name' => 'Exhausted',
				'behaviors' => [
					0 => [
						'id' => 104,
						'name' => 'pessimistic',
					],
				],
			],
			7 => [
				'category_name' => 'Relapse/Moral Failure',
				'behaviors' => [
					0 => [
						'id' => 128,
						'name' => 'feeling you just can\'t manage without your coping behavior, at least for now',
					],
				],
			],
		];

    protected function setUp() {
      $this->container = new \yii\di\Container;
      $this->container->set('common\interfaces\UserBehaviorInterface', '\site\tests\_support\MockUserBehavior');
    $this->container->set('common\interfaces\TimeInterface', function () {
      return new \common\components\Time('America/Los_Angeles');
    });
      parent::setUp();
    }

		public function testAttributeLabels()
		{
        $this->specify('setBehaviors should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
          expect('attributeLabels should be correct', $this->assertEquals($model->attributeLabels(), [
            'behaviors1' => 'Restoration',
            'behaviors2' => 'Forgetting Priorities',
            'behaviors3' => 'Anxiety',
            'behaviors4' => 'Speeding Up',
            'behaviors5' => 'Ticked Off',
            'behaviors6' => 'Exhausted',
            'behaviors7' => 'Relapsed/Moral Failure'
          ]));
        });
    }

    public function testSetBehaviors()
    {
        $this->specify('setBehaviors should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setBehaviors($this->behaviors);

          expect('behaviors1 should be correct', $this->assertEquals($model->behaviors1, [ 0 => 7 ]));
          expect('behaviors2 should be correct', $this->assertEquals($model->behaviors2, [ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ]));
          expect('behaviors3 should be correct', $this->assertEquals($model->behaviors3, [ 0 => 28, 1 => 38, 2 => 46 ]));
          expect('behaviors4 should be correct', $this->assertEquals($model->behaviors4, [ 0 => 47, 1 => 56, 2 => 62 ]));
          expect('behaviors5 should be correct', $this->assertEquals($model->behaviors5, [ 0 => 78, 1 => 79 ]));
          expect('behaviors6 should be correct', $this->assertEquals($model->behaviors6, [ 0 => 104 ]));
          expect('behaviors7 should be correct', $this->assertEquals($model->behaviors7, [ 0 => 128 ]));

					$model->setBehaviors($this->behaviors);
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors1, [ 0 => 7 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors2, [ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors3, [ 0 => 28, 1 => 38, 2 => 46 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors4, [ 0 => 47, 1 => 56, 2 => 62 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors5, [ 0 => 78, 1 => 79 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors6, [ 0 => 104 ]));
          expect('setBehaviors should not append behaviors to existing ones', $this->assertEquals($model->behaviors7, [ 0 => 128 ]));
        });
    }

		public function testValidateBehaviors()
		{
        $this->specify('validateBehaviors should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setBehaviors($this->behaviors);
          expect('validation should be good', $this->assertTrue($model->validate()));

          $model->behaviors1[0] = 'bad';
          expect('validation should be bad', $this->assertFalse($model->validate()));
        });
		}

		public function testCompileBehaviors()
		{
        $this->specify('compileBehaviors should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setBehaviors($this->behaviors);
          expect('compiling behaviors should be return a correct array', $this->assertEquals($model->compileBehaviors(), [
											  	0 => 7,
											  	1 => 12,
											  	2 => 13,
											  	3 => 17,
											  	4 => 18,
											  	5 => 28,
											  	6 => 38,
											  	7 => 46,
											  	8 => 47,
											  	9 => 56,
											  	10 => 62,
											  	11 => 78,
											  	12 => 79,
											  	13 => 104,
											  	14 => 128,
											  ]));

          $model = $this->container->get('\site\models\CheckinForm');
					$model->setBehaviors($this->behaviors);
          $model->behaviors1[0] = null;
          $model->behaviors2[0] = null;
          $model->behaviors3[0] = null;
          expect('compiling behaviors should strip out any falsy values', $this->assertEquals($model->compileBehaviors(), [
											  	2 => 13,
											  	3 => 17,
											  	4 => 18,
											  	6 => 38,
											  	7 => 46,
											  	8 => 47,
											  	9 => 56,
											  	10 => 62,
											  	11 => 78,
											  	12 => 79,
											  	13 => 104,
											  	14 => 128,
											  ]));

          $model = $this->container->get('\site\models\CheckinForm');
					$model->setBehaviors([]);
          expect('compiling behaviors should return an empty array when no behaviors are set', $this->assertEmpty($model->compileBehaviors()));
        });
		}
}
