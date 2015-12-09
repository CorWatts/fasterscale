<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
 Yii::setAlias('@webroot', __DIR__ . '/../web');
 Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'closure-compiler --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'yui-compressor --type css {from} -o {to}',
    // The list of asset bundles to compress:
    'bundles' => [
        'site\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'site\assets\JuiAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot',
            'sourcePath' => '@web/assets',
            'baseUrl' => '@web',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
      'basePath' => '@webroot/assets',
      'baseUrl' => '@web',
      'bundles' => [
        'yii\jui\JuiAsset' => [
          'sourcePath' => null,
          'basePath' => null,
          'baseUrl' => null,
          'js' => [],
          'css' => [],
        ],
      ],
    ],
];
