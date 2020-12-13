<?php

use yii\db\Migration;
use yii\db\Schema;

class m170125_054010_drop_category_and_option_tables extends Migration
{
    public function safeUp()
    {
        // be sure to drop the foreign keys first
        $this->dropForeignKey('option_link_option_fk', '{{%user_option_link}}');
        $this->dropForeignKey('option_category_fk', '{{%option}}');
        $this->dropForeignKey("question_option_fk", "{{%question}}");

        $this->dropTable('{{%option}}');
        $this->dropTable('{{%category}}');
    }

    public function safeDown()
    {
        $this->createTables();
        $this->populateTables();
        $this->addForeignKeys();
    }

    private function createTables()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%option}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_TEXT . ' NOT NULL',
        'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'weight' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    private function populateTables()
    {
        $this->batchInsert("{{%option}}", ["id", "name", "category_id"], array_map('array_values', \common\models\Option::$options));
        $this->batchInsert('{{%category}}', ["id", 'name', 'weight'], array_map('array_values', \common\models\Category::$categories));
    }

    private function addForeignKeys()
    {
        $this->addForeignKey('option_link_option_fk', '{{%user_option_link}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('option_category_fk', '{{%option}}', 'category_id', '{{%category}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('question_option_fk', '{{%question}}', 'option_id', '{{%option}}', 'id', 'CASCADE', 'CASCADE');
    }
}
