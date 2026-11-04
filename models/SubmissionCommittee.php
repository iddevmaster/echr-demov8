<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_committee".
 *
 * @property int $id รหัส
 * @property int $status สถานะ
 * @property int $person_id กรรมการ/เลขา
 * @property int $project_id โครงการวิจัย
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property string $submit_date วันที่ส่งเอกสารไปให้กรรมการอ่าน
 * @property string $return_date วันที่ได้รับ comment กลับมา
 * @property string $remark หมายเหตุ comment
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property int $can_meeting เข้าประชุมได้หรือไม่
 * @property int $is_meeting เข้าประชุมได้หรือไม่
 * @property int $resolution ผลประเมิน
 * @property int $remark_meeting ชี้แจงเพ่มเติมเกี่ยวกับการเข้าประชุม
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $committee_position_id ตำแหน่งกรรมการ

 *
 * @property Person $person
 * @property Project $project
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionCommitteeDocument[] $submissionCommitteeDocuments
 * @property QuestionnaireAnswer[] $questionnaireAnswers 
 * @property SubmissionCommitteeRevise[] $submissionCommitteeRevises
 */
class SubmissionCommittee extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    const SCENARIO_ASSESS = 'assess';
    const SCENARIO_SELECT = 'select';
    const STATUS_ACCEPTED = '200';
    const STATUS_REJECTED = '100';
    const STATUS_PENDING = '50';
    const STATUS_RETURN = '300';
    const STATUS_RETURN_C = '250';
    const CANMEEING_NO = 0;
    const CANMEEING_YES = 1;
    const CANMEEING_YES_FULL = 2;

    public static function getStatusLabels() {
        return [
            self::STATUS_ACCEPTED => Yii::t('app', 'ตอบรับ'),
            self::STATUS_REJECTED => Yii::t('app', 'ปฏิเสธ'),
            self::STATUS_PENDING => Yii::t('app', 'รอการตอบรับ'),
            self::STATUS_RETURN => Yii::t('app', 'ส่งแบบประเมินแล้ว'),
            self::STATUS_RETURN_C => Yii::t('app', 'ส่งแบบประเมินผล C แล้ว'),
        ];
    }

    public static function getStatusLabelsCommittee() {
        return [
            self::STATUS_ACCEPTED => Yii::t('app', 'ตอบรับแล้ว'),
            self::STATUS_REJECTED => Yii::t('app', 'ปฏิเสธการอ่านงานวิจัย'),
            self::STATUS_PENDING => Yii::t('app', 'รอการตอบรับ'),
            self::STATUS_RETURN => Yii::t('app', 'ส่งแบบประเมินแล้ว'),
            self::STATUS_RETURN_C => Yii::t('app', 'ส่งแบบประเมินผล C แล้ว'),
        ];
    }

    public static function getStatusLabelsForm() {
        return [
            self::STATUS_ACCEPTED => Yii::t('app', 'ตอบรับ'),
            self::STATUS_REJECTED => Yii::t('app', 'ปฏิเสธ'),
        ];
    }

    public static function getStatusLabelsCanMeeting() {
        return [
            self::CANMEEING_NO => Yii::t('app', 'เข้าประชุมตามวันที่กำหนดไม่ได้'),
            self::CANMEEING_YES => Yii::t('app', 'เข้าประชุมตามวันที่กำหนดได้'),
            self::CANMEEING_YES_FULL => Yii::t('app', 'เข้าประชุมตามวันที่กำหนดไม่ได้'),
        ];
    }

    public static function tableName() {
        return 'submission_committee';
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
            [['resolution'], 'required', 'on' => self::SCENARIO_ASSESS],
            [['committee_position_id'], 'required', 'on' => self::SCENARIO_SELECT],
            [['status', 'person_id', 'project_id', 'submission_id', 'deleted', 'created_by', 'updated_by', 'can_meeting', 'is_meeting', 'committee_position_id'], 'integer'],
            [['submit_date', 'return_date', 'created_at', 'updated_at'], 'safe'],
            [['remark', 'remark_meeting', 'resolution'], 'string'],
            [['committee_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommitteePosition::className(), 'targetAttribute' => ['committee_position_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัส'),
            'status' => Yii::t('app', 'สถานะ'),
            'person_id' => Yii::t('app', 'กรรมการ/เลขา'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'submit_date' => Yii::t('app', 'วันที่ส่งเอกสารไปให้กรรมการอ่าน'),
            'return_date' => Yii::t('app', 'วันที่ได้รับ comment กลับมา'),
            'remark' => Yii::t('app', 'หมายเหตุ comment'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'version' => Yii::t('app', 'เวอร์ชันเอกสาร'),
            'version_at' => Yii::t('app', 'วันที่เวอร์ชันเอกสาร'),
            'can_meeting' => Yii::t('app', 'สามารถเข้าประชุมได้หรือไม่'),
            'is_meeting' => Yii::t('app', 'โครงการต้องเข้าประชุมหรือไม่'),
            'resolution' => Yii::t('app', 'ผลประเมิน'),
            'remark_meeting' => Yii::t('app', 'หมายเหตุเพิ่มเติมการเข้าประชุม'),
            'committee_position_id' => Yii::t('app', 'ตำแหน่งกรรมการ'),
            'committeePosition.fullName' => Yii::t('app', 'ตำแหน่งกรรมการ'),
            'startMeetingDate' => Yii::t('app', 'จากวันที่เข้าประชุม'),
            'endMeetingDate' => Yii::t('app', 'ถึงวันที่เข้าประชุม'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject() {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
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
        return $this->hasOne(User::className(), ['id' => 'updated_by'])
                        ->from(['uu' => User::tableName()]);
    }

    public function getCreatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('createdBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('updatedBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommitteeDocuments() {
        return $this->hasMany(SubmissionCommitteeDocument::className(), ['submission_committee_id' => 'id']);
    }

    public function getQuestionnaireAnswers() {
        return $this->hasMany(QuestionnaireAnswer::className(), ['submission_committee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommitteePosition() {
        return $this->hasOne(CommitteePosition::className(), ['id' => 'committee_position_id']);
    }
    public function getSubmissionCommitteeRevise() {
        return $this->hasOne(SubmissionCommitteeRevise::className(),  ['submission_committee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommitteeRevises() {
        return $this->hasMany(SubmissionCommitteeRevise::className(), ['submission_committee_id' => 'id']);
    }

    public function getRequireSubmissionDocuments() {
        $subDocs = $this->getSubmissionCommitteeDocuments()->isDeleted(FALSE)->all();
        $docs = [];
        foreach ($subDocs as $subDoc) {
            if (isset($subDoc->documentSubmissionType) && $subDoc->documentSubmissionType->is_require 
                    && $subDoc->documentSubmissionType->committee_position_id == $this->committee_position_id) {
                $docs[] = $subDoc;
            }
        }
        return $docs;
//        $docs = array_map(function($model) {
//            if (isset($model->documentSubmissionType) && $model->documentSubmissionType->is_require) {
//                return $model;
//            }
//        }, $this->getSubmissionDocuments()->isDeleted(FALSE)->all());
//        \yii\helpers\VarDumper::dump($docs);
//        return $docs;
    }

    public static function toArrayData($objects)
    {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties()
    {
        return [
            \app\models\SubmissionCommittee::class => [
                'id',
                'status',
                'person_id',
                'project_id',
                'submission_id',
                'submit_date',
                'return_date',
                'remark',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'can_meeting',
                'remark_meeting',
                'is_meeting',
                'resolution',
                'committee_position_id',
                'title' => function($model) {
                    return $model->person->title->name ?? null;
                },
                'first_name' => function($model) {
                    return $model->person->first_name ?? null;
                },
                'last_name' => function($model) {
                    return $model->person->last_name ?? null;
                },
                'email' => function($model) {
                    return $model->person->email ?? null;
                },
                'mobile_no' => function($model) {
                    return $model->person->mobile_no ?? null;
                },
                'title_eng' => function($model) {
                    return $model->person->title->name_eng ?? null;
                },
                'first_name_eng' => function($model) {
                    return $model->person->first_name_eng ?? null;
                },
                'last_name_eng' => function($model) {
                    return $model->person->last_name_eng ?? null;
                },
                'person_ec_id' => function($model) {
                    return $model->person_id ?? null;
                },
                'submissionCommitteeDocuments' => function($model) {
                    return SubmissionCommitteeDocument::toArrayData($model->getSubmissionCommitteeDocuments()->isDeleted(false)->all());
                },
            ]
        ];
    }

    public static function getStatusLabelsMeeting() {
        return [
            self::CANMEEING_NO => Yii::t('app', 'ไม่ต้องเข้าประชุม'),
            self::CANMEEING_YES => Yii::t('app', 'เข้าประชุม'),
            self::CANMEEING_YES_FULL => Yii::t('app', 'ไม่ต้องเข้าประชุม'),
        ];
    }

    /**
     * @inheritdoc
     * @return SubmissionCommitteeQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionCommitteeQuery(get_called_class());
    }

}
