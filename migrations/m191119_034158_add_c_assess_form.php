<?php

use yii\db\Migration;

/**
 * Class m191119_034158_add_c_assess_form
 */
class m191119_034158_add_c_assess_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('c_assess_form', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'opinion' => $this->integer(),
            'opinion_remark' => $this->text(),
            'suggestion' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('c_assess_form', 'opinion', 'สรุปความเห็นโดยรวม');
        $this->addCommentOnColumn('c_assess_form', 'opinion_remark', 'ข้อคิดเห็นเพิ่มเติม');
        $this->addCommentOnColumn('c_assess_form', 'suggestion', 'ข้อเสนอแนะอื่นๆ');
        $this->addCommentOnColumn('c_assess_form', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('c_assess_form', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('c_assess_form', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('c_assess_form', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('c_assess_form', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_c_assess_form_opinion', 'c_assess_form', 'opinion');
        $this->addForeignKey('fk_c_assess_form_submission_id', 'c_assess_form', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_c_assess_form_submission_committee_id', 'c_assess_form', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_c_assess_form_created_by', 'c_assess_form', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_c_assess_form_updated_by', 'c_assess_form', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('c_assess_form');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191119_034158_add_c_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
