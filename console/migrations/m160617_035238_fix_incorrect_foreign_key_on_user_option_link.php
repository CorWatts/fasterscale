<?php

use yii\db\Migration;

class m160617_035238_fix_incorrect_foreign_key_on_user_option_link extends Migration
{
  public function safeUp()
  {
    $this->dropForeignKey('option_link_option_fk', '{{%user_option_link}}');
    $this->delete('{{%user_option_link}}', 'option_id = 39 OR option_id = 40');
    $this->addForeignKey('option_link_option_fk', '{{%user_option_link}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
  }

  public function safeDown()
  {
    echo "m160617_035238_fix_incorrect_foreign_key_on_user_option_link cannot be reverted and THAT'S OKAY.\n";
    return true;
  }
}
