<?php

namespace common\models;

use Yii;
use common\interfaces\QuestionInterface;
use \common\components\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $option_id
 * @property integer $user_option_id
 * @property integer $question
 * @property string $answer
 * @property string $date
 *
 * @property User $user
 * @property Option $option
 * @property UserOptionLink $userOption
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
      [['user_id', 'option_id', 'user_option_id', 'question', 'answer', 'date'], 'required'],
      [['user_id', 'option_id', 'user_option_id', 'question'], 'integer'],
      ['option_id', 'in', 'range' => array_column(\common\models\Option::$options, 'id')],
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
      'option_id'      => 'Option ID',
      'user_option_id' => 'User Option ID',
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
    return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUserOption()
  {
    return $this->hasOne(\common\models\UserOption::className(), ['id' => 'user_option_id']);
  }
}
