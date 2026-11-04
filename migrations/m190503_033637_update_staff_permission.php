<?php

use yii\db\Migration;

/**
 * Class m190503_033637_update_staff_permission
 */
class m190503_033637_update_staff_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('เจ้าหน้าที่');
        $permissionsR = [
            'submission.change-responsible',

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
        echo "m190503_033637_update_staff_permission cannot be reverted.\n";

        return false;
    }
    */
}
