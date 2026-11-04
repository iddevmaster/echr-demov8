<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_volunteer".
 *
 * @property int $id
 * @property int $submission_id การยื่นโครงการ
 * @property int $volunteer_id อาสาสมัคร
 * @property int $type ประเภทการติดตาม
 * @property int $follow_up_no ติดตามครั้งที่
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property Submission $submission
 * @property User $updatedBy
 * @property Volunteer $volunteer
 */
class SubmissionVolunteer extends \yii\db\ActiveRecord {

    const TYPE_INITIAL = 1;
    const TYPE_FOLLOW_UP = 2;
    
    const SCENARIO_CREATE = 'create';
    
    public $volunteerCode;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'submission_volunteer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['submission_id', 'volunteer_id', 'type', 'follow_up_no', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['volunteerCode'], 'required', 'on' => self::SCENARIO_CREATE],
            [['volunteerCode'], 'trim'],
            [['volunteerCode'], 'string', 'max' => 255],
            ['volunteerCode', 'unique', 'targetAttribute' => ['volunteer_id', 'submission_id'], 'filter' => ['and', ['deleted' => 0], ['not', ['submission_id' => null]]], 'on' => self::SCENARIO_CREATE],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'submission_id' => Yii::t('app', 'การยื่นโครงการ'),
            'volunteer_id' => Yii::t('app', 'อาสาสมัคร'),
            'volunteerCode' => Yii::t('app', 'เลขที่อาสาสมัคร'),
            'type' => Yii::t('app', 'ประเภทการติดตาม'),
            'typeLabel' => Yii::t('app', 'ประเภทการติดตาม'),
            'isAssessed' => Yii::t('app', 'ประเมินแล้ว'),
            'follow_up_no' => Yii::t('app', 'ติดตามครั้งที่'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'agendaTitle' => Yii::t('app', 'การประชุม'),
            'isDead' => Yii::t('app', 'เสียชีวิตหรือไม่'),
            'isDeadHtml' => Yii::t('app', 'เสียชีวิตหรือไม่'),
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
        $this->volunteerCode = $this->volunteer->code;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteer() {
        return $this->hasOne(Volunteer::className(), ['id' => 'volunteer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocument() {
        return $this->hasOne(SubmissionDocument::className(), [
                'volunteer_id' => 'volunteer_id',
                'submission_id' => 'submission_id',
            ])->isDeleted(false);
    }
    
    public function getHistories() {
        return $this->hasMany(SubmissionVolunteer::class, [
            'volunteer_id' => 'volunteer_id',
        ])->isDeleted(false)->orderBy('id')->submissionStatus('>=' . Submission::STATUS_DOC_APPROVED);
    }
    
    public function getSaeVolunteers() {
        return $this->hasMany(SaeVolunteer::class, [
            'submission_id' => 'submission_id',
            'volunteer_id' => 'volunteer_id',
        ])->isDeleted(false);
    }

    public function getSaeVolunteerCount() {
        return $this->getSaeVolunteers()->count();
    }

    public function getTypeLabel() {
        if (!isset($this->type)) {
            return null;
        }
        $res = self::typeLabels()[$this->type];
        if ($this->type == self::TYPE_FOLLOW_UP) {
            $res .= "({$this->follow_up_no})";
        }
        return $res;
    }

    public function getIsAssessed($sCommitteeId) {
        return $this->getSaeVolunteers()->submissionCommittee($sCommitteeId)->count() > 0;
    }
    
    public function getAgendaTitle() {
        $ma = $this->submission->getMeetingAgenda();
        if (isset($ma)) {
            return "[{$ma->meeting->yearNo}] {$ma->fullTitle}";
        }
        return null;
    }
    
    public function getIsDead() {
        return $this->getSaeVolunteers()->isDead()->exists();
    }
    
    public function getIsDeadHtml() {
        return $this->isDead ? Yii::$app->util->booleanIconFormat($this->isDead) : "";
    }
    
    /**
     * {@inheritdoc}
     * @return SubmissionVolunteerQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionVolunteerQuery(get_called_class());
    }
    
    public static function typeLabels() {
        return [
            self::TYPE_INITIAL => 'Initial',
            self::TYPE_FOLLOW_UP => 'Follow up',
        ];
    }

    public static function toArrayData($objects)
    {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties()
    {
        return [
            \app\models\SubmissionVolunteer::class => [
                'id',
                'submission_id',
                'volunteer_id',
                'type',
                'follow_up_no',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'volunteer' => function ($model) {
                    return $model->volunteer;
                },
            ]
        ];
    }
}
