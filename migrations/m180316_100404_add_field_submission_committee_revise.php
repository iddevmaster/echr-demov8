<?php

use yii\db\Migration;

/**
 * Class m180316_100404_add_field_submission_committee_revise
 */
class m180316_100404_add_field_submission_committee_revise extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_committee_revise', 'remark', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
                $this->dropColumn('submission_committee_revise', 'submission_committee_revise');        

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180316_100404_add_field_submission_committee_revise cannot be reverted.\n";

        return false;
    }
    */
}
