<?php

use yii\db\Migration;

/**
 * Class m180225_084930_alter_meeting_agenda
 */
class m180225_084930_alter_meeting_agenda extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('meeting_agenda', 'resolution', $this->string());
        $this->addCommentOnColumn('meeting_agenda', 'resolution', 'มติที่ประชุม');
        $this->createIndex('idx_meeting_agenda_resolution', 'meeting_agenda', 'resolution');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('meeting_agenda', 'resolution');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180225_084930_alter_meeting_agenda cannot be reverted.\n";

      return false;
      }
     */
}
