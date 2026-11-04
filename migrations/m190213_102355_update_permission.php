<?php

use yii\db\Migration;

/**
 * Class m190213_102355_update_permission
 */
class m190213_102355_update_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {


        $this->addColumn('submission', 'leader_comment', $this->text());
        $this->addColumn('submission', 'is_accept', $this->integer());
        $this->addCommentOnColumn('submission', 'leader_comment', 'comment เพิ่มเติมของหัวหน้าโครงการ');
        $this->addCommentOnColumn('submission', 'is_accept', 'สถานะตอบรับของหัวหน้าโครงการ');

        $this->addColumn('project_consultant', 'ack_token', $this->string());
        $this->addCommentOnColumn('project_consultant', 'ack_token', 'รหัสการตอบรับ');
        $this->addColumn('project_consultant', 'cv_file', $this->string());
        $this->addCommentOnColumn('project_consultant', 'cv_file', 'ไฟล์ประวัติ');

        $auth = Yii::$app->authManager;
        $roleR = $auth->getRole('นักวิจัย');
        $permissionsR = [
            'submission.pm-accept',
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
      echo "m190213_102355_update_permission cannot be reverted.\n";

      return false;
      }
     */
}
