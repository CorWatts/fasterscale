<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "option".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 *
 * @property UserOptionLink[] $userOptionLinks
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['name'], 'string'],
            [['category_id'], 'integer']
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
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOption()
    {
        return $this->hasMany(UserOptionLink::className(), ['option_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getAllOptionsByCategory() {
            $query = new Query;
            $query->select("c.id as category_id, c.name as name, c.weight as weight, COUNT(o.id) as option_count")
                ->from('category c')
                ->join("INNER JOIN", "option o", "o.category_id = c.id")
                ->groupBy('c.id, c.name, c.weight')
                ->orderBy('c.id')
                ->indexBy('category_id');
            return $query->all();
    }
}
