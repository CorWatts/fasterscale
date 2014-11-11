<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\MemCache',
	    'servers' => [
		[
		    'host' => 'localhost',
		    'port' => 11211,
		    'weight' => 100
		]
	    ]
        ],
    ],
];
