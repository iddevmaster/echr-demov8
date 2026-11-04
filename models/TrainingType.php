<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "training_type".
 *
 * @property int $id
 * @property string $name ชื่อประเภทการอบรม
 * @property int $validity_years ระยะเวลาที่ถือว่าใช้ได้ (ปี)
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property PersonTraining[] $personTrainings
 * @property User $createdBy
 * @property User $updatedBy
 */
class TrainingType extends \yii\db\ActiveRecord {

    const CATEGORY_GCP = 1;
    const CATEGORY_ETHICS = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'training_type';
    }

    public static function getCategoryLabels() {
        return [
            self::CATEGORY_GCP => Yii::t('app', 'GCP'),
            self::CATEGORY_ETHICS => Yii::t('app', 'จริยธรรมการวิจัย (Ethics)'),
        ];
    }

    public function getCategoryLabel() {
        $labels = self::getCategoryLabels();
        return isset($labels[$this->category]) ? $labels[$this->category] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['validity_years', 'category', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ชื่อประเภทการอบรม'),
            'validity_years' => Yii::t('app', 'ระยะเวลาที่ถือว่าใช้ได้ (ปี)'),
            'category' => Yii::t('app', 'หมวดการอบรม'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedBy.person.fullName' => Yii::t('app', 'ปรับปรุงโดย'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonTrainings() {
        return $this->hasMany(PersonTraining::className(), ['training_type_id' => 'id']);
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
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return TrainingTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TrainingTypeQuery(get_called_class());
    }

}
