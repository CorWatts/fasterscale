<?php
namespace site\models;

use Yii;
use yii\base\Model;

/**
 * Checkin form
 */
class CheckinForm extends Model
{
    public $options1;
    public $options2;
    public $options3;
    public $options4;
    public $options5;
    public $options6;
    public $options7;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['options1', 'options2', 'options3', 'options4', 'options5', 'options6', 'options7'], 'validateOptions'],
        ];
    }

    public function attributeLabels() {
        return [
            'options1' => 'Restoration',
            'options2' => 'Forgetting Priorities',
            'options3' => 'Anxiety',
            'options4' => 'Speeding Up',
            'options5' => 'Ticked Off',
            'options6' => 'Exhausted',
            'options7' => 'Relapsed/Moral Failure'
        ];
    }

    public function validateOptions($attribute, $params)
    {
        if (!$this->hasErrors()) {
            foreach($this->$attribute as $option) {
                if(!is_numeric($option))
                    $this->addError($attribute, 'One of your options is not an integer!');
            }
        }
    }
}
