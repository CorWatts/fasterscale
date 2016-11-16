<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
 Yii::setAlias('@webroot', __DIR__ . '/../web');
 Yii::setAlias('@web', '/');

$mainConf = require(Yii::getAlias('@site/config/main.php'));

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'uglifyjs {from} --compress --mangle --output {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'uglifycss --debug --ugly-comments {from} > {to}',
    // The list of asset bundles to compress:
    'bundles' => [
        'site\assets\JqueryAsset',
        'site\assets\AppAsset',
        'yii\web\YiiAsset',
        'site\assets\BootstrapPluginAsset',
        'site\assets\MomentAsset',
        'site\assets\ChartjsAsset',
        'site\assets\PickadateAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class'      => 'yii\web\AssetBundle',
            'basePath'   => '@webroot',
            'sourcePath' => '@web/assets',
            'baseUrl'    => '@web',
            'js'         => 'js/all-{hash}.js',
            'css'        => 'css/all-{hash}.css',
            'depends'    => [], // Include all remaining assets
        ],
        'pickadate' => [
          'class'      => 'yii\web\AssetBundle',
          'basePath'   => '@webroot',
          'sourcePath' => '@web/assets',
          'baseUrl'    => '@web',
          'js'         => 'js/pickadate-{hash}.js',
          'depends'    => [
            'site\assets\PickadateAsset',
          ]
        ],
    ],
    // Asset manager configuration:
    'assetManager' => array_merge($mainConf['components']['assetManager'], [
      'basePath' => '@webroot/assets',
      'baseUrl' => '@web',
      'bundles' => [
        'yii\bootstrap\BootstrapAsset' => [
          'sourcePath' => null,
          'basePath' => null,
          'baseUrl' => null,
          'js' => [],
          'css' => [],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
          'sourcePath' => null,
          'basePath' => null,
          'baseUrl' => null,
          'js' => [],
          'css' => [],
        ],
        'yii\web\JqueryAsset' => [
          'sourcePath' => null,
          'basePath' => null,
          'baseUrl' => null,
          'js' => [],
          'css' => [],
        ],
        'yii\jui\JuiAsset' => [
          'sourcePath' => null,
          'basePath' => null,
          'baseUrl' => null,
          'js' => [],
          'css' => [],
        ],
      ],
    ]),
];
