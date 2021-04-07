<?php

$test_config = [
    'components' => [
        'request' => [
            'class' => common\tests\_support\MockRequest::class
        ]
    ]
];

$asdf = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
    require __DIR__ . '/test-local.php',
    $test_config
);

return $asdf;
