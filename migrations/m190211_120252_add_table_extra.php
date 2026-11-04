<?php

use yii\db\Migration;

/**
 * Class m190211_120252_add_table_extra
 */
class m190211_120252_add_table_extra extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('project_consultant', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'project_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'mail_sent' => $this->boolean(),
            'mail_sent_at' => $this->dateTime(),
            'acknowledge_status' => $this->integer(),
            'acknowledge_by' => $this->integer(),
            'acknowledge_at' => $this->datetime(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_consultant', 'id', 'รหัสหน่วยงาน');
        $this->addCommentOnColumn('project_consultant', 'person_id', 'ที่ปรึกษา');
        $this->addCommentOnColumn('project_consultant', 'project_id', 'โครงการวิจัย');
        $this->addCommentOnColumn('project_consultant', 'submission_id', 'submission');
        $this->addCommentOnColumn('project_consultant', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('project_consultant', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_consultant', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('project_consultant', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('project_consultant', 'updated_at', 'ปรับปรุงเมื่อ');
        $this->addCommentOnColumn('project_consultant', 'mail_sent', 'ส่งอีเมล์หรือยัง');
        $this->addCommentOnColumn('project_consultant', 'mail_sent_at', 'ส่งเมล์เมื่อ');
        $this->addCommentOnColumn('project_consultant', 'acknowledge_status', 'ผลตอบรับร่วมวิจัย');
        $this->addCommentOnColumn('project_consultant', 'acknowledge_by', 'ผู้ตอบรับ');
        $this->addCommentOnColumn('project_consultant', 'acknowledge_at', 'ตอบรับเมื่อ');

        $this->addForeignKey('fk_project_consultant_person_id', 'project_consultant', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_consultant_project_id', 'project_consultant', 'project_id', 'project', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_consultant_user1', 'project_consultant', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_consultant_user2', 'project_consultant', 'updated_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_consultant_acknowledge_by', 'project_consultant', 'acknowledge_by', 'user', 'id');
        $this->addForeignKey('fk_project_consultant_submission_id', 'project_consultant', 'submission_id', 'submission', 'id');


        $this->createTable('submission_project_consultant', [
            'id' => $this->primaryKey(),
            'project_consultant_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'status' => $this->integer(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_project_consultant', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_project_consultant', 'project_consultant_id', 'ที่ปรึกษา');
        $this->addCommentOnColumn('submission_project_consultant', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_project_consultant', 'status', 'สถานะ');
        $this->addCommentOnColumn('submission_project_consultant', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('submission_project_consultant', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_project_consultant', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_project_consultant', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_project_consultant', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_project_consultant', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_project_consultant_id', 'submission_project_consultant', ['id']);
        $this->addForeignKey('fk_submission_project_consultant_submission_id', 'submission_project_consultant', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_consultant_project_consultant_id', 'submission_project_consultant', 'project_consultant_id', 'project_consultant', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_consultant_user1', 'submission_project_consultant', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_consultant_user2', 'submission_project_consultant', 'updated_by', 'user', 'id', 'NO ACTION');


        $this->addColumn('submission', 'is_legacy', $this->boolean()->defaultValue(false));
        $this->addColumn('submission', 'submission_by', $this->integer());
        $this->addColumn('submission', 'project_coordinator_id', $this->integer());
        $this->addColumn('project', 'project_coordinator_id', $this->integer());
        $this->addCommentOnColumn('submission', 'is_legacy', 'ส่งโครงการที่ผ่านการรับรองแล้ว');
        $this->addCommentOnColumn('submission', 'submission_by', 'คนส่งงานวิจัย');
        $this->addCommentOnColumn('submission', 'project_coordinator_id', 'ผู้ประสานงานโครงการ');
        $this->addCommentOnColumn('project', 'project_coordinator_id', 'ผู้ประสานงานโครงการ');
        $this->addForeignKey('fk_submission_submission_by', 'submission', 'submission_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_coordinator_id', 'submission', 'project_coordinator_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_project_coordinator_id', 'project', 'project_coordinator_id', 'user', 'id', 'NO ACTION');

        $this->insert('role', ['id' => 8, 'name' => 'ผู้ประสานงานโครงการ']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('project_consultant');
        $this->dropTable('submission_project_consultant');
        $this->dropColumn('submission', 'is_legacy');
        $this->dropColumn('submission', 'submission_by');
        $this->dropColumn('submission', 'project_coordinator_id');
        $this->dropColumn('project', 'project_coordinator_id');
        $this->dropForeignKey('fk_submission_submission_by', 'submission');
        $this->dropForeignKey('fk_submission_project_coordinator_id', 'submission');
        $this->dropForeignKey('fk_project_project_coordinator_id', 'project');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190211_120252_add_table_extra cannot be reverted.\n";

      return false;
      }
     */
}
