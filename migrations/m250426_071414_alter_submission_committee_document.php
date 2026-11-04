<?php

use yii\db\Migration;

/**
 * Class m250426_071414_alter_submission_committee_document
 */
class m250426_071414_alter_submission_committee_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_committee_document', 'crec_document_id', $this->integer()->comment('รหัสเอกสารจาก CREC'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission_committee_document', 'crec_document_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250426_071414_alter_submission_committee_document cannot be reverted.\n";

        return false;
    }
    */
}
