<?php

use yii\db\Migration;

/**
 * Class m191021_041855_create_project_type_question
 */
class m191021_041855_create_project_type_question extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('project_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'is_alert' => $this->boolean()->notNull()->defaultValue(false),
            'min_occur' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_type', 'name', 'ชื่อประเภท');
        $this->addCommentOnColumn('project_type', 'is_alert', 'แจ้งเตือนกรรมการหรือไม่');
        $this->addCommentOnColumn('project_type', 'min_occur', 'จำนวนครั้งที่เกิดต่อนักวิจัย ต่อปี แล้วต้องแจ้งเตือน');
        $this->addCommentOnColumn('project_type', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_type', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_type', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_type', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_type', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_project_type_name', 'project_type', ['name']);
        $this->createIndex('idx_project_type_is_alert', 'project_type', ['is_alert']);
        $this->addForeignKey('fk_project_type_created_by', 'project_type', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_project_type_updated_by', 'project_type', 'updated_by', 'user', 'id');

        $this->createTable('project_question', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'answer_type' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_question', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('project_question', 'name', 'คำถาม');
        $this->addCommentOnColumn('project_question', 'answer_type', '1=single choice, 2=multi choices');
        $this->addCommentOnColumn('project_question', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_question', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_question', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_question', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_question', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_project_question_name', 'project_question', ['name']);
        $this->createIndex('idx_project_question_answer_type', 'project_question', ['answer_type']);
        $this->addForeignKey('fk_project_question_created_by', 'project_question', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_project_question_updated_by', 'project_question', 'updated_by', 'user', 'id');

        $this->createTable('project_question_choice', [
            'id' => $this->primaryKey(),
            'project_question_id' => $this->integer(),
            'project_type_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_question_choice', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('project_question_choice', 'project_question_id', 'คำถาม');
        $this->addCommentOnColumn('project_question_choice', 'project_type_id', 'คำตอบที่เลือกได้');
        $this->addCommentOnColumn('project_question_choice', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_question_choice', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_question_choice', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_question_choice', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_question_choice', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_project_question_choice_project_question_id', 'project_question_choice', 'project_question_id', 'project_question', 'id');
        $this->addForeignKey('fk_project_question_choice_project_type_id', 'project_question_choice', 'project_type_id', 'project_type', 'id');
        $this->addForeignKey('fk_project_question_choice_created_by', 'project_question_choice', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_project_question_choice_updated_by', 'project_question_choice', 'updated_by', 'user', 'id');

        $this->createTable('project_agenda_question', [
            'id' => $this->primaryKey(),
            'agenda_id' => $this->integer(),
            'project_question_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_agenda_question', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('project_agenda_question', 'agenda_id', 'วาระ');
        $this->addCommentOnColumn('project_agenda_question', 'project_question_id', 'คำถาม');
        $this->addCommentOnColumn('project_agenda_question', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_agenda_question', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_agenda_question', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_agenda_question', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_agenda_question', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_project_agenda_question_agenda_id', 'project_agenda_question', 'agenda_id', 'agenda', 'id');
        $this->addForeignKey('fk_project_agenda_question_project_question_id', 'project_agenda_question', 'project_question_id', 'project_question', 'id');
        $this->addForeignKey('fk_project_agenda_question_created_by', 'project_question_choice', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_project_agenda_question_updated_by', 'project_question_choice', 'updated_by', 'user', 'id');

        $this->createTable('project_agenda_answer', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'project_question_id' => $this->integer(),
            'project_type_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_agenda_answer', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('project_agenda_answer', 'project_id', 'โครงการ');
        $this->addCommentOnColumn('project_agenda_answer', 'submission_id', 'submission');
        $this->addCommentOnColumn('project_agenda_answer', 'project_question_id', 'คำถาม');
        $this->addCommentOnColumn('project_agenda_answer', 'project_type_id', 'คำตอบ');
        $this->addCommentOnColumn('project_agenda_answer', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_agenda_answer', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_agenda_answer', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_agenda_answer', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_agenda_answer', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_project_agenda_answer_project_id', 'project_agenda_answer', 'project_id', 'project', 'id');
        $this->addForeignKey('fk_project_agenda_answer_submission_id', 'project_agenda_answer', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_project_agenda_answer_project_question_id', 'project_agenda_answer', 'project_question_id', 'project_question', 'id');
        $this->addForeignKey('fk_project_agenda_answer_project_type_id', 'project_agenda_answer', 'project_type_id', 'project_type', 'id');
        $this->addForeignKey('fk_project_agenda_answer_created_by', 'project_agenda_answer', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_project_agenda_answer_updated_by', 'project_agenda_answer', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('project_agenda_answer');
        $this->dropTable('project_agenda_question');
        $this->dropTable('project_question_choice');
        $this->dropTable('project_question');
        $this->dropTable('project_type');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191021_041855_create_project_type_question cannot be reverted.\n";

      return false;
      }
     */
}
