<?php

use yii\db\Migration;
use app\models\SubmissionType;

/**
 * Class m200303_080750_alter_submission_type_assess_form
 */
class m200303_080750_alter_submission_type_assess_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->update('submission_type_assess_form', [
            'assess_form' => SubmissionType::FORM_DEVIATION,
        ], ['submission_type_id' => SubmissionType::TYPE_DEVIATION]);
        
        $this->createTable('deviation_assess_form_review', [
            'id' => $this->primaryKey(),
            'deviation_assess_form_id' => $this->integer(),
            'review_choice_id' => $this->integer(),
            'review_choice_text' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('deviation_assess_form_review', 'deviation_assess_form_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('deviation_assess_form_review', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('deviation_assess_form_review', 'review_choice_text', 'ชนิดรายงานอื่นๆ');
        $this->addCommentOnColumn('deviation_assess_form_review', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('deviation_assess_form_review', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('deviation_assess_form_review', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('deviation_assess_form_review', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('deviation_assess_form_review', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_deviation_assess_form_review_deviation_assess_form_id', 'deviation_assess_form_review', 'deviation_assess_form_id', 'deviation_assess_form', 'id');
        $this->addForeignKey('fk_deviation_assess_form_review_review_choice_id', 'deviation_assess_form_review', 'review_choice_id', 'review_choice', 'id');
        $this->addForeignKey('fk_deviation_assess_form_review_created_by', 'deviation_assess_form_review', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_deviation_assess_form_review_updated_by', 'deviation_assess_form_review', 'updated_by', 'user', 'id');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('deviation_assess_form_review');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200303_080750_alter_submission_type_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
