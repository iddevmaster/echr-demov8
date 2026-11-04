<?php

use yii\db\Migration;

/**
 * Class m250322_084624_alter_submission
 */
class m250322_084624_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'crec_resolution', $this->string()->comment('ผลการพิจารณา CREC'));
        $this->createIndex('idx_submission_crec_resolution', 'submission', 'crec_resolution');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_crec_resolution', 'submission');
        $this->dropColumn('submission', 'crec_resolution');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250322_084624_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
