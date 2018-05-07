<?php
return [
  'name' => "The Faster Scale App",
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
  'container' => [
    'definitions' => [
      'common\interfaces\UserInterface' => \common\models\User::class,
      'common\interfaces\UserBehaviorInterface' => \common\models\UserBehavior::class,
      'common\interfaces\QuestionInterface' => \common\models\Question::class,
      'common\interfaces\BehaviorInterface' => \common\models\Behavior::class,
      'common\interfaces\CategoryInterface' => \common\models\Category::class,
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
      'class' => \corwatts\MarkdownFiles\Module::class,
      'posts' => '@site/views/blog/posts',
      'drafts' => '@site/views/blog/drafts',
    ]
  ],
  'components' => [
    'cache' => [
      'class' => yii\caching\FileCache::class, // OR USE MEMCACHE OR SOMETHING
    ],
    'session' => [
      'class'=> yii\web\CacheSession::class,
    ],
    'mailer' => [
      'class' => yii\swiftmailer\Mailer::class,
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ],
  'aliases' => [
    '@bower' => '@vendor/bower-asset'
  ]
];
