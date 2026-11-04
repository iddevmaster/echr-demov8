<?php

use yii\db\Migration;

/**
 * Class m190505_042942_update_permission
 */
class m190505_042942_update_permission extends Migration {

    public function safeUp() {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $permissionsR = [
            'submission-result-document.delete',
        ];

        foreach ($permissionsR as $permR) {
            $pR = $auth->createPermission('echr' . ".{$permR}");
            $auth->add($pR);
            $auth->addChild($roleR, $pR);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $permissionsR = [
            'submission-result-document.delete',
        ];
        foreach ($permissionsR as $permR) {
            $pR = $auth->getPermission('echr' . ".{$permR}");
            $auth->removeChild($roleR, $pR);
            $auth->remove($pR);
        }
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190505_042942_update_permission cannot be reverted.\n";

      return false;
      }
     */
}
