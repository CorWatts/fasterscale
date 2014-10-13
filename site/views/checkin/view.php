<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
/**
 * @var yii\web\View $this
 */

$this->title = "Past Checkins";

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
    $checked_val = ($checked) ? "active" : "";
    return "<button class='btn btn-default $checked_val' data-toggle='button' name='$name' value='$value'>$label</button>";
}
?>
<h1>View Past Checkins</h1>
<div id='past-checkin-nav' class='btn-group'>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date -1 week"))]); ?>">&lt;&lt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date -1 day"))]); ?>">&lt;</a> 
        <?= yii\jui\DatePicker::widget([
            'name' => 'attributeName', 
            'value' => date("Y-m-d", strtotime($date)), 
            'options' => ['class'=> 'btn btn-default'],
            'language' => 'en',
            'dateFormat' => 'yyyy-MM-dd', 
            'clientOptions' => [
                'defaultDate' => date("Y-m-d", strtotime($date)),
                'onSelect' => new \yii\web\JsExpression("function(dateText, obj) { location.href = '/checkin/view/'+dateText; }"),
                'beforeShowDay' => new \yii\web\JsExpression("function(date) { 
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    var dates = ".json_encode($past_checkin_dates).";
                    return [ dates.indexOf(string) > -1 ];
                }")
            ]
        ]) ?>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date +1 day"))]); ?>">&gt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>date("Y-m-d", strtotime("$date +1 week"))]); ?>">&gt;&gt;</a> 
</div>

<?php
    if($score < 30) {
        $alert_level = "success";
        $alert_msg = "You're doing well! Keep on doing whatever it is you're doing!";
    } else if($score < 50) {
        $alert_level = "info";
        $alert_msg = "Some warning signs, but nothing too bad. Have some quiet time, process things, and call a friend.";
    } else if($score < 70) {
        $alert_level = "warning";
        $alert_msg = "Definite warning signs. You aren't doing well. Take some time out, write out what you're feeling, and discuss it with someone.";
    } else {
        $alert_level = "danger";
        $alert_msg = "Welcome to the dangerzone. You need to take action right now, or else you WILL act out. Go call someone.";
    }
?>

<div id='score'>
    <h2>Score: <?php print $score; ?></h2>
    <div class='alert alert-<?php print $alert_level; ?>'><?php print $alert_msg; ?></div>
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
