<?php

use yii\db\Migration;

/**
 * Class m180321_141900_insert_more_submission_type
 */
class m180321_141900_insert_more_submission_type extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->insert('submission_type', ['id' => 14, 'name' => 'โครงการวิจัยต่อเนื่องที่เคยเสนอเข้าพิจารณา และจะรับรองหลังจากการแก้ไขตามมติที่ประชุม', 'is_new' => 0, 'submission_type_group_id' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'internal' => 1, 'meeting_consideration' => 1, 'resolution' => 'C', 'committee_count' => 1]);
        $this->insert('submission_type', ['id' => 15, 'name' => 'อื่นๆ', 'is_new' => 0, 'submission_type_group_id' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'committee_count' => 1, 'add_subject' => 1]);
    
        $this->insert('agenda_submission_type', ['id' => 16, 'agenda_id' => 12, 'submission_type_id' => 14]);
        $this->insert('agenda_submission_type', ['id' => 17, 'agenda_id' => 18, 'submission_type_id' => 15]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->delete('agenda_submission_type', ['id' => [16, 17]]);
        $this->delete('submission_type', ['id' => [14, 15]]);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180321_141900_insert_more_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
