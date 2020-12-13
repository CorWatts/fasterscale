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
\site\assets\ChartjsAsset::register($this);

$this->title = "The Faster Scale App | Previous Check-ins";
$time = Yii::$container->get('common\interfaces\TimeInterface');
$this->registerJsFile('/js/checkin/view.js', ['depends' => [\site\assets\AppAsset::class]]);

function checkboxItemTemplate($index, $label, $name, $checked, $value)
{
    $checked_val = ($checked) ? "btn-primary" : "";
    return "<button class='btn btn-default $checked_val' data-toggle='button' disabled='disabled' name='$name' value='$value'>$label</button>";
}

$minus_week = $time->alterLocalDate($actual_date, "-1 week");
$minus_day  = $time->alterLocalDate($actual_date, "-1 day");
$plus_day   = $time->alterLocalDate($actual_date, "+1 day");
$plus_week  = $time->alterLocalDate($actual_date, "+1 week");

$pie_data = [];
if ($answer_pie) {
    $values     = array_map('intval', array_column($answer_pie, "count"));
    $labels     = array_column($answer_pie, "name");
    $colors     = array_column($answer_pie, "color");
    $highlights = array_column($answer_pie, "highlight");

    $pie_data = [
    "labels"   => $labels,
    "datasets" => [[
        "data"                 => $values,
        "backgroundColor"      => $colors,
        "hoverBackgroundColor" => $highlights
    ]]
  ];
}
?>
<div class="checkin-view">
  <div class="row">
    <div class="col-md-8">
      <h1>Previous Check-ins</h1>
      <div id='past-checkin-nav' role='toolbar' class='btn-toolbar'>
        <div class='form-inline'>
          <div class='btn-group' role='group'>
            <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$minus_week]); ?>" title="<?=Html::encode($minus_week)?>" aria-label="Previous Week">&lt;&lt;</a> 
            <a class="btn btn-default" href="<?= Url::toRoute(['checkin/view', 'date'=>$minus_day]); ?>" title="<?=Html::encode($minus_day)?>" aria-label="Previous Day">&lt;</a> 
          </div>
          <div class='btn-group datepicker-container' role='group'>
            <input type="text" id="datepicker" class='form-control btn btn-default' value="<?=$actual_date?>" data-value="<?=$actual_date?>" readOnly="true" />
          </div>
          <div class='btn-group' role='group'>
            <a class="btn btn-default<?= $isToday ? " disabled" : "" ?>" href="<?= Url::toRoute(['checkin/view', 'date'=>$plus_day]); ?>" title="<?=Html::encode($plus_day)?>" aria-label="Next Day">&gt;</a> 
            <a class="btn btn-default<?= $isToday ? " disabled" : "" ?>" href="<?= Url::toRoute(['checkin/view', 'date'=>$plus_week]); ?>" title="<?=Html::encode($plus_week)?>" aria-label="Next Week">&gt;&gt;</a> 
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
    <canvas id='category-pie-chart' <?= ($answer_pie) ? "" : 'class="hidden-xs hidden-sm hidden-md"' ?> height=260></canvas>
    </div>
  </div>

  <?php if ($questions) {
    foreach ($questions as $behavior_id => $behavior_questions) {
        print "<div class='well well-sm'>";
        print "<button type='button' class='btn btn-primary' disabled='disabled'>{$behavior_questions['question']['behavior_name']}</button>";
        print "<div class='row'>";
        foreach ($behavior_questions['answers'] as $question) {
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

  foreach ($categories as $category) {
      $behaviors = AH::map($behaviorsList[$category['id']], 'id', 'name');
      print $form
            ->field($model, "behaviors{$category['id']}")
            ->checkboxList(
                $behaviors,
                ['item' => "checkboxItemTemplate"]
            );
  }
  ActiveForm::end();

$this->registerJson($past_checkin_dates, "past-checkin-dates");
$this->registerJson($pie_data, "pie-chart-data");
?>
</div>
