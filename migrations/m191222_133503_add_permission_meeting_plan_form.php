<?php

use yii\db\Migration;

/**
 * Class m191222_133503_add_permission_meeting_plan_form
 */
class m191222_133503_add_permission_meeting_plan_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;

        $roleS = $auth->getRole('เจ้าหน้าที่');
        $permissionsA = [
            'submission.meeting-plan',
            'submission.*',
        ];

        foreach ($permissionsA as $permA) {
            $pA = $auth->getPermission(\Yii::$app->id . ".{$permA}");
            if (!isset($pA)) {
                $pA = $auth->createPermission(\Yii::$app->id . ".{$permA}");
                $auth->add($pA);
            }
            if ($auth->canAddChild($roleS, $pA)) {
                $auth->addChild($roleS, $pA);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $auth = Yii::$app->authManager;
        $roleS = $auth->getRole('เจ้าหน้าที่');
        $permissionsA = [
            'submission.meeting-plan',
            'submission.*',
        ];
        foreach ($permissionsA as $permR) {
            $pR = $auth->getPermission(\Yii::$app->id . ".{$permR}");
            if (isset($pR)) {
                $auth->removeChild($roleS, $pR);
            }
        }
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191222_133503_add_permission_meeting_plan_form cannot be reverted.\n";

      return false;
      }
     */
}
