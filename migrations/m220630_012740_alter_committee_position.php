<?php

use yii\db\Migration;

/**
 * Class m220630_012740_alter_committee_position
 */
class m220630_012740_alter_committee_position extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('committee_position', 'status', $this->integer());
        $this->addCommentOnColumn('committee_position', 'status', 'สถานะตำแหน่งกรรมการ');
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
        echo "m220630_012740_alter_committee_position cannot be reverted.\n";

        return false;
    }
    */
}
