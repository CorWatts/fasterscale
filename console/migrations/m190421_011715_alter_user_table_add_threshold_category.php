<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190421_011715_alter_user_table_add_threshold_category
 */
class m190421_011715_alter_user_table_add_threshold_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'email_category', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 4'); // defaults to "Speeding Up" behavior category
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'email_category');
    }
}
