<?php

use yii\db\Migration;

/**
 * Class m190509_081333_add_permission_for_secretary
 */
class m190509_081333_add_permission_for_secretary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เลขานุการ');
        $permissionsR = [
            'meeting-agenda.update-info-secretary',

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
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190509_081333_add_permission_for_secretary cannot be reverted.\n";

        return false;
    }
    */
}
