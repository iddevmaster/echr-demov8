<?php

use yii\db\Migration;

/**
 * Class m180110_054746_add_table_100161
 */
class m180110_054746_add_table_100161 extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('person_role', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'role_id' => $this->integer(),
            'sign' => $this->boolean()->notNull()->defaultValue(FALSE),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('person_role', 'id', 'รหัส');
        $this->addCommentOnColumn('person_role', 'person_id', 'บุคคลกร');
        $this->addCommentOnColumn('person_role', 'role_id', 'หน้าที่');
        $this->addCommentOnColumn('person_role', 'sign', 'สิทธิในการเซ็นต์หนังสือ');
        $this->addCommentOnColumn('person_role', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('person_role', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('person_role', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('person_role', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('person_role', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_person_role_id', 'person_role', ['id']);
        $this->addForeignKey('fk_person_role_person_id', 'person_role', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_role_id1', 'person_role', 'role_id', 'role', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_user1', 'person_role', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_user2', 'person_role', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('person_role_panel', [
            'id' => $this->primaryKey(),
            'person_role_id' => $this->integer(),
            'panel_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('person_role_panel', 'id', 'รหัส');
        $this->addCommentOnColumn('person_role_panel', 'person_role_id', 'บุคคลกร');
        $this->addCommentOnColumn('person_role_panel', 'panel_id', 'หน้าที่');
        $this->addCommentOnColumn('person_role_panel', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('person_role_panel', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('person_role_panel', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('person_role_panel', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('person_role_panel', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_person_role_panel_id', 'person_role_panel', ['id']);
        $this->addForeignKey('fk_person_role_panel_person_role_id', 'person_role_panel', 'person_role_id', 'person_role', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_panel_panel_id', 'person_role_panel', 'panel_id', 'panel', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_panel_user1', 'person_role_panel', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_role_panel_user2', 'person_role_panel', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('meeting_role', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting_role', 'id', 'รหัส');
        $this->addCommentOnColumn('meeting_role', 'name', 'หน้าที่ในการประชุม');
        $this->addCommentOnColumn('meeting_role', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_role', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_role', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_role', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_role', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_role_name', 'meeting_role', ['name']);
        $this->addForeignKey('fk_meeting_role_user1', 'meeting_role', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_user2', 'meeting_role', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->createTable('meeting_role_setup', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'panel_id' => $this->integer(),
            'meeting_role_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting_role_setup', 'id', 'รหัส');
        $this->addCommentOnColumn('meeting_role_setup', 'person_id', 'บุคคลากร');
        $this->addCommentOnColumn('meeting_role_setup', 'panel_id', 'หน้าที่');
        $this->addCommentOnColumn('meeting_role_setup', 'meeting_role_id', 'หน้าที่ในการประชุม');
        $this->addCommentOnColumn('meeting_role_setup', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_role_setup', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_role_setup', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_role_setup', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_role_setup', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_role_setup_id', 'meeting_role_setup', ['id']);
        $this->addForeignKey('fk_meeting_role_setup_person_id', 'meeting_role_setup', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_setup_panel_id', 'meeting_role_setup', 'panel_id', 'panel', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_setup_meeting_role_id', 'meeting_role_setup', 'meeting_role_id', 'meeting_role', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_setup_user1', 'meeting_role_setup', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_setup_user2', 'meeting_role_setup', 'updated_by', 'user', 'id', 'NO ACTION');


        $this->createTable('job_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('job_category', 'id', 'รหัส');
        $this->addCommentOnColumn('job_category', 'name', 'กลุ่มของงาน');
        $this->addCommentOnColumn('job_category', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('job_category', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('job_category', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('job_category', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('job_category', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_job_category_name', 'job_category', ['name']);
        $this->addForeignKey('fk_job_category_user1', 'job_category', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_job_category_user2', 'job_category', 'updated_by', 'user', 'id', 'NO ACTION');


        $this->addColumn('register_transaction', 'meeting_role_setup_id', $this->integer());
        $this->addCommentOnColumn('register_transaction', 'meeting_role_setup_id', 'ผ้เข้าประชุม');
        $this->addForeignKey('fk_register_transaction_meeting_role_setup_id', 'register_transaction', 'meeting_role_setup_id', 'meeting_role_setup', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('job_category');
        $this->dropTable('meeting_role_setup');
        $this->dropTable('meeting_role');
        $this->dropTable('person_role_panel');
        $this->dropTable('person_role');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180110_054746_add_table_100161 cannot be reverted.\n";

      return false;
      }
     */
}
