<?php
return [
  'name' => "The Faster Scale App",
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
  'modules' => [
    'blog' => [
      'class' => \corwatts\MarkdownFiles\Module::className(),
      'posts' => '@site/views/blog/posts',
      'drafts' => '@site/views/blog/drafts',
    ]
  ],
  'components' => [
    'cache' => [
      'class' => 'yii\caching\DummyCache', // OR USE MEMCACHE OR SOMETHING
    ],
    'session' => [
      'class'=> 'yii\web\CacheSession',
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      'messageClass' => '\common\components\Message',
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ],
];
