<?php

use yii\db\Migration;

/**
 * Class m250415_112306_alter_submission_type
 */
class m250415_112306_alter_submission_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_type', 'crec_id', $this->integer()->comment('รหัสประเภทการส่งโครงการในระบบ CREC'));
        $this->createIndex('idx_submission_type-crec_id', 'submission_type', 'crec_id');

        $this->update('submission_type', ['crec_id' => 7], ['id' => 7]);
        $this->update('submission_type', ['crec_id' => 8], ['id' => 8]);
        $this->update('submission_type', ['crec_id' => 9], ['id' => 9]);
        $this->update('submission_type', ['crec_id' => 10], ['id' => 10]);
        $this->update('submission_type', ['crec_id' => 11], ['id' => 11]);
        $this->update('submission_type', ['crec_id' => 12], ['id' => 12]);
        $this->update('submission_type', ['crec_id' => 13], ['id' => 13]);
        $this->update('submission_type', ['crec_id' => 14], ['id' => 14]);
        $this->update('submission_type', ['crec_id' => 15], ['id' => 15]);
        $this->update('submission_type', ['crec_id' => 16], ['id' => 16]);
        $this->update('submission_type', ['crec_id' => 18], ['id' => 18]);
        $this->update('submission_type', ['crec_id' => 20], ['id' => 20]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_submission_type-crec_id', 'submission_type');
        $this->dropColumn('submission_type', 'crec_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250415_112306_alter_submission_type cannot be reverted.\n";

        return false;
    }
    */
}
