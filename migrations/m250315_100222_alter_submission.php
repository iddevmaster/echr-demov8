<?php

use yii\db\Migration;

/**
 * Class m250315_100222_alter_submission
 */
class m250315_100222_alter_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'crec_leader_name', $this->string()->comment('ชื่อหัวหน้าโครงการ CREC'));
        $this->addColumn('submission', 'crec_leader_name_eng', $this->string()->comment('ชื่อหัวหน้าโครงการ CREC ภาษาอังกฤษ'));
        $this->addColumn('submission', 'crec_leader_site_name', $this->string()->comment('Site หัวหน้าโครงการ CREC'));
        $this->addColumn('submission', 'crec_leader_site_name_eng', $this->string()->comment('Site หัวหน้าโครงการ CREC ภาษาอังกฤษ'));
        $this->addColumn('submission', 'crec_leader_org_name', $this->string()->comment('สังกัดหัวหน้าโครงการ CREC'));
        $this->addColumn('submission', 'crec_leader_org_name_eng', $this->string()->comment('สังกัดหัวหน้าโครงการ CREC ภาษาอังกฤษ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission', 'crec_leader_name');
        $this->dropColumn('submission', 'crec_leader_name_eng');
        $this->dropColumn('submission', 'crec_leader_site_name');
        $this->dropColumn('submission', 'crec_leader_site_name_eng');
        $this->dropColumn('submission', 'crec_leader_org_name');
        $this->dropColumn('submission', 'crec_leader_org_name_eng');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250315_100222_alter_submission cannot be reverted.\n";

        return false;
    }
    */
}
