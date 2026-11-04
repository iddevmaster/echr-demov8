<?php

use yii\db\Migration;

/**
 * Class m180129_083537_alter_project
 */
class m180129_083537_alter_project extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('project', 'fda_no', $this->string());
        $this->addCommentOnColumn('project', 'fda_no', 'เลขอย.');
        
        $this->addColumn('project_researcher', 'ack_token', $this->string());
        $this->addCommentOnColumn('project_researcher', 'ack_token', 'รหัสการตอบรับ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('project_researcher', 'ack_token');
        $this->dropColumn('project', 'fda_no');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180129_083537_alter_project cannot be reverted.\n";

      return false;
      }
     */
}
