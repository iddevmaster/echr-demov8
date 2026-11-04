<?php

use yii\db\Migration;

/**
 * Class m180214_065051_add_field_submission_secretary
 */
class m180214_065051_add_field_submission_secretary extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'secretary_person', $this->integer());
        $this->addCommentOnColumn('submission', 'secretary_person', 'เลขา');
        $this->addForeignKey('fk_submission_secretary_person', 'submission', 'secretary_person', 'user', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'secretary_person');        

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180214_065051_add_field_submission_secretary cannot be reverted.\n";

        return false;
    }
    */
}
