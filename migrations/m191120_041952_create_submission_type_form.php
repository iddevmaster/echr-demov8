<?php

use yii\db\Migration;
use app\models\SubmissionType;
/**
 * Class m191120_041952_create_submission_type_assess_form
 */
class m191120_041952_create_submission_type_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('submission_type_assess_form', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer(),
            'assess_form' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        
        $this->addCommentOnColumn('submission_type_assess_form', 'submission_type_id', 'ประเภทการส่งโครงการ');
        $this->addCommentOnColumn('submission_type_assess_form', 'assess_form', 'แบบฟอร์มประเมิน');
        $this->addCommentOnColumn('submission_type_assess_form', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type_assess_form', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type_assess_form', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type_assess_form', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type_assess_form', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_type_assess_form_assess_form', 'submission_type_assess_form', 'assess_form');
        $this->addForeignKey('fk_submission_type_assess_form_submission_type_id', 'submission_type_assess_form', 'submission_type_id', 'submission_type', 'id');
        $this->addForeignKey('fk_submission_type_assess_form_created_by', 'submission_type_assess_form', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_type_assess_form_updated_by', 'submission_type_assess_form', 'updated_by', 'user', 'id');
        
        $this->batchInsert('submission_type_assess_form', [
            'submission_type_id', 'assess_form'
        ], [
            [7, SubmissionType::FORM_CONTINUE],
            [8, SubmissionType::FORM_CONTINUE],
            [9, SubmissionType::FORM_CONTINUE],
            [12, SubmissionType::FORM_CONTINUE],
            [13, SubmissionType::FORM_CONTINUE],
            [10, SubmissionType::FORM_SAE],
            [11, SubmissionType::FORM_SAE],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('submission_type_assess_form');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191120_041952_create_submission_type_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
