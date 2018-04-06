<?php

use yii\db\Migration;

/**
 * Class m180406_031748_add_three_fields_for_change_email_verification
 */
class m180406_031748_add_three_fields_for_change_email_verification extends Migration {
  public function safeUp() {
    $this->addColumn('{{%user}}', 'desired_email', $this->string());
    $this->addColumn('{{%user}}', 'change_email_token', $this->string());
  }

  public function safeDown() {
    $this->dropColumn('{{%user}}', 'desired_email');
    $this->dropColumn('{{%user}}', 'change_email_token');
  }
}
