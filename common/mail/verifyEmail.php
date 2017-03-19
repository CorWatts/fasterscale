<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verify_email_token]);
?>

Hello <?= Html::encode($user->username) ?>,

Please follow the link below to verify your account:

<?= Html::a(Html::encode($verifyLink), $verifyLink) ?>

You will be unable to sign in and do your first check-in until you do so.
