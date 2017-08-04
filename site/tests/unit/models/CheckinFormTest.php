<?php

namespace site\tests\unit\models;

use Yii;

class CheckinFormTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

		public $options = [
			1 => [
				'category_name' => 'Restoration',
				'options' => [
					0 => [
						'id' => 7,
						'name' => 'making eye contact',
					],
				],
			],
			2 => [
				'category_name' => 'Forgetting Priorities',
				'options' => [
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
				'options' => [
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
				'options' => [
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
				'options' => [
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
				'options' => [
					0 => [
						'id' => 104,
						'name' => 'pessimistic',
					],
				],
			],
			7 => [
				'category_name' => 'Relapse/Moral Failure',
				'options' => [
					0 => [
						'id' => 128,
						'name' => 'feeling you just can\'t manage without your coping behavior, at least for now',
					],
				],
			],
		];
 

    protected function setUp() {
      $this->container = new \yii\di\Container;
      $this->container->set('common\interfaces\UserInterface', '\site\tests\_support\MockUser');
      $this->container->set('common\interfaces\UserOptionInterface', '\site\tests\_support\MockUserOption');
      $this->container->set('common\interfaces\QuestionInterface', '\site\tests\_support\MockQuestion');
    $this->container->set('common\interfaces\TimeInterface', function () {
      return new \common\components\Time('America/Los_Angeles');
    });
      parent::setUp();
    }

    //protected function tearDown()
    //{
        //parent::tearDown();
    //}

		public function testAttributeLabels()
		{
        $this->specify('setOptions should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
          expect('attributeLabels should be correct', $this->assertEquals($model->attributeLabels(), [
            'options1' => 'Restoration',
            'options2' => 'Forgetting Priorities',
            'options3' => 'Anxiety',
            'options4' => 'Speeding Up',
            'options5' => 'Ticked Off',
            'options6' => 'Exhausted',
            'options7' => 'Relapsed/Moral Failure'
          ]));
        });
    }

    public function testSetOptions()
    {
        $this->specify('setOptions should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setOptions($this->options);

          expect('options1 should be correct', $this->assertEquals($model->options1, [ 0 => 7 ]));
          expect('options2 should be correct', $this->assertEquals($model->options2, [ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ]));
          expect('options3 should be correct', $this->assertEquals($model->options3, [ 0 => 28, 1 => 38, 2 => 46 ]));
          expect('options4 should be correct', $this->assertEquals($model->options4, [ 0 => 47, 1 => 56, 2 => 62 ]));
          expect('options5 should be correct', $this->assertEquals($model->options5, [ 0 => 78, 1 => 79 ]));
          expect('options6 should be correct', $this->assertEquals($model->options6, [ 0 => 104 ]));
          expect('options7 should be correct', $this->assertEquals($model->options7, [ 0 => 128 ]));

					$model->setOptions($this->options);
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options1, [ 0 => 7 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options2, [ 0 => 12, 1 => 13, 2 => 17, 3 => 18 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options3, [ 0 => 28, 1 => 38, 2 => 46 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options4, [ 0 => 47, 1 => 56, 2 => 62 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options5, [ 0 => 78, 1 => 79 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options6, [ 0 => 104 ]));
          expect('setOptions should not append options to existing ones', $this->assertEquals($model->options7, [ 0 => 128 ]));
        });
    }

		public function testValidateOptions()
		{
        $this->specify('validateOptions should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setOptions($this->options);
          expect('validation should be good', $this->assertTrue($model->validate()));

          $model->options1[0] = 'bad';
          expect('validation should be bad', $this->assertFalse($model->validate()));
        });
		}

		public function testCompileOptions()
		{
        $this->specify('compileOptions should function properly', function () {
          $model = $this->container->get('\site\models\CheckinForm');
					$model->setOptions($this->options);
          expect('compiling options should be return a correct array', $this->assertEquals($model->compileOptions(), [
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
					$model->setOptions($this->options);
          $model->options1[0] = null;
          $model->options2[0] = null;
          $model->options3[0] = null;
          expect('compiling options should strip out any falsy values', $this->assertEquals($model->compileOptions(), [
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
					$model->setOptions([]);
          expect('compiling options should return an empty array when no options are set', $this->assertEmpty($model->compileOptions()));
        });
		}
}
