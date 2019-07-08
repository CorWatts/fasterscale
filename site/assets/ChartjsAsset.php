<?php

namespace site\assets;

use yii\web\AssetBundle;

class ChartjsAsset extends AssetBundle
{
    public $sourcePath = '@bower/chartjs/dist';
    public $js = [
        'Chart.min.js',
    ];
    public $depends = [
      'yii\web\JqueryAsset',
    ];
}

