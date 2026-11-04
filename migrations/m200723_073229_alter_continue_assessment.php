<?php

use yii\db\Migration;

/**
 * Class m200723_073229_alter_continue_assessment
 */
class m200723_073229_alter_continue_assessment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('continue_assess_form_ethics', 'is_appropriate', $this->integer());
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
        echo "m200723_073229_alter_continue_assessment cannot be reverted.\n";

        return false;
    }
    */
}
