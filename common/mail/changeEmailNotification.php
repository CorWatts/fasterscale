<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */
$contactLink = Yii::$app->urlManager->createAbsoluteUrl(['site/contact']);
?>

<p>This is a notification that your email address has been changed from <?= Html::encode($user->email) ?> to <?= Html::encode($user->desired_email) ?>.</p>

<p>If this is in error, please contact us immediately via <?= Html::a(Html::encode($contactLink), $contactLink) ?>.</p>
