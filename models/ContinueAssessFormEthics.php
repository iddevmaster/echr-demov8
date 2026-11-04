<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "continue_assess_form_ethics".
 *
 * @property int $id
 * @property int $continue_assess_form_id ฟอร์มประเมิน
 * @property int $ethics_id ประเด็นจริยธรรม
 * @property int $is_appropriate เหมาะสมหรือไม่
 * @property string $other อื่นๆ
 * @property string $remark หมายเหตุ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ContinueAssessForm $continueAssessForm
 * @property User $createdBy
 * @property Ethics $ethics
 * @property User $updatedBy
 */
class ContinueAssessFormEthics extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    const INAPPROPRIATE = 0;
    const APPROPRIATE = 1;
    const NOT_INVOLVED = 2;

    public static function getStatusLabelsEthics() {
        return [
            self::INAPPROPRIATE => Yii::t('app', 'ไม่เหมาะสม'),
            self::APPROPRIATE => Yii::t('app', 'เหมาะสม'),
            self::NOT_INVOLVED => Yii::t('app', 'ไม่เกี่ยวข้อง'),
        ];
    }

    public static function tableName() {
        return 'continue_assess_form_ethics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['continue_assess_form_id', 'ethics_id', 'is_appropriate', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['other', 'remark'], 'string', 'max' => 255],
            [['continue_assess_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContinueAssessForm::className(), 'targetAttribute' => ['continue_assess_form_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['ethics_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ethics::className(), 'targetAttribute' => ['ethics_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'continue_assess_form_id' => Yii::t('app', 'ฟอร์มประเมิน'),
            'ethics_id' => Yii::t('app', 'ประเด็นจริยธรรม'),
            'is_appropriate' => Yii::t('app', 'เหมาะสมหรือไม่'),
            'other' => Yii::t('app', 'อื่นๆ'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
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
    public function getContinueAssessForm() {
        return $this->hasOne(ContinueAssessForm::className(), ['id' => 'continue_assess_form_id']);
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
    public function getEthics() {
        return $this->hasOne(Ethics::className(), ['id' => 'ethics_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormEthicsQuery the active query used by this AR class.
     */
    public static function find() {
        return new ContinueAssessFormEthicsQuery(get_called_class());
    }

}
