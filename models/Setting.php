<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
/**
 * This is the model class for table "setting".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $key รหัสการตั้งค่า
 * @property string $name ชื่อการตั้งค่า
 * @property string $value ค่าการตั้งค่า
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Setting extends \yii\db\ActiveRecord {

    const MEETING_NAME = 'MEETING_NAME';
    const ALERT_DOC_CHECK = 'ALERT_DOC_CHECK';
    const ALERT_HE_NOTIFY = 'ALERT_HE_NOTIFY';
    const ALERT_COMMITTEE_REPICK_NOTIFY = 'ALERT_COMMITTEE_REPICK_NOTIFY';
    const ALERT_COMMITTEE_ASSESS = 'ALERT_COMMITTEE_ASSESS';
    const ALERT_RESUBMIT = 'ALERT_RESUBMIT';
    const INITIAL_CREC_SUBMISSION_TYPE = 'CREC_SUBMISSION_TYPE';
    const CREC_FUNDING_SOURCE = 'CREC_FUNDING_SOURCE';
    const CREC_URL = 'CREC_URL';
    const CREC_ACCESS_TOKEN = 'CREC_ACCESS_TOKEN';
    const CREC_RESPONSIBLE_PERSON_EMAIL = 'CREC_RESPONSIBLE_PERSON_EMAIL';
    const CV_FRESHNESS_MONTHS = 'CV_FRESHNESS_MONTHS';
    const TRAINING_EXPIRE_ALERT_PERIODS = 'TRAINING_EXPIRE_ALERT_PERIODS';
    const ESIGN_OTP_ENABLE = 'ESIGN_OTP_ENABLE';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'setting';
    }
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'createdByUserProfile.fullName',
            'updatedByUserProfile.fullName',
        ]);
    }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'name', 'value'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'key' => Yii::t('app', 'รหัสการตั้งค่า'),
            'name' => Yii::t('app', 'ชื่อการตั้งค่า'),
            'value' => Yii::t('app', 'ค่าการตั้งค่า'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
        ];
    }
    public function behaviors() {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])
                        ->from(['uu' => User::tableName()]);
    }
    public function getUpdatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('updatedBy');
    }

    /**
     * @inheritdoc
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find() {
        return new SettingQuery(get_called_class());
    }

    public static function getValue($key) {
        $s = Setting::find()->isDeleted(FALSE)->key($key)->one();
        return isset($s) ? $s->value : NULL;
    }

    public static function getAlertNames() {
        return [
            self::ALERT_DOC_CHECK => 'เจ้าหน้าที่ตรวจสอบเอกสาร',
            self::ALERT_HE_NOTIFY => 'เจ้าหน้าที่แจ้งเลขที่โครงการวิจัย',
            self::ALERT_COMMITTEE_REPICK_NOTIFY => 'เจ้าหน้าที่แจ้งเลขาเพื่อเสนอกรรมการท่านใหม่',
            self::ALERT_COMMITTEE_ASSESS => 'กรรมการพิจารณาโครงการ',
            self::ALERT_RESUBMIT=> 'ผู้วิจัยส่งแก้ไข',
            self::INITIAL_CREC_SUBMISSION_TYPE=> 'ประเภทโครงการวิจัย CREC MOU',
            self::CREC_FUNDING_SOURCE=> 'ทุนวิจัย CREC MOU',
            self::CREC_URL=> 'URL ของระบบ CREC',
            self::CREC_ACCESS_TOKEN=> 'Access Token ของระบบ CREC',
            self::CREC_RESPONSIBLE_PERSON_EMAIL=> 'Email ผู้รับผิดชอบโครงการ CREC MOU',
            self::CV_FRESHNESS_MONTHS=> 'อายุ CV ที่ยอมรับได้ (เดือน)',
            self::TRAINING_EXPIRE_ALERT_PERIODS=> 'แจ้งเตือนการอบรมใกล้หมดอายุล่วงหน้า (วัน, คั่นด้วยจุลภาค)',
            self::ESIGN_OTP_ENABLE=> 'ใช้ OTP ในการลงนามอิเล็กทรอนิกส์ (1=ใช้, 0=ใช้รหัสผ่าน)',
        ];
    }

    /**
     * Get a setting value, returning $default when the key is missing/null.
     */
    public static function getValueOrDefault($key, $default) {
        $value = self::getValue($key);
        return ($value === null || $value === '') ? $default : $value;
    }

}
