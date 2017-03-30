<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verify_email_token]);
?>

<p>Hello <?= Html::encode($user->email) ?>,<br/>
Please follow the link below to verify your account:</p>

<p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>

<p>You will be unable to sign in and do your first check-in until you do so.</p>
