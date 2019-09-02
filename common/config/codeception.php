<?php
$config = [
  'components' => [
    'request' => [
      'class' => common\tests\_support\MockRequest::class
    ]
  ]
];

return yii\helpers\ArrayHelper::merge(
  require __DIR__ . '/main.php',
  require __DIR__ . '/main-local.php',
  require __DIR__ . '/test.php',
  require __DIR__ . '/test-local.php',
  $config
);