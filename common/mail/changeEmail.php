<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

$changeLink = Yii::$app->urlManager->createAbsoluteUrl(['profile/change-email', 'token' => $token]);
?>

<p>Hello <?= Html::encode($current_email) ?>,<br/>
Please click the link below to confirm your email address change to <?= Html::encode($desired_email) ?>:</p>

<p><?= Html::a(Html::encode($changeLink), $changeLink) ?></p>
