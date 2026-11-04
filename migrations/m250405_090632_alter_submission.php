<?php

use yii\db\Migration;

/**
 * Class m250405_090632_alter_submission
 */
class m250405_090632_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'crec_issue_req_detail', $this->text()->comment('รายละเอียดนำส่งเรื่องประเมิน'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'crec_issue_req_detail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250405_090632_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
