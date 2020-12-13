<?php

use yii\db\Schema;
use yii\db\Migration;

class m141028_013921_fix_typos_in_options extends Migration
{
    public function up()
    {
        $this->update("option", array("name"=>"procrastination causing crises in money, work, or relationships"), array("name"=>"procrastinatino causing crises in money, work, or relationships"));
        $this->update("option", array("name"=>"irrationality"), array("name"=>"rrationality"));
    }

    public function down()
    {
        echo "Why even revert this.";
    }
}
