<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$this->title = "The Faster Scale App | $name";
$code = $exception->statusCode;

if($code === 404):
  $this->title = "The Faster Scale App | Page Not Found";
?>

  <h1>Hmm, that's not quite right.</h1>
  <div class="alert alert-danger"><?= nl2br(Html::encode($name)) ?></div>
  <p>We've got no current secrets and we're being open....we honestly have no idea what page you're trying to find. If you landed on this page from a link on The Faster Scale App please send us a message on our <a href="<?=Url::to(['site/contact'])?>">contact form</a>.</p>
  <p>Otherwise, please ensure your attempted url is correct.</p>

<?php
elseif($code >= 500 && $code < 600):
  $this->title = "The Faster Scale App | Oops";
?>
  <h1>It's not you. It's us.</h1>
  <div class="alert alert-danger"><?= nl2br(Html::encode($name)) ?></div>
  <p>Well this is embarrassing...we're having an error on our side. So sorry for the annoyance. If you'd like to help, please send us a message on our <a href='<?=Url::to(['site/contact'])?>'>contact form</a>.</p>

<?php else: ?>

<div class="site-error">
  <h1><?= Html::encode($name) ?></h1>
  <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
  <p>The above error occurred while the Web server was processing your request.</p>
  <p>Please contact us if you think this is a server error. Thank you.</p>
</div>
<?php endif; ?> 
