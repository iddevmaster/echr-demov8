<?php

use yii\db\Migration;

/**
 * Class m200421_131336_alter_submission_amendment
 */
class m200421_131336_alter_submission_amendment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'event_amendment', $this->string());
        $this->addCommentOnColumn('submission', 'event_amendment', 'เหตุการณ์ที่เกิดขึ้นในการ Amendment');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'event_amendment');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_131336_alter_submission_amendment cannot be reverted.\n";

        return false;
    }
    */
}
