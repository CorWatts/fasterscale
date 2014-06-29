<?php

use yii\db\Schema;

class m140526_184554_alter_option_table_add_category extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%option}}', 'category_id', Schema::TYPE_INTEGER . ' NOT NULL');

        $this->addForeignKey('option_category_fk', '{{%option}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('option_category_fk', '{{%option}}');
        $this->dropColumn('{{%option}}', 'category_id');
    }
}
