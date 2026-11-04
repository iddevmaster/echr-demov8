<?php

use yii\db\Migration;

/**
 * Class m171224_102248_add_field_meeting_agenda
 */
class m171224_102248_add_field_meeting_agenda extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('meeting_agenda', 'agenda_id', $this->integer());
        $this->addCommentOnColumn('meeting_agenda', 'agenda_id', 'วาระการประชุม');
        $this->addForeignKey('fk_meeting_agenda_agenda_id', 'meeting_agenda', 'agenda_id', 'agenda', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        echo "m171224_102248_add_field_meeting_agenda cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m171224_102248_add_field_meeting_agenda cannot be reverted.\n";

      return false;
      }
     */
}
