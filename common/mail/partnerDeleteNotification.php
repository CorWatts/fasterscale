<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

?>

<p>Hello <?= Html::encode($email) ?>,</p>

<p>This is a notification that <?= Html::encode($user->email) ?> has deleted their account. Thank you for encouraging and supporting them!</p>
