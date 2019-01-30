<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\db\Query;
use common\models\Behavior;
use common\models\Category;
use common\models\UserBehavior;

/**
 * Class m190829_141748_alter_user_behavior_link_add_category_id
 */
class m190829_141748_alter_user_behavior_link_add_category_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
      // create the new columns
      $this->addColumn('{{%user_behavior_link}}', 'category_id', Schema::TYPE_INTEGER);
      $this->addColumn('{{%question}}', 'category_id', Schema::TYPE_INTEGER);

      // then populate the columns
      $query = (new Query())->from('user_behavior_link');
      foreach($query->batch() as $rows) {
        foreach($rows as $row) {
          $bhvr = Behavior::getBehavior('id', $row['behavior_id']);
          if($bhvr) {
            $this->update('{{%user_behavior_link}}', ['category_id' => $bhvr['category_id']], ['id' => $row['id']]);
          }
        }
      }

      $query = (new Query())->from('question');
      foreach($query->batch() as $rows) {
        foreach($rows as $row) {
          $bhvr = Behavior::getBehavior('id', $row['behavior_id']);
          if($bhvr) {
            $this->update('{{%question}}', ['category_id' => $bhvr['category_id']], ['id' => $row['id']]);
          }
        }
      }

      // once the columns are created and have been populated with category_ids we
      // can set them to be NOT NULL
      $this->alterColumn('{{%user_behavior_link}}', 'category_id', 'SET NOT NULL');
      $this->alterColumn('{{%question}}', 'category_id', 'SET NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
      /**
       * If custom behaviors are saved in the user_behavior_link table, we run this migration down, then
       * try to run it up again, it will fail.
       * If a custom behavior exists in the user_behavior_link table when we try to run this up migration, there will be rows with NULL for a category_id (we add the category_id column, but then Behavior::getBehavior() returns false).
       * It's easiest to just not allow this migration to be ran down.
       */
      return false;

      // $this->dropColumn('{{%user_behavior_link}}', 'category_id');
      // $this->dropColumn('{{%question}}', 'category_id');
    }
}
