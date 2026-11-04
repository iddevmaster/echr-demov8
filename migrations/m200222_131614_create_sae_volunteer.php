<?php

use yii\db\Migration;

/**
 * Class m200222_131614_create_sae_volunteer
 */
class m200222_131614_create_sae_volunteer extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('sae_volunteer', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'volunteer_id' => $this->integer(),
            'dead' => $this->integer(),
            'cured' => $this->integer(),
            'drug' => $this->integer(),
            'comment' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);


        $this->addCommentOnColumn('sae_volunteer', 'dead', 'เสียชีวิตหรือไม่');
        $this->addCommentOnColumn('sae_volunteer', 'cured', 'รักษาจนเป็นปกติหรือไม่');
        $this->addCommentOnColumn('sae_volunteer', 'drug', 'สัมพันธ์กับยาวิจัยหรือไม่');
        $this->addCommentOnColumn('sae_volunteer', 'comment', 'ข้อคิดเห็นเพิ่มเติม');
        $this->addCommentOnColumn('sae_volunteer', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('sae_volunteer', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('sae_volunteer', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('sae_volunteer', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('sae_volunteer', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_sae_volunteer_submission_id', 'sae_volunteer', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_sae_volunteer_submission_committee_id', 'sae_volunteer', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_sae_volunteer_volunteer_id', 'sae_volunteer', 'volunteer_id', 'volunteer', 'id');
        $this->addForeignKey('fk_sae_volunteer_created_by', 'sae_volunteer', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_sae_volunteer_updated_by', 'sae_volunteer', 'updated_by', 'user', 'id');

        $this->createTable('sae_volunteer_ethics', [
            'id' => $this->primaryKey(),
            'sae_volunteer_id' => $this->integer(),
            'ethics_id' => $this->integer(),
            'is_appropriate' => $this->integer(),
            'other' => $this->string(),
            'remark' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('sae_volunteer_ethics', 'sae_volunteer_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'ethics_id', 'ประเด็นจริยธรรม');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'is_appropriate', 'เหมาะสมหรือไม่');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'other', 'อื่นๆ');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'remark', 'หมายเหตุ');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('sae_volunteer_ethics', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_sae_volunteer_ethics_is_appropriate', 'sae_volunteer_ethics', 'is_appropriate');
        $this->addForeignKey('fk_sae_volunteer_ethics_sae_volunteer_id', 'sae_volunteer_ethics', 'sae_volunteer_id', 'sae_volunteer', 'id');
        $this->addForeignKey('fk_sae_volunteer_ethics_ethics_id', 'sae_volunteer_ethics', 'ethics_id', 'ethics', 'id');
        $this->addForeignKey('fk_sae_volunteer_ethics_created_by', 'sae_volunteer_ethics', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_sae_volunteer_ethics_updated_by', 'sae_volunteer_ethics', 'updated_by', 'user', 'id');
        
        $tableName = $this->db->tablePrefix . 'sae_assess_form_ethics';
        if ($this->db->getTableSchema($tableName, true) !== null) {
            $this->dropTable('sae_assess_form_ethics');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('sae_volunteer_ethics');
        $this->dropTable('sae_volunteer');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200222_131614_create_sae_volunteer cannot be reverted.\n";

      return false;
      }
     */
}
