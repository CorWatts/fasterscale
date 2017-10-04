<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
 Yii::setAlias('@webroot', __DIR__ . '/../web');
 Yii::setAlias('@web', '/');

$mainConf = require(Yii::getAlias('@site/config/main.php'));
$converter = $mainConf['components']['assetManager']['converter'];
$bundles = $mainConf['components']['assetManager']['bundles'];

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'uglifyjs {from} --compress --mangle --output {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'uglifycss --debug --ugly-comments {from} > {to}',
    // The list of asset bundles to compress:
    'bundles' => [
      'yii\web\JqueryAsset',
      'site\assets\AppAsset',
      'yii\web\YiiAsset',
      'site\assets\BootstrapPluginAsset',
      'yii\widgets\ActiveFormAsset',
      'yii\validators\ValidationAsset',
      'yii\captcha\CaptchaAsset',
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
    'assetManager' => [
      'basePath'   => '@webroot/assets',
      'baseUrl'    => '@web',
      'linkAssets' => true,
      'converter'  => $converter,
      'bundles'    => $bundles,
    ],
];
