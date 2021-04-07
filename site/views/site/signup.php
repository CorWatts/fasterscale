<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\Category;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\SignupForm $model
 */
$this->title = 'The Faster Scale App | Sign up';
$timezones = DateTimeZone::listIdentifiers();

$this->registerMetaTag([
  'name' => 'description',
  'content' => 'Sign up here for the Faster Scale App, the online version of the popular emotional mindfulness questionnaire. Sign up is easy and completely free.'
]);
$this->registerJsFile('/js/site/signup.js', ['depends' => [\site\assets\AppAsset::class]]);
?>
<div class="site-signup">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1>Sign up</h1>
      <p>To do a check-in you need an account. But don't worry -- <?= Html::a("we won't do anything with your data", ['site/faq']) ?>.</p>
            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'options' => [ 'validateOnSubmit' => true ]
            ]); ?>
        <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>'])->input('email') ?>
        <?= $form->field($model, 'password', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn"><button id="password-toggle" class="btn btn-default" type="button">Show</button></span></div>'])->passwordInput() ?>
        <?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
        <?= $form->field($model, 'captcha')->widget(Captcha::class, [
          'template' => '<div class="row"><div class="col-md-5">{image}</div><div class="col-md-6 col-md-offset-1">{input}</div></div>',
        ]) ?>
        <?= $form->field($model, 'send_email')->checkbox(['disabled' => true]) ?>
        <div id='send_email_fields' <?php if (!$model->send_email) {
            ?>style="display: none;"<?php
                                    } ?>>
          <?= $form->field($model, 'email_category')->dropdownList(Category::getCategories(), ['data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-delay' => '{"show": 500, "hide": 100}', 'title' => 'Want to send an email with every check-in? Try setting this to "Restoration"']) ?>
          <?= $form->field($model, 'partner_email1')->input('email') ?>
          <?= $form->field($model, 'partner_email2')->input('email') ?>
          <?= $form->field($model, 'partner_email3')->input('email') ?>
        </div>
        <div class="form-group">
          <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
      <p>If the email doesn't arrive quickly check your spam folder.</p>
    </div>
  </div>
</div>
