<?php

use yii\db\Migration;
use app\models\Setting;

/**
 * Class m180319_042634_create_alert
 */
class m180319_042634_create_alert extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('alert', [
            'id' => $this->primaryKey(),
            'message' => $this->string(),
            'type' => $this->integer(),
            'is_acknowledged' => $this->boolean()->notNull()->defaultValue(FALSE),
            'acknowledged_by' => $this->integer(),
            'acknowledged_at' => $this->dateTime(),
            'alerted_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'user_id' => $this->integer(),
            'submission_id' => $this->integer(),
        ]);

        $this->addCommentOnColumn('alert', 'message', 'ข้อความเตือน');
        $this->addCommentOnColumn('alert', 'type', 'ประเภทการเตือน');
        $this->addCommentOnColumn('alert', 'is_acknowledged', 'รับรู้การเตือน');
        $this->addCommentOnColumn('alert', 'acknowledged_by', 'รับรู้โดย');
        $this->addCommentOnColumn('alert', 'acknowledged_at', 'รับรู้เมื่อ');
        $this->addCommentOnColumn('alert', 'alerted_at', 'เตือนเมื่อ');
        $this->addCommentOnColumn('alert', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('alert', 'user_id', 'เตือนผู้ใช้');
        $this->addCommentOnColumn('alert', 'submission_id', 'การส่งพิจารณาโครงการ');

        $this->addForeignKey('fk_alert_acknowledged_by', 'alert', 'acknowledged_by', 'user', 'id');
        $this->addForeignKey('fk_alert_user_id', 'alert', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_alert_submission_id', 'alert', 'submission_id', 'submission', 'id');
        
        $this->insert('setting', ['id' => 2, 'key' => Setting::ALERT_DOC_CHECK, 'name' => Setting::getAlertNames()[Setting::ALERT_DOC_CHECK], 'value' => 3]);
//        $this->insert('setting', ['id' => 3,'key' => Setting::ALERT_HE_NOTIFY, 'name' => Setting::getAlertNames()[Setting::ALERT_HE_NOTIFY], 'value' => 3]);
        $this->insert('setting', ['id' => 3,'key' => Setting::ALERT_COMMITTEE_REPICK_NOTIFY, 'name' => Setting::getAlertNames()[Setting::ALERT_COMMITTEE_REPICK_NOTIFY], 'value' => 1]);
        $this->insert('setting', ['id' => 4,'key' => Setting::ALERT_COMMITTEE_ASSESS, 'name' => Setting::getAlertNames()[Setting::ALERT_COMMITTEE_ASSESS], 'value' => 7]);
        $this->insert('setting', ['id' => 5,'key' => Setting::ALERT_RESUBMIT, 'name' => Setting::getAlertNames()[Setting::ALERT_RESUBMIT], 'value' => 14]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('setting', ['id' >= 2]);
        
        $this->dropTable('alert');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180319_042634_create_alert cannot be reverted.\n";

      return false;
      }
     */
}
