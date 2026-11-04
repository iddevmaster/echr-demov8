<?php

use yii\db\Migration;

/**
 * Class m191016_141349_alter_table_cr3
 */
class m191016_141349_alter_table_cr3 extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('document_submission_type', 'sort', $this->integer());
        $this->addCommentOnColumn('document_submission_type', 'sort', 'เรียงลำดับเอกสาร');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191016_141349_alter_table_cr3 cannot be reverted.\n";

      return false;
      }
     */
}
