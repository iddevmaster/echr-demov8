<?php

use yii\db\Migration;

/**
 * Class m190523_042611_update_permission
 */
class m190523_042611_update_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $roleSe = $auth->getRole('เลขานุการ');

        $permissionsR = [
            'submission-committee-document.create',
        ];
        $permissionsS = [
            'meeting.staff-check',
        ];
        $permissionsSe = [
            'meeting.check-secretary',
        ];
        foreach ($permissionsR as $permR) {
            $pR = $auth->createPermission('echr' . ".{$permR}");
//            $auth->add($pR);
            $auth->addChild($roleR, $pR);
        }
        foreach ($permissionsS as $permS) {
            $pS = $auth->createPermission('echr' . ".{$permS}");
            $auth->add($pS);
            $auth->addChild($roleR, $pS);
        }
        foreach ($permissionsSe as $permSe) {
            $pSe = $auth->createPermission('echr' . ".{$permSe}");
            $auth->add($pSe);
            $auth->addChild($roleSe, $pSe);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190523_042611_update_permission cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190523_042611_update_permission cannot be reverted.\n";

      return false;
      }
     */
}
