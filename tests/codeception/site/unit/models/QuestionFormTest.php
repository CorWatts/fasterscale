<?php

namespace tests\codeception\site\unit\models;

use Yii;
use tests\codeception\site\unit\TestCase;
use site\models\QuestionForm;

class QuestionFormTest extends TestCase
{
  use \Codeception\Specify;

  /*
  protected function setUp()
  {
    parent::setUp();
  }

  protected function tearDown()
  {
    parent::tearDown();
  }
  */

  public function testGetBhvrValidator()
  {
    $this->specify('getBhvrValidator should function properly', function () {
      $model = new QuestionForm();
    });
  }
}
