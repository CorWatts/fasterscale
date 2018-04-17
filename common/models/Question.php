<?php

namespace common\models;

use Yii;
use common\interfaces\QuestionInterface;
use \common\components\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $behavior_id
 * @property integer $user_behavior_id
 * @property integer $question
 * @property string $answer
 * @property string $date
 *
 * @property User $user
 * @property Behavior $behavior
 * @property UserBehaviorLink $userBehavior
 */
class Question extends ActiveRecord implements QuestionInterface
{
  public static $TYPES = [
    'a' => 1,
    'b' => 2,
    'c' => 3
  ];

  public static $QUESTIONS = [
    1 => "How does it affect me? How do I act and feel?",
    2 => "How does it affect the important people in my life?",
    3 => "Why do I do this? What is the benefit for me?"
  ];

  public $question_text;

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'question';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['user_id', 'behavior_id', 'user_behavior_id', 'question', 'answer', 'date'], 'required'],
      [['user_id', 'behavior_id', 'user_behavior_id', 'question'], 'integer'],
      ['behavior_id', 'in', 'range' => array_column(\common\models\Behavior::$behaviors, 'id')],
      ['answer', 'string'],
      ['date', 'safe']
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id'             => 'ID',
      'user_id'        => 'User ID',
      'behavior_id'      => 'Behavior ID',
      'user_behavior_id' => 'User Behavior ID',
      'question'       => 'Question',
      'answer'         => 'Answer',
      'date'           => 'Date',
    ];
  }

  public function afterFind()
  {
    $this->question_text = self::$QUESTIONS[$this->question];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUserBehavior()
  {
    return $this->hasOne(\common\models\UserBehavior::class, ['id' => 'user_behavior_id']);
  }
}
