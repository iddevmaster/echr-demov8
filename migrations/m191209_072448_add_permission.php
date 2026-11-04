<?php

use yii\db\Migration;

/**
 * Class m191209_072448_add_permission
 */
class m191209_072448_add_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;

        $roles = [
            'เจ้าหน้าที่',
        ];
        $permissionsA = [
            'site.report',
            'site.report-new',
            'site.report-con',
            'site.report-new-panel',
            'site.report-con-panel',
            'site.report-new-monthly',
            'site.report-con-monthly',
            'site.report-submission-statistics',
            'site.report-agenda-panel-report',
            'site.report-agenda-5year-report',
            'site.report-summary-duration',
            'site.submission-fda-report',
            'continue-assess-form.create',
            'c-assess-form.create',
            'sae-assess-form.create',
        ];
        $rolesB = [
            'กรรมการ',
        ];
        $permissionsB = [
            'continue-assess-form.create',
            'c-assess-form.create',
            'sae-assess-form.create',
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
        foreach ($permissionsB as $permA) {
            $pA = $auth->getPermission(\Yii::$app->id . ".{$permA}");
            if (!isset($pA)) {
                $pA = $auth->createPermission(\Yii::$app->id . ".{$permA}");
                $auth->add($pA);
            }
            foreach ($rolesB as $role) {
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
        ];
        $permissionsA = [
            'site.report',
            'site.report-new',
            'site.report-con',
            'site.report-new-panel',
            'site.report-con-panel',
            'site.report-new-monthly',
            'site.report-con-monthly',
            'site.report-submission-statistics',
            'site.report-agenda-panel-report',
            'site.report-agenda-5year-report',
            'site.report-summary-duration',
            'site.submission-fda-report',
            'continue-assess-form.create',
            'c-assess-form.create',
            'sae-assess-form.create',
        ];
        $rolesB = [
            'กรรมการ',
        ];
        $permissionsB = [
            'continue-assess-form.create',
            'c-assess-form.create',
            'sae-assess-form.create',
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
        foreach ($permissionsB as $permR) {
            $pR = $auth->getPermission(\Yii::$app->id . ".{$permR}");
            if (isset($pR)) {
                foreach ($rolesB as $role) {
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
      echo "m191209_072448_add_permission cannot be reverted.\n";

      return false;
      }
     */
}
