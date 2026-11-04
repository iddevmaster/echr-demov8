<?php

use yii\db\Migration;

/**
 * Class m200303_080815_alter_deviation_event_type
 */
class m200303_080815_alter_deviation_event_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_deviation_event_type_deviation_event_id', 'deviation_event_type');
        $this->dropColumn('deviation_event_type', 'deviation_event_id');
        
        $this->addColumn('deviation_event_type', 'submission_event_id', $this->integer());
        $this->addCommentOnColumn('deviation_event_type', 'submission_event_id', 'เหตุการณ์');
        $this->addForeignKey('fk_deviation_event_type_submission_event_id', 'deviation_event_type', 'submission_event_id', 'submission_event', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200303_080815_alter_deviation_event_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200303_080815_alter_deviation_event_type cannot be reverted.\n";

        return false;
    }
    */
}
