<?php

use yii\db\Migration;

/**
 * Class m260428_174109_alter_submission
 */
class m260428_174109_alter_submission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $this->addColumn('submission', 'president_person', $this->integer());
        $this->addCommentOnColumn('submission', 'president_person', 'ประธานลงนาม');
        $this->addForeignKey('fk_submission_president_person', 'submission', 'president_person', 'user', 'id');
        $this->addColumn('submission', 'president_comment', $this->string());
        $this->addCommentOnColumn('submission', 'president_comment', 'คอมเม้นท์หนังสือแจ้งผลจากประธาน');
        $this->addColumn('submission', 'ref_certificate_no', $this->string());
        $this->addCommentOnColumn('submission', 'ref_certificate_no', 'เลขที่หนังสือแจ้งผลจาก CREC');

        $this->addColumn('submission_result_document', 'coa_token', $this->string());
        $this->addCommentOnColumn('submission_result_document', 'coa_token', 'COA Token');

        $this->addColumn('person', 'signature_thai', $this->string());
        $this->addCommentOnColumn('person', 'signature_thai', 'ลายเซ็นต์ไทย');
        $this->addColumn('person', 'signature', $this->string());
        $this->addCommentOnColumn('person', 'signature', 'ลายเซ็นต์อังฤกษ');


        $this->addColumn('submission_committee', 'ref_letter_no', $this->string());
        $this->addCommentOnColumn('submission_committee', 'ref_letter_no', 'เลขที่หนังสือแจ้งขอความอนุเคราะห์ประเมิน local issue');
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
      echo "m260428_174109_alter_submission cannot be reverted.\n";

      return false;
      }
     */
}
