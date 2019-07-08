<?php

namespace site\assets;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle
{
    public $sourcePath = '@vendor/moment/moment/min';
    public $js = [
        'moment.min.js',
    ];
}

