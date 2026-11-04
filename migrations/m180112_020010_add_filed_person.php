<?php

use yii\db\Migration;

/**
 * Class m180112_020010_add_filed_person
 */
class m180112_020010_add_filed_person extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('position', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('position', 'id', 'รหัส');
        $this->addCommentOnColumn('position', 'name', 'ตำแหน่ง');
        $this->addCommentOnColumn('position', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('position', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('position', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('position', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('position', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_position_name', 'position', ['name']);
        $this->addForeignKey('fk_position_user1', 'position', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_position_user2', 'position', 'updated_by', 'user', 'id', 'NO ACTION');


        $this->addColumn('person', 'department_id', $this->integer());
        $this->addColumn('person', 'position_id', $this->integer());
        $this->addColumn('person', 'organization_id', $this->integer());
        $this->addColumn('person', 'job_category_id', $this->integer());
        $this->addColumn('person', 'is_paediatrician', $this->boolean()->notNull()->defaultValue(FALSE));

        $this->addCommentOnColumn('person', 'department_id', 'แผนก');
        $this->addCommentOnColumn('person', 'position_id', 'ตำแหน่ง');
        $this->addCommentOnColumn('person', 'organization_id', 'องค์กร');
        $this->addCommentOnColumn('person', 'job_category_id', 'หน้าที่ในที่ประชุม');
        $this->addCommentOnColumn('person', 'is_paediatrician', 'กุมารแพทย์');

        $this->addForeignKey('fk_person_department_id', 'person', 'department_id', 'department', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_position_id', 'person', 'position_id', 'position', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_organization_id', 'person', 'organization_id', 'organization', 'id', 'NO ACTION');
        $this->addForeignKey('fk_person_job_category_id', 'person', 'job_category_id', 'job_category', 'id', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        echo "m180112_020010_add_filed_person cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180112_020010_add_filed_person cannot be reverted.\n";

      return false;
      }
     */
}
