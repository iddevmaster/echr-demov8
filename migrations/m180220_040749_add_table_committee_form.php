<?php

use yii\db\Migration;

/**
 * Class m180220_040749_add_table_committee_form
 */
class m180220_040749_add_table_committee_form extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {

        $this->createTable('questionnaire_title', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer(),
            'questionnaire_type' => $this->integer(),
            'title' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('questionnaire_title', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('questionnaire_title', 'title', 'หัวข้อแบบสอบถามการประเมิน');
        $this->addCommentOnColumn('questionnaire_title', 'questionnaire_type', 'ประเภทแบบสอบถามการประเมิน');
        $this->addCommentOnColumn('questionnaire_title', 'submission_type_id', 'รหัสประเภทการส่ง submission');
        $this->addCommentOnColumn('questionnaire_title', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('questionnaire_title', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('questionnaire_title', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('questionnaire_title', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('questionnaire_title', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_questionnaire_title_title', 'questionnaire_title', ['title']);
        $this->addForeignKey('fk_questionnaire_title_submission_type', 'questionnaire_title', 'submission_type_id', 'submission_type', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_title_user1', 'questionnaire_title', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_title_user2', 'questionnaire_title', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('questionnaire_choice', [
            'id' => $this->primaryKey(),
            'questionnaire_title_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('questionnaire_choice', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('questionnaire_choice', 'title', 'หัวข้อแบบสอบถาม');
        $this->addCommentOnColumn('questionnaire_choice', 'questionnaire_title_id', 'หัวข้อแบบสอบถาม');
        $this->addCommentOnColumn('questionnaire_choice', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('questionnaire_choice', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('questionnaire_choice', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('questionnaire_choice', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('questionnaire_choice', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_questionnaire_choice_title', 'questionnaire_choice', ['title']);
        $this->addForeignKey('fk_questionnaire_choice_questionaire_title1', 'questionnaire_choice', 'questionnaire_title_id', 'questionnaire_title', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_choice_user1', 'questionnaire_choice', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_choice_user2', 'questionnaire_choice', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('questionnaire_answer', [
            'id' => $this->primaryKey(),
            'submission_committee_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'questionnaire_title_id' => $this->integer(),
            'questionnaire_choice_id' => $this->integer(),
            'text_answer' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('questionnaire_answer', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('questionnaire_answer', 'submission_committee_id', 'กรรมการส่งประเมิน');
        $this->addCommentOnColumn('questionnaire_answer', 'submission_id', 'ส่งประเมิน');
        $this->addCommentOnColumn('questionnaire_answer', 'questionnaire_title_id', 'หัวข้อแบบสอบถาม');
        $this->addCommentOnColumn('questionnaire_answer', 'questionnaire_choice_id', 'ตัวเลือกแบบสอบถาม');
        $this->addCommentOnColumn('questionnaire_answer', 'text_answer', 'คำตอบสำหรับ Text');

        $this->addCommentOnColumn('questionnaire_answer', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('questionnaire_answer', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('questionnaire_answer', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('questionnaire_answer', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('questionnaire_answer', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_questionnaire_answer_id', 'questionnaire_answer', ['id']);
        $this->addForeignKey('fk_questionnaire_answer_questionnaire_title_id', 'questionnaire_answer', 'questionnaire_title_id', 'questionnaire_title', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_answer_questionnaire_choice_id', 'questionnaire_answer', 'questionnaire_choice_id', 'questionnaire_choice', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_answer_submission_committee_id', 'questionnaire_answer', 'submission_committee_id', 'submission_committee', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_answer_submission_id', 'questionnaire_answer', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_answer_user1', 'questionnaire_answer', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_questionnaire_answer_user2', 'questionnaire_answer', 'updated_by', 'user', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        echo "m180220_040749_add_table_committee_form cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180220_040749_add_table_committee_form cannot be reverted.\n";

      return false;
      }
     */
}
