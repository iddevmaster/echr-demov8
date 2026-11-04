<?php

use yii\db\Migration;

/**
 * Class m180212_104712_alter_submission
 */
class m180212_104712_alter_submission extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission', 'correspondence_no', $this->string());
        $this->addColumn('submission', 'correspondence_at', $this->dateTime());
        
        $this->addCommentOnColumn('submission', 'correspondence_no', 'เลขที่หนังสือ');
        $this->addCommentOnColumn('submission', 'correspondence_at', 'วันที่ออกหนังสือ');
        
        $this->createIndex('idx_submission_correspondence_no', 'submission', 'correspondence_no');
        
        $this->addColumn('submission_type', 'internal', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('submission_type', 'internal', 'ใช้ภายในเท่านั้น');
        
        $this->createTable('submission_status_history', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'status' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('submission_status_history', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_status_history', 'submission_id', 'การยื่นโครงการ');
        $this->addCommentOnColumn('submission_status_history', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission_status_history', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_status_history', 'created_at', 'สร้างเมื่อ');
        
        $this->addForeignKey('fk_submission_status_history_submission_id', 'submission_status_history', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_submission_status_history_created_by', 'submission_status_history', 'created_by', 'user', 'id');
        
        $this->createTable('meeting_person', [
            'id' => $this->primaryKey(),
            'meeting_id' => $this->integer(),
            'person_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('meeting_person', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('meeting_person', 'meeting_id', 'การประชุม');
        $this->addCommentOnColumn('meeting_person', 'person_id', 'บุคคล');
        $this->addCommentOnColumn('meeting_person', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_person', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_person', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_person', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_person', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_meeting_person_person_id', 'meeting_person', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_person_meeting_id', 'meeting_person', 'meeting_id', 'meeting', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_created_by', 'meeting_person', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_updated_by', 'meeting_person', 'updated_by', 'user', 'id', 'NO ACTION');
        
        $this->addColumn('meeting', 'panel_id', $this->integer());
        $this->addCommentOnColumn('meeting', 'panel_id', 'Panel');
        $this->addForeignKey('fk_meeting_panel_id', 'meeting', 'panel_id', 'panel', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropForeignKey('fk_meeting_panel_id', 'meeting');
        $this->dropColumn('meeting', 'panel_id');
        
        $this->dropTable('meeting_person');
        $this->dropTable('submission_status_history');
        $this->dropColumn('submission_type', 'internal');        
        $this->dropColumn('submission', 'correspondence_at');
        $this->dropColumn('submission', 'correspondence_no');

        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180212_104712_alter_submission cannot be reverted.\n";

      return false;
      }
     */
}
