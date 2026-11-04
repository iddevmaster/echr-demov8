<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sae_volunteer".
 *
 * @property int $id
 * @property int $submission_id
 * @property int $submission_committee_id
 * @property int $volunteer_id
 * @property int $dead เสียชีวิตหรือไม่
 * @property int $cured รักษาจนเป็นปกติหรือไม่
 * @property int $drug สัมพันธ์กับยาวิจัยหรือไม่
 * @property string $comment ข้อคิดเห็นเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $updatedBy
 * @property Volunteer $volunteer
 * @property SaeVolunteerEthics[] $saeVolunteerEthics
 */
class SaeVolunteer extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'sae_volunteer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['dead', 'cured', 'drug'], 'required'],
            [['submission_id', 'submission_committee_id', 'volunteer_id', 'dead', 'cured', 'drug', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
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
            'submission_committee_id' => Yii::t('app', 'Submission Committee ID'),
            'volunteer_id' => Yii::t('app', 'Volunteer ID'),
            'dead' => Yii::t('app', 'เสียชีวิตหรือไม่'),
            'cured' => Yii::t('app', 'รักษาจนเป็นปกติหรือไม่'),
            'drug' => Yii::t('app', 'สัมพันธ์กับยาวิจัยหรือไม่'),
            'comment' => Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม'),
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
    public function getVolunteer() {
        return $this->hasOne(Volunteer::className(), ['id' => 'volunteer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaeVolunteerEthics() {
        return $this->hasMany(SaeVolunteerEthics::className(), ['sae_volunteer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionVolunteer() {
        return $this->hasOne(SubmissionVolunteer::className(), [
                    'volunteer_id' => 'volunteer_id',
                    'submission_id' => 'submission_id',
                ])->isDeleted(false);
    }

    public function getCanEdit() {
//        return $this->submission->status < Submission::STATUS_COMMITTEE_ASSESSED;
        return $this->submission->status <= Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
    }

    public static function getVolunteerCount($submissionId, $type, $value) {
        $sub = SaeVolunteer::find()->isDeleted(FALSE)->submission($submissionId)->andWhere(["sae_volunteer.{$type}" => $value]);
        return $sub->count();
    }

    /**
     * {@inheritdoc}
     * @return SaeVolunteerQuery the active query used by this AR class.
     */
    public static function find() {
        return new SaeVolunteerQuery(get_called_class());
    }

}
