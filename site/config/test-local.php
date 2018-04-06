<?php
return yii\helpers\ArrayHelper::merge(
  require(dirname(dirname(__DIR__)) . '/common/config/test-local.php'),
  require(__DIR__ . '/test.php')
);

