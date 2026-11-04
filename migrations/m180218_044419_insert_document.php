<?php

use yii\db\Migration;

/**
 * Class m180218_044419_insert_document
 */
class m180218_044419_insert_document extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('document', ['id' => 1, 'number' => 1, 'name' => 'แบบรายงานความก้าวหน้าการดำเนินงานวิจัย', 'template_file' => 'AF_01_06_03.0_progress.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 2, 'number' => 1, 'name' => 'แบบรายงานผลการดำเนินการวิจัย', 'template_file' => 'AF_02_06_03.0_Renew.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 3, 'number' => 1, 'name' => 'แบบรายงานการแก้ไขปรับปรุงโครงการวิจัย', 'template_file' => 'AF_03_06_03.0_Amendment.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 4, 'number' => 1, 'name' => 'แบบรายงานเหตุการณ์ไม่พึงประสงค์ชนิดร้ายแรงที่เกิดแก่อาสาสมัครในสถาบัน', 'template_file' => 'AF_04_06_03.0_SAE_in-site.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 5, 'number' => 1, 'name' => 'แบบรายงานเหตุการณ์ไม่พึงประสงค์ชนิดร้ายแรงที่เกิดแก่อาสาสมัครนอกสถาบัน', 'template_file' => 'AF_05_06_03.0_SAE_out-site.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 6, 'number' => 1, 'name' => 'แบบแจ้งการดำเนินงานวิจัยที่เบี่ยงเบน หรือฝ่าฝืนจากโครงร่างการวิจัยเดิม', 'template_file' => 'AF_06_06_03.0_Devia_viola_non-compi.doc', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 7, 'number' => 1, 'name' => 'แบบสรุปผลการดำเนินงานวิจัยเพื่อแจ้งปิดโครงการวิจัย', 'template_file' => 'AF_07_06_03.0_Closing.doc', 'role_id' => \app\models\Role::RESEARCHER]);

        $this->insert('document', ['id' => 8, 'number' => 1, 'name' => 'แบบเสนอขอรับคำปรึกษาด้านจริยธรรมการวิจัยเครื่องมือแพทย์', 'template_file' => 'AF-01-10-03.0-แบบเสนอโครงการ-medical-device.docx', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 9, 'number' => 1, 'name' => 'แบบเสนอเพื่อขอยกเว้นการพิจารณาจริยธรรมการวิจัยในมนุษย์', 'template_file' => 'AF-05-03-03.2_แบบเสนอเพื่อขอยกเว้นการพิจารณาจริยธรรมฯ-Exemption.docx', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 10, 'number' => 1, 'name' => 'แบบเสนอขอรับการพิจารณาสำหรับโครงการวิจัยที่เข้าข่ายการพิจารณาแบบเร็ว (Expedited review)', 'template_file' => 'AF-06-03-03.2_แบบเสนอขอรับการพิจารณาจริยธรรมฯแบบเร็ว-Expedited.docx', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 11, 'number' => 1, 'name' => 'แบบเสนอเพื่อขอรับการพิจารณาด้านจริยธรรมของการวิจัยในมนุษย์สำหรับโครงการวิจัยทดลองทางคลินิก (Clinical trial/Experimental study)', 'template_file' => 'AF-07-03-03.2_แบบเสนอขอรับการพิจารณาจริยธรรมฯ-ทางคลินิก-ฉบับภาษาไทย.docx', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 12, 'number' => 1, 'name' => 'แบบเสนอเพื่อขอรับการพิจารณาด้านจริยธรรมของการวิจัยในมนุษย์สำหรับโครงการวิจัยด้านสังคมศาสตร์/มานุษยวิทยา (Social/Anthropological study)', 'template_file' => 'AF-09-03-03.2_แบบเสนอขอรับการพิจารณาจริยธรรมฯ-ทางสังคมศาสตร์ฯ-ฉบับภาษาไทย.docx', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 13, 'number' => 1, 'name' => 'บันทึกข้อความการชี้แจงต่อข้อคำถามหรือข้อเสนอแนะของคณะกรรมการฯ', 'template_file' => 'AF-20-03-03.2_บันทึกข้อความชี้แจงต่อข้อคำถามของกรรมการ.docx', 'role_id' => \app\models\Role::RESEARCHER]);

        $this->insert('document', ['id' => 14, 'number' => 1, 'name' => 'เอกสารประกอบอื่นๆที่เกี่ยวข้อง', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 15, 'number' => 1, 'name' => 'เอกสารคำชี้แจงและแบบฟอร์มยินยอมของอาสาสมัครคนแรก(ในกรณีเป็นรายงานครั้งที่1) หรือฉบับที่ใช้ปัจจุบัน', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 16, 'number' => 1, 'name' => 'หลักฐานการชำระค่าธรรมเนียม (เฉพาะโครงการที่มีแหล่งทุนสนับสนุนจากภายนอก มข.)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 17, 'number' => 1, 'name' => 'หลักฐานการชำระเงิน', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 18, 'number' => 1, 'name' => 'โครงการวิจัย/กิจกรรมฉบับสมบรูณ์', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 19, 'number' => 1, 'name' => 'เอกสารคำชี้แจงสำหรับอาสาสมัคร (ถ้าเกี่ยวข้อง)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 20, 'number' => 1, 'name' => 'แบบคำยินยอมให้ทำการวิจัยจากอาสาสมัคร หรือแบบเสนอขอยกเว้นการขอความยินยอมด้วยการลงนาม (ถ้าเกี่ยวข้อง)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 21, 'number' => 1, 'name' => 'ประวัติและความรู้ความชำนาญของผู้วิจัยและผู้ร่วมวิจัย ฉบับภาษาไทยหรือภาษาอังกฤษ', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 22, 'number' => 1, 'name' => 'เครื่องมือที่ใช้ในการวิจัย (เช่น แบบบันทึกข้อมูล, คู่มือนักวิจัย, แผ่นป้ายประชาสัมพันธ์, ฯลฯ)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 23, 'number' => 1, 'name' => 'สำเนาแบบเสนอเค้าโครงวิทยานิพนธ์หรือการศึกษาอิสระสำหรับนักศึกษาระดับบัณฑิตศึกษา  มหาวิทยาลัยขอนแก่น (บว. 23) (กรณีโครงการของนักศึกษาระดับบัณฑิตศึกษา)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 24, 'number' => 1, 'name' => 'หนังสือขออนุญาตใช้ตัวอย่างชีวภาพจากผู้อำนวยการโรงพยาบาล (ถ้าเกี่ยวข้อง)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 25, 'number' => 1, 'name' => 'หนังสือลงนามอนุญาตให้ใช้ตัวอย่างที่เหลือจากโครงการโดยหัวหน้าโครงการวิจัยเดิม และแบบคำชี้แจงอาสาสมัครของโครงการวิจัยเดิม (ถ้าเกี่ยวข้อง)', 'role_id' => \app\models\Role::RESEARCHER]);
        $this->insert('document', ['id' => 26, 'number' => 1, 'name' => 'หนังสืออนุญาตใช้ข้อมูลที่มีอยู่แล้วหรือแบบแบบคำชี้แจงอาสาสมัครของโครงการวิจัยเดิม (ถ้าเกี่ยวข้อง)', 'role_id' => \app\models\Role::RESEARCHER]);

        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 17, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 11, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 19, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 20, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 18, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 21, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 22, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 1, 'document_id' => 23, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 17, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 12, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 19, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 20, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 18, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 21, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 22, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 2, 'document_id' => 23, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 3, 'document_id' => 17, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 3, 'document_id' => 9, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 3, 'document_id' => 18, 'is_require' => TRUE]);

        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 17, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 10, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 19, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 20, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 18, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 21, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 22, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 23, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 24, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 25, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 4, 'document_id' => 26, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 7, 'document_id' => 1, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 7, 'document_id' => 15, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 7, 'document_id' => 14, 'is_require' => TRUE]);

        $this->insert('document_submission_type', ['submission_type_id' => 8, 'document_id' => 2, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 8, 'document_id' => 14, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 8, 'document_id' => 16, 'is_require' => TRUE]);

        $this->insert('document_submission_type', ['submission_type_id' => 9, 'document_id' => 3, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 9, 'document_id' => 14, 'is_require' => FALSE]);
        $this->insert('document_submission_type', ['submission_type_id' => 9, 'document_id' => 16, 'is_require' => TRUE]);

        $this->insert('document_submission_type', ['submission_type_id' => 10, 'document_id' => 4, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 10, 'document_id' => 14, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 11, 'document_id' => 5, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 11, 'document_id' => 14, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 12, 'document_id' => 6, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 12, 'document_id' => 14, 'is_require' => FALSE]);

        $this->insert('document_submission_type', ['submission_type_id' => 13, 'document_id' => 7, 'is_require' => TRUE]);
        $this->insert('document_submission_type', ['submission_type_id' => 13, 'document_id' => 14, 'is_require' => FALSE]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('document_submission_type');
        $this->delete('document');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180218_044419_insert_document cannot be reverted.\n";

      return false;
      }
     */
}
