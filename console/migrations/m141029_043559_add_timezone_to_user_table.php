<?php

use yii\db\Schema;

class m141029_043559_add_timezone_to_user_table extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn("user", "timezone", "VARCHAR(255) NOT NULL DEFAULT 'America/Los_Angeles'");
    }

    public function down()
    {
        $this->dropColumn("user", "timezone");
    }
}
