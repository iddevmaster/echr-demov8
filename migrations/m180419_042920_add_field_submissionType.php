<?php

use yii\db\Migration;

/**
 * Class m180419_042920_add_field_submissionType
 */
class m180419_042920_add_field_submissionType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_type', 'close', $this->boolean()->notNull()->defaultValue(FALSE));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        echo "m180419_042920_add_field_submissionType cannot be reverted.\n";
//
//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_042920_add_field_submissionType cannot be reverted.\n";

        return false;
    }
    */
}
