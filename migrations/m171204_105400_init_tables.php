<?php

use yii\db\Migration;

/**
 * Class m171204_105400_init_tables
 */
class m171204_105400_init_tables extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->createIndex('idx_user_username', 'user', ['username']);

        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('role', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('role', 'name', 'ชื่อหน้าที่');
        $this->addCommentOnColumn('role', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('role', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('role', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('role', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('role', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_role_name', 'role', ['name']);
        $this->addForeignKey('fk_role_user_created_by', 'role', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_role_user_updated_by', 'role', 'updated_by', 'user', 'id');

        $this->createTable('title', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('title', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('title', 'name', 'ชื่อคำนำหน้าชื่อ');
        $this->addCommentOnColumn('title', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('title', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('title', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('title', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('title', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_title_name', 'title', ['name']);
        $this->addForeignKey('fk_title_user_created_by', 'title', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_title_user_updated_by', 'title', 'updated_by', 'user', 'id');

        $this->createTable('person', [
            'id' => $this->primaryKey(),
            'idcard_no' => $this->string(),
            'title_id' => $this->integer(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'role_id' => $this->integer(),
            'user_id' => $this->integer(),
            'email' => $this->string(),
            'tel' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('person', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('person', 'idcard_no', 'เลขบัตรประชาขน');
        $this->addCommentOnColumn('person', 'title_id', 'รหัสคำนำหน้าชื่อ');
        $this->addCommentOnColumn('person', 'first_name', 'ชื่อ');
        $this->addCommentOnColumn('person', 'last_name', 'นามสกุล');
        $this->addCommentOnColumn('person', 'email', 'อีเมลล์');
        $this->addCommentOnColumn('person', 'tel', 'เบอร์โทรศัพท์');
        $this->addCommentOnColumn('person', 'role_id', 'รหัสหน้าที่');
        $this->addCommentOnColumn('person', 'user_id', 'รหัสผู้ใช้');
        $this->addCommentOnColumn('person', 'deleted', '0=ไม่ลบ,1=ลบ');
        $this->addCommentOnColumn('person', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('person', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('person', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('person', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_person_idcard_no', 'person', ['idcard_no']);
        $this->addForeignKey('fk_person_title_title_id', 'person', 'title_id', 'title', 'id');
        $this->addForeignKey('fk_person_role_role_id', 'person', 'role_id', 'role', 'id');
        $this->addForeignKey('fk_person_created_by', 'person', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_person_updated_by', 'person', 'updated_by', 'user', 'id');
        $this->addForeignKey('fk_person_user_id', 'person', 'user_id', 'user', 'id');

        $user = new app\models\User();
        $user->generateAuthKey();
        $user->setPassword('1q2w3e4r');
        $this->insert('user', ['id' => 1, 'username' => 'admin', 'auth_key' => $user->auth_key, 'password_hash' => $user->password_hash]);

        $this->insert('title', ['id' => 1, 'name' => 'นาย']);
        $this->insert('title', ['id' => 2, 'name' => 'น.ส.']);
        $this->insert('title', ['id' => 3, 'name' => 'นาง']);

        $this->insert('role', ['id' => 1, 'name' => 'ผู้ดูแลระบบ']);

        $this->insert('person', ['id' => 1, 'role_id' => 1, 'title_id' => 1, 'user_id' => 1, 'first_name' => 'ปฏิพัทธิ์', 'last_name' => 'ทิพยศิรินทร์']);
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('ผู้ดูแลระบบ');
        $auth->add($admin);
        $auth->assign($admin, 1);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        $this->dropTable('person');
        $this->dropTable('title');
        $this->dropTable('role');
        $this->dropTable('user');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m171204_105400_init_tables cannot be reverted.\n";

      return false;
      }
     */
}
