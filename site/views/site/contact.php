<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \site\models\ContactForm $model
 */
$this->title = 'The Faster Scale App | Contact Us';
$this->registerMetaTag([
  'name' => 'description',
  'content' => 'The Faster Scale App is a totally free, online version of Michael Dye\'s relapse awareness scale. Please send us comments, questions, or suggestions here.'
]);
?>
<div class="site-contact">
  <h1>Contact Us</h1>
  
  
  <div class="row">
    <div class="col-md-5 mailing-list-container">
      <h2>Join our mailing list</h2>
      <p>Our mailing list is the primary location for announcements and ongoing discussions.</p>
      <form action="https://www.freelists.org/cgi-bin/subscription.cgi" method="post">
      
      <div class="form-group">
        <label for="email-subscribe">Mailing List Email</label>
        <div class="input-group"><span class="input-group-addon">@</span><input type="email" name="email" id="email-subscribe" class="form-control" required></div>
      </div>
      <div class="form-group">
        <label for="action">Mailing List Action</label>
        <select name="action" id="action" class="form-control">
          <option value="subscribe">Subscribe</option>
          <option value="unsubscribe">Unsubscribe</option>
          <option value="set digest">Turn Digest mode on</option>
          <option value="unset digest">Turn Digest mode off</option>
          <option value="set vacation">Turn Vacation mode on</option>
          <option value="unset vacation">Turn Vacation mode off</option>
          <option value="help">Get Help</option>
        </select>
      </div>

      <input type=hidden name="list" value="fsa-discuss">
      <input type=hidden name="url_or_message" value="Thanks! Be sure to follow the instructions of any confirmation email you receive.">

      <input type=submit value="Subscribe" class="btn btn-success">
      </form>
    </div>

    <div class="col-md-5 col-md-offset-1 well">
      <h2 style="margin-top: 0px;">Send us a message</h2>
      <p>If you'd like to contact us directly, this is the form to use.</p>
      <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'email', ['inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>'])->input('email') ?>
        <?= $form->field($model, 'subject') ?>
        <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

        <?php if(Yii::$app->user->isGuest) {
          // only show captcha if user is not logged in
          print $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-6">{input}</div></div>',
          ]);
        } ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
