<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use \DateTimeZone;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\SignupForm $model
 */
$this->title = 'Signup';
$timezones = DateTimeZone::listIdentifiers();
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin([
				'id' => 'form-signup',
				'enableClientValidation' => false,
				'enableAjaxValidation' => true
			]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>']); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-md-5">{image}</div><div class="col-md-6 col-md-offset-1">{input}</div></div>',
                ]) ?>
                <?= $form->field($model, 'send_email')->checkbox() ?>
                <div id='email_threshold_fields' <?php if(!$model->send_email) { ?>style="display: none;"<?php } ?>>
                    <?= $form->field($model, 'email_threshold', ['template' => '{label}<div class="row"><div class="col-md-2">{input}</div><div class="col-md-10"><p class="bg-info text-center" style="margin: 5px 0px;">We recommend a score of 60 to start out with</p></div></div>{error}'])->textInput(['class'=>'form-control']) ?>
                    <?= $form->field($model, 'partner_email1')->input('email'); ?>
                    <?= $form->field($model, 'partner_email2')->input('email'); ?>
                    <?= $form->field($model, 'partner_email3')->input('email'); ?>
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
        $('#email_threshold_fields').toggle();
    });"
);
