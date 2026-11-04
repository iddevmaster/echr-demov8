<?php

use yii\db\Migration;

/**
 * Class m260618_183409_alter_submission
 */
class m260618_183409_alter_submission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission', 'ex_text_result', $this->string());
        $this->addColumn('submission', 'ex_text_result_eng', $this->string());
        $this->addColumn('submission', 'amendment_text_result', $this->string());
        $this->addColumn('submission', 'amendment_text_result_eng', $this->string());
        $this->addColumn('submission', 'n_text_result', $this->string());
        $this->addColumn('submission', 'n_date', $this->dateTime());
        $this->addColumn('submission', 't_date', $this->dateTime());
        $this->addColumn('submission', 'last_keep_date', $this->dateTime());
        $this->addCommentOnColumn('submission', 'ex_text_result', 'ข้อเสนอแนะ Expedite , Exemption ในใบแจ้งผล');
        $this->addCommentOnColumn('submission', 'ex_text_result_eng', 'ข้อเสนอแนะ Expedite , Exemption ในใบแจ้งผลภาษาอังกฤษ');
        $this->addCommentOnColumn('submission', 'amendment_text_result', 'ข้อเสนอแนะ Amendment ในใบแจ้งผล');
        $this->addCommentOnColumn('submission', 'amendment_text_result_eng', 'ข้อเสนอแนะ Amendment ในใบแจ้งผลภาษาอังกฤษ');
        $this->addCommentOnColumn('submission', 'n_text_result', 'ข้อเสนอแนะ ผล N');
        $this->addCommentOnColumn('submission', 'n_date', 'ให้ติดต่อภายในวันที่');
        $this->addCommentOnColumn('submission', 't_date', 'ให้ติดต่อภายในวันที่');
        $this->addCommentOnColumn('submission', 'last_keep_date', 'เก็บเอกสารถึงวันที่');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
//        $this->addColumn('submission_committee', 'assess_type', $this->integer());
//        $this->addCommentOnColumn('submission_committee', 'assess_type', 'ประเภทการพิจารณา');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260618_183409_alter_submission cannot be reverted.\n";

      return false;
      }
     */
}
