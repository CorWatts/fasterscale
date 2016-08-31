<?php
return [
  'name' => "The Faster Scale App",
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache', // OR USE MEMCACHE OR SOMETHING
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      'messageClass' => '\common\components\Message',
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ],
];
