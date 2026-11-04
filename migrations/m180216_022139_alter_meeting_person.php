<?php

use yii\db\Migration;

/**
 * Class m180216_022139_alter_meeting_person
 */
class m180216_022139_alter_meeting_person extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('meeting_person', 'role_id', $this->integer());
        $this->addCommentOnColumn('meeting_person', 'role_id', 'หน้าที่');
        $this->addForeignKey('fk_meeting_person_role_id', 'meeting_person', 'role_id', 'role', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropForeignKey('fk_meeting_person_role_id', 'meeting_person');
//        echo "m180216_022139_alter_meeting_person cannot be reverted.\n";
//
//        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180216_022139_alter_meeting_person cannot be reverted.\n";

      return false;
      }
     */
}
