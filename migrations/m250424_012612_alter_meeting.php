<?php

use yii\db\Migration;

/**
 * Class m250424_012612_alter_meeting
 */
class m250424_012612_alter_meeting extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('meeting', 'checked_president', $this->integer());
        $this->addColumn('meeting', 'pre_checked_at', $this->dateTime());

        $this->addCommentOnColumn('meeting', 'checked_president', 'ประธานตรวจสอบรายงานการประชุม');
        $this->addCommentOnColumn('meeting', 'pre_checked_at', 'วันที่ประธานตรวจสอบ');

        $this->addForeignKey('fk_meeting_checked_president', 'meeting', 'checked_president', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('meeting', 'checked_president');
        $this->dropForeignKey('fk_meeting_checked_president', 'meeting');
        $this->dropColumn('meeting', 'pre_checked_at');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m250424_012612_alter_meeting cannot be reverted.\n";

      return false;
      }
     */
}
