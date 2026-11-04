<?php

use app\models\Setting;
use yii\db\Migration;

/**
 * Class m250315_065459_insert_crec_setting
 */
class m250315_065459_insert_crec_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('setting', ['key' => Setting::CREC_URL, 'value' => 'http://crec-ec/crec-ec/']);
        $this->insert('setting', ['key' => Setting::CREC_ACCESS_TOKEN, 'value' => '1234567890']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('setting', ['key' => Setting::CREC_URL]);
        $this->delete('setting', ['key' => Setting::CREC_ACCESS_TOKEN]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250315_065459_insert_crec_setting cannot be reverted.\n";

        return false;
    }
    */
}
