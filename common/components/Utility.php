<?php
namespace common\components;

use yii;

class Utility {
  public static $REVISION_FILE = "./REVISION";

  public static function getGithubRevUrl() {
    if($hash = self::getRevHash()) {
      return "https://github.com/CorWatts/fasterscale/commit/$hash";
    }
    return false;
  }

  public static function getRevHash() {
    if(file_exists(self::$REVISION_FILE)) {
      return substr(file_get_contents(self::$REVISION_FILE), 0, 7);
    }
    return false;
  }

}

