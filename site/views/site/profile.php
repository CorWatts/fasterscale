<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Profile';
$timezones = \DateTimeZone::listIdentifiers();
?>
<div class="site-profile">
  <h1><?= Html::encode($this->title) ?></h1>

  <p>Edit your account information below:</p>

  <div class="row">
    <div class="col-md-5">
			<?php $form = ActiveForm::begin([
				'id' => 'form-profile',
				'enableClientValidation' => true,
				'options' => ['validateOnSubmit' => true]
			]); ?>
        <?= $form->field($profile, 'username') ?>
        <?= $form->field($profile, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>']); ?>
        <?= $form->field($profile, 'password')->passwordInput() ?>
		    <?= $form->field($profile, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
        <?= $form->field($profile, 'send_email')->checkbox() ?>
        <div id='email_threshold_fields' <?php if(!$profile->send_email) { ?>style="display: none;"<?php } ?>>
          <?= $form->field($profile, 'email_threshold')->textInput(['class'=>'form-control', 'style'=>'width: 50px;']) ?>
          <?= $form->field($profile, 'partner_email1')->input('email'); ?>
          <?= $form->field($profile, 'partner_email2')->input('email'); ?>
          <?= $form->field($profile, 'partner_email3')->input('email'); ?>
        </div>
        <div class="form-group">
          <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'profile-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>

  <div class="row">
  <div class="col-md-5">
    <h4>Delete your account?</h4>
    <p>Aww shucks, we're sorry to see you go! If you're really sure about this, please enter your password below and click the Delete button. Your account and all your stored data will be deleted, and a notification email will be sent to you and your partners.</p>
    <?php $form = ActiveForm::begin([
    'id' => 'form-delete-account',
    'action' => ['site/delete-account'],
    'method' => 'post',
    'enableClientValidation' => true,
    'options' => ['validateOnSubmit' => true]
    ]); ?>
    <?= $form->field($delete, 'password')->passwordInput() ?>
    <div class="form-group">
    <?= Html::submitButton('Delete', ['class' => 'btn btn-danger', 'name' => 'delete-account-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
  </div>
</div>

<?php $this->registerJs(
  "$('#editprofileform-send_email').click(function() {
    $('#email_threshold_fields').toggle();
  });"
);

