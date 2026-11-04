<?php

use yii\db\Migration;

/**
 * Class m181123_041853_add_field_eng
 */
class m181123_041853_add_field_eng extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('job_category', 'name_eng', $this->string());
        $this->addColumn('volunteer_number', 'name_eng', $this->string());
        $this->addColumn('submission_type', 'name_eng', $this->string());
        $this->addColumn('submission_type', 'resolution_label_eng', $this->string());
        $this->addColumn('submission_type_group', 'name_eng', $this->string());
        $this->addColumn('result_document', 'name_eng', $this->string());
        $this->addColumn('funding_source', 'name_eng', $this->string());
        $this->addColumn('meeting_room', 'name_eng', $this->string());
        $this->addColumn('role', 'name_eng', $this->string());
        $this->addColumn('panel', 'name_eng', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('job_category', 'name_eng');
        $this->dropColumn('volunteer_number', 'name_eng');
        $this->dropColumn('submission_type', 'name_eng');
        $this->dropColumn('submission_type', 'resolution_label_eng');
        $this->dropColumn('submission_type_group', 'name_eng');
        $this->dropColumn('result_document', 'name_eng');
        $this->dropColumn('funding_source', 'name_eng');
        $this->dropColumn('meeting_room', 'name_eng');
        $this->dropColumn('role', 'name_eng');
        $this->dropColumn('panel', 'name_eng');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181123_041853_add_field_eng cannot be reverted.\n";

        return false;
    }
    */
}
