<?php

namespace console\controllers;

use yii\helpers\BaseConsole as Console;
use common\models\User;

/**
 * Faster Scale App CLI Helpers
 */
class FsaController extends \yii\console\Controller
{
    public $defaultAction = null;

  /**
   * Deletes unconfirmed accounts that are older than the lifetime of the email verification token.
   */
    public function actionRemoveOldUnconfirmedAccounts(): void
    {
        $thresholdInDays = \Yii::$app->params['user.verifyAccountTokenExpire'] / (60 * 60 * 24); // param is in seconds
        $this->stdout("Removing accounts older than {$thresholdInDays} days...\n", Console::FG_YELLOW);
        $count = User::deleteAll([
        'and',
        ['and',
          ['not', ['verify_email_token' => null]],
          // Yes, I know we have a wildcard at the start of this LIKE clause.
          // The user table shouldn't be nearly as big as question or
          // user_behavior_Like, so a sequential table scan shouldn't take
          // _that_ long. I wrote an alternative clause (commented out below)
          // using text positions but it doesn't appear to be faster according
          // to EXPLAIN ANALYZE.
          //
          // we have to escape the '_' at the start of User::CONFIRMED_STRING
          ['not like', 'verify_email_token', '%\\' . User::CONFIRMED_STRING, false]
          //"position ('".User::CONFIRMED_STRING."' in verify_email_token) > 0"
        ],
        // elsewhere we check the expiration of the token itself. For this query
        // it's simpler to just check the created_at time. The timestamp here
        // should be the same as the timestamp in the token.
        ['<', 'created_at', $this->getTimeThreshold()],
        ]);

        $this->stdout("Removed $count accounts\n", Console::FG_GREEN);
    }

  /**
   * Returns the cut-off time threshold for unconfirmed users we should delete
   *
   * The param is the lifetime of the token. When the token is created its lifetime
   * would be time() + verifyAcccounTokenExpire. To check if the token has expired
   * we have a bit of a shortcut in the query above. This function returns a
   * timestamp that demarcates users with created_at values we should delete or
   * keep.
   *
   * @return integer
   */
    public function getTimeThreshold(): int
    {
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];
        return time() - $expire;
    }
}
