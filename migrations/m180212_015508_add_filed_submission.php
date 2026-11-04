<?php

use yii\db\Migration;

/**
 * Class m180212_015508_add_filed_submission
 */
class m180212_015508_add_filed_submission extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('submission', 'responsible_person', $this->integer());
        $this->addColumn('submission', 'responsible_date', $this->dateTime());
        $this->addColumn('submission', 'meeting_plan_date', $this->dateTime());
        $this->addColumn('submission', 'send_plan_date', $this->dateTime());

        $this->addCommentOnColumn('submission', 'responsible_person', 'เจ้าหน้าที่ที่รับผิดชอบ');
        $this->addCommentOnColumn('submission', 'responsible_date', 'วันที่รับงาน');
        $this->addCommentOnColumn('submission', 'meeting_plan_date', 'วันที่คาดว่าโครงการจะเข้าที่ประชุมโดยประมาณ');
        $this->addCommentOnColumn('submission', 'send_plan_date', 'กรุณาส่งแบบประเมินก่อนวันที่');

        $this->addForeignKey('fk_submission_responsible_person', 'submission', 'responsible_person', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('submission', 'send_plan_date');
        $this->dropColumn('submission', 'meeting_plan_date');        
        $this->dropColumn('submission', 'responsible_date');
        $this->dropColumn('submission', 'responsible_person');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180212_015508_add_filed_submission cannot be reverted.\n";

      return false;
      }
     */
}
