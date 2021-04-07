<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

$changeLink = Yii::$app->urlManager->createAbsoluteUrl(['profile/change-email', 'token' => $token]);
?>

<p>Hello <?php echo Html::encode($current_email) ?>,<br/>
Please click the link below to confirm your email address change to <?php echo Html::encode($desired_email) ?>:</p>

<p><?php echo Html::a(Html::encode($changeLink), $changeLink) ?></p>
