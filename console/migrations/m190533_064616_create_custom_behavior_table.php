<?php

use yii\db\Migration;

/**
 * Handles the creation of table `custom_behavior`.
 */
class m190533_064616_create_custom_behavior_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('custom_behavior', [
            'id' => $this->primaryKey(),
            "user_id" => $this->integer() . " NOT NULL",
            "category_id" => $this->integer() . " NOT NULL",
            "name" => $this->string(255) . " NOT NULL",
            "created_at" => $this->integer() . " NOT NULL",
            "updated_at" => $this->integer() . " NOT NULL"
        ]);

        $this->addForeignKey('custom_behavior_user_fk', '{{%custom_behavior}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey("custom_behavior_user_fk", "{{%custom_behavior}}");
        $this->dropTable('custom_behavior');
    }
}
