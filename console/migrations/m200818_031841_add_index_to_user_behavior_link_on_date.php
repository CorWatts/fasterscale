<?php

use yii\db\Migration;

/**
 * Class m200818_031841_add_index_to_user_behavior_link_on_date
 */
class m200818_031841_add_index_to_user_behavior_link_on_date extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createIndex('user_behavior_link_date', '{{%user_behavior_link}}', 'date');
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropIndex('user_behavior_link_date', '{{%user_behavior_link}}');
  }
}
