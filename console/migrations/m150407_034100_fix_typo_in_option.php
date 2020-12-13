<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_034100_fix_typo_in_option extends Migration
{
    public function up()
    {
        $this->update("option", array("name"=>"making goals and lists you can't complete"), array("name"=>"makin goals and lists you can't complete"));
    }

    public function down()
    {
        echo "Why even revert this.";
    }
}
