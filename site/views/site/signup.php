<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\SignupForm $model
 */
$this->title = 'The Faster Scale App | Signup';
$timezones = DateTimeZone::listIdentifiers();

$this->registerMetaTag([
  'name' => 'description',
  'content' => 'Sign up here for the Faster Scale App, the online version of the popular emotional mindfulness questionnaire. Sign up is easy and always completely free!'
]);
$this->registerJsFile('/js/site/signup.js', ['depends' => [\site\assets\AppAsset::className()]]);
?>
<div class="site-signup">
  <h1>Signup</h1>
  <div class="row">
    <div class="col-md-5">
			<?php $form = ActiveForm::begin([
				'id' => 'form-signup',
				'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'options' => [ 'validateOnSubmit' => true ]
			]); ?>
        <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>'])->input('email') ?>
        <?= $form->field($model, 'password', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn"><button id="password-toggle" class="btn btn-default" type="button">Show</button></span></div>'])->passwordInput() ?>
        <?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
        <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
          'template' => '<div class="row"><div class="col-md-5">{image}</div><div class="col-md-6 col-md-offset-1">{input}</div></div>',
        ]) ?>
        <?= $form->field($model, 'send_email')->checkbox(['disabled'=>true]) ?>
        <div id='email_threshold_fields' <?php if(!$model->send_email) { ?>style="display: none;"<?php } ?>>
          <?= $form->field($model, 'email_threshold', ['template' => '{label}<div class="row"><div class="col-md-3">{input}</div><div class="col-md-9"><p class="bg-info text-center" style="margin: 5px 0px;">We recommend starting with 30</p></div></div>{error}'])->textInput(['class'=>'form-control'])->input('number', ['min' => 0, 'max' => 100]) ?>
          <?= $form->field($model, 'partner_email1')->input('email') ?>
          <?= $form->field($model, 'partner_email2')->input('email') ?>
          <?= $form->field($model, 'partner_email3')->input('email') ?>
        </div>
        <div class="form-group">
          <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>

<?php $this->registerJs(
  "$('#signupform-send_email').click(function() {
    if( $('#signupform-send_email').prop('checked') ) {
      $('#email_threshold_fields').show();
    } else {
      $('#email_threshold_fields').hide();
    }
  });

  $('#password-toggle').click(function () {
    if( $('#signupform-password').attr('type') === 'password' ) {
      $('#password-toggle').text('Hide');
      $('#signupform-password').attr('type', 'text');
    } else {
      $('#password-toggle').text('Show');
      $('#signupform-password').attr('type', 'password');
    }
  });"
);
