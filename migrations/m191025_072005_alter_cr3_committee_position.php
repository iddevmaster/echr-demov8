<?php

use yii\db\Migration;

/**
 * Class m191025_072005_alter_cr3_committee_position
 */
class m191025_072005_alter_cr3_committee_position extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('committee_position', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('committee_position', 'id', 'รหัสองค์กร');
        $this->addCommentOnColumn('committee_position', 'name', 'ตำแหน่งของกรรมการ');
        $this->addCommentOnColumn('committee_position', 'description', 'อธิบายเพิ่มเติม');
        $this->addCommentOnColumn('committee_position', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('committee_position', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('committee_position', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('committee_position', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('committee_position', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_committee_position_name', 'committee_position', ['name']);
        $this->addForeignKey('fk_committee_position_user1', 'committee_position', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_committee_position_user2', 'committee_position', 'updated_by', 'user', 'id', 'NO ACTION');

        $this->insert('committee_position', ['id' => 1, 'name' => 'primary reviewer ท่านที่ 1', 'description' => 'primary reviewer ท่านที่ 1']);
        $this->insert('committee_position', ['id' => 2, 'name' => 'primary reviewer ท่านที่ 2', 'description' => 'primary reviewer ท่านที่ 2']);
        $this->insert('committee_position', ['id' => 3, 'name' => 'primary reviewer ที่พิจารณา ICF', 'description' => 'primary reviewer ที่พิจารณา ICF']);

        $this->addColumn('submission_committee', 'committee_position_id', $this->integer());
        $this->addCommentOnColumn('submission_committee', 'committee_position_id', 'ตำแหน่งกรรมการ');
        $this->addForeignKey('fk_submission_committee_committee_position_id', 'submission_committee', 'committee_position_id', 'committee_position', 'id');


        $this->addColumn('document_submission_type', 'committee_position_id', $this->integer());
        $this->addColumn('document_submission_type', 'ref_submission_type_id', $this->integer());
        $this->addCommentOnColumn('document_submission_type', 'committee_position_id', 'ตำแหน่งกรรมการ');
        $this->addCommentOnColumn('document_submission_type', 'ref_submission_type_id', 'อ้างอิงประเภทโครงการสำหรับการแก้ R');
        $this->addForeignKey('fk_document_submission_type_committee_position_id', 'document_submission_type', 'committee_position_id', 'committee_position', 'id');        
        $this->addForeignKey('fk_document_submission_type_ref_submission_type_id', 'document_submission_type', 'ref_submission_type_id', 'submission_type', 'id');        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('committee_position');
        $this->dropForeignKey('fk_submission_committee_committee_position_id', 'submission_committee');
        $this->dropForeignKey('fk_document_submission_type_ref_submission_type_id', 'document_submission_type');
        $this->dropForeignKey('fk_document_submission_type_committee_position_id', 'document_submission_type');
        $this->dropColumn('submission_committee', 'committee_position_id');
        $this->dropColumn('document_submission_type', 'committee_position_id');
        $this->dropColumn('document_submission_type', 'ref_submission_type_id');
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191025_072005_alter_cr3_committee_position cannot be reverted.\n";

      return false;
      }
     */
}
