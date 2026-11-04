<?php

use yii\db\Migration;

/**
 * Class m190505_090035_create_project_code_history
 */
class m190505_090035_create_project_code_history extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('project_code_history', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'submission_id' => $this->integer(),
            'old_code' => $this->string(),
            'new_code' => $this->string(),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('project_code_history', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('project_code_history', 'submission_id', 'submission');
        $this->addCommentOnColumn('project_code_history', 'project_id', 'โครงการ');
        $this->addCommentOnColumn('project_code_history', 'old_code', 'รหัสโครงการเดิม');
        $this->addCommentOnColumn('project_code_history', 'new_code', 'รหัสโครงการใหม่');
        $this->addCommentOnColumn('project_code_history', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('project_code_history', 'created_at', 'สร้างเมื่อ');


        $this->addForeignKey('fk_project_code_history_submission_id', 'project_code_history', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_project_code_history_project_id', 'project_code_history', 'project_id', 'project', 'id');
        $this->addForeignKey('fk_project_code_history_created_by', 'project_code_history', 'created_by', 'user', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('project_code_history');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190505_090035_create_project_code_history cannot be reverted.\n";

      return false;
      }
     */
}
