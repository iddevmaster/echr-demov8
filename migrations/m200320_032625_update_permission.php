<?php

use yii\db\Migration;

/**
 * Class m200320_032625_update_permission
 */
class m200320_032625_update_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;
        $roles = [
            'เจ้าหน้าที่',
        ];
        $permissionsA = [
            'submission-document.group-document',
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
        ];
        $permissionsA = [
            'submission-document.group-document',
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
        echo "m200320_032625_update_permission cannot be reverted.\n";

        return false;
    }
    */
}
