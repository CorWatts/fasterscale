<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\ResetPasswordForm $model
 */
$this->title = 'The Unofficial Faster Scale App | Reset Password';
?>
<div class="site-reset-password">
  <h1><?= Html::encode($this->title) ?></h1>
  <p>Please choose your new password:</p>

  <div class="row">
    <div class="col-md-5">
      <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <div class="form-group">
          <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
