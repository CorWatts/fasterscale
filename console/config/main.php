<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
  'id' => 'app-console',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log', 'blog'],
  'controllerNamespace' => 'console\controllers',
  'controllerMap' => [
      'fixture' => [
          'class' => 'yii\console\controllers\FixtureController',
          'namespace' => 'common\fixtures',
      ],
      'migrate' => [
          'class' => 'yii\console\controllers\MigrateController',
          'migrationPath' => '@console/migrations',
      ],
  ],
  'components' => [
    'log' => [
      'targets' => [
        [
          'class' => yii\log\FileTarget::class,
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
  ],
  'params' => $params,
];
