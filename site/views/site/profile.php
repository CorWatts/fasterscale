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
    <div class="col-md-6">
			<?php $form = ActiveForm::begin([
				'id' => 'form-profile',
				'enableClientValidation' => true,
				'options' => ['validateOnSubmit' => true]
			]); ?>
        <?= $form->field($profile, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>']); ?>
        <?= $form->field($profile, 'password')->passwordInput() ?>
		    <?= $form->field($profile, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($profile, 'send_email')->checkbox() ?>
        <div id='email_threshold_fields' <?php if(!$profile->send_email) { ?>style="display: none;"<?php } ?>>
          <?= $form->field($profile, 'email_threshold')->textInput(['class'=>'form-control', 'style'=>'width: 50px;'])->input('number', ['min' => 0, 'max' => 1000]) ?>
          <?= $form->field($profile, 'partner_email1')->input('email'); ?>
          <?= $form->field($profile, 'partner_email2')->input('email'); ?>
          <?= $form->field($profile, 'partner_email3')->input('email'); ?>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2 col-xs-2">
      <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'profile-button', 'name' => 'profile-button']) ?>
      </div>
    </div>
  </div>
  <?php ActiveForm::end(); ?>

  <hr />

  <div class="row">
    <div class="col-md-6 col-md-push-6 bg-success">
      <h4>Export Your Check-in Data</h4>
      <p>To export ALL of your check-in data, click this button. You will be redirected to a CSV download that can be opened in any spreadsheet program.</p>
    
      <div class="form-group">
        <?= Html::a('Export', ['/site/export'], ['class'=>'btn btn-success']) ?>
      </div>
    </div>

    <div class="col-md-6 bg-danger col-md-pull-6">
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

