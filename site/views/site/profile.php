<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\SignupForm $model
 */
$this->title = 'Profile';
$timezones = \DateTimeZone::listIdentifiers();
?>
<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Edit your account information below:</p>

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin([
				'id' => 'form-profile',
				'enableClientValidation' => true,
				'options' => ['validateOnSubmit' => true]
			]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>']); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
		        <?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
                <?= $form->field($model, 'send_email')->checkbox() ?>
                <div id='email_threshold_fields' <?php if(!$model->send_email) { ?>style="display: none;"<?php } ?>>
                    <?= $form->field($model, 'email_threshold')->textInput(['class'=>'form-control', 'style'=>'width: 50px;']) ?>
                    <?= $form->field($model, 'partner_email1')->input('email'); ?>
                    <?= $form->field($model, 'partner_email2')->input('email'); ?>
                    <?= $form->field($model, 'partner_email3')->input('email'); ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'profile-button']) ?>
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

