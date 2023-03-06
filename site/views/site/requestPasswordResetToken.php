<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\PasswordResetRequestForm $model
 */
$this->title = 'The Unofficial Faster Scale App | Request Password Reset';
?>
<div class="site-request-password-reset">
  <h1><?= Html::encode($this->title) ?></h1>
  <p>Please fill out your email. A link to reset password will be sent there.</p>

  <div class="row">
    <div class="col-md-5">
      <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email') ?>
        <div class="form-group">
          <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
