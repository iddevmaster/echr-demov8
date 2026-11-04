<?php

use yii\db\Migration;

/**
 * Class m260620_100009_add_original_file
 *
 * Keeps the original (un-stamped) CV / training file so the e-signature flow can
 * serve the stamped file when "ประทับชื่อ" is chosen, or revert to the original
 * when it is not — without losing the raw upload.
 */
class m260620_100009_add_original_file extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('person', 'cv_original_file', $this->string(255)->null()->comment('ไฟล์ CV ต้นฉบับ (ก่อนประทับลายเซ็น)'));
        $this->addColumn('person_training', 'original_file', $this->string(255)->null()->comment('ไฟล์การอบรมต้นฉบับ (ก่อนประทับลายเซ็น)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('person_training', 'original_file');
        $this->dropColumn('person', 'cv_original_file');
    }

}
