<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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

