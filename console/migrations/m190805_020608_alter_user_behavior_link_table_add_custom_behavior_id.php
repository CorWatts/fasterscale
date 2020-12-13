<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190805_020608_alter_user_behavior_link_table_add_custom_behavior_id
 */
class m190805_020608_alter_user_behavior_link_table_add_custom_behavior_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // make the behavior_id column NULLable
        // THIS WILL WORK ON POSTGRESQL ONLY :(
        $this->alterColumn("{{%user_behavior_link}}", "behavior_id", "DROP NOT NULL");
        $this->addColumn("{{%user_behavior_link}}", "custom_behavior", Schema::TYPE_STRING);
        // make the behavior_id column NULLable
        // THIS WILL WORK ON POSTGRESQL ONLY :(
        $this->alterColumn('{{%question}}', 'behavior_id', 'DROP NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%user_behavior_link}}", "custom_behavior");
        /*
         * we won't worry about setting the above columns to be NULLable
         * they may have NULL values in them (it can happen with a custom behavior).
         * We would be sad because it would fail
         */
    }
}
