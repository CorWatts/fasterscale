<?php
$config = yii\helpers\ArrayHelper::merge(
  require(__DIR__ . '/main.php'),
  require(__DIR__ . '/main-local.php'),
  require(__DIR__ . '/test.php')
);

unset($config['components']['db']);
return $config;
