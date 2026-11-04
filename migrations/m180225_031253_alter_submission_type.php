<?php

use yii\db\Migration;

/**
 * Class m180225_031253_alter_submission_type
 */
class m180225_031253_alter_submission_type extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission_type', 'resolution', $this->string());
        $this->addColumn('submission_type', 'committee_count', $this->integer());
        
        $this->addCommentOnColumn('submission_type', 'resolution', 'มติที่ประชุมครั้งก่อน');
        $this->addCommentOnColumn('submission_type', 'committee_count', 'จำนวนกรรมการที่ต้องอ่าน');
        
        $this->createIndex('idx_submission_type_resolution', 'submission_type', 'resolution');
        
        $this->update('submission_type', ['resolution' => \app\models\Submission::RESOLUTION_C], ['id' => 5]);
        $this->update('submission_type', ['resolution' => \app\models\Submission::RESOLUTION_R], ['id' => 6]);
        $this->update('submission_type', ['committee_count' => 1], ['is_new' => 0]);
        $this->update('submission_type', ['committee_count' => 2], ['is_new' => 1]);
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('submission_type', 'committee_count');
        $this->dropColumn('submission_type', 'resolution');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180225_031253_alter_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
