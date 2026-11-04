<?php

use yii\db\Migration;

/**
 * Class m260620_100001_create_training_type
 *
 * Master data for training types (ประเภทการอบรม) with a configurable validity
 * period in years. Used to auto-compute training expiry dates.
 */
class m260620_100001_create_training_type extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('training_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->comment('ชื่อประเภทการอบรม'),
            'validity_years' => $this->integer()->null()->comment('ระยะเวลาที่ถือว่าใช้ได้ (ปี) NULL=ไม่หมดอายุ'),
            'deleted' => $this->smallInteger()->defaultValue(0)->comment('0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => $this->integer()->comment('สร้างโดย'),
            'created_at' => $this->dateTime()->comment('สร้างเมื่อ'),
            'updated_by' => $this->integer()->comment('ปรับปรุงโดย'),
            'updated_at' => $this->dateTime()->comment('ปรับปรุงเมื่อ'),
        ]);

        // Seed the 5 standard training types, all valid for 5 years (admin-editable).
        $this->batchInsert('training_type', ['id', 'name', 'validity_years', 'deleted'], [
            [1, 'จริยธรรมการวิจัยในมนุษย์พื้นฐาน (Human Subject Protection)', 5, 0],
            [2, 'จริยธรรมการวิจัยในมนุษย์สำหรับการวิจัยชีวการแพทย์ (Biomedical Research Ethics)', 5, 0],
            [3, 'การปฏิบัติการวิจัยทางคลินิกที่ดี (Good Clinical Practice (GCP))', 5, 0],
            [4, 'จริยธรรมการวิจัยในมนุษย์ด้านพฤติกรรมศาสตร์และสังคมศาสตร์ (Social Science and Behavioral Science Research)', 5, 0],
            [5, 'การอบรมมาตรฐานการวิจัยทางคลินิกที่ดีเกี่ยวกับเครื่องมือแพทย์ (Medical device)', 5, 0],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('training_type');
    }

}
