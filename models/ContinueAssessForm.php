<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "continue_assess_form".
 *
 * @property int $id
 * @property int $submission_id
 * @property int $submission_committee_id 
 * @property int $review_choice_id ชนิดรายงาน
 * @property string $review_choice_text ชนิดรายงานอื่นๆ
 * @property int $resolution_id ข้อคิดเห็นกรรมการ
 * @property string $suggestion ข้อเสนอแนะเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $volunteer_id อาสาสมัคร
 * @property int $event_no เลขเหตุการณ์ของ Deviation
 *
 * @property User $createdBy
 * @property Resolution $resolution
 * @property ReviewChoice $reviewChoice
 * @property Submission $submission
 * @property SubmissionCommittee $submissionCommittee 
 * @property User $updatedBy
 * @property ContinueAssessFormEthics[] $continueAssessFormEthics
 * @property ContinueAssessFormReview[] $continueAssessFormReviews
 * @property Volunteer $volunteer
 */
class ContinueAssessForm extends \yii\db\ActiveRecord {

    public $reviewIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'continue_assess_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['review_choice_id'], 'required'],
            [['submission_id', 'submission_committee_id', 'review_choice_id', 'resolution_id', 'deleted', 'created_by', 'updated_by', 'volunteer_id', 'event_no'], 'integer'],
            [['suggestion'], 'string'],
            [['created_at', 'updated_at', 'reviewIds'], 'safe'],
            [['review_choice_text'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['resolution_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resolution::className(), 'targetAttribute' => ['resolution_id' => 'id']],
            [['review_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['review_choice_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['volunteer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Volunteer::className(), 'targetAttribute' => ['volunteer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_id' => Yii::t('app', 'Submission ID'),
            'review_choice_id' => Yii::t('app', 'ชนิดรายงาน'),
            'review_choice_text' => Yii::t('app', 'ชนิดรายงานอื่นๆ'),
            'resolution_id' => Yii::t('app', 'ข้อคิดเห็นกรรมการ'),
            'suggestion' => Yii::t('app', 'ข้อเสนอแนะเพิ่มเติม'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'volunteer_id' => Yii::t('app', 'อาสาสมัคร'),
            'event_no' => Yii::t('app', 'เลขเหตุการณ์ของ Deviation'),
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
        $this->reviewIds = ArrayHelper::getColumn($this->continueAssessFormReviews, 'review_choice_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteer() {
        return $this->hasOne(Volunteer::className(), ['id' => 'volunteer_id']);
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
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
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
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContinueAssessFormEthics() {
        return $this->hasMany(ContinueAssessFormEthics::className(), ['continue_assess_form_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContinueAssessFormReviews() {
        return $this->hasMany(ContinueAssessFormReview::className(), ['continue_assess_form_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormQuery the active query used by this AR class.
     */
    public static function find() {
        return new ContinueAssessFormQuery(get_called_class());
    }

}
