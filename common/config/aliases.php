<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@site', dirname(dirname(__DIR__)) . '/site');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@web', '/');
Yii::setAlias('@webroot', '@site/web');
Yii::setAlias('@assets', dirname(dirname(__DIR__)) . '/site/assets/publish');
Yii::setAlias('@graphImgPath', '@webroot/charts');
Yii::setAlias('@graphImgUrl', '/charts');
