<?php

namespace site\assets;

use yii\web\AssetBundle;

class ChartjsAsset extends AssetBundle
{
    public $sourcePath = '@npm';
    public $js = [
        'chart.js/dist/Chart.min.js',
        'luxon/build/global/luxon.min.js',
        //'luxon/build/cjs-browser/luxon.js', // maybe?
        'chartjs-adapter-luxon/dist/chartjs-adapter-luxon.min.js',
    ];
}
