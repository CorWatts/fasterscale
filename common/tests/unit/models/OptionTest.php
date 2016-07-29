<?php

namespace common\unit\models;

use Yii;
use Codeception\Specify;
use common\models\Option;

date_default_timezone_set('UTC');

/**
 * Time test
 */

class OptionTest extends \Codeception\Test\Unit {
  use Specify;

  public function testGetCategories() {
    $options = Option::getCategories();

    expect('getCategories should return an array of 7 categories', $this->assertEquals(count($options), 7));

    foreach($options as $option) {
      expect('this option to have a "name" key', $this->assertArrayHasKey('name', $option));
      expect('this option to have a "weight" key', $this->assertArrayHasKey('weight', $option));
      expect('this option to have a "option_count" key', $this->assertArrayHasKey('option_count', $option));
      expect('this option to have a "category_id" key', $this->assertArrayHasKey('category_id', $option));
    }
  }

  public function testGetOption() {
    expect('getOption should return false when asked for an option that does not exist', $this->assertEquals(Option::getOption('id', 99999999), false));

    expect('getOption should return the asked-for option data', $this->assertEquals(Option::getOption('id', 3), ["id" => 3, "name" => "identifying fears and feelings", "category_id" => 1]));

    expect('getOption SHOULD NOT work quite right for indexing by category_id', $this->assertEquals(Option::getOption('category_id', 3), ["id" => 28, "name" => "worry", "category_id" => 3]));
  }

}
