<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace site\assets;

use yii\web\AssetBundle;

class JqueryUiAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.js',
    ];
    public $depends = [
      'site\assets\JqueryAsset',
    ];
}
