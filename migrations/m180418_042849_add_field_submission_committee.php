<?php

use yii\db\Migration;

/**
 * Class m180418_042849_add_field_submission_committee
 */
class m180418_042849_add_field_submission_committee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission_committee', 'is_meeting', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('submission_committee', 'resolution', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180418_042849_add_field_submission_committee cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180418_042849_add_field_submission_committee cannot be reverted.\n";

        return false;
    }
    */
}
