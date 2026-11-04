<?php

use app\models\CommitteePosition;
use yii\db\Migration;

/**
 * Class m250426_061831_insert_committee_position
 */
class m250426_061831_insert_committee_position extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('committee_position', [
            'id' => CommitteePosition::POSITION_LEC,
            'name' => 'กรรมการ LEC',
            'description' => 'กรรมการ LEC',
            'status' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('committee_position', ['id' => CommitteePosition::POSITION_LEC]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250426_061831_insert_committee_position cannot be reverted.\n";

        return false;
    }
    */
}
