<?php

namespace site\tests\unit\models;

use Yii;

class ChangePasswordFormTest extends \Codeception\Test\Unit
{
    private $user;

    public function setUp(): void
    {
        $this->container = new \yii\di\Container();
        $this->user = $this->getMockBuilder('\site\tests\_support\MockUser')
        ->setMethods(['validatePassword', 'setPassword', 'save'])
        ->getMock();
        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->user = null;
        parent::tearDown();
    }

    public function testSimpleRulesShouldValidate()
    {
        $user = $this->user;
        $this->container = new \yii\di\Container();
        $form = $this->container->get('\site\models\ChangePasswordForm', [$user]);

        $form->attributes = [];
        expect('with no values, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = ['old_password' => '12345678'];
        expect('with one value, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = ['new_password' => '12345678'];
        expect('with one value, the form should not pass validation', $this->assertFalse($form->validate()));

        $user
        ->expects($this->never())
        ->method('validatePassword');
        $form->attributes = [
        'old_password' => '123456',
        'new_password' => '789012',
        ];
        expect('with a new password of < 8 chars, the form should not pass validation', $this->assertFalse($form->validate()));
    }

    public function testValidatePasswordShouldFailWithABadPassword()
    {
        $user = $this->user;

        $old = 'not_my_current_pass';
        $user
        ->expects($this->once())
        ->method('validatePassword')
        ->with($this->equalTo($old));
        $user
        ->method('validatePassword')
        ->willReturn(false);

        $form = $this->container->get('\site\models\ChangePasswordForm', [$user]);
        $form->attributes = [
        'old_password' => $old,
        'new_password' => 'my_desired_Pass',
        ];
        expect('a failed password should not pass validation', $this->assertFalse($form->validate()));
        expect('a failed password should add an error fail validation', $this->assertGreaterThan(0, count($form->errors)));
    }

    public function testValidatePasswordShouldPassWithAGoodPassword()
    {
        $user = $this->user;
        $old = 'my_current_pass';

        $user
        ->method('validatePassword')
        ->willReturn(true);
        $user
        ->expects($this->once())
        ->method('validatePassword')
        ->with($this->equalTo($old));

        $form = $this->container->get('\site\models\ChangePasswordForm', [$user]);
        $form->attributes = [
        'old_password' => $old,
        'new_password' => 'my_desired_Pass',
        ];
        expect('a good password should pass validation', $this->assertTrue($form->validate()));
        expect('a good password should not add any errors', $this->assertEquals(0, count($form->errors)));
    }

    public function testChangePassword()
    {
        $user = $this->user;
        $old = 'my_current_pass';
        $new = 'my_desired_Pass';

        $user
        ->method('save')
        ->willReturn(true);
        $user
        ->expects($this->once())
        ->method('setPassword')
        ->with($this->equalTo($new));

        $form = $this->container->get('\site\models\ChangePasswordForm', [$user]);
        $form->attributes = [
        'old_password' => $old,
        'new_password' => $new,
        ];
        expect('changePassword should return true if save returns true', $this->assertTrue($form->changePassword()));
    }
}
