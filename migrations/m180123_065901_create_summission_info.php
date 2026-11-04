<?php

use yii\db\Migration;

/**
 * Class m180123_065901_create_summission_info
 */
class m180123_065901_create_summission_info extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('extra_info', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addCommentOnColumn('extra_info', 'id', 'รหัสข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('extra_info', 'name', 'ชื่อข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('extra_info', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('extra_info', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('extra_info', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('extra_info', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('extra_info', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_extra_info_name', 'extra_info', ['name']);
        $this->addForeignKey('fk_extra_info_created_by', 'extra_info', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_extra_info_updated_by', 'extra_info', 'updated_by', 'user', 'id');
        
        $this->createTable('submission_type_extra_info', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer(),
            'extra_info_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('submission_type_extra_info', 'id', 'รหัสข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('submission_type_extra_info', 'submission_type_id', 'ประเภทการส่งโครงการ');
        $this->addCommentOnColumn('submission_type_extra_info', 'extra_info_id', 'ข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('submission_type_extra_info', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type_extra_info', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type_extra_info', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type_extra_info', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type_extra_info', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_submission_type_extra_info_submission_type_id', 'submission_type_extra_info', 'submission_type_id', 'submission_type', 'id');
        $this->addForeignKey('fk_submission_type_extra_info_extra_info_id', 'submission_type_extra_info', 'extra_info_id', 'extra_info', 'id');
        $this->addForeignKey('fk_submission_type_extra_info_created_by', 'submission_type_extra_info', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_type_extra_info_updated_by', 'submission_type_extra_info', 'updated_by', 'user', 'id');
        
        $this->createTable('submission_extra_info', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'extra_info_id' => $this->integer(),
            'value' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('submission_extra_info', 'id', 'รหัสข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('submission_extra_info', 'submission_id', 'การส่งโครงการ');
        $this->addCommentOnColumn('submission_extra_info', 'extra_info_id', 'ข้อมูลเพิ่มเติม');
        $this->addCommentOnColumn('submission_extra_info', 'value', 'ข้อมูล');
        $this->addCommentOnColumn('submission_extra_info', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_extra_info', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_extra_info', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_extra_info', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_extra_info', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_submission_extra_info_submission_id', 'submission_extra_info', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_submission_extra_info_extra_info_id', 'submission_extra_info', 'extra_info_id', 'extra_info', 'id');
        $this->addForeignKey('fk_submission_extra_info_created_by', 'submission_extra_info', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_extra_info_updated_by', 'submission_extra_info', 'updated_by', 'user', 'id');
        
        $this->addColumn('person_role_panel', 'is_regular', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('person_role_panel', 'is_regular', 'ประจำหรือไม่');
        
        $this->addColumn('submission', 'resolution', $this->string());
        $this->addColumn('submission', 'ref_submission_id', $this->integer());
        
        $this->addCommentOnColumn('submission', 'resolution', 'มติที่ประชุม');
        $this->addCommentOnColumn('submission', 'ref_submission_id', 'อ้างอิงการส่ง');
        
        $this->createIndex('idx_submission_resolution', 'submission', 'resolution');
        $this->addForeignKey('fk_submission_submission_id', 'submission', 'ref_submission_id', 'submission', 'id');
        
        $this->createTable('division', [
            'id' => $this->primaryKey(),
            'department_id' => $this->integer(),
            'name' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('division', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('division', 'department_id', 'คณะ');
        $this->addCommentOnColumn('division', 'name', 'ชื่อภาควิชา');
        $this->addCommentOnColumn('division', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('division', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('division', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('division', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('division', 'updated_at', 'ปรับปรุงเมื่อ');
        
        $this->addForeignKey('fk_division_department_id', 'division', 'department_id', 'department', 'id');
        $this->addForeignKey('fk_division_created_by', 'division', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_division_updated_by', 'division', 'updated_by', 'user', 'id');
        
        $this->addColumn('department', 'job_category_id', $this->integer());
        $this->addCommentOnColumn('department', 'job_category_id', 'ประเภทอาชีพ');
        $this->addForeignKey('fk_department_job_category_id', 'department', 'job_category_id', 'job_category', 'id');
        
        $this->addColumn('organization', 'is_internal', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('organization', 'is_internal', 'หน่วยงานภายใน');
        
        $this->addColumn('person', 'division_id', $this->integer());
        $this->addColumn('person', 'gender', $this->integer());
        $this->addCommentOnColumn('person', 'division_id', 'ภาควิชา');
        $this->addCommentOnColumn('person', 'gender', 'เพศ');
        $this->addForeignKey('fk_person_division_id', 'person', 'division_id', 'division', 'id');
        
        $this->createTable('setting', [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'name' => $this->string(),
            'value' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('setting', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('setting', 'key', 'รหัสการตั้งค่า');
        $this->addCommentOnColumn('setting', 'name', 'ชื่อการตั้งค่า');
        $this->addCommentOnColumn('setting', 'value', 'ค่าการตั้งค่า');
        $this->addCommentOnColumn('setting', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('setting', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('setting', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('setting', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('setting', 'updated_at', 'ปรับปรุงเมื่อ');
        
        $this->createIndex('idx_setting_key', 'setting', 'key');
        $this->addForeignKey('fk_setting_created_by', 'setting', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_setting_updated_by', 'setting', 'updated_by', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('setting');
        $this->dropForeignKey('fk_person_division_id', 'person');
        $this->dropColumn('person', 'gender');
        $this->dropColumn('person', 'division_id');
        
        $this->dropColumn('organization', 'is_internal');
        
        $this->dropForeignKey('fk_department_job_category_id', 'department');
        $this->dropColumn('department', 'job_category_id');
        
        $this->dropTable('division');
        
        $this->dropForeignKey('fk_submission_submission_id', 'submission');
        $this->dropColumn('submission', 'ref_submission_id');
        $this->dropColumn('submission', 'resolution');
        $this->dropColumn('person_role_panel', 'is_regular');
        
        $this->dropTable('submission_extra_info');
        $this->dropTable('submission_type_extra_info');
        $this->dropTable('extra_info');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180123_065901_create_summission_info cannot be reverted.\n";

      return false;
      }
     */
}
