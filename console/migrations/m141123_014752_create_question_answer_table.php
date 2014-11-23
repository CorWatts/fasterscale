<?php

use yii\db\Schema;
use yii\db\Migration;

class m141123_014752_create_question_answer_table extends Migration
{
    public function up()
    {
        $this->createTable("question_answer", [
            "id" => Schema::TYPE_PK,
            "question_type" => Schema::TYPE_INTEGER . ' NOT NULL',
            "date" => Schema::TYPE_DATETIME . ' NOT NULL',
    }

    public function down()
    {
        echo "m141123_014752_create_question_answer_table cannot be reverted.\n";

        return false;
    }
}
