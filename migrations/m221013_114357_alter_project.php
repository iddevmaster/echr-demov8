<?php

use yii\db\Migration;

/**
 * Class m221013_114357_alter_project
 */
class m221013_114357_alter_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project', 'name_changed', $this->boolean()->notNull()->defaultValue(false));
        $this->addCommentOnColumn('project', 'name_changed', 'เปลี่ยนชื่อโดย Admin');

        $this->createIndex('idx_project_name_changed', 'project', 'name_changed');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project', 'name_changed');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221013_114357_alter_project cannot be reverted.\n";

        return false;
    }
    */
}
