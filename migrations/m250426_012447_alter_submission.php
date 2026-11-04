<?php

use yii\db\Migration;

/**
 * Class m250426_012447_alter_submission
 */
class m250426_012447_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'crec_send_plan_date', $this->dateTime()->comment('ส่งแบบประเมินก่อนวันที่จาก CREC'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'crec_send_plan_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250426_012447_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
