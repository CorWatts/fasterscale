<?php

use yii\db\Schema;

class m140526_181732_create_option_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_TEXT . ' NOT NULL',
            'weight' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%option}}');
    }
}
