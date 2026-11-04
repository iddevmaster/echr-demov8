<?php

use yii\db\Migration;

/**
 * Class m191104_024929_create_assess_form
 */
class m191104_024929_create_assess_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('review_choice', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'type' => $this->integer(),
            'need_text' => $this->boolean()->notNull()->defaultValue(false),
            'parent_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('review_choice', 'name', 'ชื่อชนิดรายงาน');
        $this->addCommentOnColumn('review_choice', 'type', 'ชนิดการพิจารณา');
        $this->addCommentOnColumn('review_choice', 'need_text', 'ระบุข้อความหรือไม่');
        $this->addCommentOnColumn('review_choice', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('review_choice', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('review_choice', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('review_choice', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('review_choice', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_review_choice_name', 'review_choice', ['name']);
        $this->createIndex('idx_review_choice_type', 'review_choice', ['type']);
        $this->createIndex('idx_review_choice_need_text', 'review_choice', ['need_text']);
        $this->addForeignKey('fk_review_choice_parent_id', 'review_choice', 'parent_id', 'review_choice', 'id');
        $this->addForeignKey('fk_review_choice_created_by', 'review_choice', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_review_choice_updated_by', 'review_choice', 'updated_by', 'user', 'id');
        
        $this->createTable('resolution', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'resolution' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('resolution', 'name', 'ชื่อผลการพิจารณา');
        $this->addCommentOnColumn('resolution', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('resolution', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('resolution', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('resolution', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('resolution', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_resolution_name', 'resolution', ['name']);
        $this->createIndex('idx_resolution_resolution', 'resolution', ['resolution']);
        $this->addForeignKey('fk_resolution_created_by', 'resolution', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_resolution_updated_by', 'resolution', 'updated_by', 'user', 'id');
        
        $this->createTable('ethics', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'need_text' => $this->boolean()->notNull()->defaultValue(false),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('ethics', 'name', 'ประเด็นจริยธรรม');
        $this->addCommentOnColumn('ethics', 'need_text', 'ระบุข้อความหรือไม่');
        $this->addCommentOnColumn('ethics', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('ethics', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('ethics', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('ethics', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('ethics', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_ethics_name', 'ethics', ['name']);
        $this->createIndex('idx_ethics_need_text', 'ethics', ['need_text']);
        $this->addForeignKey('fk_ethics_created_by', 'ethics', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_ethics_updated_by', 'ethics', 'updated_by', 'user', 'id');
        
        $this->createTable('continue_assess_form', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'review_choice_id' => $this->integer(),
            'review_choice_text' => $this->string(),
            'resolution_id' => $this->integer(),
            'suggestion' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('continue_assess_form', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('continue_assess_form', 'review_choice_text', 'ชนิดรายงานอื่นๆ');
        $this->addCommentOnColumn('continue_assess_form', 'resolution_id', 'ข้อคิดเห็นกรรมการ');
        $this->addCommentOnColumn('continue_assess_form', 'suggestion', 'ข้อเสนอแนะเพิ่มเติม');
        $this->addCommentOnColumn('continue_assess_form', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('continue_assess_form', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('continue_assess_form', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('continue_assess_form', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('continue_assess_form', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_continue_assess_form_submission_id', 'continue_assess_form', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_continue_assess_form_submission_committee_id', 'continue_assess_form', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_continue_assess_form_review_choice_id', 'continue_assess_form', 'review_choice_id', 'review_choice', 'id');
        $this->addForeignKey('fk_continue_assess_form_resolution_id', 'continue_assess_form', 'resolution_id', 'resolution', 'id');
        $this->addForeignKey('fk_continue_assess_form_created_by', 'continue_assess_form', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_continue_assess_form_updated_by', 'continue_assess_form', 'updated_by', 'user', 'id');
        
        $this->createTable('continue_assess_form_review', [
            'id' => $this->primaryKey(),
            'continue_assess_form_id' => $this->integer(),
            'review_choice_id' => $this->integer(),
            'review_choice_text' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('continue_assess_form_review', 'continue_assess_form_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('continue_assess_form_review', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('continue_assess_form_review', 'review_choice_text', 'ชนิดรายงานอื่นๆ');
        $this->addCommentOnColumn('continue_assess_form_review', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('continue_assess_form_review', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('continue_assess_form_review', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('continue_assess_form_review', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('continue_assess_form_review', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_continue_assess_form_review_continue_assess_form_id', 'continue_assess_form_review', 'continue_assess_form_id', 'continue_assess_form', 'id');
        $this->addForeignKey('fk_continue_assess_form_review_review_choice_id', 'continue_assess_form_review', 'review_choice_id', 'review_choice', 'id');
        $this->addForeignKey('fk_continue_assess_form_review_created_by', 'continue_assess_form_review', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_continue_assess_form_review_updated_by', 'continue_assess_form_review', 'updated_by', 'user', 'id');
        
        $this->createTable('continue_assess_form_ethics', [
            'id' => $this->primaryKey(),
            'continue_assess_form_id' => $this->integer(),
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

        $this->addCommentOnColumn('continue_assess_form_ethics', 'continue_assess_form_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'ethics_id', 'ประเด็นจริยธรรม');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'is_appropriate', 'เหมาะสมหรือไม่');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'other', 'อื่นๆ');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('continue_assess_form_ethics', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_continue_assess_form_ethics_continue_assess_form_id', 'continue_assess_form_ethics', 'continue_assess_form_id', 'continue_assess_form', 'id');
        $this->addForeignKey('fk_continue_assess_form_ethics_ethics_id', 'continue_assess_form_ethics', 'ethics_id', 'ethics', 'id');
        $this->addForeignKey('fk_continue_assess_form_ethics_created_by', 'continue_assess_form_ethics', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_continue_assess_form_ethics_updated_by', 'continue_assess_form_ethics', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('continue_assess_form_ethics');
        $this->dropTable('continue_assess_form_review');
        $this->dropTable('continue_assess_form');
        $this->dropTable('ethics');
        $this->dropTable('resolution');
        $this->dropTable('review_choice');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191104_024929_create_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
