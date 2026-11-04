<?php

use yii\db\Migration;

/**
 * Class m190402_141156_update_permission_crec
 */
class m190402_141156_update_permission_meeting_for_staff_crec extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $permissionsR = [
            'meeting.*',
            'person.role-name-by-panel',
            'meeting-agenda.update-info',
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
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190402_141156_update_permission_crec cannot be reverted.\n";

      return false;
      }
     */
}
