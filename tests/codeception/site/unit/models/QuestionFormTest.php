<?php

namespace tests\codeception\site\unit\models;

use Yii;
use \tests\codeception\site\unit\TestCase;
use \site\models\QuestionForm;

class QuestionFormTest extends TestCase
{
  use \Codeception\Specify;

  public function testAttributeLabels()
  {
    $this->specify('setOptions should function properly', function () {
      $model = new QuestionForm();
      expect('attributeLabels should be correct', $this->assertEquals($model->attributeLabels(), [
        'user_option_id1' => 'Restoration',
        'user_option_id2' => 'Forgetting Priorities',
        'user_option_id3' => 'Anxiety',
        'user_option_id4' => 'Speeding Up',
        'user_option_id5' => 'Ticked Off',
        'user_option_id6' => 'Exhausted',
        'user_option_id7' => 'Relapsed/Moral Failure'
      ]));
    });
  }

  public function testGetBhvrValidator()
  {
    $model = new QuestionForm();
    $validator = $model->getBhvrValidator();

    $this->specify('getBhvrValidator should function properly', function () use($model, $validator) {
      expect('getBhvrValidator should return false when nothing is set on the form', $this->assertFalse($validator($model, "user_option_id1")));
      expect('getBhvrValidator should return false when nothing is set on the form', $this->assertFalse($validator($model, "user_option_id7")));

      $model->answer_1a = "processing emotions";
      expect('getBhvrValidator should return true when there is one answer set for this option', $this->assertTrue($validator($model, "user_option_id1")));

      $model->answer_1b = "also processing emotions";
      expect('getBhvrValidator should return true when there are two answers set for this option', $this->assertTrue($validator($model, "user_option_id1")));

      $model->answer_1c = "yep, processing emotions";
      expect('getBhvrValidator should return true when there are three answers set for this option', $this->assertTrue($validator($model, "user_option_id1")));

      expect('getBhvrValidator should return false when the answers that are set are NOT for this option', $this->assertFalse($validator($model, "user_option_id3")));
    });
  }
}
