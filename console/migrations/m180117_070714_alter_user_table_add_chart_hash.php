<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180117_070714_alter_user_table_add_chart_hash
 */
class m180117_070714_alter_user_table_add_chart_hash extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%user}}", "expose_graph", Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
    }

    public function safeDown()
    {
        $this->dropColumn("{{%user}}", "expose_graph");
    }
}
