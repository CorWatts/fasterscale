<?php

use yii\db\Migration;

class m170104_042421_fix_misspelling_on_check_in_page_judging_others_motives_behavior extends Migration
{
    public function safeUp()
    {
        $this->update("option", array("name" => "judging others' motives"), array("name" => "juding others' motives"));
    }

    public function safeDown()
    {
        $this->update("option", array("name" => "juding others' motives"), array("name" => "judging others' motives"));
    }
}
