<?php

use yii\db\Migration;

/**
 * Class m190504_154023_alter_agenda_result_document
 */
class m190504_154023_alter_submission_result_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission_result_document', 'name', $this->string());
        $this->addCommentOnColumn('submission_result_document', 'name', 'ชื่อเอกสาร');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('submission_result_document', 'name');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190504_154023_alter_agenda_result_document cannot be reverted.\n";

      return false;
      }
     */
}
