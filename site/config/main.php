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
      'identityClass' => \common\models\User::class,
      'enableAutoLogin' => true,
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => yii\log\FileTarget::class,
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
        '' => 'site/index',
        '<action:(index|about|blog|faq|contact|signup|login|logout|welcome|privacy|terms|request-password-reset|reset-password)>' => 'site/<action>',
        'checkin/view/<date:\d{4}-\d{2}-\d{2}>' => 'checkin/view',
        'checkin/history/<period:\d{2,3}>' => 'checkin/history',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

      ]
    ],
    'request' => [
      'enableCookieValidation' => true,
      'enableCsrfValidation' => true
    ],
    'assetManager' => [
      // this is only used by the `./yii asset` command
      // in all other cases, this is overridden by bundles-local.php
      'bundles' => [
        'yii\bootstrap\BootstrapAsset' => false,
      ],
      'linkAssets' => true,
      'converter' => [
        'class' => yii\web\AssetConverter::class,
        'commands' => [
          'scss' => ['css', 'node-sass --include-path "../../../vendor/bower-asset/pickadate/lib/themes" --include-path "../../../vendor/bower-asset/bootstrap-sass/assets/stylesheets" --output-style compressed {from} > {to}'],
        ],
      ],
    ],
    'view' => [
      'class' => common\components\View::class
    ]
  ],
  'params' => $params,
];
