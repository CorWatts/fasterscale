<?php

namespace console\tests\unit\controllers;

use Yii;
use \console\controllers\FsaController;

class FsaControllerTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    public function testgetTimeThreshold()
    {
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];

        $controller = new FsaController('fsa', 'console');
        $created_at = $controller->getTimeThreshold();
        expect('asdf', $this->assertEquals(10, strlen((string)$created_at)));
    }
}

