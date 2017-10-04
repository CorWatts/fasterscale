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

