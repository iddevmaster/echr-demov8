<?php

use yii\db\Migration;

/**
 * Class m180116_072738_add_filed_person
 */
class m180116_072738_add_filed_person extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {


        $this->createTable('person_training', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->notNull(),
            'name_thai_course' => $this->string(),
            'name_eng_course' => $this->string(),
            'start_date'=>$this->dateTime(),
            'end_date'=>$this->dateTime(),
            'remark'=>$this->text(),
            'file'=>$this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('person_training', 'id', 'รหัส');
        $this->addCommentOnColumn('person_training', 'name_thai_course', 'ชื่อหลักสูตรภาษาไทย');
        $this->addCommentOnColumn('person_training', 'name_eng_course', 'ชื่อหลักสูตรภาษาอังกฤษ');
        $this->addCommentOnColumn('person_training', 'start_date', 'วันที่เริ่มหลักสูตร');
        $this->addCommentOnColumn('person_training', 'end_date', 'วันที่สินสุดหลักสูตร');
        $this->addCommentOnColumn('person_training', 'remark', 'รายละเอียดเพิ่มเติม');
        $this->addCommentOnColumn('person_training', 'file', 'ไฟล์เพิ่มเติม');
        $this->addCommentOnColumn('person_training', 'person_id', 'ผู้เข้าอบรม');
        $this->addCommentOnColumn('person_training', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('person_training', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('person_training', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('person_training', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('person_training', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_person_training_name_thai_course', 'person_training', ['name_thai_course']);
        $this->addForeignKey('fk_person_training_user1', 'person_training', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_training_user2', 'person_training', 'updated_by', 'user', 'id', 'NO ACTION');



        $this->addColumn('person', 'first_name_eng', $this->string());
        $this->addColumn('person', 'last_name_eng', $this->string());
        $this->addColumn('organization', 'name_eng', $this->string());
        $this->addColumn('department', 'name_eng', $this->string());
        $this->addColumn('position', 'name_eng', $this->string());
        $this->addColumn('document_submission_type', 'number', $this->integer());
        $this->addColumn('document', 'role_id', $this->integer());

        $this->addCommentOnColumn('person', 'first_name_eng', 'ชื่อภาษาอังกฤษ');
        $this->addCommentOnColumn('person', 'last_name_eng', 'นามสกุลอังกฤษ');
        $this->addCommentOnColumn('organization', 'name_eng', 'ชื่อภาษาอังกฤษ');
        $this->addCommentOnColumn('department', 'name_eng', 'ชื่อภาษาอังกฤษ');
        $this->addCommentOnColumn('position', 'name_eng', 'ชื่อภาษาอังกฤษ');
        $this->addCommentOnColumn('document_submission_type', 'number', 'จำนวนเอกสารที่ต้องส่ง');
        $this->addCommentOnColumn('document', 'role_id', 'หน้าที่สำหรับการใช้งาน');


        $this->addForeignKey('fk_document_role_id', 'document', 'role_id', 'role', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
                $this->dropTable('person_training');

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180116_072738_add_filed_person cannot be reverted.\n";

      return false;
      }
     */
}
