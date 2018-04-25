<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper as AH;

/**
 * @property integer $id
 * @property string $name
 * @property integer $weight
 */
class Category extends \yii\base\BaseObject
{

  // a weight of 0 means selections in that category do not increase the score
  public static $categories = [
    [ "id" => 1, "weight" => 0,  "name" => "Restoration"],
    [ "id" => 2, "weight" => 1,  "name" => "Forgetting Priorities"],
    [ "id" => 3, "weight" => 2,  "name" => "Anxiety"],
    [ "id" => 4, "weight" => 4,  "name" => "Speeding Up"],
    [ "id" => 5, "weight" => 6,  "name" => "Ticked Off"],
    [ "id" => 6, "weight" => 8,  "name" => "Exhausted"],
    [ "id" => 7, "weight" => 10, "name" => "Relapse/Moral Failure"],
  ];

  public static $colors = [
    1 => [
      "color" => "#008000",
      "highlight" => "#199919"
    ],
    2 => [
      "color" => "#4CA100",
      "highlight" => "#61B219"
    ],
    3 => [
      "color" => "#98C300",
      "highlight" => "#AACC33"
    ],
    4 => [
      "color" => "#E5E500",
      "highlight" => "#E5E533"
    ],
    5 => [
      "color" => "#E59900",
      "highlight" => "#E5AA33"
    ],
    6 => [
      "color" => "#E54B00",
      "highlight" => "#E56D33"
    ],
    7 => [
      "color" => "#CC0000",
      "highlight" => "#CC3333"
    ]
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
