<?php

use yii\db\Schema;

class m140526_184537_create_category_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'weight' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->batchInsert('{{%category}}', ['name', 'weight'], [
            ['Restoration', 0],
            ['Forgetting Priorities', 1],
            ['Anxiety', 2],
            ['Speeding Up', 4],
            ['Ticked Off', 6],
            ['Exhausted', 8],
            ['Relapse/Moral Failure', 10]
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
