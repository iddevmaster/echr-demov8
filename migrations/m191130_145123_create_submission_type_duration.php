<?php

use yii\db\Migration;
use app\models\SubmissionType;

/**
 * Class m191130_145123_create_submission_type_duration
 */
class m191130_145123_create_submission_type_duration extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('submission_type_duration', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer(),
            'duration_type' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        
        $this->addCommentOnColumn('submission_type_duration', 'submission_type_id', 'ประเภทการส่งโครงการ');
        $this->addCommentOnColumn('submission_type_duration', 'duration_type', 'ประเภท');
        $this->addCommentOnColumn('submission_type_duration', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_type_duration', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_type_duration', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_type_duration', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_type_duration', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_submission_type_duration_duration_type', 'submission_type_duration', 'duration_type');
        $this->addForeignKey('fk_submission_type_duration_submission_type_id', 'submission_type_duration', 'submission_type_id', 'submission_type', 'id');
        $this->addForeignKey('fk_submission_type_duration_created_by', 'submission_type_duration', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_submission_type_duration_updated_by', 'submission_type_duration', 'updated_by', 'user', 'id');
        
        $this->batchInsert('submission_type_duration', [
            'submission_type_id', 'duration_type'
        ], [
            [1, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [1, SubmissionType::DURATION_APPROVE_TO_MEETING],
            [1, SubmissionType::DURATION_MEETING_TO_ENDORSE],
            [2, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [2, SubmissionType::DURATION_APPROVE_TO_MEETING],
            [2, SubmissionType::DURATION_MEETING_TO_ENDORSE],
            [3, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [4, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [7, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [8, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [9, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [10, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [11, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [12, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [13, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
            [15, SubmissionType::DURATION_APPROVE_TO_ENDORSE],
        ]);
    }

    /** 
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('submission_type_duration');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191130_145123_create_submission_type_duration cannot be reverted.\n";

      return false;
      }
     */
}
