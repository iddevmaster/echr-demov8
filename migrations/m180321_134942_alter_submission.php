<?php

use yii\db\Migration;

/**
 * Class m180321_134942_alter_submission
 */
class m180321_134942_alter_submission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission_type', 'add_subject', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addCommentOnColumn('submission_type', 'add_subject', 'ต้องเพิ่มเรื่อง');
        
        $this->addColumn('submission', 'subject', $this->string());
        $this->addColumn('submission', 'issue1', $this->text());
        $this->addColumn('submission', 'issue2', $this->text());
        $this->addCommentOnColumn('submission', 'subject', 'เรื่อง');
        $this->addCommentOnColumn('submission', 'issue1', 'ประเด็นเพิ่มเติม 1');
        $this->addCommentOnColumn('submission', 'issue2', 'ประเด็นเพิ่มเติม 2');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('submission', 'issue2');
        $this->dropColumn('submission', 'issue1');
        $this->dropColumn('submission', 'subject');
        $this->dropColumn('submission_type', 'add_subject');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180321_134942_alter_submission cannot be reverted.\n";

      return false;
      }
     */
}
