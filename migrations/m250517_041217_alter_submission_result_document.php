<?php

use yii\db\Migration;

/**
 * Class m250517_041217_alter_submission_result_document
 */
class m250517_041217_alter_submission_result_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'acknowledged_crec_result', $this->integer()->defaultValue(0)->comment('0=ไม่ต้องรับทราบ, 1=รับทราบผล, 2=รอตอบรับผลจาก CREC'));
        $this->addColumn('submission', 'notify_crec_result_leader', $this->boolean()->notNull()->defaultValue(false)->comment('0=ไม่ส่งให้หัวหน้าโครงการ, 1=ส่งให้หัวหน้าโครงการ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'notify_crec_result_leader');
        $this->dropColumn('submission', 'acknowledged_crec_result');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250517_041217_alter_submission_result_document cannot be reverted.\n";

        return false;
    }
    */
}
