<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
/**
 * @var yii\web\View $this
 */

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
    return "<label class='checkbox-inline'><input type='checkbox' name='$name' value='$value' />$label</label>";
}
?>
<h1>Check In</h1>
<p>Click all the options below that apply to your current emotional state. Once finished, click the submit button at the bottom.</p>
<?php
$form = ActiveForm::begin([
    'id' => 'checkin-form',
    'options' => ['class' => 'form-horizontal'],
]);

foreach($categories as $category) {
    print $form->field($model, "options{$category['id']}")->checkboxList($optionsList[$category['id']], ['item' => "checkboxItemTemplate"]);
}
print Html::submitButton('Submit', ['class' => 'btn btn-success']); 
ActiveForm::end();
?>
