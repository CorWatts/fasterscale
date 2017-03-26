<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Check-in";

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
  return Html::checkbox
    (
      $name,
      $checked,
      [
        'value' => $value,
        'label' => $label,
        'container' => false,
        'labelOptions' =>
        [
          'class' => $checked ? 'btn btn-default active' : 'btn btn-default',
        ],
      ]
    );
}
?>
<h1>Check-in</h1>
<p>Click all the options below that apply to your current emotional state. Once finished, click the submit button at the bottom.</p>
<?php
$form = ActiveForm::begin([
  'id' => 'checkin-form',
  'options' => ['class' => 'form-horizontal'],
]);

foreach($categories as $category) {
  print $form->field($model, "options{$category['id']}")->checkboxList($optionsList[$category['id']], ['data-toggle' => 'buttons', 'item' => "checkboxItemTemplate"]);
}
print Html::submitButton('Submit', ['class' => 'btn btn-success']); 
ActiveForm::end();
?>
