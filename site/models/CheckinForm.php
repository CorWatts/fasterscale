<?php
namespace site\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use common\models\UserOption;

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

  public $compiled_options;
  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      // username and password are both required
      [
        [
          'options1',
          'options2',
          'options3',
          'options4',
          'options5',
          'options6',
          'options7'
        ],
        'validateOptions'],
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
        if(!is_numeric($option)) {
          $this->addError($attribute, 'One of your options is not an integer!');
        }
      }
    }
  }

  public function compileOptions() {
    $options = array_merge((array)$this->options1,
                           (array)$this->options2,
                           (array)$this->options3,
                           (array)$this->options4,
                           (array)$this->options5,
                           (array)$this->options6,
                           (array)$this->options7);

    return array_filter($options); // strip out false values
  }

  public function save() {
    if(empty($this->compiled_options)) {
      $this->commpiled_options = $this->compileOptions();
    }

    $rows = [];
    foreach($this->compiled_options as $option_id) {
      $temp = [
        Yii::$app->user->id,
        (int)$option_id,
        new Expression("now()::timestamp")
      ];
      $rows[] = $temp;
    }

    Yii::$app
      ->db
      ->createCommand()
      ->batchInsert(
        UserOption::tableName(),
        ['user_id', 'option_id', 'date'],
        $rows
      )->execute();
  }
}
