<?php

use yii\db\Migration;

/**
 * Class m180222_070804_agenda_auto_desc
 */
class m180222_070804_agenda_auto_desc extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
            
        $this->createTable('agenda_auto_desc', [
            'id' => $this->primaryKey(),
            'agenda_id' => $this->integer(),
            'auto_type' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('agenda_auto_desc', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('agenda_auto_desc', 'agenda_id', 'วาระ');
        $this->addCommentOnColumn('agenda_auto_desc', 'auto_type', 'ประเภทการดึงข้อมูล');
        $this->addCommentOnColumn('agenda_auto_desc', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('agenda_auto_desc', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('agenda_auto_desc', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('agenda_auto_desc', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('agenda_auto_desc', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_agenda_auto_desc_agenda_id', 'agenda_auto_desc', 'agenda_id', 'agenda', 'id');
        $this->addForeignKey('fk_agenda_auto_desc_created_by', 'agenda_auto_desc', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_agenda_auto_desc_updated_by', 'agenda_auto_desc', 'updated_by', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('agenda_auto_desc');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180222_070804_agenda_auto_desc cannot be reverted.\n";

      return false;
      }
     */
}
