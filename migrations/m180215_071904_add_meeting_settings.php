<?php

use yii\db\Migration;

/**
 * Class m180215_071904_add_meeting_settings
 */
class m180215_071904_add_meeting_settings extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('setting', [
            'key' => \app\models\Setting::MEETING_NAME,
            'name' => 'ชื่อการประชุม',
            'value' => 'การประชุมคณะกรรมการจริยธรรมการวิจัยในมนุษย์มหาวิทยาลัยขอนแก่น',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('setting', [
            'key' => app\models\Setting::MEETING_NAME
        ]);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180215_071904_add_meeting_settings cannot be reverted.\n";

      return false;
      }
     */
}
