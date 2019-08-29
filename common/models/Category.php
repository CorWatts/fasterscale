<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper as AH;

/**
 * @property integer $id
 * @property string $name
 */
class Category extends \yii\base\BaseObject implements \common\interfaces\CategoryInterface {

  public static $categories = [
    [ "id" => 1, "name" => "Restoration"],
    [ "id" => 2, "name" => "Forgetting Priorities"],
    [ "id" => 3, "name" => "Anxiety"],
    [ "id" => 4, "name" => "Speeding Up"],
    [ "id" => 5, "name" => "Ticked Off"],
    [ "id" => 6, "name" => "Exhausted"],
    [ "id" => 7, "name" => "Relapse/Moral Failure"],
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
      ['name', 'required'],
      ['name', 'string', 'max' => 255],
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
    ];
  }

  /**
   * getCategories() returns a array of categories indexed by the category id.
   * The format is similar to:
   *     [
   *        1 => 'Restoration',
   *        2 => 'Forgetting Priorities',
   *        ...
   *      ]
   * @return array of categories
   */
  public static function getCategories() {
    return AH::map(\common\models\Category::$categories, 'id', 'name');
  }

  /**
   * Given a $key => $value pair, returns the matching category.
   * Example:
   *     getCategory('id', 1);
   * Should return:
   *     [ "id" => 1, "name" => "Restoration"]
   *
   * @param string $key the name of the attribute to filter on
   * @param string $val the value of the attribute to filter on
   * @return a single category
   */
  public static function getCategory($key, $val) {
    $ret = array_values(array_filter(self::$categories, function($cat) use ($key, $val) {
      return $cat[$key] === $val;
    }));
    return $ret ? $ret[0] : null;
  }
}
