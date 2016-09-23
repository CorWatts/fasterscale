<?php

namespace tests\codeception\site\unit\models;

use Yii;
use tests\codeception\site\unit\TestCase;
use site\models\CheckinForm;

class CheckinFormTest extends TestCase
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
 

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testSetOptions()
    {
        $this->specify('setOptions should function properly', function () {
        	$model = new CheckinForm();
					$model->setOptions($this->options);

          expect('options1 should be correct', $this->assertEquals($model->options1, [ 0 => 7 ]));
          expect('options2 should be correct', $this->assertEquals($model->options2, [
																																										   0 => 12,
																																										   1 => 13,
																																										   2 => 17,
																																										   3 => 18,
																																										 ]));
          expect('options3 should be correct', $this->assertEquals($model->options3, [
																																										   0 => 28,
																																										   1 => 38,
																																										   2 => 46,
																																										 ]));
          expect('options4 should be correct', $this->assertEquals($model->options4, [
																																										   0 => 47,
																																										   1 => 56,
																																										   2 => 62,
																																										 ]));
          expect('options5 should be correct', $this->assertEquals($model->options5, [
																																										   0 => 78,
																																										   1 => 79,
																																										 ]));
          expect('options6 should be correct', $this->assertEquals($model->options6, [ 0 => 104 ]));
          expect('options7 should be correct', $this->assertEquals($model->options7, [ 0 => 128 ]));
        });
    }

		public function testValidateOptions()
		{
        $this->specify('validateOptions should function properly', function () {
        	$model = new CheckinForm();
					$model->setOptions($this->options);
          expect('validation should be good', $this->assertTrue($model->validate()));

          $model->options1[0] = 'bad';
          expect('validation should be bad', $this->assertFalse($model->validate()));
        });
		}
}
