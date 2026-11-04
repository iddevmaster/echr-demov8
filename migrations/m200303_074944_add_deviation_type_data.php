<?php

use yii\db\Migration;

/**
 * Class m200303_074944_add_deviation_type_data
 */
class m200303_074944_add_deviation_type_data extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->batchInsert('deviation_type', ['name'], [
            ['การสุ่มอาสาสมัครที่ไม่มีคุณสมบัติเหมาะสม'],
            ['การรับอาสาสมัครที่เข้าข่ายคัดออกจากโครงการ'],
            ['ไม่ได้ทำตามขั้นตอนการคัดกรองที่ระบุ'],
            ['ทำตามขั้นตอนการคัดกรองหรือขั้นตอนการวิจัยนอกระยะเวลาที่ระบุ'],
            ['การรักษาไม่ตรงตามที่ระบุ'],
            ['การให้ยาไม่ตรงตามที่ระบุ'],
            ['ไม่ทำตามขั้นตอนการวิจัยที่ระบุ'],
            ['การนัดหมายไม่ตรงตามที่ระบุ'],
            ['รายงานการต่ออายุล่าช้า'],
            ['รายงานความก้าวหน้าล่าช้า'],
            ['ไม่แจ้งปิดโครงการวิจัย'],
            ['ใช้แบบคำชี้แจงอาสาสมัครและแบบคำยินยอมอาสาสมัครฉบับที่ไม่ได้ประทับตรารับรองจากศูนย์ฯ'],
            ['ใช้ใบประชาสัมพันธ์ที่ไม่ได้ประทับตรารับรองจากศูนย์ฯ'],
            ['รายงาน SAE ล่าช้า'],
            ['อื่นๆ (Other)'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->delete('deviation_type');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200303_074944_add_deviation_type_data cannot be reverted.\n";

      return false;
      }
     */
}
