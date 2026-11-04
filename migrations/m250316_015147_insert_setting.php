<?php

use app\models\Setting;
use yii\db\Migration;

/**
 * Class m250316_015147_insert_setting
 */
class m250316_015147_insert_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('setting', ['key' => Setting::CREC_RESPONSIBLE_PERSON_EMAIL, 'value' => 'patipat.tip@gmail.com']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('setting', ['key' => Setting::CREC_RESPONSIBLE_PERSON_EMAIL]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250316_015147_insert_setting cannot be reverted.\n";

        return false;
    }
    */
}
