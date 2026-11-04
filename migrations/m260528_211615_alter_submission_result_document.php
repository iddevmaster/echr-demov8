<?php

use yii\db\Migration;

/**
 * Class m260528_211615_alter_submission_result_document
 */
class m260528_211615_alter_submission_result_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_result_document', 'code', $this->string());
        $this->addCommentOnColumn('submission_result_document', 'code', 'เลขเอกสาร');
        $this->addColumn('submission_result_document', 'qrcode', $this->string());
        $this->addCommentOnColumn('submission_result_document', 'qrcode', 'qrcode');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission_result_document', 'code');
        $this->dropColumn('submission_result_document', 'qrcode');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260528_211615_alter_submission_result_document cannot be reverted.\n";

      return false;
      }
     */
}
