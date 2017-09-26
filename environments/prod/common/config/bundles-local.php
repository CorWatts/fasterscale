<?php

return [
  'yii\bootstrap\BootstrapPluginAsset' => false,
  'yii\bootstrap\BootstrapAsset' => false,
  'yii\web\JqueryAsset' => [
    'sourcePath' => '@assets',
    'js' => [
      'js/jquery-3.2.1.min.js',
    ]
  ],
];
//return array_merge(
//  require(dirname($_SERVER['SCRIPT_FILENAME']) . '/../assets/assets-compressed.php'),
//  [
//    'yii\bootstrap\BootstrapPluginAsset' => false,
//    'yii\bootstrap\BootstrapAsset' => false
//  ]
//);
