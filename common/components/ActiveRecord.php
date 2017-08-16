<?php

namespace common\components;

class ActiveRecord extends \yii\db\ActiveRecord {

  public static function instantiate($row)
  {
    return \Yii::$container->get(static::class);
  }

}
