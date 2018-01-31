<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\helpers\ArrayHelper as AH;

use common\models\User;
use common\models\Question;
/**
 * @var yii\web\View $this
 */
\site\assets\PickadateAsset::register($this);

$this->title = "The Faster Scale App | Previous Check-ins";
$time = Yii::$container->get('common\interfaces\TimeInterface');
$this->registerJsFile('/js/checkin/view.js', ['depends' => [\site\assets\AppAsset::className()]]);

function checkboxItemTemplate($index, $label, $name, $checked, $value) {
  $checked_val = ($checked) ? "btn-primary" : "";
  return "<button class='btn btn-default $checked_val' data-toggle='button' disabled='disabled' name='$name' value='$value'>$label</button>";
}
?>
<div class="checkin-view">
  <h1>View Previous Check-ins</h1>
  <div id='past-checkin-nav' role='toolbar' class='btn-toolbar'>
    <div class='form-inline'>
      <div class='btn-group' role='group'>
        <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$time->alterLocalDate($actual_date, "-1 week")]); ?>" aria-label="Previous Week">&lt;&lt;</a> 
        <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$time->alterLocalDate($actual_date, "-1 day")]); ?>" aria-label="Previous Day">&lt;</a> 
      </div>
      <div class='btn-group datepicker-container' role='group'>
        <input type="text" id="datepicker" class='form-control' value="<?=$actual_date?>" data-value="<?=$actual_date?>" readOnly="true" />
      </div>
      <div class='btn-group' role='group'>
        <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$time->alterLocalDate($actual_date, "+1 day")]); ?>" aria-label="Next Day">&gt;</a> 
        <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$time->alterLocalDate($actual_date, "+1 week")]); ?>" aria-label="Next Week">&gt;&gt;</a> 
      </div>
    </div>
  </div>

  <?php
              switch(true) {
                case ($score < 30):
                  $alert_level = "success";
                  $alert_msg = "You're doing well! Keep on doing whatever it is you're doing!";
                  break;

              case ($score < 40):
                $alert_level = "info";
                $alert_msg = "Some warning signs, but nothing too bad. Analyze what you're feeling and be watchful you don't move further down the scale.";
                break;

              case ($score < 60):
                $alert_level = "warning";
                $alert_msg = "Definite warning signs. Sounds like you aren't doing well. Take a break from whatever you're doing and process your thoughts and emotions.";
                break;

              default:
                $alert_level = "danger";
                $alert_msg = "Welcome to the dangerzone. You need to take action right now or you'll continue moving down the scale. Go call someone. Try visiting <a href='http://emergency.nofap.org/'>http://emergency.nofap.org</a> for immediate help.";
            }
?>

<div id='score'>
    <h2>Score: <?php print $score; ?></h2>
    <div class='alert alert-<?=$alert_level?>'><?=$alert_msg?></div>
</div>

  <?php if($questions) {
  foreach($questions as $behavior_id => $behavior_questions) {
    print "<div class='well well-sm'>";
    print "<button type='button' class='btn btn-primary' disabled='disabled'>{$behavior_questions['question']['title']}</button>";
    print "<div class='row'>";
    foreach($behavior_questions['answers'] as $question) { 
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
    $behaviors = AH::map($behaviorsList[$category['id']], 'id', 'name');
    print $form
            ->field($model, "behaviors{$category['id']}")
            ->checkboxList($behaviors,
                           ['item' => "checkboxItemTemplate"]);
  }
  ActiveForm::end();

$this->registerJson($past_checkin_dates, "past-checkin-dates");
?>
</div>
