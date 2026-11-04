<?php

use yii\db\Migration;

/**
 * Class m180129_044006_add_field_division
 */
class m180129_044006_add_field_division extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('division', 'organization_id', $this->integer());
        $this->addColumn('division', 'name_eng', $this->string());
        $this->addCommentOnColumn('division', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('division', 'name_eng', 'ชื่อภาษาอังกฤษ');
        $this->addForeignKey('fk_division_organization_id', 'division', 'organization_id', 'organization', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180129_044006_add_field_division cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180129_044006_add_field_division cannot be reverted.\n";

        return false;
    }
    */
}
