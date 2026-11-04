<?php

use yii\db\Migration;

/**
 * Class m200317_035536_alter_review_choice
 */
class m200317_035536_alter_review_choice extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission_type', 'review_choice_id', $this->integer());
        $this->addCommentOnColumn('submission_type', 'review_choice_id', 'วาระการประชุม');
        $this->addForeignKey('fk_submission_type_review_choice_id', 'submission_type', 'review_choice_id', 'review_choice', 'id');
        
        $this->addColumn('submission_event', 'meeting_violation_type', $this->integer());
        $this->createIndex('fk_submission_event_meeting_violation_type', 'submission_event', 'meeting_violation_type');
        
        $this->update('submission_type', ['review_choice_id' => 2], ['id' => 7]);
        $this->update('submission_type', ['review_choice_id' => 9], ['id' => 8]);
        $this->update('submission_type', ['review_choice_id' => 10], ['id' => 9]);
        $this->update('submission_type', ['review_choice_id' => 13], ['id' => 10]);
        $this->update('submission_type', ['review_choice_id' => 13], ['id' => 11]);
        $this->update('submission_type', ['review_choice_id' => 11], ['id' => 12]);
        $this->update('submission_type', ['review_choice_id' => 1], ['id' => 13]);
        $this->update('submission_type', ['review_choice_id' => 15], ['id' => 15]);
        $this->update('submission_type', ['review_choice_id' => 3], ['id' => 18]);
        $this->update('submission_type', ['review_choice_id' => 3], ['id' => 20]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('submission_event', 'meeting_violation_type');
        $this->dropForeignKey('fk_submission_type_review_choice_id', 'submission_type');
        $this->dropColumn('submission_type', 'review_choice_id');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200317_035536_alter_review_choice cannot be reverted.\n";

      return false;
      }
     */
}
