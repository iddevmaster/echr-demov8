<?php

use yii\db\Migration;

/**
 * Class m180213_085032_alter_submission_document
 */
class m180213_085032_alter_submission_document extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission_document', 'version', $this->string());
        $this->addColumn('submission_document', 'version_at', $this->dateTime());
        
        $this->addCommentOnColumn('submission_document', 'version', 'เวอร์ชันเอกสาร');
        $this->addCommentOnColumn('submission_document', 'version_at', 'วันที่เวอร์ชันเอกสาร');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('submission_document', 'version_at');
        $this->dropColumn('submission_document', 'version');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180213_085032_alter_submission_document cannot be reverted.\n";

      return false;
      }
     */
}
