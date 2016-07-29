<?php
return [
  'name' => "The Faster Scale App",
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
  'container' => [
    'definitions' => [
      'common\interfaces\UserInterface' => '\common\models\User',
      'common\interfaces\UserOptionInterface' => '\common\models\UserOption',
      'common\interfaces\QuestionInterface' => '\common\models\Question',
      'common\interfaces\OptionInterface' => '\common\models\Option',
      'common\interfaces\CategoryInterface' => '\common\models\Category',
      'common\interfaces\TimeInterface' => function () {
        if(Yii::$app->user->getIsGuest()) {
          return new \common\components\Time('UTC');
        } else {
          return new \common\components\Time(Yii::$app->user->identity->timezone);
        }
      },
    ],
  ],
  'modules' => [
    'blog' => [
      'class' => \corwatts\MarkdownFiles\Module::className(),
      'posts' => '@site/views/blog/posts',
      'drafts' => '@site/views/blog/drafts',
    ]
  ],
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache', // OR USE MEMCACHE OR SOMETHING
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
  'aliases' => [
    '@bower' => '@vendor/bower-asset'
  ]
];
