<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use common\models\User;
use common\models\Question;
use common\components\Time;
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Previous Check-ins";

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
  $checked_val = ($checked) ? "btn-primary" : "";
  return "<button class='btn btn-default $checked_val' data-toggle='button' disabled='disabled' name='$name' value='$value'>$label</button>";
}
?>
<h1>View Previous Check-ins</h1>
<div id='past-checkin-nav' role='toolbar' class='btn-toolbar'>
  <div class='btn-group' role='group'>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>Time::alterLocalDate($actual_date, "-1 week")]); ?>">&lt;&lt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>Time::alterLocalDate($actual_date, "-1 day")]); ?>">&lt;</a> 
  </div>
  <div class='btn-group' role='group'>
<?= yii\jui\DatePicker::widget([
  'name' => 'attributeName', 
  'value' => $actual_date,
  'options' => ['class'=> 'btn btn-default datepicker', 'readonly' => true],
  'dateFormat' => 'yyyy-MM-dd', 
  'clientOptions' => [
    'defaultDate' => $actual_date,
    'onSelect' => new \yii\web\JsExpression("function(dateText, obj) { location.href = '/checkin/view/'+dateText; }"),
    'beforeShowDay' => new \yii\web\JsExpression("function(date) { 
      var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
      var dates = ".json_encode($past_checkin_dates).";
      return [ dates.indexOf(string) > -1 ];
                }")
              ]
            ]) ?>
  </div>
  <div class='btn-group' role='group'>
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>Time::alterLocalDate($actual_date, "+1 day")]); ?>">&gt;</a> 
    <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>Time::alterLocalDate($actual_date, "+1 week")]); ?>">&gt;&gt;</a> 
  </div>
</div>

<?php
            switch(true) {
              case ($score < 30):
                $alert_level = "success";
                $alert_msg = "You're doing well! Keep on doing whatever it is you're doing!";
                break;

              case ($score < 50):
                $alert_level = "info";
                $alert_msg = "Some warning signs, but nothing too bad. Have some quiet time, process things, and call a friend.";
                break;

              case ($score < 70):
                $alert_level = "warning";
                $alert_msg = "Definite warning signs. You aren't doing well. Take some time out, write down what you're feeling, and discuss it with someone.";
                break;

              default:
                $alert_level = "danger";
                $alert_msg = "Welcome to the dangerzone. You need to take action right now, or else you WILL act out. Go call someone. Try visiting <a href='http://emergency.nofap.org/'>http://emergency.nofap.org</a> for immediate help.";
            }
?>

<div id='score'>
    <h2>Score: <?php print $score; ?></h2>
    <div class='alert alert-<?php print $alert_level; ?>'><?php print $alert_msg; ?></div>
</div>

<?php if($questions) {
foreach($questions as $option_id => $option_questions) {
  print "<div class='well well-sm'>";
  print "<button type='button' class='btn btn-primary' disabled='disabled'>{$option_questions['question']['title']}</button>";
  print "<div class='row'>";
  foreach($option_questions['answers'] as $question) { 
    print "<div class='col-md-4'>";
    print "<p><strong>{$question['title']}</strong></p>";
    print "<p>".Html::encode($question['answer'])."</p>";
    print "</div>";
  }
  print "</div></div>";
}
            }

$form = ActiveForm::begin([
  'id' => 'checkin-form',
  'options' => ['class' => 'form-horizontal'],
]);

foreach($categories as $category) {
  print $form->field($model, "options{$category['id']}")->checkboxList($optionsList[$category['id']], ['item' => "checkboxItemTemplate"]);
}
ActiveForm::end();
?>
