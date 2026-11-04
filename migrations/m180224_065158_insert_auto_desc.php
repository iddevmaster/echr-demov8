<?php

use yii\db\Migration;
use app\models\MeetingAgenda;

/**
 * Class m180224_065158_insert_auto_desc
 */
class m180224_065158_insert_auto_desc extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('agenda_auto_desc', ['agenda_id' => 6, 'auto_type' => MeetingAgenda::AUTO_TYPE_CLOSING_PROTOCOL]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 6, 'auto_type' => MeetingAgenda::AUTO_TYPE_VOLUNTEER_NUMBER]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 6, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 7, 'auto_type' => MeetingAgenda::AUTO_TYPE_CONTINUE_REPORT]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 7, 'auto_type' => MeetingAgenda::AUTO_TYPE_VOLUNTEER_NUMBER]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 7, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 8, 'auto_type' => MeetingAgenda::AUTO_TYPE_EXEMPTION_RESEARCH]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 9, 'auto_type' => MeetingAgenda::AUTO_TYPE_EXPEDITED1]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 9, 'auto_type' => MeetingAgenda::AUTO_TYPE_EXPEDITED2]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 10, 'auto_type' => MeetingAgenda::AUTO_TYPE_CORRESPONDENCE]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 10, 'auto_type' => MeetingAgenda::AUTO_TYPE_SUBMISSION_DOCS]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 10, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 11, 'auto_type' => MeetingAgenda::AUTO_TYPE_AMENDMENT_FAST]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 11, 'auto_type' => MeetingAgenda::AUTO_TYPE_SUBMISSION_DOCS]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 11, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 12, 'auto_type' => MeetingAgenda::AUTO_TYPE_EXPEDITED_FULL_BOARD]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 12, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 13, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 14, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 15, 'auto_type' => MeetingAgenda::AUTO_TYPE_RESUBMIT]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 15, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 16, 'auto_type' => MeetingAgenda::AUTO_TYPE_RENEW]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 16, 'auto_type' => MeetingAgenda::AUTO_TYPE_SUBMISSION_DOCS]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 16, 'auto_type' => MeetingAgenda::AUTO_TYPE_VOLUNTEER_NUMBER]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 16, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 17, 'auto_type' => MeetingAgenda::AUTO_TYPE_AMENDMENT]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 17, 'auto_type' => MeetingAgenda::AUTO_TYPE_VOLUNTEER_NUMBER]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 17, 'auto_type' => MeetingAgenda::AUTO_TYPE_SUBMISSION_DOCS]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 17, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);

        $this->insert('agenda_auto_desc', ['agenda_id' => 18, 'auto_type' => MeetingAgenda::AUTO_TYPE_DEVIATION]);
        $this->insert('agenda_auto_desc', ['agenda_id' => 18, 'auto_type' => MeetingAgenda::AUTO_TYPE_ASSESSMENT]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('agenda_auto_desc');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180224_065158_insert_auto_desc cannot be reverted.\n";

      return false;
      }
     */
}
