<?php

use app\models\FundingSource;
use app\models\Setting;
use app\models\SubmissionType;
use yii\db\Migration;

/**
 * Class m250309_080038_insert_crec_funding_source
 */
class m250309_080038_insert_crec_funding_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // เพิ่มข้อมูล funding source ของโครงการ CREC
        $this->insert('funding_source', ['id' => 6, 'name' => 'ทุนของโครงการ CREC', 'name_eng' => 'CREC']);

        // เพิ่มข้อมูล setting สำหรับเก็บค่าเริ่มต้นของการสร้าง submission ของ CREC
        $this->insert('setting', ['key' => Setting::INITIAL_CREC_SUBMISSION_TYPE, 'value' => SubmissionType::TYPE_CREC]);
        $this->insert('setting', ['key' => Setting::CREC_FUNDING_SOURCE, 'value' => FundingSource::CREC]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('funding_source', ['id' => 6]);
        $this->delete('setting', ['key' => Setting::INITIAL_CREC_SUBMISSION_TYPE]);
        $this->delete('setting', ['key' => Setting::CREC_FUNDING_SOURCE]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_080038_insert_crec_funding_source cannot be reverted.\n";

        return false;
    }
    */
}
