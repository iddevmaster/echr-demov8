<?php

use yii\db\Migration;

/**
 * Class m180321_082942_add_field_submission_committee_revise
 */
class m180321_082942_add_field_submission_committee_revise extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_committee_revise', 'researcher_receive_date', $this->dateTime());
        $this->addColumn('submission_committee_revise', 'committee_send_date', $this->dateTime());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('submission_committee_revise', 'committee_send_date', $this->dateTime());
        $this->addColumn('submission_committee_revise', 'researcher_receive_date', $this->dateTime());

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180321_082942_add_field_submission_committee_revise cannot be reverted.\n";

        return false;
    }
    */
}
