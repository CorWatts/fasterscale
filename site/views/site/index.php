<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = 'The Unofficial Faster Scale App | Welcome';
$this->registerMetaTag([
  'name' => 'description',
  'content' => 'A totally free, online version of the Faster Scale. Increase your self-awareness and practice emotional mindfulness by signing up or logging in today.'
]);
?>
<div class="site-index">
  <div class="jumbotron">
    <h1>The<br />Faster Scale App</h1>
    <p class="lead">Become aware of your emotional state and see temptation coming before it arrives.</p>
  </div>

  <div class="body-content">
    <div class="row block">
      <div class="col-md-12">
        <p>Relapse is not an event that suddenly occurs. By using Michael Dye's "The Faster Scale", a person can train themself to notice their pattern of biological, psychological, and social changes that lead to relapse.</p>
      </div>
    </div>
    <div class="row block">
      <div class="col-md-12">
        <p>Once identified, evasive action can be taken. This website is an online version of The Faster Scale.</p>
      </div>
    </div>
    <div class="row block">
      <div class="col-md-12">
        <p>Log the emotions and behaviors you are exhibiting in order to see how vulnerable to temptation you are in the moment. Do a check-in every day to see how your mental state changes over time.</p>
        <p>More questions? Take a look at our <?=Html::a("FAQ", Url::to(['site/faq']))?>.</p>
        <p>Sound interesting? <?=Html::a("Create an account", Url::to(['site/signup']))?> and do your first check-in.</p>
      </div>
    </div>
  </div>
</div>
