<?php

use yii\db\Schema;
use yii\db\Migration;

class m160403_060352_delete_incorrect_behaviors extends Migration
{
    public function safeUp()
    {
        $this->delete("{{%option}}", "name='pornography' OR name='masturbation'");
    }

    public function safeDown()
    {
        $this->batchInsert("{{%option}}", ["name", "category_id"], [
            ["masturbation", 3]
            , ["pornography", 3]
        ]);
    }
}
