<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project".
 *
 * @property int $id รหัสหน่วยงาน
 * @property string $name_thai ชื่อโครงการภาษาไทย
 * @property string $name_eng ชื่อโครงการภาษาอังกฤษ
 * @property string $start_date วันที่เริ่มต้น
 * @property string $end_date วันที่สิ้นสุด
 * @property int $funding_source_id ทุนวิจัย
 * @property string $funding_source_description รายละเอียดทุน
 * @property int $is_child_project เป็นโครงการเด็กหรือไม่ 0=ไม่ 1= ใช่
 * @property int $progress_period ระยะเวลาในการติดตาม
 * @property string $remark หมายเหตุ
 * @property string $certified_date วันที่รับรอง
 * @property int $status สถานะ
 * @property string $project_code เลขที่โครงการ
 * @property int $panel_id กลุ่มผู้ปฏิบัติงาน
 * @property int $organization_id องค์กร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $fda_no เลขอย.
 * @property string $certificate_no เลขที่การรับรอง 
 * @property string $expire_at วันที่หมดอายุรับรอง 
 * @property int $is_closed ปิดโครงการหรือยัง
 * @property string $closed_at ปิดโครงการเมื่อ
 * @property string $next_progress_at ปิดโครงการเมื่อ
 * @property int $project_coordinator_id ผู้ประสานงานโครงการ
 * @property int $project_coordinator_2nd_id ผู้ประสานงานโครงการคนที่ 2
 * @property int $project_coordinator_3rd_id ผู้ประสานงานโครงการคนที่ 3
 * @property int $project_viewer_id  viewer
 * @property int $is_fda ต้องรายงานอย.
 * @property string $crec_number หมายเลข CREC
 * @property string $crec_number_certificate หมายเลขรับรอง CREC
 * @property int $name_changed เปลี่ยนชื่อโดย Admin
 * @property int $is_active
 * @property string $crec_certified_date วันที่รับรองจาก CREC
 * @property string $crec_expire_at วันที่หมดอายุรับรองจาก CREC
 * @property string $crec_next_progress_at วันที่รายงานความก้าวหน้าจาก CREC
 * @property int $crec_progress_period ระบะเวลาในการายงานความก้าวหน้าจาก CREC
 * 
 * @property MeetingAgenda[] $meetingAgendas
 * @property FundingSource $fundingSource
 * @property Organization $organization
 * @property Panel $panel
 * @property User $createdBy
 * @property User $updatedBy
 * @property ProjectResearcher[] $projectResearchers
 * @property ProjectConsultant[] $projectConsultants
 * @property Submission[] $submissions
 * @property SubmissionCommittee[] $submissionCommittees
 * @property SubmissionDocument[] $submissionDocuments
 * @property SubmissionVolunteerNumber[] $submissionVolunteerNumbers
 * @property ProjectCodeHistory[] $projectCodeHistories
 * @property Volunteer[] $volunteers
 */
class Project extends \yii\db\ActiveRecord {

    const PREFIX_CODE = 'HE';
    const NAME_NOT_CHANGED = 0;
    const NAME_THAI_CHANGED = 1;
    const NAME_ENG_CHANGED = 2;
    const NAME_BOTH_CHANGED = 3;

    const SCENARIO_CREATE_CREC = 'create-crec';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name_thai', 'name_eng', 'is_child_project','funding_source_id'], 'required'],
            [['crec_number'], 'required', 'on' => self::SCENARIO_CREATE_CREC],
            [['start_date', 'end_date', 'certified_date', 'created_at', 'updated_at', 'expire_at', 'closed_at', 'next_progress_at', 'crec_certified_date', 'crec_expire_at', 'crec_next_progress_at'], 'safe'],
            [['funding_source_id', 'is_child_project', 'progress_period', 'status', 'panel_id', 'organization_id', 'deleted', 'created_by', 'updated_by', 'is_closed', 'project_coordinator_id', 'is_fda','project_coordinator_2nd_id','project_coordinator_3rd_id','project_viewer_id', 'name_changed', 'is_active', 'crec_progress_period'], 'integer'],
            [['funding_source_description', 'remark', 'project_code', 'fda_no', 'certificate_no', 'crec_number_certificate', 'crec_number'], 'string', 'max' => 255],
            [['name_thai', 'name_eng', 'crec_leader_name', 'crec_leader_name_eng', 'crec_leader_site_name', 'crec_leader_site_name_eng', 'crec_leader_org_name', 'crec_leader_org_name_eng'], 'string', 'max' => 750],
            [['funding_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundingSource::className(), 'targetAttribute' => ['funding_source_id' => 'id']],
            [['project_coordinator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_id' => 'id']],
            [['project_coordinator_2nd_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_2nd_id' => 'id']],
            [['project_coordinator_3rd_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_3rd_id' => 'id']],
            [['project_viewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_viewer_id' => 'id']],            
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'name_thai' => Yii::t('app', 'Research Title (Thai)'),
            'name_eng' => Yii::t('app', 'Research Title (English)'),
            'start_date' => Yii::t('app', 'วันที่เริ่มต้น'),
            'end_date' => Yii::t('app', 'วันที่สิ้นสุด'),
            'funding_source_id' => Yii::t('app', 'ทุนวิจัย'),
            'funding_source_description' => Yii::t('app', 'ชื่อหน่วยงาน / บริษัท ที่ให้ทุน'),
            'is_child_project' => Yii::t('app', 'มีอาสาสมัครที่อายุต่ำกว่า 18 ปี'),
            'progress_period' => Yii::t('app', 'ระยะเวลาในการติดตาม'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'certified_date' => Yii::t('app', 'วันที่รับรอง'),
            'status' => Yii::t('app', 'สถานะ'),
            'project_code' => Yii::t('app', 'เลขที่โครงการ'),
            'panel_id' => Yii::t('app', 'กลุ่มผู้ปฏิบัติงาน'),
            'organization_id' => Yii::t('app', 'องค์กร'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'fda_no' => Yii::t('app', 'รหัสโครงการวิจัยที่ตั้งโดยผู้ให้ทุน เช่น รหัสโครงการที่ยื่น อย.'),
            'certificate_no' => Yii::t('app', 'เลขที่การรับรอง'),
            'expire_at' => Yii::t('app', 'วันที่หมดอายุรับรอง'),
            'is_closed' => Yii::t('app', 'ปิดโครงการหรือยัง'),
            'closed_at' => Yii::t('app', 'ปิดโครงการเมื่อ'),
            'next_progress_at' => Yii::t('app', 'วันที่ต้องรายงานความก้าวหน้าครั้งต่อไป'),
            'project_coordinator_id' => Yii::t('app', 'ผู้ประสานงานโครงการ'),
            'project_coordinator_2nd_id' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 2'),
            'project_coordinator_3rd_id' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 3'),
            'project_viewer_id' => Yii::t('app', 'viewer'),
            'projectCoordinator.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการ'),
            'projectCoordinator2nd.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 2'),
            'projectCoordinator3rd.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 3'),
            'projectViewer.person.i18nFullName' => Yii::t('app', 'viewer'),
            'is_fda' => Yii::t('app', 'ต้องรายงานอย.'),
            'isFda' => Yii::t('app', 'ต้องรายงานอย.'),
            'crec_number' => Yii::t('app', 'CREC Number'),
            'crec_number_certificate' => Yii::t('app', 'หมายเลขรับรอง CREC'),
            'codeName' => Yii::t('app', 'โครงการ'),
            'is_active' => Yii::t('app', 'Active/Inactive'),
            'isActiveLabel' => Yii::t('app', 'Active/Inactive'),
            'crec_certified_date' => Yii::t('app', 'วันที่รับรองจาก CREC'),
            'crec_expire_at' => Yii::t('app', 'วันที่หมดอายุรับรองจาก CREC'),
            'crec_next_progress_at' => Yii::t('app', 'วันที่รายงานความก้าวหน้าจาก CREC'),
            'crec_progress_period' => Yii::t('app', 'ระบะเวลาในการายงานความก้าวหน้าจาก CREC'),
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
            'AuditTrailBehavior' => [
                'class' => 'bedezign\yii2\audit\AuditTrailBehavior',
                // Array with fields to save. You don't need to configure both `allowed` and `ignored`
//                'allowed' => ['some_field'],
                // Array with fields to ignore. You don't need to configure both `allowed` and `ignored`
//                'ignored' => ['another_field'],
                // Array with classes to ignore
//                'ignoredClasses' => ['common\models\Model'],
                // Is the behavior is active or not
                'active' => true,
                // Date format to use in stamp - set to "Y-m-d H:i:s" for datetime or "U" for timestamp
                'dateFormat' => 'Y-m-d H:i:s',
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCoordinator() {
        return $this->hasOne(User::className(), ['id' => 'project_coordinator_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCoordinator2nd() {
        return $this->hasOne(User::className(), ['id' => 'project_coordinator_2nd_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCoordinator3rd() {
        return $this->hasOne(User::className(), ['id' => 'project_coordinator_3rd_id']);
    }
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectViewer() {
        return $this->hasOne(User::className(), ['id' => 'project_viewer_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendas() {
        return $this->hasMany(MeetingAgenda::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFundingSource() {
        return $this->hasOne(FundingSource::className(), ['id' => 'funding_source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization() {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPanel() {
        return $this->hasOne(Panel::className(), ['id' => 'panel_id']);
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
    public function getProjectResearchers() {
        return $this->hasMany(ProjectResearcher::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectConsultants() {
        return $this->hasMany(ProjectConsultant::className(), ['project_id' => 'id']);
    }

    public function getProjectResearchersBYsubmission() {
        return $this->hasMany(ProjectResearcher::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissions() {
        return $this->hasMany(Submission::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastSubmission() {
        return $this->hasOne(Submission::className(), ['project_id' => 'id'])
                        ->orderBy('submission.id DESC')->isDeleted(false);
    }
        public function getFirstSubmission() {
        return $this->hasOne(Submission::className(), ['project_id' => 'id'])
                        ->orderBy('submission.id ASC')->isDeleted(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittees() {
        return $this->hasMany(SubmissionCommittee::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments() {
        return $this->hasMany(SubmissionDocument::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionVolunteerNumbers() {
        return $this->hasMany(SubmissionVolunteerNumber::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCodeHistories() {
        return $this->hasMany(ProjectCodeHistory::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteers() {
        return $this->hasMany(Volunteer::className(), ['project_id' => 'id']);
    }

    public function getProjectLeader() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader()->one();
    }

    public function getProjectCoResearchers() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->all();
    }

    public function getIsChildProjectLabel() {
        return $this->is_child_project ? Yii::t('app', 'ใช่') : Yii::t('app', 'ไม่');
    }

    public function getIsFda() {
        return $this->is_fda ? Yii::t('app', 'ใช่') : Yii::t('app', 'ไม่');
    }
        public function getCodeName() {
        return "{$this->project_code} : {$this->name_thai}";
    }

    public function generateHECode() {
        if (isset($this->project_code)) {
            return;
        }
        if (!isset($this->panel_id)) {
            return;
        }

        $year = \Yii::$app->util->getBEYear(new \DateTime());
        $prefix = self::PREFIX_CODE . substr($year, 2) . $this->panel_id;
        $this->project_code = RunningCode::generateCode($prefix, 3);
    }

    public function getProjectCodeHistoriesHtml() {
        $codes = \yii\helpers\ArrayHelper::getColumn($this->getProjectCodeHistories()->orderBy('id DESC')->all(), 'old_code');
        return count($codes) > 0 ? implode(', ', $codes) : '';
    }

    public function getI18nName() {
        if (\Yii::$app->language == 'en') {
            $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        } else {
            $attr = 'name_thai';
        }
        return $this->{$attr};
    }

    public function getProjectCodeWithHistory() {
        if (isset($this->project_code)) {
            $codes = $this->projectCodeHistoriesHtml;
            return $this->project_code . (empty($codes) ? '' : "({$codes})");
        } else {
            return Yii::t('app', 'N/A');
        }
    }

    public function getProjectDocumentHistories() {
        $submission = $this->getFirstEndorsedSubmission();
        $docs = [];
        if (isset($submission)) {
            $docs = $submission->getSubmissionDocs(true, true);
        }
//        \yii\helpers\VarDumper::dump($docs, 4, true);
//        exit;
        return $docs;
    }

    public function getLatestEndorseDocuments() {
        $submission = $this->getFirstEndorsedSubmission();
        $docs = [];
        if (isset($submission)) {
            $docs = $submission->getSubmissionDocs(true, true);
        }

        $submitStatus = Submission::STATUS_SUBMITTED;
        $amendmentSubs = $this->getSubmissions()->isDeleted(false)->status(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->submissionType(SubmissionType::TYPE_AMENDMENT)->orderBy("
            (
                SELECT created_at FROM submission_status_history 
                WHERE submission_id = submission.id AND status = {$submitStatus}
                LIMIT 1
            ) ASC")->all();
//        \yii\helpers\VarDumper::dump($amendmentSubs, 4, true);
//        exit;
//        echo '<table><tbody>';
//        foreach ($docs as $doc) {
//            echo "<tr>";
//            echo "<td>{$doc->name}</td>";
//            echo "<td>{$doc->version}</td>";
//            echo "<td>{$doc->version_at}</td>";
//            echo "<td>{$doc->document_id}</td>";
//            echo "</tr>";
//        }
//        echo '</tbody></table><hr>';
//        \yii\helpers\VarDumper::dump($docs, 4, true);
//        exit;
        foreach ($amendmentSubs as $sub) {
            $newDocs = $sub->getSubmissionDocs(true, true);
            $docs = SubmissionDocument::updateLatestDocs($docs, $newDocs);
        }
//        echo '<table><tbody>';
//        foreach ($docs as $doc) {
//            echo "<tr>";
//            echo "<td>{$doc->id}</td>";
//            echo "<td>{$doc->name}</td>";
//            echo "<td>{$doc->version}</td>";
//            echo "<td>{$doc->version_at}</td>";
//            echo "<td>{$doc->document_id}</td>";
//            echo "</tr>";
//            $subDocs = $doc->getEndorseHistories();
//            foreach ($subDocs as $doc) {
//                echo "<tr>";
//                echo "<td> - {$doc->id}</td>";
//                echo "<td>{$doc->name}</td>";
//                echo "<td>{$doc->version}</td>";
//                echo "<td>{$doc->version_at}</td>";
//                echo "<td>{$doc->document_id}</td>";
//                echo "</tr>";
//            }
//        }
//        echo '</tbody></table><hr>';
//        exit;
        return $docs;
    }

    public function getFirstEndorsedSubmission() {
        $docs = [];
        return $this->getSubmissions()->joinWith(['submissionType'])->isDeleted(false)
                        ->submissionTypeGroup(SubmissionTypeGroup::GROUP_NEW)
                        ->resolution(Submission::RESOLUTION_Y)->one();
    }

    public function getSaeVolunteers() {
        return SaeVolunteer::find()->joinWith(['submission'])->isDeleted(false)
                        ->project($this->id)->orderBy([
                    'sae_volunteer.volunteer_id' => SORT_ASC,
                    'sae_volunteer.submission_id' => SORT_ASC,
                    'sae_volunteer.submission_committee_id' => SORT_ASC,
                ])->all();
    }
    public function getSaeVolunteersReport() {
        return SaeVolunteer::find()->joinWith(['submission'])->isDeleted(false)->status(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)
                        ->project($this->id)->orderBy([
                    'sae_volunteer.volunteer_id' => SORT_ASC,
                    'sae_volunteer.submission_id' => SORT_ASC,
                    'sae_volunteer.submission_committee_id' => SORT_ASC,
                ])->all();
    }
    public function getIsActiveLabel() {
        if (isset($this->is_active)) {
            return self::isActiveLabels()[$this->is_active];
        }
        return '';
    }

    public function hasCrecNumber() {
        return !empty($this->crec_number);
    }

    public static function isActiveLabels() {
        return [
            0 => 'Inactive',
            1 => 'Active',
        ];
    }
    public static function isOngoing() {
        return [
            0 => 'Ongoing',
            1 => 'Close',
        ];
    }
    /**
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectQuery(get_called_class());
    }

    public static function toArrayData($objects)
    {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties()
    {
        return [
            \app\models\Project::class => [
                'id',
                'name_thai',
                'name_eng',
                'start_date',
                'end_date',
                'funding_source_id',
                'funding_source_description',
                'is_child_project',
                'progress_period',
                'remark',
                'certified_date',
                'status',
                'project_code',
                'panel_id',
                'organization_id',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'fda_no',
                'certificate_no',
                'expire_at',
                'is_closed',
                'closed_at',
                'next_progress_at',
                'project_coordinator_id',
                'is_fda',
                'crec_number',
                'crec_number_certificate',
                'project_coordinator_2nd_id',
                'project_coordinator_3rd_id',
                'project_viewer_id',
                'name_changed',
                'is_active',
            ]
        ];
    }

}
