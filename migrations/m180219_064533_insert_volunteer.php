<?php

use yii\db\Migration;

/**
 * Class m180219_064533_insert_volunteer
 */
class m180219_064533_insert_volunteer extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('volunteer_number', ['id' => 1, 'name' => 'จำนวนอาสาสมัครที่ต้องการทั้งหมด']);
        $this->insert('volunteer_number', ['id' => 2, 'name' => 'จำนวนอาสาสมัครที่ลงนามยินยอมเข้าร่วมโครงการ (Total subjects consented)']);
        $this->insert('volunteer_number', ['id' => 3, 'name' => 'จำนวนอาสาสมัครที่ไม่ผ่านการคัดกรอง (Screening failure)']);
        $this->insert('volunteer_number', ['id' => 4, 'name' => 'จำนวนอาสาสมัครที่ถอนตัวออกจากโครงการ (Withdrawal)']);
        $this->insert('volunteer_number', ['id' => 5, 'name' => 'จำนวนที่อาสาสมัครเสียชีวิตระหว่างการวิจัย (Death)']);
        $this->insert('volunteer_number', ['id' => 6, 'name' => 'จำนวนอาสาสมัครที่อยู่ในระหว่างการวิจัย (Active subjects)']);
        $this->insert('volunteer_number', ['id' => 7, 'name' => 'จำนวนอาสาสมัครที่อยู่ในระหว่างติดตาม (Subjects in follow-up)']);
        $this->insert('volunteer_number', ['id' => 8, 'name' => 'จำนวนอาสาสมัครที่เสร็จสิ้นการวิจัย (Completed or Inactive subjects)']);
        $this->insert('volunteer_number', ['id' => 9, 'name' => 'มีเหตุการณ์ไม่พึงประสงค์ที่เกิดขึ้นกับอาสาสมัครทั้งสิ้น']);
        $this->insert('volunteer_number', ['id' => 10, 'name' => 'เป็นเหตุการณ์ไม่พึงประสงค์ชนิดร้ายแรง หรือที่ไม่คาดคิดมาก่อน']);
        
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 1]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 2]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 3]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 4]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 5]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 6]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 7]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 7, 'volunteer_number_id' => 8]);
        
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 1]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 2]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 3]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 4]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 5]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 6]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 7]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 8, 'volunteer_number_id' => 8]);
        
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 1]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 2]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 3]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 4]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 5]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 6]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 7]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 9, 'volunteer_number_id' => 8]);
        
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 1]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 2]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 3]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 4]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 5]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 8]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 9]);
        $this->insert('submission_type_volunteer_number', ['submission_type_id' => 13, 'volunteer_number_id' => 10]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('submission_type_volunteer_number');
        $this->delete('volunteer_number');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180219_064533_insert_volunteer cannot be reverted.\n";

      return false;
      }
     */
}
