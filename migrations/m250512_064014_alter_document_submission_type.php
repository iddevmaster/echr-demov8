<?php

use yii\db\Migration;

/**
 * Class m250512_064014_alter_document_submission_type
 */
class m250512_064014_alter_document_submission_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('document_submission_type', 'is_api', $this->boolean()->notNull()->defaultValue(false)->comment('เพิ่มเอกสารจาก API'));
        $this->createIndex('idx_document_submission_type_is_api', 'document_submission_type', 'is_api');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_document_submission_type_is_api', 'document_submission_type');
        $this->dropColumn('document_submission_type', 'is_api');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250512_064014_alter_document_submission_type cannot be reverted.\n";

        return false;
    }
    */
}
