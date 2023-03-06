<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 */

$this->title = "The Unofficial Faster Scale App | Check-in | Questions";

function radioItemTemplate($index, $label, $name, $checked, $value)
{
    return Html::radio(
        $name,
        $checked,
        ['value' => $value,
     'label' => $label,
     'container' => false,
     'labelOptions' => [ 'class' => $checked
                                     ? 'btn btn-info active'
                                     : 'btn btn-info'],
    ]
    );
}

$categories = array_intersect_key(
    \common\models\Category::getCategories(),
    array_flip(array_keys($behaviors))
);

?>
<h1>Check-in Questions</h1>
<p>Answer the questions below to compete your check-in.</p>
<p>In each category, select one behavior. Then answer the related questions.</p>
<?php
$form = ActiveForm::begin([
  'id' => 'checkin-form',
]);

foreach ($categories as $category_id => $category_name) {
    print $form->field($model, "user_behavior_id{$category_id}")->radioList($behaviors[$category_id], ['class' => "btn-group", 'data-toggle' => 'buttons', 'item'=>"radioItemTemplate"]);
    print $form->field($model, "answer_{$category_id}a")->textarea()->label("How does it affect me? How do I act and feel?");
    print $form->field($model, "answer_{$category_id}b")->textarea()->label("How does it affect the important people in my life?");
    print $form->field($model, "answer_{$category_id}c")->textarea()->label("Why do I do this? What is the benefit for me?");
}

print Html::submitButton('Submit', ['class' => 'btn btn-success']);
ActiveForm::end();
?>
