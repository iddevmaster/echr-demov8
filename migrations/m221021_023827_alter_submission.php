<?php

use yii\db\Migration;

/**
 * Class m221021_023827_alter_submission
 */
class m221021_023827_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_status_history', 'remark_checkdoc_staff', $this->string());
        $this->addCommentOnColumn('submission_status_history', 'remark_checkdoc_staff', 'เจ้าหน้าที่หมายเหตุการตีกลับเอกสาร');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221021_023827_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
