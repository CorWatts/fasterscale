<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = 'The Unofficial Faster Scale App | Updates';
$this->registerMetaTag([
  'name' => 'description',
  'content' => 'A totally free, online version of the Faster Scale. Increase your self-awareness and practice emotional mindfulness by signing up or logging in today.'
]);
?>
<div class="site-blog">
  <div class="row">
    <div class="col-md-12 blog">
      <h2>Updates</h2>
      <?=$this->render('/partials/posts', ['posts' => $posts])?>
    </div>
  </div>
</div>