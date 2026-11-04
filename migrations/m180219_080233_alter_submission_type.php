<?php

use yii\db\Migration;

/**
 * Class m180219_080233_alter_submission_type
 */
class m180219_080233_alter_submission_type extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission_type', 'meeting_consideration', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('submission_type', 'meeting_consideration', 'ต้องพิจารณาเพื่อเข้าที่ประชุม');
        
        $this->update('submission_type', ['meeting_consideration' => TRUE], ['id' => 5]);
        
        $this->renameColumn('submission', 'is_fullboard', 'is_meeting');
        $this->renameColumn('submission', 'fullboard_by', 'meeting_by');
        $this->renameColumn('submission', 'fullboard_at', 'meeting_at');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->renameColumn('submission', 'is_meeting', 'is_fullboard');
        $this->renameColumn('submission', 'meeting_by', 'fullboard_by');
        $this->renameColumn('submission', 'meeting_at', 'fullboard_at');
        
        $this->dropColumn('submission_type', 'meeting_consideration');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180219_080233_alter_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
