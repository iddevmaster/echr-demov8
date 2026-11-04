<?php

use yii\db\Migration;
use app\models\ReviewChoice;

/**
 * Class m191106_082419_insert_continue_assess_form
 */
class m191106_082419_insert_continue_assess_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->batchInsert('resolution', ['name', 'resolution'], [
            ['รับทราบ/รับรอง', 'Y'],
            ['รับทราบ/รับรองโดยมีเงื่อนไข', 'C'],
            ['ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำมาพิจารณาใหม่อีกครั้ง', 'R'],
            ['หยุดการรับเข้าอาสาสมัครใหม่เป็นการชั่วคราว', 'W'],
            ['ระงับโครงการวิจัยชั่วคราว', 'W'],
            ['ยุติโครงการ', 'T'],
        ]);
        
        $this->batchInsert('ethics', ['name', 'need_text'], [
            ['1. ความเสี่ยงต่อประโยชน์ของอาสาสมัคร', false],
            ['2. อาสาสมัครได้รับการปฏิบัติตามที่ระบุไว้ในโครงการวิจัย', false],
            ['3. อาสาสมัครได้รับข้อมูลที่ครบถ้วนเพียงพอต่อการตัดสินใจ', false],
            ['4. นักวิจัยได้ปฏิบัติตามแนวมาตรฐานการวิจัยทางคลินิกที่ดี', false],
            ['5. อื่นๆ ระบุ', true],
        ]);
        
        $this->batchInsert('review_choice', ['id', 'name', 'type', 'need_text', 'parent_id'], [
            [1, 'รายงานการแจ้งปิดโครงการ(วาระ 3.1)', ReviewChoice::TYPE_EXPEDITED, false, null],
            [2, 'รายงานความก้าวหน้า(วาระ 3.2)', ReviewChoice::TYPE_EXPEDITED, false, null],
            [3, 'รายงานเหตุการณ์ไม่พึงประสงค์ชนิดร้ายแรง(วาระ 3.5)', ReviewChoice::TYPE_EXPEDITED, false, null],
            [4, 'การขอแก้ไขปรับปรุงเพียงเล็กน้อย(วาระ 3.6) ได้แก่', ReviewChoice::TYPE_EXPEDITED, false, null],
            [5, 'การแก้ไขการสะกดคำ วันที่ ฉบับที่ และการจัดรูปเล่มใหม่ของโครงร่างการวิจัยหรือเอกสารที่เกี่ยวข้องกับ Investigator’s Brochure', ReviewChoice::TYPE_EXPEDITED, false, 4],
            [6, 'การแก้ไขชื่อผู้ประสานงานโครงการวิจัย เฉพาะส่วนที่ไม่ เกี่ยวข้องกับชื่อที่ระบุไว้ในเอกสารคำชี้แจง', ReviewChoice::TYPE_EXPEDITED, false, 4],
            [7, 'ข้อความประชาสัมพันธ์เชิญชวนอาสาสมัครเข้าสู่โครงการ', ReviewChoice::TYPE_EXPEDITED, false, 4],
            [8, 'สัญญา ข้อตกลงการส่งมอบวัสดุ เอกสารที่ใช้ในการวิจัย', ReviewChoice::TYPE_EXPEDITED, false, 4],
            [9, 'การขอต่ออายุการรับรอง(วาระ 4.5)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [10, 'การขอแก้ไขปรับปรุงโครงการหรือขอรับรองเอกสารเพิ่มเติม (วาระ 4.6)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [11, 'การกระทำที่เบี่ยงเบน/ฝ่าฝืนจากโครงการวิจัย (วาระ 4.7)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [12, 'รายงานการไม่ปฏิบัติตามข้อกำหนด (วาระ 4.7)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [13, 'รายงานเหตุการณ์ไม่พึงประสงค์ชนิดร้ายแรงที่อาจเป็นอันตราย แก่อาสาสมัครคนอื่น (วาระ 4.7)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [14, 'รายงานเรื่องร้องเรียน (วาระ 4.7)', ReviewChoice::TYPE_FULL_BOARD, false, null],
            [15, 'รายงานอื่นๆ เช่น', ReviewChoice::TYPE_FULL_BOARD, true, null],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->delete('continue_assess_form_review');
        $this->delete('continue_assess_form_ethics');
        $this->delete('continue_assess_form');
        $this->dropForeignKey('fk_review_choice_parent_id', 'review_choice');
        $this->delete('review_choice');
        $this->delete('ethics');
        $this->delete('resolution');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191106_082419_insert_continue_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
