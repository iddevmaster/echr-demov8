<?php

use yii\db\Migration;

/**
 * Class m180420_001053_add_field_remark_submission
 */
class m180420_001053_add_field_remark_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'remark_checkdoc_staff', $this->text());
        $this->addColumn('submission', 'remark_assessed_staff', $this->text());
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
        echo "m180420_001053_add_field_remark_submission cannot be reverted.\n";

        return false;
    }
    */
}
