<?php

use yii\db\Migration;

/**
 * Class m250301_161343_alter_department
 */
class m250301_161343_alter_department extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('department', 'crec_id', $this->integer());
        $this->addCommentOnColumn('department', 'crec_id', 'Crec ID');
        $this->createIndex('idx_department_crec_id', 'department', 'crec_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_department_crec_id', 'department');
        $this->dropColumn('department', 'crec_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250301_161343_alter_department cannot be reverted.\n";

        return false;
    }
    */
}
