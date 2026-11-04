<?php

use yii\db\Migration;

/**
 * Class m251113_130001_alter_submission_and_document
 */
class m251113_130001_alter_submission_and_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission', 'submission_number_crec', $this->string());
        $this->addCommentOnColumn('submission', 'submission_number_crec', 'เลขที่รับการยื่น submission ของ crec');
        
        $this->addColumn('document', 'remark', $this->string());
        $this->addCommentOnColumn('document', 'remark', 'รายละเอียดเพิ่มเติมเกี่ยวกับเอกสาร');        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
//        $this->alterColumn('funding_source', 'crec_id', $this->string());
//        $this->update('funding_source', ['crec_id' => '2,5'], ['id' => 5]);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m251113_130001_alter_submission_and_document cannot be reverted.\n";

      return false;
      }
     */
}
