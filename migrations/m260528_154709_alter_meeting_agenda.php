<?php

use yii\db\Migration;

/**
 * Class m260528_154709_alter_meeting_agenda
 */
class m260528_154709_alter_meeting_agenda extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'special_condition', $this->text());
        $this->addCommentOnColumn('submission', 'special_condition', 'special condition ในหนังสือรับรอง');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('submission_document', 'is_certificate');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260528_154709_alter_meeting_agenda cannot be reverted.\n";

      return false;
      }
     */
}
