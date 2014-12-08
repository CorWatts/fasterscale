<?php
namespace site\models;

use Yii;
use common\models\User;
use common\models\Question;
use common\models\UserOption;
use yii\base\Model;
use yii\db\Expression;
use \DateTimeZone;

/**
 * Question form
 */
class QuestionForm extends Model
{
    public $user_option_id1;
    public $user_option_id2;
    public $user_option_id3;
    public $user_option_id4;
    public $user_option_id5;
    public $user_option_id6;
    public $user_option_id7;

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
            [['user_option_id1', 'user_option_id2', 'user_option_id3', 'user_option_id4', 'user_option_id5', 'user_option_id6', 'user_option_id7'], 'integer'],
            [['answer_1a','answer_1b','answer_1c','answer_2a','answer_2b','answer_2c','answer_3a','answer_3b','answer_3c','answer_4a','answer_4b','answer_4c','answer_5b','answer_5c','answer_6b','answer_6c','answer_7b','answer_7c'], 'safe']
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

    public function saveAnswers() {
        $result = true;
        for($i = 1; $i < 8; $i ++) {
            if(isset($this->{"user_option_id".$i})) {
                $user_option = UserOption::find()->with("option")->where(['id' => $this->{"user_option_id".$i}])->one();
                for($j = 1; $j < 4; $j ++) {
                    $answer_prop = "answer_".$i.Question::$TYPES[$j];
                    if(isset($this->$answer_prop)) {
                        $user_option_prop = "user_option_id".$i;
                        $model = new Question;
                        $model->user_id = Yii::$app->user->id;
                        $model->option_id = $user_option->option->id;
                        $model->user_option_id = $this->$user_option_prop;
                        $model->date = new Expression("now()::timestamp");
                        $model->question = $j;
                        $model->answer = $this->$answer_prop;
                        $model->save();
                        if(!$model->save()) {
                            $result = false;
                        }
                    }
                }
            }
        }
        return $result;
    }
 
}
