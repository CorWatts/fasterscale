<?php
$test_params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return yii\helpers\ArrayHelper::merge(
    require(dirname(dirname(__DIR__)) . '/common/config/test-local.php'),
    [
        'id' => 'app-console-tests',
        'basePath' => dirname(__DIR__),
        'params' => $test_params,
    ]
);
