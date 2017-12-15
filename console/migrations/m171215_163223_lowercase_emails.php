<?php

use yii\db\Migration;

class m171215_163223_lowercase_emails extends Migration
{
    public function safeUp()
    {
      $this->execute('UPDATE "user" SET email=lower(email);');
    }

    public function safeDown()
    {
        echo "You don't want this to be reversed. Continuing...";
    }
}
