<?php

use yii\db\Migration;

/**
 * Class m180312_071714_add_field_project_researcher
 */
class m180312_071714_add_field_project_researcher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project_researcher', 'submission_id', $this->integer());
        $this->addCommentOnColumn('project_researcher', 'submission_id', 'submission');
        $this->addForeignKey('fk_project_researcher_submission_id', 'project_researcher', 'submission_id', 'submission', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project_researcher', 'submission_id');        

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180312_071714_add_field_project_researcher cannot be reverted.\n";

        return false;
    }
    */
}
