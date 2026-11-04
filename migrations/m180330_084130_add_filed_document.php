<?php

use yii\db\Migration;

/**
 * Class m180330_084130_add_filed_document
 */
class m180330_084130_add_filed_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('document', 'template_file_eng', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('document', 'template_file_eng');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180330_084130_add_filed_document cannot be reverted.\n";

      return false;
      }
     */
}
