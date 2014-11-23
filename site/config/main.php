<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'emotional-checkin-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'site\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<action:(index|about|contact|signup|login|logout|profile|request-password-reset|reset-password)>' => 'site/<action>',
                'checkin/view/<date:\d{4}-\d{2}-\d{2}>' => 'checkin/view',
                'report/view/<date:\d{4}-\d{2}-\d{2}>' => 'report/view',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

            ]
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'medium',//'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd H:i:s',
            'timeFormat' => 'H:i:s',
            'timeZone' => 'UTC',
            'locale' => 'en_US'
        ],
	    'session' => [
	        'class'=> 'yii\web\CacheSession',
	    ],
        'assetManager' => [
            'converter' => [
                'class' => 'yii\web\AssetConverter',
            ],
            'bundles' => require(__DIR__ . '/../assets/assets-compressed.php'),
        ],
    ],
    'params' => $params,
    /*
     */
];
