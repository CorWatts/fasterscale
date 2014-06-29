<?php

use yii\db\Schema;

class m140608_220445_alter_option_drop_weight extends \yii\db\Migration
{
    public function up()
    {
        $this->dropColumn('{{%option}}', 'weight');

    }

    public function down()
    {
        $this->addColumn('{{%option}}', 'weight', Schema::TYPE_INTEGER . ' NOT NULL');

    }
}
