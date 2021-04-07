<?php

$main = include __DIR__ . '/main.php';
$mainlocal = include __DIR__ . '/main-local.php';

$config = [
  'id' => 'app-site-tests',
  'basePath' => dirname(__DIR__),
 'components' => [
   'mailer' => [
     'class' => yii\swiftmailer\Mailer::class,
     'viewPath' => '@common/mail',
     'useFileTransport' => true,
   ],
  ]
];

return yii\helpers\ArrayHelper::merge(
    $main,
    $mainlocal,
    $config
);
