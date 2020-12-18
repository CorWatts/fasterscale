<?php
namespace console\controllers;

use \yii\helpers\BaseConsole as Console;
use \common\models\User;

/**
 * Faster Scale App CLI Helpers
 */
class FsaController extends \yii\console\Controller
{
  public $defaultAction = null;

  public $ageThreshold = 7; // in days

  /**
   * Deletes unconfirmed accounts that are older than the lifetime of the
   * verification token
   */
  public function actionRemoveOldUnconfirmedAccounts(): void
  {
    $this->stdout("Removing accounts older than {$this->ageThreshold} days...\n", Console::FG_YELLOW);
    $count = User::deleteAll([
      'and',
        ['or',
          ['not', ['verify_email_token' => null]],
          // we have to escape the '_' at the start of User::CONFIRMED_STRING
          ['not like', 'verify_email_token', '%\\'.User::CONFIRMED_STRING, false]
        ],
        ['<', 'created_at', $this->getTimeThreshold()],
    ]);

    $this->stdout("Removed $count accounts", Console::FG_GREEN);
  }

  /**
   * Returns the cut-off time threshold for unconfirmed users we should delete
   *
   * @return integer
   */
  public function getTimeThreshold(): int
  {
        $expire = \Yii::$app->params['user.verifyAccountTokenExpire'];
        return time() - $expire;
  }

}
