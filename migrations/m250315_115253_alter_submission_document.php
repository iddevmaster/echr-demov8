<?php

use yii\db\Migration;

/**
 * Class m250315_115253_alter_submission_document
 */
class m250315_115253_alter_submission_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_document', 'is_site', $this->boolean()->notNull()->defaultValue(false)->comment('เอกสาร Site หรือไม่'));
        $this->createIndex('idx_submission_document_is_site', 'submission_document', 'is_site');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_document_is_site', 'submission_document');
        $this->dropColumn('submission_document', 'is_site');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250315_115253_alter_submission_document cannot be reverted.\n";

        return false;
    }
    */
}
