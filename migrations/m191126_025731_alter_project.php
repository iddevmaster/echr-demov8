<?php

use yii\db\Migration;

/**
 * Class m191126_025731_alter_project
 */
class m191126_025731_alter_project extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('project', 'is_fda', $this->boolean()->defaultValue(false));
        $this->addCommentOnColumn('project', 'is_fda', 'ต้องรายงานอย.');
        $this->createIndex('idx_project_is_fda', 'project', 'is_fda');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('project', 'is_fda');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191126_025731_alter_project cannot be reverted.\n";

      return false;
      }
     */
}
