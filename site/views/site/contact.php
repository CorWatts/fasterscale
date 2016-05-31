<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\ContactForm $model
 */
$this->title = 'The Faster Scale App | Contact Us';
$this->registerMetaTag([
  'name' => 'description',
  'content' => 'The Faster Scale App is a totally free, online version of Michael Dye\'s relapse awareness scale. Please send us comments, questions, or suggestions here.'
]);
?>
<div class="site-contact">
  <h1>Contact Us</h1>
  
  <p>Thanks for using this tool! If you have any comments, questions, or feedback please drop us a message using this form. Thanks!</p>
  
  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>'])->input('email') ?>
        <?= $form->field($model, 'subject') ?>
        <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

        <?php if(Yii::$app->user->isGuest) {
          // only show captcha if user is not logged in
          print $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
          ]);
        } ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
