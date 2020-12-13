<?php

use yii\db\Schema;
use yii\db\Migration;

class m141208_045658_create_question_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable("question", [
            "id" => Schema::TYPE_PK,
            "user_id" => Schema::TYPE_INTEGER . " NOT NULL",
            "option_id" => Schema::TYPE_INTEGER . " NOT NULL",
            "user_option_id" => Schema::TYPE_INTEGER . " NOT NULL",
            "question" => Schema::TYPE_INTEGER . " NOT NULL",
            "answer" => Schema::TYPE_TEXT . " NOT NULL",
            "date" => Schema::TYPE_DATETIME . " NOT NULL"
        ], $tableOptions);

        $this->addForeignKey('question_user_fk', '{{%question}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('question_option_fk', '{{%question}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('question_user_option_fk', '{{%question}}', 'user_option_id', '{{%user_option_link}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey("question_user_fk", "{{%question}}");
        $this->dropForeignKey("question_option_fk", "{{%question}}");
        $this->dropForeignKey("question_user_option_fk", "{{%question}}");
        $this->dropTable("question");
    }
}
