<?php

use yii\db\Migration;

/**
 * Class m200226_082640_deviation_cr04
 */
class m200226_082640_deviation_cr04 extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $this->createTable('deviation_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('deviation_type', 'name', 'ชนิดการเบียงเบน');
        $this->addCommentOnColumn('deviation_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_deviation_type_name', 'deviation_type', 'name');
        $this->addForeignKey('fk_deviation_type_created_by', 'deviation_type', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_type_updated_by', 'deviation_type', 'updated_by', 'user', 'id');


        $this->createTable('deviation_assess_form', [
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

        $this->addCommentOnColumn('deviation_assess_form', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('deviation_assess_form', 'review_choice_text', 'ชนิดรายงานอื่นๆ');
        $this->addCommentOnColumn('deviation_assess_form', 'submission_id', 'submission');
        $this->addCommentOnColumn('deviation_assess_form', 'submission_committee_id', 'กรรมการ');
        $this->addCommentOnColumn('deviation_assess_form', 'resolution_id', 'ข้อคิดเห็นกรรมการ');
        $this->addCommentOnColumn('deviation_assess_form', 'suggestion', 'ข้อเสนอแนะเพิ่มเติม');
        $this->addCommentOnColumn('deviation_assess_form', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_assess_form', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_assess_form', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_assess_form', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_assess_form', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_deviation_assess_form_submission_id', 'deviation_assess_form', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_deviation_assess_form_submission_committee_id', 'deviation_assess_form', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_deviation_assess_form_review_choice_id', 'deviation_assess_form', 'review_choice_id', 'review_choice', 'id');
        $this->addForeignKey('fk_deviation_assess_form_resolution_id', 'deviation_assess_form', 'resolution_id', 'resolution', 'id');
        $this->addForeignKey('fk_deviation_assess_form_created_by', 'deviation_assess_form', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_assess_form_updated_by', 'deviation_assess_form', 'updated_by', 'user', 'id');

        $this->createTable('submission_event', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'event_no' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_event', 'submission_id', 'การยื่นโครงการ');
        $this->addCommentOnColumn('submission_event', 'event_no', 'หมายเลขเหตุการณ์');
        $this->addCommentOnColumn('submission_event', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_event', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_event', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_event', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_event', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_event_event_no', 'submission_event', 'event_no');
        $this->addForeignKey('fk_submission_event_submission_id', 'submission_event', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_submission_event_created_by', 'submission_event', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_event_updated_by', 'submission_event', 'updated_by', 'user', 'id');

        $this->createTable('deviation_event', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'submission_event_id' => $this->integer(),
//            'assess_type' => $this->integer(),
            'is_major_minor_com' => $this->integer(),
            'comment' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);


        $this->addCommentOnColumn('deviation_event', 'submission_committee_id', 'กรรมการ');
        $this->addCommentOnColumn('deviation_event', 'submission_event_id', 'เหตุการณ์');
//        $this->addCommentOnColumn('deviation_event', 'assess_type', 'ประเภทการประเมิน');
        $this->addCommentOnColumn('deviation_event', 'is_major_minor_com', 'Major/Minor/Compliance');
        $this->addCommentOnColumn('deviation_event', 'comment', 'ข้อคิดเห็นเพิ่มเติม');
        $this->addCommentOnColumn('deviation_event', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_event', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_event', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_event', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_event', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_deviation_event_submission_id', 'deviation_event', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_deviation_event_submission_committee_id', 'deviation_event', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_deviation_event_volunteer_id', 'deviation_event', 'submission_event_id', 'submission_event', 'id');
        $this->addForeignKey('fk_deviation_event_created_by', 'deviation_event', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_event_updated_by', 'deviation_event', 'updated_by', 'user', 'id');

        $this->createTable('deviation_event_type', [
            'id' => $this->primaryKey(),
            'deviation_event_id' => $this->integer(),
            'deviation_type_id' => $this->integer(),
            'other' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);


        $this->addCommentOnColumn('deviation_event_type', 'deviation_event_id', 'เหตุการณ์');
        $this->addCommentOnColumn('deviation_event_type', 'deviation_type_id', 'ชนิดของการดำเนินการเบี่ยงเบน');
        $this->addCommentOnColumn('deviation_event_type', 'other', 'อื่นๆ');
        $this->addCommentOnColumn('deviation_event_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_event_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_event_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_event_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_event_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_deviation_event_type_deviation_event_id', 'deviation_event_type', 'deviation_event_id', 'deviation_event', 'id');
        $this->addForeignKey('fk_deviation_event_type_deviation_type_id', 'deviation_event_type', 'deviation_type_id', 'deviation_type', 'id');
        $this->addForeignKey('fk_deviation_event_type_created_by', 'deviation_event_type', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_event_type_updated_by', 'deviation_event_type', 'updated_by', 'user', 'id');


        $this->createTable('deviation_event_ethics', [
            'id' => $this->primaryKey(),
            'deviation_event_id' => $this->integer(),
            'ethics_id' => $this->integer(),
            'is_appropriate' => $this->integer(),
            'other' => $this->string(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('deviation_event_ethics', 'deviation_event_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('deviation_event_ethics', 'ethics_id', 'ประเด็นจริยธรรม');
        $this->addCommentOnColumn('deviation_event_ethics', 'is_appropriate', 'เหมาะสมหรือไม่');
        $this->addCommentOnColumn('deviation_event_ethics', 'other', 'อื่นๆ');
        $this->addCommentOnColumn('deviation_event_ethics', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('deviation_event_ethics', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_event_ethics', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_event_ethics', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_event_ethics', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_event_ethics', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_deviation_event_ethics_is_appropriate', 'deviation_event_ethics', 'is_appropriate');
        $this->addForeignKey('fk_deviation_event_ethics_deviation_event_id', 'deviation_event_ethics', 'deviation_event_id', 'deviation_event', 'id');
        $this->addForeignKey('fk_deviation_event_ethics_ethics_id', 'deviation_event_ethics', 'ethics_id', 'ethics', 'id');
        $this->addForeignKey('fk_deviation_event_ethics_created_by', 'deviation_event_ethics', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_event_ethics_updated_by', 'deviation_event_ethics', 'updated_by', 'user', 'id');

        $this->addColumn('submission_document', 'submission_event_id', $this->integer());
        $this->addCommentOnColumn('submission_document', 'submission_event_id', 'เหตุการณ์');
        $this->addForeignKey('fk_submission_document_submission_event_id', 'submission_document', 'submission_event_id', 'submission_event', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('deviation_type');
        $this->dropTable('deviation_event_ethics');
        $this->dropTable('deviation_event');
        $this->dropTable('submission_event');
        $this->dropTable('deviation_assess_form');
        $this->dropForeignKey('fk_submission_document_submission_event_id', 'submission_document');
        $this->dropColumn('submission_document', 'submission_event_id');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200226_082640_deviation_cr04 cannot be reverted.\n";

      return false;
      }
     */
}
