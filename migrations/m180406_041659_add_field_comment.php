<?php

use yii\db\Migration;

/**
 * Class m180406_041659_add_field_comment
 */
class m180406_041659_add_field_comment extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('document', 'is_report', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('submission_committee', 'can_meeting', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('submission_committee', 'remark_meeting', $this->text());
        $this->alterColumn('submission_committee', 'remark', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->alterColumn('submission_committee', 'remark', $this->string());
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180406_041659_add_field_comment cannot be reverted.\n";

      return false;
      }
     */
}
