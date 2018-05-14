<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m180514_045605_move_email_threshold_to_send_email
 */
class m180514_045605_move_email_threshold_to_send_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('{{%user}}', 'send_email', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
      $this->update('{{%user}}', ['send_email' => true], 'emaiL_threshold IS NOT NULL AND partner_email1 IS NOT NULL');
      $this->dropColumn('{{%user}}', 'email_threshold');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      // --------
      // For development
      // $this->addColumn("user", "email_threshold", "INT");
      // $this->dropColumn('{{%user}}', 'send_email');
      // ---------
      
      echo "m180514_045605_move_email_threshold_to_send_email cannot be reverted.\n";
      echo "We can't figure out the user's previously selected email_threshold value.";

      return false;
    }
}
