<?php
namespace site\models;

use Yii;
use common\models\User;
use common\models\Question;
use common\models\UserOption;
use common\components\Time;
use yii\base\Model;
use yii\db\Expression;

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

      [['user_option_id1', 'user_option_id2', 'user_option_id3', 'user_option_id4', 'user_option_id5', 'user_option_id6', 'user_option_id7'],
        'required',
        'when' => self::getBhvrValidator(),
        'whenClient' => "function(attribute, value) { return false; }", // lame, but acceptable
        "message" => "You must select a behavior your responses apply to."],

      [['answer_1a','answer_1b','answer_1c','answer_2a','answer_2b','answer_2c','answer_3a','answer_3b','answer_3c','answer_4a','answer_4b','answer_4c','answer_5a', 'answer_5b','answer_5c','answer_6a', 'answer_6b','answer_6c','answer_7a','answer_7b','answer_7c'], 'safe']
    ];
  }

  public function attributeLabels() {
    return [
      'user_option_id1' => 'Restoration',
      'user_option_id2' => 'Forgetting Priorities',
      'user_option_id3' => 'Anxiety',
      'user_option_id4' => 'Speeding Up',
      'user_option_id5' => 'Ticked Off',
      'user_option_id6' => 'Exhausted',
      'user_option_id7' => 'Relapsed/Moral Failure'
    ];
  }

  public static function getBhvrValidator() {
    return function($model, $attr) {
      $attrNum = $attr[strlen($attr)-1];
      foreach(['a', 'b', 'c'] as $l) {
        $attr = "answer_{$attrNum}{$l}";
        if($model->$attr) return true;
      }
      return false;
    };
  }

  public function deleteToday() {
    $date = Time::getLocalDate();
    list($start, $end) = Time::getUTCBookends($date);
    Question::deleteAll("user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date", 
      [
        ":user_id" => Yii::$app->user->id, 
        ':start_date' => $start, 
        ":end_date" => $end
      ]
    );
  }

  public function behaviorToAnswers($bhvr_number) {
    $prop_names   = array_keys($this->getPrefixProps("answer_$bhvr_number"));
    $prop_letters = array_map(function($n) { return substr($n, -1, 1); }, $prop_names);
    $answers = array_map(function($n) { return $this->$n; }, $prop_names);
 
    return array_combine($prop_letters, $answers);
  }

  public function getPrefixProps($prefix) {
    return array_filter(get_object_vars($this), function($v, $k) use($prefix) {
      if(strpos($k, $prefix) === 0 && strlen($v)) {
        return true;
      }
    }, ARRAY_FILTER_USE_BOTH);
  }

  public function getUserOptions($ids) {
    return UserOption::findAll($ids);
  }

  public function getUserBehaviors() {
    $user_bhvr_props = $this->getPrefixProps('user_option_id');
    $user_bhvrs = $this->getUserOptions(array_values($user_bhvr_props));
    return array_combine(array_keys($user_bhvr_props), $user_bhvrs);
  }

  public function getAnswers() {
    $answers = [];
    $user_bhvrs = $this->getUserBehaviors();
    foreach($user_bhvrs as $property => $user_bhvr) {
      $behavior_id = intval(substr($property, -1, 1));
      foreach($this->behaviorToAnswers($behavior_id) as $answer_letter => $answer) {
        $question_id = Question::$TYPES[$answer_letter];
        array_push($answers, [
                               'option_id'    => $user_bhvr->option_id,
                               'user_bhvr_id' => $user_bhvr->id,
                               'question_id'  => $question_id,
                               'answer'       => $answer,
                             ]);
      }
    }
    return $answers;
  }

  public function saveAnswers() {
    $result = true;
    foreach($this->getAnswers() as $answer_data) {
      if(!$this->saveModel($answer_data)) {
        $result = false;
      }
    }
    return $result;
  }

  public function saveModel(Array $answer_data) {
    extract($answer_data);
    $model = new Question;
    $model->user_id = Yii::$app->user->id;
    $model->option_id = $option_id;
    $model->user_option_id = $user_bhvr_id;
    $model->date = new Expression("now()::timestamp");
    $model->question = $question_id;
    $model->answer = $answer;
    return $model->save();
  }
}
