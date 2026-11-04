<?php

use yii\db\Migration;

/**
 * Class m250315_051810_alter_submission_document
 */
class m250315_051810_alter_submission_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_document', 'sd_crec_id', $this->integer()->comment('รหัส submission_document_id ของ CREC'));
        $this->createIndex('idx_submission_document_sd_crec_id', 'submission_document', 'sd_crec_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_document_sd_crec_id', 'submission_document');
        $this->dropColumn('submission_document', 'sd_crec_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250315_051810_alter_submission_document cannot be reverted.\n";

        return false;
    }
    */
}
