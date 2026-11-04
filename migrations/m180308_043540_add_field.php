<?php

use yii\db\Migration;

/**
 * Class m180308_043540_add_field
 */
class m180308_043540_add_field extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('person', 'cv_file', $this->string());
        $this->addCommentOnColumn('person', 'cv_file', 'ไฟล์ประวัติ');

        $this->createTable('submission_project_researcher', [
            'id' => $this->primaryKey(),
            'project_researcher_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'status' => $this->string(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_project_researcher', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_project_researcher', 'project_researcher_id', 'ผู้ร่วมวิจัย');
        $this->addCommentOnColumn('submission_project_researcher', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_project_researcher', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission_project_researcher', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_project_researcher', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_project_researcher', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_project_researcher', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_project_researcher', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_project_researcher', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_project_researcher_id', 'submission_project_researcher', ['id']);
        $this->addForeignKey('fk_submission_project_researcher_submission_id', 'submission_project_researcher', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_researcher_project_researcher_id', 'submission_project_researcher', 'project_researcher_id', 'project_researcher', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_researcher_user1', 'submission_project_researcher', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_researcher_user2', 'submission_project_researcher', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_result', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'result_type' => $this->string(),
            'result_text' => $this->string(),
            'level' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_result', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_result', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_result', 'result_type', 'ประเภท');
        $this->addCommentOnColumn('submission_result', 'result_text', 'text');
        $this->addCommentOnColumn('submission_result', 'level', 'ลำดับ');
        $this->addCommentOnColumn('submission_result', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_result', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_result', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_result', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_result', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_result_id', 'submission_result', ['id']);
        $this->addForeignKey('fk_submission_result_submission_id', 'submission_result', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_result_user1', 'submission_result', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_result_user2', 'submission_result', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('result_document', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'resolution' => $this->string(),
            'committee_resolution' => $this->string(),
            'template_file' => $this->string(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('result_document', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('result_document', 'name', 'ชื่อ');
        $this->addCommentOnColumn('result_document', 'committee_resolution', 'ผลมติของกรรมการ');
        $this->addCommentOnColumn('result_document', 'resolution', 'ผลมติ');
        $this->addCommentOnColumn('result_document', 'template_file', 'ไฟล์ต้นแบบ');
        $this->addCommentOnColumn('result_document', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('result_document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('result_document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('result_document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('result_document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('result_document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_result_document_id', 'result_document', ['id']);
        $this->addForeignKey('fk_result_document_user1', 'result_document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_result_document_user2', 'result_document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('agenda_result_document', [
            'id' => $this->primaryKey(),
            'agenda_id' => $this->integer(),
            'result_document_id' => $this->integer(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('agenda_result_document', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('agenda_result_document', 'agenda_id', 'วาระการประชุม');
        $this->addCommentOnColumn('agenda_result_document', 'result_document_id', 'ไฟล์ต้นแบบ');
        $this->addCommentOnColumn('agenda_result_document', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('agenda_result_document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('agenda_result_document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('agenda_result_document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('agenda_result_document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('agenda_result_document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_agenda_result_document_id', 'agenda_result_document', ['id']);
        $this->addForeignKey('fk_agenda_result_document_agenda_id', 'agenda_result_document', 'agenda_id', 'agenda', 'id', 'NO ACTION');
        $this->addForeignKey('fk_agenda_result_document_result_document_id', 'agenda_result_document', 'result_document_id', 'result_document', 'id', 'NO ACTION');
        $this->addForeignKey('fk_agenda_result_document_user1', 'agenda_result_document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_agenda_result_document_user2', 'agenda_result_document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_result_document', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'result_document_id' => $this->integer(),
            'document_file' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_result_document', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_result_document', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_result_document', 'result_document_id', 'ไฟล์ต้นแบบ');
        $this->addCommentOnColumn('submission_result_document', 'document_file', 'ไฟล์ที่ upload ขึ้นมา');
        $this->addCommentOnColumn('submission_result_document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_result_document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_result_document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_result_document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_result_document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_result_document_id', 'submission_result_document', ['id']);
        $this->addForeignKey('fk_submission_result_document_submission_id', 'submission_result_document', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_result_document_result_document_id', 'submission_result_document', 'result_document_id', 'result_document', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_result_document_user1', 'submission_result_document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_result_document_user2', 'submission_result_document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_committee_revise', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'resolution' => $this->string(),
            'is_meeting' => $this->boolean()->notNull()->defaultValue(FALSE),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_committee_revise', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_committee_revise', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_committee_revise', 'submission_committee_id', 'กรรมการ');
        $this->addCommentOnColumn('submission_committee_revise', 'resolution', 'ผลประเมิน');
        $this->addCommentOnColumn('submission_committee_revise', 'is_meeting', 'เข้าประชุม');
        $this->addCommentOnColumn('submission_committee_revise', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_committee_revise', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_committee_revise', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_committee_revise', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_committee_revise', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_committee_revise_id', 'submission_committee_revise', ['id']);
        $this->addForeignKey('fk_submission_committee_revise_submission_id', 'submission_committee_revise', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_revise_document_id', 'submission_committee_revise', 'submission_committee_id', 'submission_committee', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_revise_user1', 'submission_committee_revise', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_revise_user2', 'submission_committee_revise', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_committee_revise_text', [
            'id' => $this->primaryKey(),
            'submission_committee_revise_id' => $this->integer(),
            'revise_text' => $this->string(),
            'revise_type' => $this->string(),
            'level' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_committee_revise_text', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_committee_revise_text', 'submission_committee_revise_id', 'กรรมการประเมิน');
        $this->addCommentOnColumn('submission_committee_revise_text', 'revise_text', 'ผลกรรมการประเมินแก้ไข');
        $this->addCommentOnColumn('submission_committee_revise_text', 'revise_type', 'ประเภทผลกรรมการประเมินแก้ไข');
        $this->addCommentOnColumn('submission_committee_revise_text', 'level', 'ลำดับ');
        $this->addCommentOnColumn('submission_committee_revise_text', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_committee_revise_text', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_committee_revise_text', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_committee_revise_text', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_committee_revise_text', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_committee_revise_text_id', 'submission_committee_revise_text', ['id']);
        $this->addForeignKey('fk_submission_committee_revise_submission_committee_revise_id', 'submission_committee_revise_text', 'submission_committee_revise_id', 'submission_committee_revise', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_revise_text_user1', 'submission_committee_revise_text', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_revise_text_user2', 'submission_committee_revise_text', 'updated_by', 'user', 'id', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180308_043540_add_field cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180308_043540_add_field cannot be reverted.\n";

      return false;
      }
     */
}
