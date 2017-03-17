<?php

use yii\db\Schema;
use yii\db\Migration;

class m170317_044911_add_verify_email_token extends Migration
{
  public function up()
  {
    $this->addColumn("{{%user}}", "verify_email_token", Schema::TYPE_STRING);
  }

  public function down()
  {
    $this->dropColumn("{{%user}}", "verify_email_token");
  }
}
