<?php

use yii\db\Migration;

/**
 * Class m260620_100003_alter_person_cv
 *
 * Adds CV "last updated" date and denormalized CV e-signature info to person.
 */
class m260620_100003_alter_person_cv extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('person', 'cv_updated_at', $this->date()->null()->comment('วันที่ปรับปรุงประวัติ (CV) ล่าสุด'));
        $this->addColumn('person', 'cv_signed_at', $this->dateTime()->null()->comment('วันเวลาที่ลงนามอิเล็กทรอนิกส์ CV'));
        $this->addColumn('person', 'cv_signed_by', $this->integer()->null()->comment('ผู้ลงนามอิเล็กทรอนิกส์ CV (person_id)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('person', 'cv_signed_by');
        $this->dropColumn('person', 'cv_signed_at');
        $this->dropColumn('person', 'cv_updated_at');
    }

}
