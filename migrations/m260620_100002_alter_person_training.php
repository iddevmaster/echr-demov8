<?php

use yii\db\Migration;

/**
 * Class m260620_100002_alter_person_training
 *
 * Adds training type reference and computed expiry date to person_training.
 */
class m260620_100002_alter_person_training extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('person_training', 'training_type_id', $this->integer()->null()->comment('ประเภทการอบรม'));
        $this->addColumn('person_training', 'expire_date', $this->date()->null()->comment('วันหมดอายุการอบรม'));
        $this->createIndex('idx_person_training_training_type_id', 'person_training', 'training_type_id');
        $this->createIndex('idx_person_training_expire_date', 'person_training', 'expire_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_person_training_expire_date', 'person_training');
        $this->dropIndex('idx_person_training_training_type_id', 'person_training');
        $this->dropColumn('person_training', 'expire_date');
        $this->dropColumn('person_training', 'training_type_id');
    }

}
