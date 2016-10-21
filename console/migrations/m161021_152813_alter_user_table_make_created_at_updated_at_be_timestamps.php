<?php

use yii\db\Migration;

class m161021_152813_alter_user_table_make_created_at_updated_at_be_timestamps extends Migration
{
    public function safeUp()
    {
      $this->alterColumn('{{%user}}', 'created_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + created_at * interval '1 second';");
      $this->alterColumn('{{%user}}', 'updated_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + updated_at * interval '1 second';");
    }

    public function safeDown()
    {
      echo "This doesn't work right now... :(\n";
      return false;
      //$this->alterColumn("{{%user}}", "created_at", "INTEGER NOT NULL");
      //$this->alterColumn("{{%user}}", "updated_at", "INTEGER NOT NULL");
    }
}
