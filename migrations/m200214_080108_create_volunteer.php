<?php

use yii\db\Migration;

/**
 * Class m200214_080108_create_volunteer
 */
class m200214_080108_create_volunteer extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $this->createTable('sae_assess_form_ethics', [
            'id' => $this->primaryKey(),
            'sae_assess_form_id' => $this->integer(),
            'ethics_id' => $this->integer(),
            'is_appropriate' => $this->boolean(),
            'other' => $this->string(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('sae_assess_form_ethics', 'sae_assess_form_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'ethics_id', 'ประเด็นจริยธรรม');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'is_appropriate', 'เหมาะสมหรือไม่');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'other', 'อื่นๆ');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('sae_assess_form_ethics', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_sae_assess_form_ethics_sae_assess_form_id', 'sae_assess_form_ethics', 'sae_assess_form_id', 'sae_assess_form', 'id');
        $this->addForeignKey('fk_sae_assess_form_ethics_ethics_id', 'sae_assess_form_ethics', 'ethics_id', 'ethics', 'id');
        $this->addForeignKey('fk_sae_assess_form_ethics_created_by', 'sae_assess_form_ethics', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_sae_assess_form_ethics_updated_by', 'sae_assess_form_ethics', 'updated_by', 'user', 'id');
    
        
        $this->createTable('volunteer', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'code' => $this->string(),
            'status' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('volunteer', 'project_id', 'โครงการ');
        $this->addCommentOnColumn('volunteer', 'code', 'เลขที่อาสาสมัคร');
        $this->addCommentOnColumn('volunteer', 'status', 'สถานะ');
        $this->addCommentOnColumn('volunteer', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('volunteer', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('volunteer', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('volunteer', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('volunteer', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_volunteer_code', 'volunteer', 'code');
        $this->createIndex('idx_volunteer_status', 'volunteer', 'status');
        $this->addForeignKey('fk_volunteer_project_id', 'volunteer', 'project_id', 'project', 'id');
        $this->addForeignKey('fk_volunteer_created_by', 'volunteer', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_volunteer_updated_by', 'volunteer', 'updated_by', 'user', 'id');
        
        $this->addColumn('submission_document', 'volunteer_id', $this->integer());
        $this->addCommentOnColumn('submission_document', 'volunteer_id', 'อาสาสมัคร');
        $this->addForeignKey('fk_submission_document_volunteer_id', 'submission_document', 'volunteer_id', 'volunteer', 'id');
        
        $this->addColumn('continue_assess_form', 'volunteer_id', $this->integer());
        $this->addCommentOnColumn('continue_assess_form', 'volunteer_id', 'อาสาสมัคร');
        $this->addForeignKey('fk_continue_assess_form_volunteer_id', 'continue_assess_form', 'volunteer_id', 'volunteer', 'id');
        
        $this->addColumn('sae_assess_form', 'volunteer_id', $this->integer());
        $this->addCommentOnColumn('sae_assess_form', 'volunteer_id', 'อาสาสมัคร');
        $this->addForeignKey('fk_sae_assess_form_volunteer_id', 'sae_assess_form', 'volunteer_id', 'volunteer', 'id');
    
        $this->createTable('submission_volunteer', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'volunteer_id' => $this->integer(),
            'type' => $this->integer(),
            'follow_up_no' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('submission_volunteer', 'submission_id', 'การยื่นโครงการ');
        $this->addCommentOnColumn('submission_volunteer', 'volunteer_id', 'อาสาสมัคร');
        $this->addCommentOnColumn('submission_volunteer', 'type', 'ประเภทการติดตาม');
        $this->addCommentOnColumn('submission_volunteer', 'follow_up_no', 'ติดตามครั้งที่');
        $this->addCommentOnColumn('submission_volunteer', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_volunteer', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_volunteer', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_volunteer', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_volunteer', 'updated_at', 'ปรับปรุงเมื่อ');
    
        $this->createIndex('idx_submission_volunteer_type', 'submission_volunteer', 'type');
        $this->createIndex('idx_submission_volunteer_follow_up_no', 'submission_volunteer', 'follow_up_no');
        $this->addForeignKey('fk_submission_volunteer_submission_id', 'submission_volunteer', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_submission_volunteer_volunteer_id', 'submission_volunteer', 'volunteer_id', 'volunteer', 'id');
        $this->addForeignKey('fk_submission_volunteer_created_by', 'submission_volunteer', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_volunteer_updated_by', 'submission_volunteer', 'updated_by', 'user', 'id');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('submission_volunteer');
        $this->dropForeignKey('fk_sae_assess_form_volunteer_id', 'sae_assess_form');
        $this->dropColumn('sae_assess_form', 'volunteer_id');
        $this->dropForeignKey('fk_continue_assess_form_volunteer_id', 'continue_assess_form');
        $this->dropColumn('continue_assess_form', 'volunteer_id');
        $this->dropForeignKey('fk_submission_document_volunteer_id', 'submission_document');
        $this->dropColumn('submission_document', 'volunteer_id');
        $this->dropTable('volunteer');
        $this->dropTable('sae_assess_form_ethics');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200214_080108_create_volunteer cannot be reverted.\n";

      return false;
      }
     */
}
