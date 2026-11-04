<?php

use yii\db\Migration;

/**
 * Class m200220_034047_cr04_sae_deviation
 */
class m200220_034047_cr04_sae_deviation extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('document_submission_type', 'is_event', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('submission', 'events', $this->integer());
        $this->addColumn('submission_document', 'event_no', $this->integer());
        $this->addColumn('continue_assess_form', 'event_no', $this->integer());
        $this->createIndex('idx_submission_document_eventNo', 'submission_document', ['event_no']);
        $this->createIndex('idx_continue_assess_form_eventNo', 'continue_assess_form', ['event_no']);
        $this->addCommentOnColumn('continue_assess_form', 'event_no', 'เลขเหตุการณ์ของ Deviation');
        $this->addCommentOnColumn('submission_document', 'event_no', 'เลขเหตุการณ์ของ Deviation');
        $this->addCommentOnColumn('submission', 'events', 'จำนวนเหตุการณ์ของ Deviation');
        $this->addCommentOnColumn('document_submission_type', 'is_event', 'อัพโหลดเอกสารตามเหตุการณ์หรือตามอาสาสมัคร');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_submission_document_eventNo', 'submission_document');
        $this->dropIndex('idx_continue_assess_form_eventNo', 'continue_assess_form');
        $this->dropColumn('document_submission_type', 'is_event');
        $this->dropColumn('submission', 'events');
        $this->dropColumn('submission_document', 'event_no');
        $this->dropColumn('continue_assess_form', 'event_no');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200220_034047_cr04_sae_deviation cannot be reverted.\n";

      return false;
      }
     */
}
