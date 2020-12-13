<?php

namespace common\components;

use Yii;

class AccessControl extends \yii\filters\AccessControl
{
    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, they are redirected elsewhere
     * @param User $user the current user
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            // checkin/index is the route that users are redirected to
            Yii::$app->getResponse()->redirect(['checkin/index'])->send();
            return;
        }
    }
}
