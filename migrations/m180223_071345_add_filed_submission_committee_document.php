<?php

use yii\db\Migration;

/**
 * Class m180223_071345_add_filed_submission_committee_document
 */
class m180223_071345_add_filed_submission_committee_document extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission_committee_document', 'version', $this->string());
        $this->addColumn('submission_committee_document', 'version_at', $this->dateTime());

        $this->addColumn('submission_committee_document', 'document_id', $this->integer());
        $this->addCommentOnColumn('submission_committee_document', 'document_id', 'เอกสาร');
        $this->addForeignKey('fk_submission_committee_document_document_id', 'submission_committee_document', 'document_id', 'document', 'id');

        $this->addColumn('submission_committee_document', 'submission_id', $this->integer());
        $this->addCommentOnColumn('submission_committee_document', 'submission_id', 'submission');
        $this->addForeignKey('fk_submission_committee_document_submission_id', 'submission_committee_document', 'submission_id', 'submission', 'id');

        $this->addColumn('submission_committee_document', 'project_id', $this->integer());
        $this->addCommentOnColumn('submission_committee_document', 'project_id', 'project');
        $this->addForeignKey('fk_submission_committee_document_project_id', 'submission_committee_document', 'project_id', 'project', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('document_submission_type', 'project_id');
        $this->dropColumn('document_submission_type', 'submission_id');
        $this->dropColumn('document_submission_type', 'document_id');
        $this->dropColumn('document_submission_type', 'version_at');
        $this->dropColumn('document_submission_type', 'version');

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180223_071345_add_filed_submission_committee_document cannot be reverted.\n";

      return false;
      }
     */
}
