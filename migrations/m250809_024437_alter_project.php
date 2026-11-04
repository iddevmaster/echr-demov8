<?php

use yii\db\Migration;

/**
 * Class m250809_024437_alter_project
 */
class m250809_024437_alter_project extends Migration
{
    public function safeUp()
    {
        $this->addColumn('project', 'crec_leader_name', $this->string()->comment('ชื่อหัวหน้าโครงการ CREC'));
        $this->addColumn('project', 'crec_leader_name_eng', $this->string()->comment('ชื่อหัวหน้าโครงการ CREC ภาษาอังกฤษ'));
        $this->addColumn('project', 'crec_leader_site_name', $this->string()->comment('Site หัวหน้าโครงการ CREC'));
        $this->addColumn('project', 'crec_leader_site_name_eng', $this->string()->comment('Site หัวหน้าโครงการ CREC ภาษาอังกฤษ'));
        $this->addColumn('project', 'crec_leader_org_name', $this->string()->comment('สังกัดหัวหน้าโครงการ CREC'));
        $this->addColumn('project', 'crec_leader_org_name_eng', $this->string()->comment('สังกัดหัวหน้าโครงการ CREC ภาษาอังกฤษ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project', 'crec_leader_name');
        $this->dropColumn('project', 'crec_leader_name_eng');
        $this->dropColumn('project', 'crec_leader_site_name');
        $this->dropColumn('project', 'crec_leader_site_name_eng');
        $this->dropColumn('project', 'crec_leader_org_name');
        $this->dropColumn('project', 'crec_leader_org_name_eng');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250809_024437_alter_project cannot be reverted.\n";

        return false;
    }
    */
}
