<?php

use yii\db\Schema;
use yii\db\Migration;

class m141217_045837_alter_user_add_email_threshold_and_partner_emails extends Migration
{
    public function safeUp()
    {
        $this->addColumn("user", "email_threshold", "INT");
        $this->addColumn("user", "partner_email1", "TEXT");
        $this->addColumn("user", "partner_email2", "TEXT");
        $this->addColumn("user", "partner_email3", "TEXT");
    }

    public function safeDown()
    {
        $this->dropColumn("user", "email_threshold");
        $this->dropColumn("user", "partner_email1");
        $this->dropColumn("user", "partner_email2");
        $this->dropColumn("user", "partner_email3");
    }
}
