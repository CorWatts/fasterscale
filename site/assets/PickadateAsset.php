<?php

namespace site\assets;

use yii\web\AssetBundle;

class PickadateAsset extends AssetBundle
{
    public $sourcePath = '@bower/pickadate/lib';
    public $js = [
        'picker.js',
        'picker.date.js',
    ];

    public $depends = [
      'yii\web\JqueryAsset',
    ];
}
