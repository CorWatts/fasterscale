<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\Category;

$this->title = 'Profile';
$timezones = \DateTimeZone::listIdentifiers();
?>
<div class="profile-index">
  <h1><?= Html::encode($this->title) ?></h1>

  <p>Update your account information below</p>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
      <div class="card-header"><h4>Change email</h4></div>
      <div class="card-block">
        <div class="card-text">
          <?php $form = ActiveForm::begin([
            'id' => 'form-change-email',
            'action' => ['profile/request-change-email'],
            'method' => 'post',
            'enableClientValidation' => true,
            'options' => ['validateOnSubmit' => true]
          ]); ?>
          <?= $form->field($change_email, 'desired_email') ?>
          <div class="form-group">
          <?= Html::submitButton('Change', ['class' => 'btn btn-warning', 'name' => 'change-email-button']) ?>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
      <div class="card-header"><h4>Change password</h4></div>
      <div class="card-block">
        <div class="card-text">
          <?php $form = ActiveForm::begin([
            'id' => 'form-change-password',
            'action' => ['profile/change-password'],
            'method' => 'post',
            'enableClientValidation' => true,
            'options' => ['validateOnSubmit' => true]
          ]); ?>
          <?= $form->field($change_password, 'old_password')->passwordInput() ?>
          <?= $form->field($change_password, 'new_password', ['inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn"><button id="new-password-toggle" class="btn btn-default" type="button">Show</button></span></div>'])->passwordInput() ?>
          <div class="form-group">
          <?= Html::submitButton('Change', ['class' => 'btn btn-warning', 'name' => 'change-password-button']) ?>
          </div>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><h4>Update profile</h4></div>
        <div class="card-block">
          <div class="card-text">
<?php $form = ActiveForm::begin([
  'id' => 'form-profile',
  'enableClientValidation' => true,
  'options' => ['validateOnSubmit' => true]
]); ?>
            <?= $form->field($profile, 'timezone')->dropDownList(array_combine($timezones, $timezones)); ?>
            <?= $form->field($profile, 'expose_graph')->checkbox() ?>
            <?php if($profile->expose_graph): ?>
            <div class='alert alert-success behaviors-graph-info'>Your behaviors graph can be found at:<br /> <a id="behaviors-graph-link" target="_blank" href="<?=$graph_url?>"><?=$graph_url?></a></div>
            <?php endif; ?>
            <?= $form->field($profile, 'send_email')->checkbox() ?>
            <div id='send_email_fields' <?php if(!$profile->send_email) { ?>style="display: none;"<?php } ?>>
              <?= $form->field($profile, 'email_category')->dropdownList(Category::getCategories(), ['data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-delay' => '{"show": 500, "hide": 100}', 'title' => 'Want to send an email with every check-in? Try setting this to "Restoration"']) ?>
              <?= $form->field($profile, 'partner_email1')->input('email'); ?>
              <?= $form->field($profile, 'partner_email2')->input('email'); ?>
              <?= $form->field($profile, 'partner_email3')->input('email'); ?>
            </div>
          <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'id' => 'profile-button', 'name' => 'profile-button']) ?>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
          <div class="card-header"><h4>Export Check-in Data</h4></div>
        <div class="card-block">
          <div class="card-text">
            <p>To export ALL of your check-in data, click this button. You will be redirected to a CSV download that can be opened in any spreadsheet program.</p>
    
            <div class="form-group">
              <?= Html::a('Export', ['/profile/export'], ['class'=>'btn btn-success']) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><h4>Delete account</h4></div>
        <div class="card-block">
          <div class="card-text">
            <p>If you're really <em>really</em> sure about this, enter your password below and click the Delete button. You will be logged out, your account and all your stored data will be immediately deleted, and a notification email will be sent to you and your partners.</p>
        <?php $form = ActiveForm::begin([
          'id' => 'form-delete-account',
          'action' => ['profile/delete-account'],
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
    </div>
  </div>

</div>

<?php $this->registerJs(
"$(function () {
  $('#new-password-toggle').click(function () {
    if( $('#changepasswordform-new_password').attr('type') === 'password' ) {
      $('#new-password-toggle').text('Hide');
      $('#changepasswordform-new_password').attr('type', 'text');
    } else {
      $('#new-password-toggle').text('Show');
      $('#changepasswordform-new_password').attr('type', 'password');
    }
  });

  $('#editprofileform-send_email').click(function() {
    $('#send_email_fields').toggle();
  });

  $('[\data-toggle=\"tooltip\"]').tooltip();
})"
);

