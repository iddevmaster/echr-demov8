<?php

use yii\db\Migration;

/**
 * Class m250427_070420_alter_submission
 */
class m250427_070420_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%submission}}', 'is_submit_by_api', $this->boolean()->notNull()->defaultValue(false)->comment('สร้างโดย API'));
        $this->createIndex('idx_submission_is_submit_by_api', '{{%submission}}', 'is_submit_by_api');

        $this->update('{{%submission}}', ['is_submit_by_api' => 1], ['not', ['crec_leader_name' => null]]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_is_submit_by_api', '{{%submission}}');
        $this->dropColumn('{{%submission}}', 'is_submit_by_api');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250427_070420_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
