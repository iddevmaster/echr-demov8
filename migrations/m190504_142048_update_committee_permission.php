<?php

use yii\db\Migration;

/**
 * Class m190504_142048_update_committee_permission
 */
class m190504_142048_update_committee_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('กรรมการ');
        $permissionsR = [
            'submission-committee-document.delete',
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
        $roleR = $auth->getRole('กรรมการ');
        $permissionsR = [
            'submission-committee-document.delete',
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
      echo "m190504_142048_update_committee_permission cannot be reverted.\n";

      return false;
      }
     */
}
