<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "deviation_assess_form".
 *
 * @property int $id
 * @property int $submission_id submission
 * @property int $submission_committee_id กรรมการ
 * @property int $review_choice_id ชนิดรายงาน
 * @property string $review_choice_text ชนิดรายงานอื่นๆ
 * @property int $resolution_id ข้อคิดเห็นกรรมการ
 * @property string $suggestion ข้อเสนอแนะเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property Resolution $resolution
 * @property ReviewChoice $reviewChoice
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $updatedBy
 * @property DeviationAssessFormReview[] $deviationAssessFormReviews
 */
class DeviationAssessForm extends \yii\db\ActiveRecord {

    public $reviewIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'deviation_assess_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['resolution_id', 'review_choice_id'], 'required'],
            [['submission_id', 'submission_committee_id', 'review_choice_id', 'resolution_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['suggestion'], 'string'],
            [['created_at', 'updated_at', 'reviewIds'], 'safe'],
            [['review_choice_text'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['resolution_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resolution::className(), 'targetAttribute' => ['resolution_id' => 'id']],
            [['review_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['review_choice_id' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_id' => Yii::t('app', 'submission'),
            'submission_committee_id' => Yii::t('app', 'กรรมการ'),
            'review_choice_id' => Yii::t('app', 'ชนิดรายงาน'),
            'review_choice_text' => Yii::t('app', 'ชนิดรายงานอื่นๆ'),
            'resolution_id' => Yii::t('app', 'ข้อคิดเห็นกรรมการ'),
            'suggestion' => Yii::t('app', 'ข้อเสนอแนะเพิ่มเติม'),
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

    public function afterFind() {
        parent::afterFind();
        $this->reviewIds = ArrayHelper::getColumn($this->deviationAssessFormReviews, 'review_choice_id');
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
    public function getResolution() {
        return $this->hasOne(Resolution::className(), ['id' => 'resolution_id']);
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
    public function getSubmissionCommittee() {
        return $this->hasOne(SubmissionCommittee::className(), ['id' => 'submission_committee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviationAssessFormReviews() {
        return $this->hasMany(DeviationAssessFormReview::className(), ['deviation_assess_form_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DeviationAssessFormQuery the active query used by this AR class.
     */
    public static function find() {
        return new DeviationAssessFormQuery(get_called_class());
    }

}
