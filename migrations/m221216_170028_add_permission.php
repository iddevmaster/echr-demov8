<?php

use yii\db\Migration;

/**
 * Class m221216_170027_add_permission
 */
class m221216_170028_add_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;

        $roles = [
            'เจ้าหน้าที่',
            'นักวิจัย',
            'กรรมการ',
            'ผู้ประสานงานโครงการ',
            'ประธานคณะกรรมการ',
            'รองประธานคณะกรรมการ',
            'เลขานุการ',
        ];
        $permissionsA = [
//            'submission-document.view-file',
            'person.view-file',
            'person-training.view-file',
        ];

        foreach ($permissionsA as $permA) {
            $pA = $auth->getPermission(\Yii::$app->id . ".{$permA}");
            if (!isset($pA)) {
                $pA = $auth->createPermission(\Yii::$app->id . ".{$permA}");
                $auth->add($pA);
            }
            foreach ($roles as $role) {
                $r = $auth->getRole($role);
                if (isset($r)) {
                    if (!$auth->hasChild($r, $pA)) {
                        $auth->addChild($r, $pA);
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $auth = Yii::$app->authManager;
        $roles = [
            'เจ้าหน้าที่',
            'นักวิจัย',
        ];
        $permissionsA = [
            'project-researcher.accept',
            'project-consultant.accept',
        ];

        foreach ($permissionsA as $permR) {
            $pR = $auth->getPermission(\Yii::$app->id . ".{$permR}");
            if (isset($pR)) {
                foreach ($roles as $role) {
                    $r = $auth->getRole($role);
                    if (isset($r)) {
                        $auth->removeChild($r, $pR);
                    }
                }
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
      echo "m221216_170027_add_permission cannot be reverted.\n";

      return false;
      }
     */
}
