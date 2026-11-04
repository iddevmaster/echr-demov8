<?php

use yii\db\Migration;
use app\models\TrainingType;

/**
 * Class m260620_100007_alter_training_type_category
 *
 * Groups training types into requirement categories (GCP / Ethics) so the
 * per-submission-type training matrix can express "GCP required" vs
 * "at least one ethics training".
 */
class m260620_100007_alter_training_type_category extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('training_type', 'category', $this->smallInteger()->null()->comment('หมวดหมู่ 1=GCP, 2=ETHICS, NULL=อื่นๆ'));

        // Seed categories for the standard seeded types.
        // id 3 = GCP; ids 1,2,4 = ethics group (HSP / Biomedical / Social Science).
        $this->update('training_type', ['category' => TrainingType::CATEGORY_GCP], ['id' => 3]);
        $this->update('training_type', ['category' => TrainingType::CATEGORY_ETHICS], ['id' => [1, 2, 4]]);
        // id 5 (Medical device) left NULL/other unless the committee classifies it.
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('training_type', 'category');
    }

}
