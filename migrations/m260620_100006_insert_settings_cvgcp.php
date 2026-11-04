<?php

use app\models\Setting;
use yii\db\Migration;

/**
 * Class m260620_100006_insert_settings_cvgcp
 *
 * Admin-editable settings for CV freshness, training-expiry alert periods and
 * the e-signature OTP toggle.
 */
class m260620_100006_insert_settings_cvgcp extends Migration {

    private $rows = [
        [Setting::CV_FRESHNESS_MONTHS, 'อายุ CV ที่ยอมรับได้ (เดือน)', '6'],
        [Setting::TRAINING_EXPIRE_ALERT_PERIODS, 'แจ้งเตือนการอบรมใกล้หมดอายุล่วงหน้า (วัน, คั่นด้วยจุลภาค)', '60,30'],
        [Setting::ESIGN_OTP_ENABLE, 'ใช้ OTP ในการลงนามอิเล็กทรอนิกส์ (1=ใช้, 0=ใช้รหัสผ่าน)', '0'],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        foreach ($this->rows as $r) {
            $exists = (new \yii\db\Query())->from('setting')->where(['key' => $r[0]])->exists();
            if (!$exists) {
                $this->insert('setting', ['key' => $r[0], 'name' => $r[1], 'value' => $r[2], 'deleted' => 0]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        foreach ($this->rows as $r) {
            $this->delete('setting', ['key' => $r[0]]);
        }
    }

}
