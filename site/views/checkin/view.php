<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
/**
 * @var yii\web\View $this
 */

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
    $checked_val = ($checked) ? "active" : "";
    return "<button class='btn btn-default $checked_val' data-toggle='button' name='$name' value='$value'>$label</button>";
}
?>
<h1>View Past Checkins</h1>
<div id='past-checkin-nav' class='btn-group'>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date -1 week"))]); ?>">&lt;&lt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date -1 day"))]); ?>">&lt;</a> 
    <button type="button" class="btn btn-default disabled"><?php print date("F j Y", strtotime($date)); ?></button>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date +1 day"))]); ?>">&gt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date +1 week"))]); ?>">&gt;&gt;</a> 
</div>

<?php
$form = ActiveForm::begin([
    'id' => 'checkin-form',
    'options' => ['class' => 'form-horizontal'],
]);

foreach($categories as $category) {
    print $form->field($model, "options{$category['id']}")->checkboxList($optionsList[$category['id']], ['item' => "checkboxItemTemplate"]);
}
ActiveForm::end();
?>
