<?php

namespace site\assets;

use yii\web\AssetBundle;

class JuiAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.js',
    ];
    public $css = [
        'themes/flick/jquery-ui.css',
    ];
    public $depends = [
        'site\assets\JqueryAsset',
    ];
}
