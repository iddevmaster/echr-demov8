<?php

use yii\db\Migration;
use app\models\Role;
use app\models\Person;
use app\models\User;
use app\models\PersonRole;
/**
 * Class m200129_042227_add_web_api_permission
 */
class m200129_042227_add_web_api_permission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;

        $user = new app\models\User();
        $user->generateAuthKey();
        $user->setPassword('webapiP@ssw0rd');

        $u = User::findByUsername('webapi');
        if (!isset($u)) {
            $this->insert('user', ['username' => 'webapi', 'auth_key' => $user->auth_key, 'password_hash' => $user->password_hash]);
            $u = User::findByUsername('webapi');
        }
        $j = Role::find()->isDeleted(false)->andWhere(['name' => 'webapi'])->one();
        if (!isset($j)) {
            $this->insert('role', ['name' => 'webapi']);
            $j = Role::find()->isDeleted(false)->andWhere(['name' => 'webapi'])->one();
        }
        $e = Person::find()->isDeleted(false)->andWhere(['user_id' => $u->id])->one();
        if (!isset($e)) {
            $this->insert('person', ['role_id' => $j->id, 'title_id' => 1, 'user_id' => $u->id, 'first_name' => 'webapi', 'last_name' => 'webapi']);
            $e = Person::find()->isDeleted(false)->andWhere(['user_id' => $u->id])->one();
        }
        $pr = PersonRole::find()->isDeleted(false)->person($e->id)->role($j->id)->one();
        if (!isset($pr)) {
            $this->insert('person_role', ['role_id' => $j->id, 'person_id' => $e->id]);
        }
        $role = $auth->createRole('webapi');
        $auth->add($role);
        $auth->assign($role, $u->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('webapi');
        $u = User::findByUsername('webapi');
        $auth->revoke($role, $u->id);
        $auth->remove($role);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200129_042227_add_web_api_permission cannot be reverted.\n";

      return false;
      }
     */
}
