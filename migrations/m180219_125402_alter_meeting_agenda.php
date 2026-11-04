<?php

use yii\db\Migration;

/**
 * Class m180219_125402_alter_meeting_agenda
 */
class m180219_125402_alter_meeting_agenda extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('agenda', 'addable', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('agenda', 'is_submission', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('agenda', 'need_resolution', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('agenda', 'addable', 'สามารถเพิ่มวาระได้');
        $this->addCommentOnColumn('agenda', 'is_submission', 'เป็นวาระเกี่ยวกับการส่งโครงการ');
        $this->addCommentOnColumn('agenda', 'need_resolution', 'ต้องระบุมติ');
        
        $this->addColumn('meeting_agenda', 'addable', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('meeting_agenda', 'sortable', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('meeting_agenda', 'need_resolution', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('meeting_agenda', 'addable', 'สามารถเพิ่มวาระได้');
        $this->addCommentOnColumn('meeting_agenda', 'sortable', 'จัดเรียงได้หรือไม่');
        $this->addCommentOnColumn('meeting_agenda', 'need_resolution', 'ต้องระบุมติ');
        
        $this->update('agenda', ['addable' => TRUE], ['id' => [2, 5]]);
        $this->update('agenda', ['is_submission' => TRUE], ['not', ['parent_id' => NULL]]);
        $this->update('agenda', ['need_resolution' => TRUE], ['id' => 1]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('meeting_agenda', 'need_resolution');
        $this->dropColumn('meeting_agenda', 'sortable');
        $this->dropColumn('meeting_agenda', 'addable');
        $this->dropColumn('agenda', 'need_resolution');
        $this->dropColumn('agenda', 'is_submission');
        $this->dropColumn('agenda', 'addable');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180219_125402_alter_meeting_agenda cannot be reverted.\n";

      return false;
      }
     */
}
