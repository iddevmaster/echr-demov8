<?php

use yii\db\Migration;

/**
 * Class m250516_113710_alter_submission
 */
class m250516_113710_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'send_to_crec', $this->boolean()->notNull()->defaultValue(false)->comment('ส่ง Submission ไป CREC'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'send_to_crec');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250516_113710_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
