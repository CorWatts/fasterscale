<?php

namespace site\tests\unit\models;

use Yii;
use \site\models\ContactForm;

class ContactFormTest extends \Codeception\Test\Unit
{
    public function testRules()
    {
        $form = new ContactForm();

        $form->attributes = [];
        expect('with no values, the form should not pass validation', $this->assertFalse($form->validate()));

        $form->attributes = [
      'name' => 'Corey',
      'email' => 'not_an-email',
      'subject' => 'a question',
      'body' => 'hello there'
    ];
        expect('with a value that is not an email, the form should not pass validation', $this->assertFalse($form->validate()));
        $form->attributes = [
      'name' => 'Corey',
      'email' => '  email@email.COM  ',
      'subject' => 'a question',
      'body' => 'hello there'
    ];
        expect('with a valid email, the form should pass validation', $this->assertTrue($form->validate()));
    }

    public function testSendEmail()
    {
        $form = new ContactForm();

        $form->attributes = [
      'name' => 'Corey',
      'email' => '  email@email.COM  ',
      'subject' => 'a question',
      'body' => 'hello there'
    ];

        $form->validate();
        $form->sendEmail('destination@email.com');

        $this->tester->seeEmailIsSent();
        $emailMessage = $this->tester->grabLastSentEmail();
        verify($emailMessage)->instanceOf('yii\mail\MessageInterface');
        verify($emailMessage->getTo())->arrayHasKey('destination@email.com');
        verify($emailMessage->getFrom())->arrayHasKey('email@email.com');
        verify($emailMessage->getSubject())->equals('[FSA Contact] a question');
        verify($emailMessage->toString())->stringContainsString('hello there');
    }
}
