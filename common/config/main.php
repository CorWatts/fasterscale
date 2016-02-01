<?php
return [
    'name' => "The Faster Scale App",
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\DummyCache', // OR USE MEMCACHE OR SOMETHING
        ],
    ],
];
