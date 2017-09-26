<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap javascript files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BootstrapPluginAsset extends AssetBundle
{
    /**
     * we bring in the Bootstrap CSS in our
     * SCSS files. The Bootstrap CSS is not
     * handled by Yii's asset pipeline.
     */

    public $sourcePath = '@bower/bootstrap-sass/assets';
    public $js = [
        'javascripts/bootstrap.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
