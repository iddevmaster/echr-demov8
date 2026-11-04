<?php

use yii\db\Migration;

/**
 * Class m250607_051048_alter_submission_project
 */
class m250607_051048_alter_submission_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'crec_certified_date', $this->dateTime()->comment('วันที่รับรองจาก CREC'));
        $this->addColumn('submission', 'crec_expire_at', $this->dateTime()->comment('วันที่หมดอายุรับรองจาก CREC'));
        $this->addColumn('submission', 'crec_next_progress_at', $this->dateTime()->comment('วันที่รายงานความก้าวหน้าจาก CREC'));
        $this->addColumn('submission', 'crec_progress_period', $this->integer()->comment('ระบะเวลาในการายงานความก้าวหน้าจาก CREC'));

        $this->addColumn('project', 'crec_certified_date', $this->dateTime()->comment('วันที่รับรองจาก CREC'));
        $this->addColumn('project', 'crec_expire_at', $this->dateTime()->comment('วันที่หมดอายุรับรองจาก CREC'));
        $this->addColumn('project', 'crec_next_progress_at', $this->dateTime()->comment('วันที่รายงานความก้าวหน้าจาก CREC'));
        $this->addColumn('project', 'crec_progress_period', $this->integer()->comment('ระบะเวลาในการายงานความก้าวหน้าจาก CREC'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project', 'crec_certified_date');
        $this->dropColumn('project', 'crec_expire_at');
        $this->dropColumn('project', 'crec_next_progress_at');
        $this->dropColumn('project', 'crec_progress_period');

        $this->dropColumn('submission', 'crec_certified_date');
        $this->dropColumn('submission', 'crec_expire_at');
        $this->dropColumn('submission', 'crec_next_progress_at');
        $this->dropColumn('submission', 'crec_progress_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250607_051048_alter_submission_project cannot be reverted.\n";

        return false;
    }
    */
}
