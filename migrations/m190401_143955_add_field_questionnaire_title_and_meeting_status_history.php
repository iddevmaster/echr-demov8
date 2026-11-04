<?php

use yii\db\Migration;

/**
 * Class m190401_143955_add_field_questionnaire_title_and_meeting_status_history
 */
class m190401_143955_add_field_questionnaire_title_and_meeting_status_history extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $this->addColumn('questionnaire_title', 'order', $this->integer());
        $this->addCommentOnColumn('questionnaire_title', 'order', 'ลำดับ');

        $this->createTable('meeting_status_history', [
            'id' => $this->primaryKey(),
            'meeting_id' => $this->integer(),
            'status' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('meeting_status_history', 'id', 'รหัสองค์กร');
        $this->addCommentOnColumn('meeting_status_history', 'meeting_id', 'การประชุม');
        $this->addCommentOnColumn('meeting_status_history', 'status', 'สถานะ');
        $this->addCommentOnColumn('meeting_status_history', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('meeting_status_history', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('meeting_status_history', 'created_at', 'สร้างเมื่อ');

        $this->addForeignKey('fk_meeting_status_history_meeting_id', 'meeting_status_history', 'meeting_id', 'meeting', 'id', 'NO ACTION');
        $this->addForeignKey('fk_meeting_status_history_user1', 'meeting_status_history', 'created_by', 'user', 'id', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('meeting_status_history');
        $this->dropColumn('questionnaire_title', 'order');        

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190401_143955_add_field_questionnaire_title_and_meeting_status_history cannot be reverted.\n";

      return false;
      }
     */
}
