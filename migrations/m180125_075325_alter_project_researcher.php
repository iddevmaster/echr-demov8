<?php

use yii\db\Migration;

/**
 * Class m180125_075325_alter_project_researcher
 */
class m180125_075325_alter_project_researcher extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('project_researcher', 'mail_sent', $this->boolean());
        $this->addColumn('project_researcher', 'mail_sent_at', $this->datetime());
        $this->addColumn('project_researcher', 'acknowledge_status', $this->integer());
        $this->addColumn('project_researcher', 'acknowledge_by', $this->integer());
        $this->addColumn('project_researcher', 'acknowledge_at', $this->datetime());
        
        $this->addCommentOnColumn('project_researcher', 'mail_sent', 'ส่งอีเมล์หรือยัง');
        $this->addCommentOnColumn('project_researcher', 'mail_sent_at', 'ส่งเมล์เมื่อ');
        $this->addCommentOnColumn('project_researcher', 'acknowledge_status', 'ผลตอบรับร่วมวิจัย');
        $this->addCommentOnColumn('project_researcher', 'acknowledge_by', 'ผู้ตอบรับ');
        $this->addCommentOnColumn('project_researcher', 'acknowledge_at', 'ตอบรับเมื่อ');
        
        $this->addForeignKey('fk_project_researcher_acknowledge_by', 'project_researcher', 'acknowledge_by', 'user', 'id');
        
        $this->addColumn('user', 'verify_token', $this->string());
        $this->addColumn('user', 'verify_at', $this->dateTime());
        
        $this->addCommentOnColumn('user', 'verify_token', 'รหัสการยืนยันอีเมล์');
        $this->addCommentOnColumn('user', 'verify_at', 'ยืนยันอีเมล์เมื่อ');
        
        $this->addColumn('person', 'mobile_no', $this->string());
        $this->addCommentOnColumn('person', 'mobile_no', 'เบอร์มือถือ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('person', 'mobile_no');
        
        $this->dropColumn('user', 'verify_at');
        $this->dropColumn('user', 'verify_token');
        
        $this->dropForeignKey('fk_project_researcher_acknowledge_by', 'project_researcher');
        $this->dropColumn('project_researcher', 'acknowledge_at');
        $this->dropColumn('project_researcher', 'acknowledge_by');
        $this->dropColumn('project_researcher', 'acknowledge_status');
        $this->dropColumn('project_researcher', 'mail_sent_at');
        $this->dropColumn('project_researcher', 'mail_sent');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180125_075325_alter_project_researcher cannot be reverted.\n";

      return false;
      }
     */
}
