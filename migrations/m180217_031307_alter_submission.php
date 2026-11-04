<?php

use yii\db\Migration;

/**
 * Class m180217_031307_alter_submission
 */
class m180217_031307_alter_submission extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission', 'is_fullboard', $this->boolean());
        $this->addColumn('submission', 'fullboard_by', $this->integer());
        $this->addColumn('submission', 'fullboard_at', $this->dateTime());
        
        $this->addCommentOnColumn('submission', 'is_fullboard', 'ต้องเข้าพิจารณาเต็มรูปแบบ');
        $this->addCommentOnColumn('submission', 'fullboard_by', 'ผู้พิจารณา');
        $this->addCommentOnColumn('submission', 'fullboard_at', 'พิจารณาเมื่อ');
        
        $this->addForeignKey('fk_submission_fullboard_by', 'submission', 'fullboard_by', 'user', 'id');
        
        $this->addColumn('submission_type', 'resolution_label', $this->string());
        $this->addCommentOnColumn('submission_type', 'resolution_label', 'รับรองหรือรับทราบ');
        
        $this->addColumn('agenda', 'sort', $this->integer());
        $this->addColumn('agenda', 'parent_id', $this->integer());
        $this->addForeignKey('fk_agenda_parent_id', 'agenda', 'parent_id', 'agenda', 'id');
        
        $this->addColumn('meeting_agenda', 'sort', $this->integer());
        $this->alterColumn('meeting_agenda', 'description', 'mediumtext');
        $this->alterColumn('meeting_agenda', 'conclusion', 'mediumtext');
        $this->alterColumn('meeting_agenda', 'summary', 'mediumtext');
        
        $this->createTable('agenda_submission_type', [
            'id' => $this->primaryKey(),
            'agenda_id' => $this->integer(),
            'submission_type_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('agenda_submission_type', 'agenda_id', 'วาระ');
        $this->addCommentOnColumn('agenda_submission_type', 'submission_type_id', 'ประเภทการขอรับพิจารณา');
        $this->addCommentOnColumn('agenda_submission_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('agenda_submission_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('agenda_submission_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('agenda_submission_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('agenda_submission_type', 'updated_at', 'ปรับปรุงเมื่อ');
        
        $this->addForeignKey('fk_agenda_submission_type_agenda_id', 'agenda_submission_type', 'agenda_id', 'agenda', 'id');
        $this->addForeignKey('fk_agenda_submission_type_submission_type_id', 'agenda_submission_type', 'submission_type_id', 'submission_type', 'id');
        $this->addForeignKey('fk_agenda_submission_type_created_by', 'agenda_submission_type', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_agenda_submission_type_updated_by', 'agenda_submission_type', 'updated_by', 'user', 'id');
        
        $this->alterColumn('person', 'bank_account_number', $this->string());
        $this->addColumn('person', 'is_external', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('person', 'reg_code', $this->string());
        $this->addCommentOnColumn('person', 'is_external', 'เป็นบุคคลภายนอกหรือไม่');
        $this->addCommentOnColumn('person', 'reg_code', 'รหัสสำหรับลงทะเบียน');
        
        $this->addColumn('role', 'is_meeting_role', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('role', 'is_meeting_role', 'เป็นตำแหน่งในการประชุมหรือไม่');
        $this->addColumn('meeting_person', 'role_name', $this->string());
        $this->addCommentOnColumn('meeting_person', 'role_name', 'ตำแหน่งในที่ประชุม');
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('meeting_person', 'role_name');
        $this->dropColumn('role', 'is_meeting_role');
        
        $this->dropColumn('person', 'reg_code');
        $this->dropColumn('person', 'is_external');
        $this->alterColumn('person', 'bank_account_number', $this->integer());
        
        $this->dropTable('agenda_submission_type');
        
        $this->alterColumn('meeting_agenda', 'summary', $this->string());
        $this->alterColumn('meeting_agenda', 'conclusion', $this->string());
        $this->alterColumn('meeting_agenda', 'description', $this->string());
        $this->dropColumn('meeting_agenda', 'sort');
        
        $this->dropForeignKey('fk_agenda_parent_id', 'agenda');
        $this->dropColumn('agenda', 'parent_id');
        $this->dropColumn('agenda', 'sort');
        $this->dropColumn('submission_type', 'resolution_label', $this->string());
        
        $this->dropForeignKey('fk_submission_fullboard_by', 'submission');
        $this->dropColumn('submission', 'fullboard_at');
        $this->dropColumn('submission', 'fullboard_by');
        $this->dropColumn('submission', 'is_fullboard');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180217_031307_alter_submission cannot be reverted.\n";

      return false;
      }
     */
}
