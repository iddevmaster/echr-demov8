<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "submission_event".
 *
 * @property int $id
 * @property int $submission_id การยื่นโครงการ
 * @property int $event_no หมายเลขเหตุการณ์
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property string $code หมายเลขเหตุการณ์
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $meeting_violation_type
 *
 * @property DeviationEvent[] $deviationEvents
 * @property DeviationEventType[] $deviationEventTypes
 * @property SubmissionDocument[] $submissionDocuments
 * @property User $createdBy
 * @property Submission $submission
 * @property User $updatedBy
 */
class SubmissionEvent extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'submission_event';
    }

    public $deviationTypes, $other;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['submission_id', 'event_no', 'deleted', 'created_by', 'updated_by', 'meeting_violation_type'], 'integer'],
            [['created_at', 'updated_at', 'deviationTypes', 'code', 'other'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'submission_id' => Yii::t('app', 'การยื่นโครงการ'),
            'event_no' => Yii::t('app', 'หมายเลขเหตุการณ์'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'isAssessed' => Yii::t('app', 'ประเมินแล้ว'),
            'code' => Yii::t('app', 'หมายเลขเหตุการณ์'),
            'meeting_violation_type' => Yii::t('app', 'Meeting Violation Type'),
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
    public function getDeviationEvents() {
        return $this->hasMany(DeviationEvent::className(), ['submission_event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviationEventTypes() {
        return $this->hasMany(DeviationEventType::className(), ['submission_event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments() {
        return $this->hasMany(SubmissionDocument::className(), ['submission_event_id' => 'id']);
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
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getIsAssessed($sCommitteeId) {
        return $this->getDeviationEvents()->submissionCommittee($sCommitteeId)->count() > 0;
    }

    /**
     * {@inheritdoc}
     * @return SubmissionEventQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionEventQuery(get_called_class());
    }

    public function afterFind() {
        parent::afterFind();
        $this->deviationTypes = ArrayHelper::getColumn($this->deviationEventTypes, 'deviation_type_id');

        $det = \app\models\DeviationEventType::find()->isDeleted(false)->deviationType(15)->submissionEvent($this->id)->one();
        $this->other = isset($det) ? $det->other : null;
    }

    public static function toArrayData($objects)
    {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties()
    {
        return [
            \app\models\SubmissionEvent::class => [
                'id',
                'submission_id',
                'event_no',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'code',
                'meeting_violation_type',
            ]
        ];
    }

}
