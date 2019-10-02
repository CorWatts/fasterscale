<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\CustomBehaviorForm;
use \yii\di\Container;
use \common\models\Category;

class CustomBehaviorFormTest extends \Codeception\Test\Unit {

  private $user;
  private $category;

  public function setUp() {
    $this->container = new Container;
    $this->container->set(\common\interfaces\UserInterface::class, '\site\tests\_support\MockUser');
    $this->user = $this->container->get(\common\interfaces\UserInterface::class);
  }

  public function testRules() {
    $category = new Category();
    $form = new CustomBehaviorForm($this->user, $category);

    $form->attributes = [];
    expect('with no values, the form should not pass validation', $this->assertFalse($form->validate()));

    $form->attributes = [
      'name' => 'Clapping hands and smiling',
      'category_id' => 'definitely not a category id',
    ];
    expect('with an invalid category_id the form should not pass validation', $this->assertFalse($form->validate()));

    $form->attributes = [
      'category_id' => 'definitely not a category id',
    ];
    expect('without a name the form should not pass validation', $this->assertFalse($form->validate()));
    $form->attributes = [
      'name' => 'Clapping hands and smiling',
      'category_id' => 42,
    ];
    expect('with an invalid category_id the form should not pass validation', $this->assertFalse($form->validate()));

    $form->attributes = [
      'name' => 'Clapping hands and smiling',
      'category_id' => 6,
    ];
    expect('with a name and valid category_id, the form should validate', $this->assertTrue($form->validate()));

    $form->attributes = [
      'name' => 'Clapping hands and smiling',
      'category_id' => 6,
    ];
    expect('with a name and valid category_id, the form should validate', $this->assertTrue($form->validate()));

    $form->attributes = [
      'name' => '       Clapping hands and smiling .. -- aa       ',
      'category_id' => 6,
    ];
    $form->validate();
    expect('the form should trim the name when validating', $this->assertEquals('Clapping hands and smiling .. -- aa', $form->name));
  }

  public function testSave() {
    $category = new Category();
    $user_id = 123;
    $this->user->id = $user_id;

    $custom_behavior = $this->getMockBuilder(\common\models\CustomBehavior::class)
      ->setmethods(['getisnewrecord', 'attributes', 'save'])
      ->getmock();
    $custom_behavior
      ->method('attributes')
      ->willReturn([
        'id',
        'user_id',
        'category_id',
        'name',
        'created_at',
        'updated_at',
      ]);
    $custom_behavior
      ->expects($this->once())
      ->method('save')
      ->willReturn($custom_behavior);

    Yii::$container->set(\common\interfaces\CustomBehaviorInterface::class, $custom_behavior);
    $form = new CustomBehaviorForm($this->user, $category);
    $name = 'a new custom behavior';
    $category_id = 4;
    $form->name = $name;
    $form->category_id = $category_id;

    $result = $form->save();
    expect('should have name correctly set', $this->assertEquals($result->name, $name));
    expect('should have category_id correctly set', $this->assertEquals($result->category_id, $category_id));
    expect('should have name correctly set', $this->assertEquals($result->user_id, $user_id));
  }
}
