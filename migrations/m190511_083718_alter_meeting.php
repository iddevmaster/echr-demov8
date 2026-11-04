<?php

use yii\db\Migration;

/**
 * Class m190511_083718_alter_meeting
 */
class m190511_083718_alter_meeting extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('meeting', 'checked_status', $this->integer());
        $this->addCommentOnColumn('meeting', 'checked_status', 'สถานะการตรวจสอบการประชุม');

        $this->addColumn('meeting', 'checked_staff', $this->integer());
        $this->addCommentOnColumn('meeting', 'checked_staff', 'เจ้าหน้าที่รับผิดชอบตรวจสอบรายงานการประชุม');
        $this->addForeignKey('fk_meeting_checked_staff', 'meeting', 'checked_staff', 'user', 'id');

        $this->addColumn('meeting', 'checked_secretary_first', $this->integer());
        $this->addCommentOnColumn('meeting', 'checked_secretary_first', 'เลขาหลักในการตรวจสอบรายงานการประชุม');
        $this->addForeignKey('fk_meeting_checked_secretary_first', 'meeting', 'checked_secretary_first', 'user', 'id');

        $this->addColumn('meeting', 'checked_secretary_second', $this->integer());
        $this->addCommentOnColumn('meeting', 'checked_secretary_second', 'เลขารองในการรวจสอบรายงานการประชุม');
        $this->addForeignKey('fk_meeting_checked_secretary_second', 'meeting', 'checked_secretary_second', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('meeting', 'checked_status');
        $this->dropColumn('meeting', 'checked_staff');
        $this->dropColumn('meeting', 'checked_secretary_first');
        $this->dropColumn('meeting', 'checked_secretary_second');
        $this->dropForeignKey('fk_meeting_checked_staff', 'meeting');
        $this->dropForeignKey('fk_meeting_checked_secretary_first', 'meeting');
        $this->dropForeignKey('fk_meeting_checked_secretary_second', 'meeting');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190511_083718_alter_meeting cannot be reverted.\n";

      return false;
      }
     */
}
