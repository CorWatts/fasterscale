<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

?>

<p>Hello <?php echo Html::encode($email) ?>,</p>

<p>This is a notification that <?php echo Html::encode($user->email) ?> has deleted their account. Thank you for using the Faster Scale App.</p>
