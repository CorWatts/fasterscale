<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $weight
 */
class Category extends \yii\db\ActiveRecord
{
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'category';
  }

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

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getOptions()
  {
    return $this->hasMany(Option::className(), ['category_id' => 'id']);

  }
}
