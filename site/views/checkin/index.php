<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\helpers\ArrayHelper as AH;

/**
 * @var yii\web\View $this
 */

$this->title = "The Unofficial Faster Scale App | Check-in";

function checkboxItemTemplate($index, $label, $name, $checked, $value)
{
    return Html::checkbox(
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
<p>Click all the behaviors below that apply to your current emotional state. Once finished, click the submit button at the bottom.</p>

<?php
$form = ActiveForm::begin([
  'id' => 'checkin-form',
  'options' => ['class' => 'form-horizontal'],
]);

foreach ($categories as $category) {
    $behaviors = AH::map($behaviorsList[$category['id']], 'id', 'name');
    $custom = [];
    if (array_key_exists($category['id'], $customList)) {
        $custom = AH::map($customList[$category['id']], function ($cbhvr) {
            return $cbhvr['id'] . '-custom';
        }, 'name');
    }
    print $form
          ->field($model, "behaviors{$category['id']}")
          ->checkboxList(
              $behaviors + $custom,
              ['data-toggle' => 'buttons', 'item' => "checkboxItemTemplate"]
          );
}
print Html::submitButton('Submit', ['class' => 'btn btn-success']);
ActiveForm::end();
?>
