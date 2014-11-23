<?php
namespace site\models;

use common\models\User;
use yii\base\Model;
use Yii;
use \DateTimeZone;

/**
 * Question form
 */
class QuestionForm extends Model
{
    public $option_id1;
    public $option_id2;
    public $option_id3;
    public $option_id4;
    public $option_id5;
    public $option_id6;
    public $option_id7;

    public $answer_1a;
    public $answer_1b;
    public $answer_1c;

    public $answer_2a;
    public $answer_2b;
    public $answer_2c;
    
    public $answer_3a;
    public $answer_3b;
    public $answer_3c;
    
    public $answer_4a;
    public $answer_4b;
    public $answer_4c;
    
    public $answer_5a;
    public $answer_5b;
    public $answer_5c;
    
    public $answer_6a;
    public $answer_6b;
    public $answer_6c;
    
    public $answer_7a;
    public $answer_7b;
    public $answer_7c;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id1', 'option_id2', 'option_id3', 'option_id4', 'option_id5', 'option_id6', 'option_id7'], 'integer'],
            [['answer_1a', 'answer_1b', 'answer_1c'], 'required', 'when' => function($model) {return isset($model->option_id1);}],
            [['answer_2a', 'answer_2b', 'answer_2c'], 'required', 'when' => function($model) {return isset($model->option_id2);}],
            [['answer_3a', 'answer_3b', 'answer_3c'], 'required', 'when' => function($model) {return isset($model->option_id3);}],
            [['answer_4a', 'answer_4b', 'answer_4c'], 'required', 'when' => function($model) {return isset($model->option_id4);}],
            [['answer_5a', 'answer_5b', 'answer_5c'], 'required', 'when' => function($model) {return isset($model->option_id5);}],
            [['answer_6a', 'answer_6b', 'answer_6c'], 'required', 'when' => function($model) {return isset($model->option_id6);}],
            [['answer_7a', 'answer_7b', 'answer_7c'], 'required', 'when' => function($model) {return isset($model->option_id7);}],

        ];
    }

    public function attributeLabels() {
        return [
            'option_id1' => 'Restoration',
            'option_id2' => 'Forgetting Priorities',
            'option_id3' => 'Anxiety',
            'option_id4' => 'Speeding Up',
            'option_id5' => 'Ticked Off',
            'option_id6' => 'Exhausted',
            'option_id7' => 'Relapsed/Moral Failure'
        ];
    }

}
