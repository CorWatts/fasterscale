<?php

namespace site\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@assets';

    public $css = [
        'css/app.scss',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        // we disable all the css in the bootstrap asset here. But we pull the css into the main appasset css at the top.
        // however, the bootstrap fonts are still pulled in here in this bootstrap asset. The url is different, because the css (pulled in via appasset) is looking for the fonts in its same asset directory.
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'site\assets\MomentAsset',
        'site\assets\ChartjsAsset',
    ];
}
