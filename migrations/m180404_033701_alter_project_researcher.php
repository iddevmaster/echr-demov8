<?php

use yii\db\Migration;

/**
 * Class m180404_033701_alter_project_researcher
 */
class m180404_033701_alter_project_researcher extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('project_researcher', 'cv_file', $this->string());
        $this->addCommentOnColumn('project_researcher', 'cv_file', 'ไฟล์ประวัติ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('project_researcher', 'cv_file');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180404_033701_alter_project_researcher cannot be reverted.\n";

      return false;
      }
     */
}
