<?php

use yii\db\Migration;

/**
 * Class m180222_122427_add_field_submission_document_role
 */
class m180222_122427_add_field_submission_document_role extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('document_submission_type', 'role_id', $this->integer());
        $this->addCommentOnColumn('document_submission_type', 'role_id', 'หน้าที่');
        $this->addForeignKey('fk_document_submission_type_role_id', 'document_submission_type', 'role_id', 'role', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('document_submission_type', 'role_id');        

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180222_122427_add_field_submission_document_role cannot be reverted.\n";

        return false;
    }
    */
}
