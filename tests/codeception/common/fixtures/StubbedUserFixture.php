<?php

namespace tests\codeception\common\fixtures;

use yii\test\Fixture;

/**
 * User fixture
 */
class StubbedUserFixture extends Fixture
{
    public $modelClass = 'common\models\User';

    public function load() {
      $this->timezone = "America/Los_Angeles";
    }
}
