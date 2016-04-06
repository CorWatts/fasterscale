<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @var yii\web\View $this
 */

$this->title = "Check-In | Questions";

function radioItemTemplate($index, $label, $name, $checked, $value) {
  return Html::radio
    (
      $name,
      $checked,
      [
        'value' => $value,
        'label' => $label,
        'container' => false,
        'labelOptions' =>
        [
          'class' => $checked ? 'btn btn-info active' : 'btn btn-info',
        ],
      ]
    );
}

$categories = [];
$restructured_options = [];
foreach($options as $option) {
  $categories[$option['category_id']] = $option['category_name'];
  $restructured_options[$option['category_id']][$option['user_option_id']] = $option['option_name'];
}
?>
<h1>Check-In Questions</h1>
<p>In each category, select one feeling, then answer the related questions.</p>
<?php
$form = ActiveForm::begin([
  'id' => 'checkin-form',
]);

foreach($categories as $category_id => $category_name) {
  print $form->field($model, "user_option_id{$category_id}")->radioList($restructured_options[$category_id], ['class' => "btn-group", 'data-toggle' => 'buttons', 'item'=>"radioItemTemplate"]);
  print $form->field($model, "answer_{$category_id}a")->textarea()->label("How does it affect me? How do I act and feel?");
  print $form->field($model, "answer_{$category_id}b")->textarea()->label("How does it affect the important people in my life?");
  print $form->field($model, "answer_{$category_id}c")->textarea()->label("Why do I do this? What is the benefit for me?");
}

print Html::submitButton('Submit', ['class' => 'btn btn-success']); 
ActiveForm::end();
?>
