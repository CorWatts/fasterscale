<?php

namespace console\tests\unit\controllers;

use Yii;
use \console\controllers\FsaController;
use \common\fixtures\UserFixture;

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

        $count = (new \yii\db\Query())->select('count(*)')->from('user')->one();
        expect("starting count", $this->assertEquals($count['count'], 8));
        $controller = new FsaController('fsa', 'console');
        $controller->actionRemoveOldUnconfirmedAccounts();
        $new_count = (new \yii\db\Query())->select('count(*)')->from('user')->one();
        expect("starting count", $this->assertEquals($new_count['count'], 5));
    }
}

