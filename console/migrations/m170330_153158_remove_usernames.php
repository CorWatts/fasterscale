<?php

use yii\db\Migration;

class m170330_153158_remove_usernames extends Migration
{
    public function up()
    {
        $this->dropColumn("{{%user}}", "username");
    }

    public function down()
    {
        echo "m170330_153158_remove_usernames cannot be reverted.\n";
        echo "Usernames are all gone.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
