<?php

use yii\db\Schema;

class m140526_182444_create_option_link_table extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_option_link}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'option_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => Schema::TYPE_DATETIME . ' NOT NULL'
        ], $tableOptions);

        $this->addForeignKey('option_link_user_fk', '{{%user_option_link}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('option_link_option_fk', '{{%user_option_link}}', 'user_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('option_link_user_fk', '{{%user_option_link}}');
        $this->dropForeignKey('option_link_option_fk', '{{%user_option_link}}');
        $this->dropTable('{{%user_option_link}}');
    }
}
