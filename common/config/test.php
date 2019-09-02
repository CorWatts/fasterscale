<?php
$config = [
  'id' => 'app-common-tests',
  'basePath' => dirname(__DIR__),
  'components' => [
    'mailer' => [
      'class' => yii\swiftmailer\Mailer::class,
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ]
];

return $config;
