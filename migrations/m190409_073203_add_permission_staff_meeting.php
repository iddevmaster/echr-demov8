<?php

use yii\db\Migration;

/**
 * Class m190409_073203_add_permission_staff_meeting
 */
class m190409_073203_add_permission_staff_meeting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $permissionsR = [
            'meeting-agenda.*',

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
        echo "m190409_073203_add_permission_staff_meeting cannot be reverted.\n";

        return false;
    }
    */
}
