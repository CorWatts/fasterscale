<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-md-5">{image}</div><div class="col-md-6 col-md-offset-1">{input}</div></div>',
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
