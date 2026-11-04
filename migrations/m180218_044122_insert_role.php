<?php

use yii\db\Migration;

/**
 * Class m180218_044122_insert_role
 */
class m180218_044122_insert_role extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $roles = \app\models\Role::roleLabels();
        $auth = Yii::$app->authManager;
        
        $this->insert('job_category', ['id' => 1, 'name' => 'แพทย์']);
        $this->insert('job_category', ['id' => 2, 'name' => 'บุคลากรที่เกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('job_category', ['id' => 3, 'name' => 'บุคคลที่ไม่ใช่สาขาวิทยาศาสตร์']);
        
        foreach ($roles as $key => $val) {
            $data = ['id' => $key, 'name' => $val];
            if (in_array($key, \app\models\Role::meetingRoles())) {
                $data['is_meeting_role'] = 1;
            }
            $this->insert('role', $data);
            $r = $auth->createRole($val);
            $auth->add($r);
        }
        $panels = [1, 2, 3];
        
        $this->insert('person_role', ['id' => 1, 'person_id' => 1, 'role_id' => 1]);
        foreach ($panels as $i) {
            $this->insert('panel', ['id' => $i, 'name' => "Panel {$i}"]);
            $this->insert('person_role_panel', ['person_role_id' => 1, 'panel_id' => $i]);
        }
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $roles = \app\models\Role::roleLabels();
        $auth = Yii::$app->authManager;
        
        $this->delete('person_role_panel');
        $this->delete('person_role');
        $this->delete('panel');
        
        foreach ($roles as $key => $val) {
            $this->delete('role', ['id' => $key]);
            $r = $auth->getRole($val);
            $auth->remove($r);
        }
        
        $this->delete('job_category');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180218_044122_insert_role cannot be reverted.\n";

      return false;
      }
     */
}
