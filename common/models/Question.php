<?php

namespace common\models;

use Yii;
use \common\interfaces\QuestionInterface;
use \common\components\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $behavior_id
 * @property integer $category_id
 * @property integer $user_behavior_id
 * @property integer $question
 * @property string $answer
 * @property string $date
 *
 * @property User $user
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
     * @codeCoverageIgnore
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
      [['user_id', 'category_id', 'user_behavior_id', 'question', 'answer', 'date'], 'required'],
      [['user_id', 'behavior_id', 'category_id', 'user_behavior_id', 'question'], 'integer'],
      ['behavior_id', 'in', 'range' => array_column(\common\models\Behavior::$behaviors, 'id')],
      ['category_id', 'in', 'range' => array_column(\common\models\Category::$categories, 'id')],
      ['answer', 'string'],
      ['date', 'safe']
    ];
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
      'id'             => 'ID',
      'user_id'        => 'User ID',
      'behavior_id'      => 'Behavior ID',
      'category_id'      => 'Category ID',
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
     * @codeCoverageIgnore
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @codeCoverageIgnore
     */
    public function getUserBehavior()
    {
        return $this->hasOne(\common\models\UserBehavior::class, ['id' => 'user_behavior_id']);
    }

    public function getByUser(int $user_id, $local_date = null)
    {
        $time = Yii::$container->get(\common\interfaces\TimeInterface::class);
        if (is_null($local_date)) {
            $local_date = $time->getLocalDate();
        }
        list($start, $end) = $time->getUTCBookends($local_date);

        $questions = $this->find()
      ->where(
          "user_id=:user_id 
      AND date > :start_date 
      AND date < :end_date",
          [
      "user_id" => Yii::$app->user->id,
      ':start_date' => $start,
      ":end_date" => $end
    ]
      )
    ->with('userBehavior')
    ->all();

        return $this->parseQuestionData($questions);
    }

    /**
     * Parses question array (formatted like the return value from Question::find())
     * into an easily-iterable format for display.
     *
     *
     * @param array $questions the raw list of questions and data
     * @return an array of easily-iterable questions and related data
     */
    public function parseQuestionData($questions)
    {
        if (!$questions) {
            return [];
        }

        $question_answers = [];
        foreach ($questions as $question) {
            $user_behavior_id = $question->user_behavior_id;

            $behavior_name = $question->behavior_id
                // TODO: I think this is potentially a source of exceptions
                // do we check if the behavior_id is an expected value on
                // form submission?
                ? \common\models\Behavior::getBehavior('id', $question->behavior_id)['name']
                : $question->userBehavior->custom_behavior;

            $category_name = \common\models\Category::getCategory('id', $question->category_id)['name'] ?? "Question";

            $question_answers[$user_behavior_id]['category_id'] = $question->category_id;
            $question_answers[$user_behavior_id]['category_name'] = $category_name;
            $question_answers[$user_behavior_id]['question'] = [
                "user_behavior_id" => $user_behavior_id,
                "behavior_name" => $behavior_name,
            ];

            $question_answers[$user_behavior_id]["answers"][] = [
                "title" => $this::$QUESTIONS[$question['question']],
                "answer" => $question['answer']
            ];
        }

        /*
         * Sort the questions by category_id, ascending. For some reason
         * questions/answers associated with custom behaviors are always last
         * when supplied func. Looks like those user_behavior_ids are always a
         * higher number than the user_behavior_ids for regular behaviors.
         *
         * Instead of acutally hunting down and fixing where this originally
         * happens we can sort the results here. Lazy, I know, but it gets the
         * job done.
         */
        usort($question_answers, function($a, $b) {
            return $a['category_id'] <=> $b['category_id'];
        });
        return $question_answers;
    }
}
