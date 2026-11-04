<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "deviation_event".
 *
 * @property int $id
 * @property int $submission_id
 * @property int $submission_committee_id กรรมการ
 * @property int $submission_event_id เหตุการณ์
 * @property int $is_major_minor_com Major/Minor/Compliance
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
 * @property SubmissionEvent $submissionEvent
 * @property DeviationEventEthics[] $deviationEventEthics
 * @property DeviationEventType[] $deviationEventTypes
 */
class DeviationEvent extends \yii\db\ActiveRecord {

    const MAJOR = 1;
    const MINOR = 2;
    const NON = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'deviation_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['is_major_minor_com'], 'required'],
            [['submission_id', 'submission_committee_id', 'submission_event_id', 'is_major_minor_com', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['submission_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionEvent::className(), 'targetAttribute' => ['submission_event_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_id' => Yii::t('app', 'Submission ID'),
            'submission_committee_id' => Yii::t('app', 'กรรมการ'),
            'submission_event_id' => Yii::t('app', 'เหตุการณ์'),
            'is_major_minor_com' => Yii::t('app', 'รายละเอียดประเภทโครงการวิจัยเบี่ยงเบน'),
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
    public function getSubmissionEvent() {
        return $this->hasOne(SubmissionEvent::className(), ['id' => 'submission_event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviationEventEthics() {
        return $this->hasMany(DeviationEventEthics::className(), ['deviation_event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviationEventTypes() {
        return $this->hasMany(DeviationEventType::className(), ['deviation_event_id' => 'id']);
    }
    
    public function getCanEdit() {
        return $this->submission->status <= Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
    }

    /**
     * {@inheritdoc}
     * @return DeviationEventQuery the active query used by this AR class.
     */
    public static function find() {
        return new DeviationEventQuery(get_called_class());
    }
    
    public static function violationLabels() {
        return [
            self::MAJOR => 'major protocol violation',
            self::MINOR => 'minor protocol violation',
            self::NON => 'non compliance',
        ];
    }

}
