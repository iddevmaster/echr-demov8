<?php

use yii\db\Migration;

/**
 * Class m171206_065614_add_new_table
 */
class m171206_065614_add_new_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() {

        $this->createTable('organization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('organization', 'id', 'รหัสองค์กร');
        $this->addCommentOnColumn('organization', 'name', 'องค์กร');
        $this->addCommentOnColumn('organization', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('organization', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('organization', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('organization', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('organization', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_organization_name', 'organization', ['name']);
        $this->addForeignKey('fk_organization_user1', 'organization', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_organization_user2', 'organization', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('panel', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('panel', 'id', 'รหัสกลุ่มของผู้ปฏิบัติงาน');
        $this->addCommentOnColumn('panel', 'name', 'กลุ่มของผู้ปฏิบัติงาน');
        $this->addCommentOnColumn('panel', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('panel', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('panel', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('panel', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('panel', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_panel_name', 'panel', ['name']);
        $this->addForeignKey('fk_panel_user1', 'panel', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_panel_user2', 'panel', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('funding_source', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('funding_source', 'id', 'รหัสแหล่งทุน');
        $this->addCommentOnColumn('funding_source', 'name', 'แหล่งทุน');
        $this->addCommentOnColumn('funding_source', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('funding_source', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('funding_source', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('funding_source', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('funding_source', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_funding_source_name', 'funding_source', ['name']);
        $this->addForeignKey('fk_funding_source_user1', 'funding_source', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_funding_source_user2', 'funding_source', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'number' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('document', 'id', 'รหัสทะเบียนเอกสารที่ต้องส่งในแต่ละประเภทโครงการ');
        $this->addCommentOnColumn('document', 'name', 'ทะเบียนเอกสารที่ต้องส่งในแต่ละประเภทโครงการ');
        $this->addCommentOnColumn('document', 'number', 'จำนวน');
        $this->addCommentOnColumn('document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_document_name', 'document', ['name']);
        $this->addForeignKey('fk_document_user1', 'document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_document_user2', 'document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('volunteer_number', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('volunteer_number', 'id', 'รหัสอาสาสมัคร');
        $this->addCommentOnColumn('volunteer_number', 'name', 'อาสาสมัคร');
        $this->addCommentOnColumn('volunteer_number', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('volunteer_number', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('volunteer_number', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('volunteer_number', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('volunteer_number', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_volunteer_number_name', 'volunteer_number', ['name']);
        $this->addForeignKey('fk_volunteer_number_user1', 'volunteer_number', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_volunteer_number_user2', 'volunteer_number', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_type_group', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_type_group', 'id', 'รหัสกลุ่มประเภทโครงการวิจัย');
        $this->addCommentOnColumn('submission_type_group', 'name', 'กลุ่มประเภทโครงการวิจัย');
        $this->addCommentOnColumn('submission_type_group', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type_group', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type_group', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type_group', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type_group', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_type_group_name', 'submission_type_group', ['name']);
        $this->addForeignKey('fk_submission_type_group_user1', 'submission_type_group', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_group_user2', 'submission_type_group', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('department', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'address' => $this->string(),
            'tel' => $this->string(),
            'email' => $this->string(),
            'website' => $this->string(),
            'organization_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('department', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('department', 'name', 'หน่วยงาน');
        $this->addCommentOnColumn('department', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('department', 'address', 'ที่อยู่');
        $this->addCommentOnColumn('department', 'tel', 'เบอร์โทร');
        $this->addCommentOnColumn('department', 'email', 'อีเมลล์');
        $this->addCommentOnColumn('department', 'website', 'เว็บไซต์');
        $this->addCommentOnColumn('department', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('department', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('department', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('department', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('department', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_department_name', 'department', ['name']);
        $this->addForeignKey('fk_department_organization_id', 'department', 'organization_id', 'organization', 'id', 'NO ACTION');
        $this->addForeignKey('fk_department_user1', 'department', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_department_user2', 'department', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(FALSE),
            'is_fullboard' => $this->boolean()->notNull()->defaultValue(FALSE),
            'is_exemption' => $this->boolean()->notNull()->defaultValue(FALSE),
            'agenda_title' => $this->string(),
            'submission_type_group_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_type', 'id', 'รหัสประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_type', 'name', 'ประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_type', 'is_new', 'เป็นโครงการใหม่หรือไม่   0=ไม่ 1=ใช่');
        $this->addCommentOnColumn('submission_type', 'is_fullboard', 'เป็น Fullboard หรือไม่ 0=ไม่ 1=ใช่');
        $this->addCommentOnColumn('submission_type', 'is_exemption', 'เป็น exemption หรือไม่ 0=ไม่ 1=ใช่');
        $this->addCommentOnColumn('submission_type', 'agenda_title', 'หัวข้อวาระ');
        $this->addCommentOnColumn('submission_type', 'submission_type_group_id', 'ประเภทกลุ่มกานำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_type_name', 'submission_type', ['name']);
        $this->addForeignKey('fk_submission_type_submission_type_group_id', 'submission_type', 'submission_type_group_id', 'submission_type_group', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_user1', 'submission_type', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_user2', 'submission_type', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('person_submission_type', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'submission_type_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('person_submission_type', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('person_submission_type', 'person_id', 'นักวิจัย');
        $this->addCommentOnColumn('person_submission_type', 'submission_type_id', 'ประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('person_submission_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('person_submission_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('person_submission_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('person_submission_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('person_submission_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_person_submission_type_person_id', 'person_submission_type', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_submission_type_submission_type_id', 'person_submission_type', 'submission_type_id', 'submission_type', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_submission_type_user1', 'person_submission_type', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_submission_type_user2', 'person_submission_type', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('document_submission_type', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer(),
            'document_id' => $this->integer(),
            'is_require' => $this->boolean()->notNull()->defaultValue(FALSE),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('document_submission_type', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('document_submission_type', 'submission_type_id', 'ประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('document_submission_type', 'document_id', 'ประเภทเอกสาร');
        $this->addCommentOnColumn('document_submission_type', 'is_require', 'จำเป็นหรือไม่ 0=ไม่จำเป็น   1=จำเป็น');
        $this->addCommentOnColumn('document_submission_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('document_submission_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('document_submission_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('document_submission_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('document_submission_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_document_submission_type_submission_type_id', 'document_submission_type', 'submission_type_id', 'submission_type', 'id', 'NO ACTION');
        $this->addForeignKey('fk_document_submission_type_document_id', 'document_submission_type', 'document_id', 'document', 'id', 'NO ACTION');
        $this->addForeignKey('fk_document_submission_type_user1', 'document_submission_type', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_document_submission_type_user2', 'document_submission_type', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('project', [
            'id' => $this->primaryKey(),
            'name_thai' => $this->string(),
            'name_eng' => $this->string(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'funding_source_id' => $this->integer(),
            'funding_source_description' => $this->string(),
            'is_child_project' => $this->boolean()->notNull()->defaultValue(FALSE),
            'progress_period' => $this->integer(),
            'remark' => $this->string(),
            'certified_date' => $this->dateTime(),
            'status' => $this->integer(),
            'project_code' => $this->string(),
            'panel_id' => $this->integer(),
            'organization_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('project', 'name_thai', 'ชื่อโครงการภาษาไทย');
        $this->addCommentOnColumn('project', 'name_eng', 'ชื่อโครงการภาษาอังกฤษ');
        $this->addCommentOnColumn('project', 'start_date', 'วันที่เริ่มต้น');
        $this->addCommentOnColumn('project', 'end_date', 'วันที่สิ้นสุด');
        $this->addCommentOnColumn('project', 'funding_source_id', 'ทุนวิจัย');
        $this->addCommentOnColumn('project', 'funding_source_description', 'รายละเอียดทุน');
        $this->addCommentOnColumn('project', 'is_child_project', 'เป็นโครงการเด็กหรือไม่ 0=ไม่ 1= ใช่');
        $this->addCommentOnColumn('project', 'progress_period', 'ระยะเวลาในการติดตาม');
        $this->addCommentOnColumn('project', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('project', 'certified_date', 'วันที่รับรอง');
        $this->addCommentOnColumn('project', 'status', 'สถานะ');
        $this->addCommentOnColumn('project', 'project_code', 'เลขที่โครงการ');
        $this->addCommentOnColumn('project', 'panel_id', 'กลุ่มผู้ปฏิบัติงาน');
        $this->addCommentOnColumn('project', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('project', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_project_name_thai', 'project', ['name_thai']);
        $this->addForeignKey('fk_project_panel_id', 'project', 'panel_id', 'panel', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_funding_source_id', 'project', 'funding_source_id', 'funding_source', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_organization_id', 'project', 'organization_id', 'organization', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_user1', 'project', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_user2', 'project', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('project_researcher', [
            'id' => $this->primaryKey(),
            'is_leader' => $this->boolean()->notNull()->defaultValue(FALSE),
            'person_id' => $this->integer(),
            'project_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_researcher', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('project_researcher', 'is_leader', 'หัวหน้าโครงการ');
        $this->addCommentOnColumn('project_researcher', 'person_id', 'นักวิจัย');
        $this->addCommentOnColumn('project_researcher', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('project_researcher', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_researcher', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_researcher', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_researcher', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_researcher', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_project_researcher_is_leader', 'project_researcher', ['is_leader']);
        $this->addForeignKey('fk_project_researcher_person_id', 'project_researcher', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_researcher_project_id', 'project_researcher', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_researcher_user1', 'project_researcher', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_researcher_user2', 'project_researcher', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission', [
            'id' => $this->primaryKey(),
            'remark' => $this->string(),
            'certified_date' => $this->dateTime(),
            'status' => $this->integer(),
            'project_id' => $this->integer(),
            'organization_id' => $this->integer(),
            'full_doc_file' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('submission', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission', 'certified_date', 'วันที่รับรอง');
        $this->addCommentOnColumn('submission', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('submission', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('submission', 'full_doc_file', 'ไฟล์รวมเอกสาร');
        $this->addCommentOnColumn('submission', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_submission_project_id', 'submission', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_organization_id', 'submission', 'organization_id', 'organization', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_user1', 'submission', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_user2', 'submission', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_revise', [
            'id' => $this->primaryKey(),
            'remark' => $this->string(),
            'submission_id' => $this->integer(),
            'send_date' => $this->dateTime(),
            'return_date' => $this->dateTime(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_revise', 'id', 'รหัสกำหนดวันแก้ไข');
        $this->addCommentOnColumn('submission_revise', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_revise', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_revise', 'send_date', 'วันที่สง');
        $this->addCommentOnColumn('submission_revise', 'return_date', 'วันที่ตอบกลับ');
        $this->addCommentOnColumn('submission_revise', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_revise', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_revise', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_revise', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_revise', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_revise_send_date', 'submission_revise', ['send_date']);
        $this->addForeignKey('fk_submission_revise_submission_id', 'submission_revise', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_revise_user1', 'submission_revise', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_revise_user2', 'submission_revise', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_volunteer_number', [
            'id' => $this->primaryKey(),
            'value' => $this->integer(),
            'volunteer_number_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'project_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_volunteer_number', 'id', 'รหัส');
        $this->addCommentOnColumn('submission_volunteer_number', 'value', 'จำนวนอาสาสมัคร');
        $this->addCommentOnColumn('submission_volunteer_number', 'volunteer_number_id', 'อาสาสมัคร');
        $this->addCommentOnColumn('submission_volunteer_number', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_volunteer_number', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('submission_volunteer_number', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_volunteer_number', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_volunteer_number', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_volunteer_number', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_volunteer_number', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_volunteer_number_value', 'submission_volunteer_number', ['value']);
        $this->addForeignKey('fk_submission_volunteer_number_uvolunteer_number_id', 'submission_volunteer_number', 'volunteer_number_id', 'volunteer_number', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_volunteer_number_submission_id', 'submission_volunteer_number', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_volunteer_number_project_id', 'submission_volunteer_number', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_volunteer_number_user1', 'submission_volunteer_number', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_volunteer_number_user2', 'submission_volunteer_number', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_committee', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(),
            'person_id' => $this->integer(),
            'project_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'submit_date' => $this->dateTime(),
            'return_date' => $this->dateTime(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_committee', 'id', 'รหัส');
        $this->addCommentOnColumn('submission_committee', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission_committee', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_committee', 'person_id', 'กรรมการ/เลขา');
        $this->addCommentOnColumn('submission_committee', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('submission_committee', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_committee', 'submit_date', 'วันที่ส่งเอกสารไปให้กรรมการอ่าน');
        $this->addCommentOnColumn('submission_committee', 'return_date', 'วันที่ได้รับ comment กลับมา');
        $this->addCommentOnColumn('submission_committee', 'remark', 'หมายเหตุ comment');
        $this->addCommentOnColumn('submission_committee', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_committee', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_committee', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_committee', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_committee', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_committee_submit_date', 'submission_committee', ['submit_date']);
        $this->addForeignKey('fk_submission_committee_person_id', 'submission_committee', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_project_id', 'submission_committee', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_submission_id', 'submission_committee', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_user1', 'submission_committee', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_user2', 'submission_committee', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_committee_document', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'file_name' => $this->string(),
            'remark' => $this->string(),
            'submission_committee_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_committee_document', 'id', 'รหัส');
        $this->addCommentOnColumn('submission_committee_document', 'name', 'ชื่อเอกสาร');
        $this->addCommentOnColumn('submission_committee_document', 'file_name', 'ไฟล์เอกสาร');
        $this->addCommentOnColumn('submission_committee_document', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_committee_document', 'submission_committee_id', 'กรรมการในโครงการวิจัย');
        $this->addCommentOnColumn('submission_committee_document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_committee_document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_committee_document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_committee_document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_committee_document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_committee_document_name', 'submission_committee_document', ['name']);
        $this->addForeignKey('fk_submission_committee_document_submission_committee_id', 'submission_committee_document', 'submission_committee_id', 'submission_committee', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_document_user1', 'submission_committee_document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_committee_document_user2', 'submission_committee_document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_document', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'file_name' => $this->string(),
            'project_id' => $this->integer(),
            'status' => $this->integer(),
            'remark' => $this->string(),
            'document_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_document', 'id', 'รหัส');
        $this->addCommentOnColumn('submission_document', 'name', 'ชื่อเอกสาร');
        $this->addCommentOnColumn('submission_document', 'file_name', 'ไฟล์เอกสาร');
        $this->addCommentOnColumn('submission_document', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('submission_document', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission_document', 'document_id', 'ประเภทเอกสาร');
        $this->addCommentOnColumn('submission_document', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_document', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_document', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_document', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_document', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_document', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_document', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_document_name', 'submission_document', ['name']);
        $this->addForeignKey('fk_submission_document_project_id', 'submission_document', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_document_document_id', 'submission_document', 'document_id', 'document', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_document_submission_id', 'submission_document', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_document_user1', 'submission_document', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_document_user2', 'submission_document', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('submission_type_volunteer_number', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'submission_type_id' => $this->integer(),
            'volunteer_number_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_type_volunteer_number', 'id', 'รหัส');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'name', 'หัวข้ออาสาสมัคร');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'submission_type_id', 'ประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'volunteer_number_id', 'อาสาสมัคร');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type_volunteer_number', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_type_volunteer_number_name', 'submission_type_volunteer_number', ['name']);
        $this->addForeignKey('fk_submission_type_volunteer_number_submission_type_id', 'submission_type_volunteer_number', 'submission_type_id', 'submission_type', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_volunteer_number_volunteer_number_id', 'submission_type_volunteer_number', 'volunteer_number_id', 'volunteer_number', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_volunteer_number_user1', 'submission_type_volunteer_number', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_type_volunteer_number_user2', 'submission_type_volunteer_number', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('meeting', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'start_time' => $this->time(),
            'end_time' => $this->time(),
            'status' => $this->string(),
            'is_public' => $this->boolean()->notNull()->defaultValue(FALSE),
            'department_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'organization_id' => $this->integer(),
            'meeting_no' => $this->integer(),
            'year' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting', 'id', 'รหัส');
        $this->addCommentOnColumn('meeting', 'title', 'ชื่อการประชุม');
        $this->addCommentOnColumn('meeting', 'start_date', 'วันที่ประชุม');
        $this->addCommentOnColumn('meeting', 'end_date', 'วันที่ปิดประชุม');
        $this->addCommentOnColumn('meeting', 'start_time', 'เวลาเปิดประชุม');
        $this->addCommentOnColumn('meeting', 'end_time', 'เวลาปิดประชุม');
        $this->addCommentOnColumn('meeting', 'status', 'สถานะ');
        $this->addCommentOnColumn('meeting', 'is_public', 'เปิดเผยแพร่');
        $this->addCommentOnColumn('meeting', 'department_id', 'หน่วยงาน');
        $this->addCommentOnColumn('meeting', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('meeting', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('meeting', 'meeting_no', 'ครั้งที่');
        $this->addCommentOnColumn('meeting', 'year', 'ปี');
        $this->addCommentOnColumn('meeting', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_title', 'meeting', ['title']);
        $this->addForeignKey('fk_meeting_department_id', 'meeting', 'department_id', 'department', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_submission_id', 'meeting', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_organization_id', 'meeting', 'organization_id', 'organization', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_user1', 'meeting', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_user2', 'meeting', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('register_transaction', [
            'id' => $this->primaryKey(),
            'meeting_id' => $this->integer(),
            'person_id' => $this->integer(),
            'invited_at' => $this->dateTime(),
            'registered_at' => $this->dateTime(),
            'out_at' => $this->dateTime(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('register_transaction', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('register_transaction', 'meeting_id', 'การประชุม');
        $this->addCommentOnColumn('register_transaction', 'person_id', 'ผู้เข้าร่วมประชุม');
        $this->addCommentOnColumn('register_transaction', 'invited_at', 'วันที่เชิญประชุม');
        $this->addCommentOnColumn('register_transaction', 'registered_at', 'วันเวลาเข้าห้องประชุม');
        $this->addCommentOnColumn('register_transaction', 'out_at', 'วันเวลาออกจากห้องประชุม');
        $this->addCommentOnColumn('register_transaction', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('register_transaction', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('register_transaction', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('register_transaction', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('register_transaction', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_register_transaction_meeting_id', 'register_transaction', ['meeting_id']);
        $this->addForeignKey('fk_register_transaction_meeting_id', 'register_transaction', 'meeting_id', 'meeting', 'id', 'NO ACTION');
        $this->addForeignKey('fk_register_transaction_person_id', 'register_transaction', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_register_transaction_user1', 'register_transaction', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_register_transaction_user2', 'register_transaction', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('alert_peroid', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'remark' => $this->string(),
            'active' => $this->boolean()->notNull()->defaultValue(FALSE),
            'error_day' => $this->integer(),
            'warning_day' => $this->integer(),
            'normal_day' => $this->integer(),
            'description' => $this->string(),
            'submission_type_group_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('alert_peroid', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('alert_peroid', 'name', 'ชื่อการแจ้งเตือน');
        $this->addCommentOnColumn('alert_peroid', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('alert_peroid', 'active', 'ทำงาน');
        $this->addCommentOnColumn('alert_peroid', 'error_day', 'จำนวนวันที่เกินการแจ้งเตือน');
        $this->addCommentOnColumn('alert_peroid', 'warning_day', 'จำนวนวันที่จะแจ้งเตือน');
        $this->addCommentOnColumn('alert_peroid', 'normal_day', 'จำนวนวันปกติ');
        $this->addCommentOnColumn('alert_peroid', 'description', 'รายละเอียด');
        $this->addCommentOnColumn('alert_peroid', 'submission_type_group_id', 'กลุ่มประเภทการนำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('alert_peroid', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('alert_peroid', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('alert_peroid', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('alert_peroid', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('alert_peroid', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_alert_peroid_name', 'alert_peroid', ['name']);
        $this->addForeignKey('fk_alert_peroid_submission_type_group_id', 'alert_peroid', 'submission_type_group_id', 'submission_type_group', 'id', 'NO ACTION');
        $this->addForeignKey('fk_alert_peroid_user1', 'alert_peroid', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_alert_peroid_user2', 'alert_peroid', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('notification_history', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(),
            'alert_peroid_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'person_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('notification_history', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('notification_history', 'status', 'สถานะ');
        $this->addCommentOnColumn('notification_history', 'alert_peroid_id', 'ระยะแจ้งเตือน');
        $this->addCommentOnColumn('notification_history', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('notification_history', 'person_id', 'บุคลากร');
        $this->addCommentOnColumn('notification_history', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('notification_history', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('notification_history', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('notification_history', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('notification_history', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_notification_history_status', 'notification_history', ['status']);
        $this->addForeignKey('fk_notification_history_alert_peroid_id', 'notification_history', 'alert_peroid_id', 'alert_peroid', 'id', 'NO ACTION');
        $this->addForeignKey('fk_notification_history_submission_id', 'notification_history', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_notification_history_person_id', 'notification_history', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_notification_history_user1', 'notification_history', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_notification_history_user2', 'notification_history', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('meeting_agenda', [
            'id' => $this->primaryKey(),
            'meeting_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->string(),
            'conclusion' => $this->string(),
            'summary' => $this->string(),
            'sort_label' => $this->string(),
            'project_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'approved_at' => $this->dateTime(),
            'approved_by' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting_agenda', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('meeting_agenda', 'meeting_id', 'การประชุม');
        $this->addCommentOnColumn('meeting_agenda', 'title', 'หัวข้อวาระ');
        $this->addCommentOnColumn('meeting_agenda', 'description', 'รายละเอียด');
        $this->addCommentOnColumn('meeting_agenda', 'conclusion', 'มติที่ประชุม');
        $this->addCommentOnColumn('meeting_agenda', 'summary', 'สรุปประชุม');
        $this->addCommentOnColumn('meeting_agenda', 'sort_label', 'ระดับ');
        $this->addCommentOnColumn('meeting_agenda', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('meeting_agenda', 'submission_id', 'นำเสนอโครงการวิจัย');
        $this->addCommentOnColumn('meeting_agenda', 'parent_id', 'ลำดับวาระ');
        $this->addCommentOnColumn('meeting_agenda', 'approved_at', 'วันเวลาตอบรับ');
        $this->addCommentOnColumn('meeting_agenda', 'approved_by', 'ผู้ตอบรับ');
        $this->addCommentOnColumn('meeting_agenda', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_agenda', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_agenda', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_agenda', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_agenda', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_agenda_title', 'meeting_agenda', ['title']);
        $this->addForeignKey('fk_meeting_agenda_meeting_id', 'meeting_agenda', 'meeting_id', 'meeting', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_agenda_project_id', 'meeting_agenda', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_agenda_submission_id', 'meeting_agenda', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_agenda_approved_by', 'meeting_agenda', 'approved_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_agenda_user1', 'meeting_agenda', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_agenda_user2', 'meeting_agenda', 'updated_by', 'user', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('meeting_agenda');
        $this->dropTable('notification_history');
        $this->dropTable('alert_peroid');
        $this->dropTable('register_transaction');
        $this->dropTable('meeting');
        $this->dropTable('submission_type_volunteer_number');
        $this->dropTable('submission_document');
        $this->dropTable('submission_committee_document');
        $this->dropTable('submission_committee');
        $this->dropTable('submission_volunteer_number');
        $this->dropTable('submission_revise');
        $this->dropTable('submission');
        $this->dropTable('project_researcher');
        $this->dropTable('project');
        $this->dropTable('document_submission_type');
        $this->dropTable('person_submission_type');
        $this->dropTable('submission_type');
        $this->dropTable('department');
        $this->dropTable('submission_type_group');
        $this->dropTable('volunteer_number');
        $this->dropTable('document');
        $this->dropTable('funding_source');
        $this->dropTable('panel');
        $this->dropTable('organization');
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171206_065614_add_new_table cannot be reverted.\n";

        return false;
    }
    */
}
