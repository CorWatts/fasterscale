<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/aliases.php';

$config = yii\helpers\ArrayHelper::merge(
  require __DIR__ . '/../../common/config/main.php',
  require __DIR__ . '/../../common/config/main-local.php',
  require __DIR__ . '/../config/main.php',
  require __DIR__ . '/../config/main-local.php'
);

$bundles = require __DIR__ . '/../config/bundles-prod.php';
$config['components']['assetManager']['bundles'] = $bundles;

$application = new yii\web\Application($config);
$application->run();
