<?php

use yii\db\Migration;

/**
 * Class m260620_100004_create_person_document_audit
 *
 * Audit trail for electronic signing of researcher documents (CV / training
 * certificates): who signed, when, with which auth method, the certification
 * statement they confirmed, and whether a stamp/cover sheet was applied.
 */
class m260620_100004_create_person_document_audit extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('person_document_audit', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->comment('เจ้าของเอกสาร (person_id)'),
            'doc_type' => $this->smallInteger()->comment('1=CV, 2=TRAINING'),
            'ref_id' => $this->integer()->null()->comment('person_training.id สำหรับเอกสารอบรม'),
            'file_name' => $this->string(255)->null()->comment('ชื่อไฟล์ขณะลงนาม'),
            'certify_confirmed' => $this->smallInteger()->defaultValue(0)->comment('กดยืนยันรับรองความถูกต้องแล้ว'),
            'certify_statement' => $this->text()->null()->comment('ข้อความรับรองที่แสดง (snapshot)'),
            'signed' => $this->smallInteger()->defaultValue(0)->comment('ลงนามแล้ว'),
            'signer_person_id' => $this->integer()->null()->comment('ผู้ลงนาม (person_id)'),
            'signer_name' => $this->string(255)->null()->comment('ชื่อผู้ลงนาม (snapshot)'),
            'signed_at' => $this->dateTime()->null()->comment('วันเวลาที่ลงนาม'),
            'auth_method' => $this->smallInteger()->null()->comment('1=PASSWORD, 2=OTP'),
            'stamp_applied' => $this->smallInteger()->defaultValue(0)->comment('ประทับตราลงไฟล์แล้ว'),
            'stamp_type' => $this->smallInteger()->null()->comment('1=PDF_OVERLAY, 2=COVERSHEET'),
            'ip_address' => $this->string(64)->null()->comment('IP ผู้ลงนาม'),
            'user_agent' => $this->string(255)->null()->comment('User agent ผู้ลงนาม'),
            'deleted' => $this->smallInteger()->defaultValue(0)->comment('0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => $this->integer()->comment('สร้างโดย'),
            'created_at' => $this->dateTime()->comment('สร้างเมื่อ'),
            'updated_by' => $this->integer()->comment('ปรับปรุงโดย'),
            'updated_at' => $this->dateTime()->comment('ปรับปรุงเมื่อ'),
        ]);

        $this->createIndex('idx_person_document_audit_person', 'person_document_audit', ['person_id', 'doc_type']);
        $this->createIndex('idx_person_document_audit_ref', 'person_document_audit', ['doc_type', 'ref_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('person_document_audit');
    }

}
