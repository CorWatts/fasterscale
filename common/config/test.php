<?php
$params = array_merge(
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);
return [
  'id' => 'app-common-tests',
  'basePath' => dirname(__DIR__),
  'params' => $params,
  'components' => [
    'db' => new \yii\helpers\UnsetArrayValue(),
    'mailer' => [
      'class' => yii\swiftmailer\Mailer::class,
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ]
];