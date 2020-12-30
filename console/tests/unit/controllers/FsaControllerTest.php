<?php

namespace console\tests\unit\controllers;

use Yii;
use \console\controllers\FsaController;
use \console\fixtures\UserFixture;

class FsaControllerTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testGetTimeThreshold()
    {
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];

        $controller = new FsaController('fsa', 'console');
        $created_at = $controller->getTimeThreshold();
        expect('asdf', $this->assertEquals(10, strlen((string)$created_at)));
    }
}

