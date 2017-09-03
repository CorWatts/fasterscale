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
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = ['label' => 'Check-in', 'url' => ['/checkin/index']];
                $menuItems[] = ['label' => 'Previous Check-ins', 'url' => ['/checkin/view']];
                $menuItems[] = ['label' => 'Statistics', 'url' => ['/checkin/report']];
                $menuItems[] = ['label' => Yii::$app->user->identity->email, 'url' => ['/site/profile']];
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
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <p class="pull-left">&copy; <a href="https://corwatts.com">Corey Watts</a> <?= date('Y') ?> | <a href="<?=Url::to(['site/privacy'])?>">Privacy</a> | <a href="<?=Url::to(['site/terms'])?>">Terms</a></p>
            </div>
            <div class="col-md-6">
              <p class="pull-right">FSA rev. <?=$rev_link?> is powered by <a href="http://yiiframework.com">Yii</a>, written in <a href="http://www.vim.org">Vim</a></p>
            </div>
          </div>
        </div>
    </footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
