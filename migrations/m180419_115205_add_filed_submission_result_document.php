<?php

use yii\db\Migration;

/**
 * Class m180419_115205_add_filed_submission_result_document
 */
class m180419_115205_add_filed_submission_result_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_result_document', 'submission_committee_revise_id', $this->integer());
        $this->addForeignKey('fk_submission_result_document_submission_committee_revise_id', 'submission_result_document', 'submission_committee_revise_id', 'submission_committee_revise', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission_result_document', 'submission_committee_revise_id');   
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_115205_add_filed_submission_result_document cannot be reverted.\n";

        return false;
    }
    */
}
