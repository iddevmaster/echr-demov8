<?php

use yii\db\Migration;

/**
 * Class m171223_061959_create_table_agenda_for_submission_type
 */
class m171223_061959_create_table_agenda_for_submission_type extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('agenda', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'submission_type_id' => $this->integer(),
            'label' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('agenda', 'id', 'รหัสองค์กร');
        $this->addCommentOnColumn('agenda', 'name', 'หัวข้อวาระการประชุม');
        $this->addCommentOnColumn('agenda', 'submission_type_id', 'ประเภท submission');
        $this->addCommentOnColumn('agenda', 'label', 'label');
        $this->addCommentOnColumn('agenda', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('agenda', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('agenda', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('agenda', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('agenda', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_agenda_name', 'agenda', ['name']);
        $this->addForeignKey('fk_agenda_submission_type_id', 'agenda', 'submission_type_id', 'submission_type', 'id', 'NO ACTION');
        $this->addForeignKey('fk_agenda_user1', 'agenda', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_agenda_user2', 'agenda', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->addColumn('document', 'template_file', $this->string());
        $this->addCommentOnColumn('document', 'template_file', 'ไฟล์ต้นแบบ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('agenda');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m171223_061959_create_table_agenda_for_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
