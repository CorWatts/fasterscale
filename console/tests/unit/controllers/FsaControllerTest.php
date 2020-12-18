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
        $controller->getTimeThreshold();
        expect('asdf', $this->assertTrue(false));
    }
}

