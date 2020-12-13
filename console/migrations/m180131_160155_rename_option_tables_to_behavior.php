<?php

use yii\db\Migration;

/**
 * Class m180131_160155_rename_option_tables_to_behavior
 */
class m180131_160155_rename_option_tables_to_behavior extends Migration
{
    public function safeUp()
    {
        $this->rename('option', 'behavior');
    }

    public function safeDown()
    {
        $this->rename('behavior', 'option');
    }

    private function rename(string $from, string $to)
    {
        $this->dropForeignKey("question_user_{$from}_fk", "{{%question}}");
        $this->dropForeignKey("{$from}_link_user_fk", "{{%user_{$from}_link}}");
        $this->dropPrimaryKey("user_{$from}_link_pkey", "{{%user_{$from}_link}}");

        $this->renameColumn("question", "{$from}_id", "{$to}_id");
        $this->renameColumn("question", "user_{$from}_id", "user_{$to}_id");
        $this->renameColumn("user_{$from}_link", "{$from}_id", "{$to}_id");

        $f = "user_{$from}_link_id_seq";
        $t = "user_{$to}_link_id_seq";
        $this->execute("ALTER SEQUENCE {$f} RENAME TO {$t};");

        $this->renameTable("user_{$from}_link", "user_{$to}_link");


        $this->addPrimaryKey("user_{$to}_link_pkey", "{{%user_{$to}_link}}", 'id');
        $this->addForeignKey("question_user_{$to}_fk", '{{%question}}', "user_{$to}_id", "{{%user_{$to}_link}}", 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey("{$to}_link_user_fk", "{{%user_{$to}_link}}", 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }
}
