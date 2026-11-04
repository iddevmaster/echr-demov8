<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_committee_revise".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $submission_id submission
 * @property int $submission_committee_id กรรมการ
 * @property string $resolution ผลประเมิน
 * @property int $is_meeting เข้าประชุม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $remark ปรับปรุงเมื่อ
 * @property date $researcher_receive_date วันที่รับเอกสารจากผู้วิจัย
 * @property date $committee_send_date วันที่ส่งเอกสารกรรมการ 
 * 
 *
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionCommitteeReviseText[] $submissionCommitteeReviseTexts
 * @property SubmissionResultDocument[] $SubmissionResultDocuments
 */
class SubmissionCommitteeRevise extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_committee_revise';
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
            [['submission_id', 'submission_committee_id', 'created_by', 'updated_by', 'researcher_receive_date', 'committee_send_date'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['resolution'], 'string', 'max' => 255],
            [['remark'], 'string'],
            [['is_meeting', 'deleted'], 'string', 'max' => 1],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
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
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'submission_id' => Yii::t('app', 'submission'),
            'submission_committee_id' => Yii::t('app', 'กรรมการ'),
            'resolution' => Yii::t('app', 'ผลประเมิน'),
            'is_meeting' => Yii::t('app', 'เข้าประชุม'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'remark' => Yii::t('app', 'รายละเอียดการแก้ไข'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommitteeReviseTexts() {
        return $this->hasMany(SubmissionCommitteeReviseText::className(), ['submission_committee_revise_id' => 'id']);
    }

    public function getSubmissionResultDocuments() {
        return $this->hasMany(SubmissionResultDocument::className(), ['submission_committee_revise_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return SubmissionCommitteeReviseQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionCommitteeReviseQuery(get_called_class());
    }

}
