<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "deviation_assess_form_review".
 *
 * @property int $id
 * @property int $deviation_assess_form_id ฟอร์มประเมิน
 * @property int $review_choice_id ชนิดรายงาน
 * @property string $review_choice_text ชนิดรายงานอื่นๆ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property DeviationAssessForm $deviationAssessForm
 * @property ReviewChoice $reviewChoice
 * @property User $updatedBy
 */
class DeviationAssessFormReview extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'deviation_assess_form_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['deviation_assess_form_id', 'review_choice_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['review_choice_text'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deviation_assess_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeviationAssessForm::className(), 'targetAttribute' => ['deviation_assess_form_id' => 'id']],
            [['review_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['review_choice_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'deviation_assess_form_id' => Yii::t('app', 'ฟอร์มประเมิน'),
            'review_choice_id' => Yii::t('app', 'ชนิดรายงาน'),
            'review_choice_text' => Yii::t('app', 'ชนิดรายงานอื่นๆ'),
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
    public function getDeviationAssessForm() {
        return $this->hasOne(DeviationAssessForm::className(), ['id' => 'deviation_assess_form_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewChoice() {
        return $this->hasOne(ReviewChoice::className(), ['id' => 'review_choice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return DeviationAssessFormReviewQuery the active query used by this AR class.
     */
    public static function find() {
        return new DeviationAssessFormReviewQuery(get_called_class());
    }

}
