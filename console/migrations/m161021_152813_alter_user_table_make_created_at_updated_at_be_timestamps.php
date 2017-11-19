<?php

use yii\db\Migration;

class m161021_152813_alter_user_table_make_created_at_updated_at_be_timestamps extends Migration
{
    public function safeUp()
    {
      // rename these columns to be consistent
      $this->renameColumn('{{%user_option_link}}', 'date', 'created_at');
      $this->renameColumn('{{%question}}', 'date', 'created_at');

      // add a timezone to these timestamp columns
      $this->execute("SET LOCAL timezone='UTC';");
      $this->alterColumn('{{%user_option_link}}', 'created_at', 'TIMESTAMP WITH TIMEZONE');
      $this->alterColumn('{{%question}}', 'created_at', 'TIMESTAMP WITH TIMEZONE');

      $this->alterColumn('{{%user}}', 'created_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + created_at * interval '1 second';");
      $this->alterColumn('{{%user}}', 'updated_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + updated_at * interval '1 second';");
    }

    public function safeDown()
    {
      echo "This doesn't work right now... :(\n";
      return false;

      // somehow reverse these
      // select extract(epoch from created_at)::integer as created_at from "user";
      //$this->alterColumn('{{%user}}', 'created_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + created_at * interval '1 second';");
      //$this->alterColumn('{{%user}}', 'updated_at', "TIMESTAMP WITH TIME ZONE USING TIMESTAMP WITH TIME ZONE 'epoch' + updated_at * interval '1 second';");

      // add a timezone to these timestamp columns
      $this->execute("SET LOCAL timezone='UTC';");
      $this->alterColumn('{{%user_option_link}}', 'created_at', 'TIMESTAMP WITHOUT TIMEZONE');
      $this->alterColumn('{{%question}}', 'created_at', 'TIMESTAMP WITHOUT TIMEZONE');
      
      // rename these columns to be consistent
      $this->renameColumn('{{%user_option_link}}', 'created_at', 'date');
      $this->renameColumn('{{%question}}', 'created_at', 'date');
    }
}
