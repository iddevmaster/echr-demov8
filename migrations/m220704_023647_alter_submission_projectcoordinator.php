<?php

use yii\db\Migration;

/**
 * Class m220704_023647_alter_submission_projectcoordinator
 */
class m220704_023647_alter_submission_projectcoordinator extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission', 'project_coordinator_2nd_id', $this->integer());
        $this->addColumn('submission', 'project_coordinator_3rd_id', $this->integer());
        $this->addColumn('submission', 'project_viewer_id', $this->integer());
        $this->addColumn('project', 'project_coordinator_2nd_id', $this->integer());
        $this->addColumn('project', 'project_coordinator_3rd_id', $this->integer());
        $this->addColumn('project', 'project_viewer_id', $this->integer());

        $this->addForeignKey('fk_submission_project_coordinator_2nd_id', 'submission', 'project_coordinator_2nd_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_coordinator_3rd_id', 'submission', 'project_coordinator_3rd_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_project_viewer_id', 'submission', 'project_viewer_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_project_coordinator_2nd_id', 'project', 'project_coordinator_2nd_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_project_coordinator_3rd_id', 'project', 'project_coordinator_3rd_id', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_project_project_project_viewer_id', 'project', 'project_viewer_id', 'user', 'id', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('submission', 'project_coordinator_2nd_id');
        $this->dropColumn('submission', 'project_coordinator_3rd_id');
        $this->dropColumn('submission', 'project_viewer_id');
        $this->dropColumn('project', 'project_coordinator_2nd_id');
        $this->dropColumn('project', 'project_coordinator_3rd_id');
        $this->dropColumn('project', 'project_viewer_id');
        $this->dropForeignKey('fk_submission_project_coordinator_2nd_id', 'submission');
        $this->dropForeignKey('fk_submission_project_coordinator_3rd_id', 'submission');
        $this->dropForeignKey('fk_submission_project_viewer_id', 'submission');
        $this->dropForeignKey('fk_project_project_coordinator_2nd_id', 'project');
        $this->dropForeignKey('fk_project_project_coordinator_3rd_id', 'project');
        $this->dropForeignKey('fk_project_project_project_viewer_id', 'project');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m220704_023647_alter_submission_projectcoordinator cannot be reverted.\n";

      return false;
      }
     */
}
