<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace site\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@assets';

    public $css = [
        'css/app.scss',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'site\assets\JqueryAsset',
        'site\assets\BootstrapPluginAsset',
        'site\assets\MomentAsset',
        'site\assets\ChartjsAsset',
    ];
}
