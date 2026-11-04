<?php

use yii\db\Migration;

/**
 * Class m200309_082609_add_diviation_event
 */
class m200309_082609_add_submission_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_event', 'code', $this->string());
        $this->addCommentOnColumn('submission_event', 'code', 'หมายเลขเหตุการณ์');
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
        echo "m200309_082609_add_diviation_event cannot be reverted.\n";

        return false;
    }
    */
}
