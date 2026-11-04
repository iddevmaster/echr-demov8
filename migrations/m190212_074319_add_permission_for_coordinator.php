<?php

use yii\db\Migration;

/**
 * Class m190212_074319_add_permission_for_coordinator
 */
class m190212_074319_add_permission_for_coordinator extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->insert('auth_item', ['name' => 'ผู้ประสานงานโครงการ', 'type' => 1]);
        $auth = Yii::$app->authManager;

        $roleR = $auth->getRole('นักวิจัย');
        $permissionsR = [
            'project-consultant.*',
        ];

        foreach ($permissionsR as $permR) {
            $pR = $auth->createPermission('echr' . ".{$permR}");
            $auth->add($pR);
            $auth->addChild($roleR, $pR);
        }


        $role = $auth->getRole('ผู้ประสานงานโครงการ');
        $permissions = [
            'document.download-template',
            'index.box.researcher',
            'person-training.*',
            'person.search',
            'person.update',
            'project-consultant.*',
            'project-researcher.create',
            'project-researcher.delete',
            'project-researcher.send-ack-mail',
            'project.submission',
            'site.index',
            'site.index-researcher',
            'site.list',
            'site.logout',
            'site.select-project',
            'submission-document.create',
            'submission.continue',
            'submission.index',
            'submission.new',
            'submission.project-submission.*',
            'submission.send-edit',
            'submission.submission-complete',
        ];

        foreach ($permissions as $perm) {
            $p = $auth->createPermission('echr' . ".{$perm}");
            $auth->addChild($role, $p);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190212_074319_add_permission_for_coordinator cannot be reverted.\n";

      return false;
      }
     */
}
