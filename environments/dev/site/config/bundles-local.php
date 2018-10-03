<?php

return [
  'yii\bootstrap4\BootstrapPluginAsset' => false,
  'yii\bootstrap4\BootstrapAsset' => false,
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
//    'yii\bootstrap4\BootstrapPluginAsset' => false,
//    'yii\bootstrap4\BootstrapAsset' => false
//  ]
//);
