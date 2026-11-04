<?php

use yii\db\Migration;

/**
 * Class m190418_072543_alter_project
 */
class m190418_072543_alter_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('project', 'name_thai', $this->string(500));
        $this->alterColumn('project', 'name_eng', $this->string(500));
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
        echo "m190418_072543_alter_project cannot be reverted.\n";

        return false;
    }
    */
}
