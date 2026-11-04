<?php

use yii\db\Migration;

/**
 * Class m250614_095410_alter_submission
 */
class m250614_095410_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'need_local_issue', $this->boolean()->notNull()->defaultValue(false)->comment('1=ประเมิน local issue, 0=ไม่ประเมิน local issue'));
    
        $this->update('submission', [
            'need_local_issue' => 1,
        ], [
            'is_submit_by_api' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'need_local_issue');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250614_095410_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
