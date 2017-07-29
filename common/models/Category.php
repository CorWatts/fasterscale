<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper as AH;

/**
 * @property integer $id
 * @property string $name
 * @property integer $weight
 */
class Category extends \yii\base\Object
{

  public static $categories = [
    [ "id" => 1, "name" => "Restoration", "weight" => 0],
    [ "id" => 2, "name" => "Forgetting Priorities", "weight" => 1],
    [ "id" => 3, "name" => "Anxiety", "weight" => 2],
    [ "id" => 4, "name" => "Speeding Up", "weight" => 4],
    [ "id" => 5, "name" => "Ticked Off", "weight" => 6],
    [ "id" => 6, "name" => "Exhausted", "weight" => 8],
    [ "id" => 7, "name" => "Relapse/Moral Failure", "weight" => 10]
  ];

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['name', 'weight'], 'required'],
      [['weight'], 'integer'],
      [['name'], 'string', 'max' => 255]
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'weight' => 'Weight',
    ];
  }

  public static function getCategories() {
    return AH::map(\common\models\Category::$categories, 'id', 'name');
  }

  public static function getCategory($key, $val) {
    $indexed = AH::index(self::$categories, null, $key);
    return AH::getValue($indexed, $val, [false])[0];
  }
}
