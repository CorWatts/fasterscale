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
$this->title = 'Profile';
$timezones = DateTimeZone::listIdentifiers();
?>
<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Edit your account information below:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>']); ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'profile-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
