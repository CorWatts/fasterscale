<?php

use yii\db\Migration;

/**
 * Class m210316_010628_add_indexes_to_foreign_key_refs_for_faster_cleanup
 */
class m210316_010628_add_indexes_to_foreign_key_refs_for_faster_cleanup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('user_behavior_link_user_id', '{{%user_behavior_link}}', 'user_id');
        $this->createIndex('question_user_behavior_id', '{{%question}}', 'user_behavior_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('user_behavior_link_user_id', '{{%user_behavior_link}}');
        $this->dropIndex('question_user_behavior_id', '{{%question}}');
    }
}
