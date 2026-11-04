<?php

use yii\db\Migration;

/**
 * Class m180213_074727_create_meeting_room
 */
class m180213_074727_create_meeting_room extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('meeting_room', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('meeting_room', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('meeting_room', 'name', 'ชื่อห้องประชุม');
        $this->addCommentOnColumn('meeting_room', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_room', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_room', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('meeting_room', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('meeting_room', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_meeting_room_name', 'meeting_room', ['name']);
        $this->addForeignKey('fk_meeting_room_created_by', 'meeting_room', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_meeting_room_updated_by', 'meeting_room', 'updated_by', 'user', 'id');
        
        $this->addColumn('meeting', 'meeting_room_id', $this->integer());
        $this->addCommentOnColumn('meeting', 'meeting_room_id', 'ห้องประชุม');
        $this->addForeignKey('fk_meeting_meeting_room_id', 'meeting', 'meeting_room_id', 'meeting_room', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropForeignKey('fk_meeting_meeting_room_id', 'meeting');
        $this->dropColumn('meeting', 'meeting_room_id');
        $this->dropTable('meeting_room');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180213_074727_create_meeting_room cannot be reverted.\n";

      return false;
      }
     */
}
