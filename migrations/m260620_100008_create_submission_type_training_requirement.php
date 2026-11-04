<?php

use yii\db\Migration;

/**
 * Class m260620_100008_create_submission_type_training_requirement
 *
 * Admin-editable matrix: for each submission type, which training category is
 * required. category 1=GCP, 2=ETHICS. rule 0=NA, 1=REQUIRED, 2=ANY_OF
 * ("ต้องแนบอย่างใดอย่างหนึ่ง").
 */
class m260620_100008_create_submission_type_training_requirement extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('submission_type_training_requirement', [
            'id' => $this->primaryKey(),
            'submission_type_id' => $this->integer()->comment('ประเภทการขอรับพิจารณา'),
            'category' => $this->smallInteger()->comment('หมวดการอบรม 1=GCP, 2=ETHICS'),
            'rule' => $this->smallInteger()->comment('0=NA, 1=ต้องแนบ, 2=ต้องแนบอย่างใดอย่างหนึ่ง'),
            'deleted' => $this->smallInteger()->defaultValue(0)->comment('0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => $this->integer()->comment('สร้างโดย'),
            'created_at' => $this->dateTime()->comment('สร้างเมื่อ'),
            'updated_by' => $this->integer()->comment('ปรับปรุงโดย'),
            'updated_at' => $this->dateTime()->comment('ปรับปรุงเมื่อ'),
        ]);
        $this->createIndex('idx_sttr_submission_type', 'submission_type_training_requirement', 'submission_type_id');
        $this->createIndex('uq_sttr_type_category', 'submission_type_training_requirement', ['submission_type_id', 'category'], true);

        // Seed the matrix from the requirement image.
        // category: 1=GCP, 2=ETHICS ; rule: 0=NA, 1=REQUIRED, 2=ANY_OF
        // submission_type ids: 1=ทางคลินิก, 2=ทางสังคม, 3=ขอยกเว้น, 4=พิจารณาแบบเร็ว
        $now = date('Y-m-d H:i:s');
        $rows = [
            // [submission_type_id, category, rule]
            [1, 1, 1], [1, 2, 2], // clinical: GCP required, ethics any-of
            [2, 1, 0], [2, 2, 2], // social:   GCP NA,       ethics any-of
            [3, 1, 0], [3, 2, 2], // exemption:GCP NA,       ethics any-of
            [4, 1, 1], [4, 2, 2], // expedited:GCP required, ethics any-of
        ];
        $batch = [];
        foreach ($rows as $r) {
            $batch[] = [$r[0], $r[1], $r[2], 0, $now, $now];
        }
        $this->batchInsert('submission_type_training_requirement',
                ['submission_type_id', 'category', 'rule', 'deleted', 'created_at', 'updated_at'], $batch);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('submission_type_training_requirement');
    }

}
