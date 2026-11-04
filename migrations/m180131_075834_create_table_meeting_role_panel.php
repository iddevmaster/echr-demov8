<?php

use yii\db\Migration;

/**
 * Class m180131_075834_create_table_meeting_role_panel
 */
class m180131_075834_create_table_meeting_role_panel extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('meeting_role_panel', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'panel_id' => $this->integer(),
            'role_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting_role_panel', 'id', 'รหัส');
        $this->addCommentOnColumn('meeting_role_panel', 'person_id', 'บุคคลกร');
        $this->addCommentOnColumn('meeting_role_panel', 'panel_id', 'การทำงาน');
        $this->addCommentOnColumn('meeting_role_panel', 'role_id', 'หน้าที่');
        $this->addCommentOnColumn('meeting_role_panel', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_role_panel', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_role_panel', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_role_panel', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_role_panel', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_role_panel_id', 'meeting_role_panel', ['id']);
        $this->addForeignKey('fk_meeting_role_panel_person_id', 'meeting_role_panel', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_role_id1', 'meeting_role_panel', 'role_id', 'role', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_panel', 'meeting_role_panel', 'panel_id', 'panel', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_user1', 'meeting_role_panel', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_role_panel_user2', 'meeting_role_panel', 'updated_by', 'user', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('meeting_role_panel');

//        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180131_075834_create_table_meeting_role_panel cannot be reverted.\n";

      return false;
      }
     */
}
