<?php

use yii\db\Migration;

/**
 * Class m190513_024646_alter_meeting
 */
class m190513_024646_alter_meeting extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('meeting', 'staff_checked_at', $this->dateTime());
        $this->addColumn('meeting', 'sec1_checked_at', $this->dateTime());
        $this->addColumn('meeting', 'sec2_checked_at', $this->dateTime());
        
        $this->addCommentOnColumn('meeting', 'staff_checked_at', 'วันที่เจ้าหน้าที่ตรวจสอบ');
        $this->addCommentOnColumn('meeting', 'sec1_checked_at', 'วันที่เลขาคนแรกตรวจสอบ');
        $this->addCommentOnColumn('meeting', 'sec2_checked_at', 'วันที่เลขาคนที่สองตรวจสอบ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('meeting', 'sec2_checked_at');
        $this->dropColumn('meeting', 'sec1_checked_at');
        $this->dropColumn('meeting', 'staff_checked_at');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190513_024646_alter_meeting cannot be reverted.\n";

      return false;
      }
     */
}
