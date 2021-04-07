<?php

namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * custom behavior form
 */
class CustomBehaviorForm extends Model
{
    public $name;
    public $category_id;

    private $user;
    private $categories;

    /**
     * Creates a form model
     *
     * @param  object $user
     * @param  array  $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct(\common\interfaces\UserInterface $user, \common\interfaces\CategoryInterface $category, $config = [])
    {
        $this->user = $user;
        $this->categories = $category->categories;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['name', 'category_id'], 'required'],
        ['name', 'filter', 'filter' => 'trim'],
        ['category_id', 'integer'],
        ['category_id', 'in', 'range' => array_keys($this->categories)],

        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
        'name'        => 'Behavior Name',
        'category_id' => 'Behavior Category',
        ];
    }


    public function save()
    {
        $custom_behavior = Yii::$container->get(\common\interfaces\CustomBehaviorInterface::class);
        $custom_behavior->name = $this->name;
        $custom_behavior->user_id = $this->user->id;
        $custom_behavior->category_id = $this->category_id;
        return $custom_behavior->save();
    }
}
