<?php

use yii\db\Migration;

/**
 * Class m250321_064204_alter_submission_result_document
 */
class m250321_064204_alter_submission_result_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_result_document', 'srd_crec_id', $this->integer()->comment('รหัส Crec'));
        $this->addColumn('submission_result_document', 'is_site', $this->boolean()->defaultValue(false)->comment('เอกสาร Site หรือไม่'));
        $this->createIndex('idx_submission_result_document_srd_crec_id', 'submission_result_document', 'srd_crec_id');
        $this->createIndex('idx_submission_result_document_is_site', 'submission_result_document', 'is_site');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_result_document_srd_crec_id', 'submission_result_document');
        $this->dropIndex('idx_submission_result_document_is_site', 'submission_result_document');
        $this->dropColumn('submission_result_document', 'srd_crec_id');
        $this->dropColumn('submission_result_document', 'is_site');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250321_064204_alter_submission_result_document cannot be reverted.\n";

        return false;
    }
    */
}
