<?php

use yii\db\Migration;

/**
 * Class m180312_065035_create_email_queue
 */
class m180312_065035_create_email_queue extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('email_queue', [
            'id' => $this->primaryKey(),
            'model_id' => $this->integer(),
            'type' => $this->integer(),
            'mail_at' => $this->dateTime(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('email_queue', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('email_queue', 'model_id', 'รหัสข้อมูลหลัก');
        $this->addCommentOnColumn('email_queue', 'type', 'ประเภทอีเมล์');
        $this->addCommentOnColumn('email_queue', 'mail_at', 'ส่งเมล์เมื่อ');
        $this->addCommentOnColumn('email_queue', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('email_queue', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('email_queue', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('email_queue', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('email_queue', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_email_queue_created_by', 'email_queue', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_email_queue_updated_by', 'email_queue', 'updated_by', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('email_queue');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180312_065035_create_email_queue cannot be reverted.\n";

      return false;
      }
     */
}
