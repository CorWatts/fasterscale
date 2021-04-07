<?php

namespace common\components;

use yii;

class Utility
{
    public static $REVISION_FILE = "./REVISION";

    public static function getGithubRevUrl($file = false)
    {
        if ($hash = self::getRevHash($file)) {
            return "https://github.com/CorWatts/fasterscale/commit/$hash";
        }
        return false;
    }

    public static function getRevHash($file = false)
    {
        $file = $file ?: self::$REVISION_FILE;
        if (is_file($file) && is_readable($file)) {
            return substr(file_get_contents($file), 0, 7);
        }
        return false;
    }
}
