<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use site\widgets\Alert;
use common\components\Utility;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
site\assets\AppAsset::register($this);

if($hash = Utility::getRevHash()) {
  $rev_link = '<a href="'.Utility::getGithubRevUrl().'">'.Utility::getRevHash().'</a>';
} else $rev_link = 'DEVELOPMENT';

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'The Faster Scale App',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-fixed-top navbar-expand-lg',
                ],
            ]);
            if (Yii::$app->user->isGuest) {
              $menuItems[] = ['label' => 'About', 'url' => ['/site/about']];
              $menuItems[] = ['label' => 'News', 'url' => ['/site/blog']];
              $menuItems[] = ['label' => 'Contact', 'url' => ['/site/contact']];
              $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
              $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
              $menuItems[] = ['label' => 'Check-in', 'url' => ['/checkin/index']];
              $menuItems[] = ['label' => 'Past Check-ins', 'url' => ['/checkin/view']];
              $menuItems[] = ['label' => 'Statistics', 'url' => ['/checkin/report']];
              $menuItems[] = ['label' => Yii::$app->user->identity->email, 'url' => ['/profile/index']];
              $menuItems[] = [
                'label' => 'Logout',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
              ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-8">
              <?= Alert::widget() ?>
              <?= $content ?>
            </div>
          </div>
        </div>
    </div>

    <footer class="footer">
      <div class="footer-main">
        <div class="container">
          <div class="row">
            <div class="col-sm-offset-2 col-sm-10 col-xs-12">
              <div class="row">
                <div class="col-xs-3 col-md-4 footer-info">
                  <ul class="list-unstyled">
                    <h4>Info</h4>
                    <li><a href="<?=Url::to(['site/blog'])?>">News</a>
                    <li><a href="<?=Url::to(['site/about'])?>">About</a>
                    <li><a href="<?=Url::to(['site/faq'])?>">FAQ</a>
                  </ul>
                </div>
                <div class="col-xs-3 col-md-4 footer-docs">
                  <h4>Legal</h4>
                  <ul class="list-unstyled">
                    <li><a href="<?=Url::to(['site/privacy'])?>">Privacy</a></li>
                    <li><a href="<?=Url::to(['site/terms'])?>">Terms</a></li>
                  </ul>
                </div>
                <div class="col-xs-6 col-md-4 footer-info">
                  <h4>Connect</h4>
                  <ul class="list-unstyled">
                    <li><a href="https://github.com/CorWatts/fasterscale"><img src="/img/GitHub-Mark-32px.png" height=24 width=24 /></a> rev. <?=$rev_link?></li>
                    <li><a href="<?=Url::to(['site/contact'])?>">Contact</a>
                    <li><a href="https://www.freelists.org/list/fsa-discuss">Mailing List</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-legal">
        <div class="container">
          <div class="row">
            <div class="col-sm-offset-2 col-sm-10 col-xs-12">
              <div class="row">
                <div class="col-xs-12">
                  <p class="pull-left">&copy; <a href="https://corwatts.com">Corey Watts</a> <?= date('Y') ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
