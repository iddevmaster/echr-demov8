<?php

use yii\db\Migration;

/**
 * Class m260509_120825_alter_submission_document
 */
class m260509_120825_alter_submission_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission_document', 'position', $this->integer()->comment('ตําแหน่ง'));
        $this->createIndex('idx_submission_document_position', 'submission_document', 'position');

        $this->createTable('group_doc', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_eng' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('group_doc', 'id', 'รหัสกลุ่มเอกสาร');
        $this->addCommentOnColumn('group_doc', 'name', 'กลุ่มเอกสาร');
        $this->addCommentOnColumn('group_doc', 'name_eng', 'กลุ่มเอกสารภาษาอังกฤษ');
        $this->addCommentOnColumn('group_doc', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('group_doc', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('group_doc', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('group_doc', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('group_doc', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_group_doc_name', 'group_doc', ['name']);
        $this->addForeignKey('fk_group_doc_user1', 'group_doc', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_group_doc_user2', 'group_doc', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->addColumn('submission_document', 'group_doc_id', $this->integer());
        $this->addCommentOnColumn('submission_document', 'group_doc_id', 'ประธานลงนาม');
        $this->addForeignKey('fk_submission_document_group_doc_id', 'submission_document', 'group_doc_id', 'group_doc', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_submission_document_position', 'submission_document');
        $this->dropColumn('submission_document', 'position');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260509_120825_alter_submission_document cannot be reverted.\n";

      return false;
      }
     */
}
