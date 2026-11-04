<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "person".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $idcard_no เลขบัตรประชาขน
 * @property int $title_id รหัสคำนำหน้าชื่อ
 * @property string $first_name ชื่อ
 * @property string $last_name นามสกุล
 * @property int $role_id รหัสหน้าที่
 * @property int $user_id รหัสผู้ใช้
 * @property string $email อีเมลล์
 * @property string $tel เบอร์โทรศัพท์
 * @property int $deleted 0=ไม่ลบ,1=ลบ
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $department_id แผนก
 * @property int $position_id ตำแหน่ง
 * @property int $organization_id องค์กร
 * @property int $job_category_id หน้าที่ในที่ประชุม
 * @property int $is_paediatrician กุมารแพทย์
 * @property string $first_name_eng ชื่อภาษาอังกฤษ 
 * @property string $last_name_eng นามสกุลอังกฤษ 
 * @property string $bank_account ธนาคาร 
 * @property string $bank_account_number เลขที่บัญชี 
 * @property string $bank_branch สาขาธนาคาร 
 * @property int $division_id ภาควิชา
 * @property int $gender เพศ
 * @property string $mobile_no เบอร์มือถือ
 * @property int $is_external เป็นบุคคลภายนอกหรือไม่ 
 * @property int $is_researcher_crec นักวิจัยในโครงการของ CREC 
 * @property string $reg_code รหัสสำหรับลงทะเบียน
 * @property string $expertise ความชำนาญ
 * @property string $signature ลายเซ็นต์
 * @property string $signature_thai ลายเซ็นต์ไทย
 * 
 * @property MeetingRoleSetup[] $meetingRoleSetups
 * @property NotificationHistory[] $notificationHistories
 * @property Department $department
 * @property JobCategory $jobCategory
 * @property Organization $organization
 * @property Position $position
 * @property Role $role
 * @property Title $title
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 * @property PersonRole[] $personRoles
 * @property MeetingRolePanel[] $meetingRolePanals
 * @property MeetingPersons[] $meetingPersons
 * @property PersonSubmissionType[] $personSubmissionTypes
 * @property ProjectResearcher[] $projectResearchers
 * @property ProjectConsultant[] $projectConsultants
 * @property RegisterTransaction[] $registerTransactions
 * @property SubmissionCommittee[] $submissionCommittees
 * @property PersonTraining[] $personTraining
 * @property Division $division 
 * @property SubmissionCoiPerson[] $submissionCoiPeople
 */
class Person extends \yii\db\ActiveRecord {

    const PREFIX_CODE = 'REGCODE';
    const SCENARIO_REGISTER = 'register';

    public $titleEng;
    public $verifyCode;
    public $role_ids = [];

    /** Mandatory certification checkbox shown on registration (item 9). */
    public $accept_cv_certify;
    /** Whether to apply electronic stamp to the uploaded CV (item 11). */
    public $cv_apply_stamp = 1;

    const FEMALE = '1';
    const MALE = '2';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'person';
    }

    private $_panels;

    public static function getGenderStatusLabels() {
        return [
            self::FEMALE => Yii::t('app', 'หญิง'),
            self::MALE => Yii::t('app', 'ชาย'),
        ];
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
            [['cv_file', 'accept_policy', 'organization_id'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['accept_cv_certify'], 'validateChecked', 'on' => self::SCENARIO_REGISTER, 'skipOnEmpty' => false],
            [['role_ids'], 'required', 'on' => self::SCENARIO_REGISTER, 'message' => Yii::t('app', 'กรุณาเลือกสิทธิในการเข้าถึงระบบอย่างน้อย 1 รายการ')],
            [['role_ids'], 'each', 'rule' => ['integer'], 'on' => self::SCENARIO_REGISTER],
            [['verifyCode'], 'captcha', 'on' => self::SCENARIO_REGISTER],
            [['accept_cv_certify', 'cv_apply_stamp', 'cv_signed_at', 'cv_signed_by', 'cv_original_file'], 'safe'],
            [['cv_updated_at'], 'date', 'format' => 'php:Y-m-d', 'max' => date('Y-m-d'), 'tooBig' => Yii::t('app', 'วันที่ปรับปรุงประวัติ (CV) ต้องไม่เป็นวันในอนาคต')],
            [['idcard_no'], 'unique', 'filter' => ['deleted' => 0]],
            [['email'], 'unique', 'filter' => ['deleted' => 0]],
            [['first_name', 'last_name', 'first_name_eng', 'last_name_eng', 'title_id', 'email', 'mobile_no', 'organization_id'], 'required'],
            [['title_id', 'role_id', 'user_id', 'deleted', 'created_by', 'updated_by', 'department_id', 'position_id', 'committee_qualification_id', 'organization_id', 'job_category_id', 'is_paediatrician', 'division_id', 'gender', 'is_external', 'is_researcher_crec'], 'integer'],
            [['created_at', 'updated_at', 'titleEng', 'accept_policy'], 'safe'],
            [['email'], 'email'],
            [['first_name', 'last_name', 'email', 'tel', 'first_name_eng', 'signature', 'signature_thai', 'last_name_eng', 'bank_account', 'bank_branch', 'mobile_no', 'bank_account_number', 'reg_code', 'expertise'], 'string', 'max' => 255],
            [['idcard_no'], 'string', 'max' => 13],
//                [['cv_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,gif,pdf,doc,xls,docx,xlsx,ppt,pptx,rar,zip'],
//            [['cv_file'], 'file', 'skipOnEmpty' => true],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['job_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobCategory::className(), 'targetAttribute' => ['job_category_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['title_id'], 'exist', 'skipOnError' => true, 'targetClass' => Title::className(), 'targetAttribute' => ['title_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division_id' => 'id']],
        ];
    }

    /**
     * Inline validator: passes only when the attribute represents a checked
     * checkbox. Handles scalar (1 / "1") and checkboxList array form (["1"]).
     *
     * @param string $attribute
     * @param array $params
     */
    public function validateChecked($attribute, $params) {
        $value = $this->$attribute;
        $checked = false;
        if (is_array($value)) {
            $checked = in_array(1, array_map('intval', $value), true);
        } else {
            $checked = ((int) $value === 1);
        }
        if (!$checked) {
            $this->addError($attribute, Yii::t('app', 'กรุณายืนยันการรับรองความถูกต้องของเอกสาร'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'idcard_no' => Yii::t('app', 'เลขบัตรประชาขน'),
            'title_id' => Yii::t('app', 'คำนำหน้าชื่อ'),
            'titleEng' => Yii::t('app', ''),
            'first_name' => Yii::t('app', 'ชื่อ'),
            'last_name' => Yii::t('app', 'นามสกุล'),
            'role_id' => Yii::t('app', 'รหัสหน้าที่'),
            'user_id' => Yii::t('app', 'รหัสผู้ใช้'),
            'email' => Yii::t('app', 'อีเมลล์'),
            'tel' => Yii::t('app', 'เบอร์โทรศัพท์'),
            'mobile_no' => Yii::t('app', 'เบอร์มือถือ'),
            'deleted' => Yii::t('app', '0=ไม่ลบ,1=ลบ'),
            'signature' => Yii::t('app', 'ลายเซ็นต์อังกฤษ'),
            'signature_thai' => Yii::t('app', 'ลายเซ็นต์ไทย'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงข้อมูลเมื่อ'),
            'fullName' => Yii::t('app', 'ชื่อ - สกุล'),
            'i18nFullName' => Yii::t('app', 'ชื่อ - สกุล'),
            'fullNameWithEng' => Yii::t('app', 'นักวิจัย'),
            'department_id' => Yii::t('app', 'แผนก/คณะ'),
            'position_id' => Yii::t('app', 'ตำแหน่ง'),
            'organization_id' => Yii::t('app', 'องค์กร/หน่วยงาน'),
            'job_category_id' => Yii::t('app', 'คุณสมบัติของกรรมการ (เฉพาะกรรมการจริยธรรม)'),
            'is_paediatrician' => Yii::t('app', 'ข้าพเจ้าเป็นกุมารแพทย์'),
            'first_name_eng' => Yii::t('app', 'ชื่อภาษาอังกฤษ'),
            'last_name_eng' => Yii::t('app', 'นามสกุลอังกฤษ'),
            'bank_account' => Yii::t('app', 'ธนาคาร'),
            'bank_account_number' => Yii::t('app', 'เลขที่บัญชี'),
            'bank_branch' => Yii::t('app', 'สาขาธนาคาร'),
            'division_id' => Yii::t('app', 'ฝ่าย/ภาควิชา'),
            'gender' => Yii::t('app', 'เพศ'),
            'committee_qualification_id' => Yii::t('app', 'คุณสมบัติตามวิชาชีพ'),
            'is_external' => Yii::t('app', 'ข้าพเจ้าเป็นบุคคลภายนอกองค์กร'),
            'reg_code' => Yii::t('app', 'รหัสสำหรับลงทะเบียน'),
            'expertise' => Yii::t('app', 'ความชำนาญ'),
            'cv_file' => Yii::t('app', 'ไฟล์ประวัติผู้วิจัย'),
            'verifyCode' => Yii::t('app', 'โปรดพิมพ์อักษรที่ท่านเห็นข้างล่างนี้ลงในช่องแล้วกด ถัดไป'),
            'is_researcher_crec' => Yii::t('app', 'นักวิจัยในโครงการของ CREC'),
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

    public function fields() {
        return [
            'id', 'first_name', 'first_name_eng', 'last_name', 'last_name_eng', 'email', 'mobile_no',
            'fullName', 'fullNameEng', 'fullNameWithEng', 'cv_file', 'tel', 'idcard_no'
        ];
    }

    public function extraFields() {
        return [
            'title', 'organization', 'department', 'division', 'user'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationHistories() {
        return $this->hasMany(NotificationHistory::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment() {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }

    public function getCommitteeQualification() {
        return $this->hasOne(CommitteeQualification::className(), ['id' => 'committee_qualification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision() {
        return $this->hasOne(Division::className(), ['id' => 'division_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobCategory() {
        return $this->hasOne(JobCategory::className(), ['id' => 'job_category_id']);
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
    public function getPosition() {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitle() {
        return $this->hasOne(Title::className(), ['id' => 'title_id']);
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
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonSubmissionTypes() {
        return $this->hasMany(PersonSubmissionType::className(), ['person_id' => 'id']);
    }

    public function getMeetingPersons() {
        return $this->hasMany(MeetingPerson::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCoiPeople() {
        return $this->hasMany(SubmissionCoiPerson::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectResearchers() {
        return $this->hasMany(ProjectResearcher::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectConsultants() {
        return $this->hasMany(ProjectConsultant::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterTransactions() {
        return $this->hasMany(RegisterTransaction::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittees() {
        return $this->hasMany(SubmissionCommittee::className(), ['person_id' => 'id']);
    }

    public function getPersonRoles() {
        return $this->hasMany(PersonRole::className(), ['person_id' => 'id']);
    }

    public function getPersonTraining() {
        return $this->hasMany(PersonTraining::className(), ['person_id' => 'id']);
    }

    public function getMeetingRolePanels() {
        return $this->hasMany(MeetingRolePanel::className(), ['person_id' => 'id']);
    }

    public function getRoleNameByPanel($panelId) {
        $personRoles = $this->getPersonRoles()->joinWith(['personRolePanels'])->isDeleted(FALSE)->panel($panelId)->all();
        $name = '';
        foreach ($personRoles as $pr) {
            if (!empty($name)) {
                $name .= \Yii::t('app', 'และ');
            }
            $name .= $pr->getRoleNameByPanel($panelId);
        }
        if (!empty($name) && !empty($this->isExternalLabel)) {
            $name .= \Yii::t('app', 'และ') . $this->isExternalLabel;
        }
        return $name;
    }

    public function getI18nFullName() {
        $suffix = \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        $name = isset($this->title) ? $this->title->{"name" . $suffix} : "";
        $name .= "{$this->{"first_name" . $suffix}} {$this->{"last_name" . $suffix}}";

        return $name;
    }
    
    public function getFullNameNoTitle() {
        $name = "{$this->first_name} {$this->last_name}";

        return $name;
    }

    public function getFullNameEngNoTitle() {
        $name = "{$this->first_name_eng} {$this->last_name_eng}";

        return $name;
    }
    
    public function getFullName() {
        $name = isset($this->title) ? $this->title->name : "";
        $name .= "{$this->first_name} {$this->last_name}";

        return $name;
    }

    public function getFullNameEng() {
        $name = isset($this->title) ? $this->title->name_eng : "";
        $name .= "{$this->first_name_eng} {$this->last_name_eng}";

        return $name;
    }

    public function getSignatureUrl() {
        return \yii\helpers\Url::to(['person/download-sign', 'id' => $this->id]);
    }

    public function getSignatureThaiUrl() {
        return \yii\helpers\Url::to(['person/download-sign-thai', 'id' => $this->id]);
    }

    public function getTemplatePathAliasSignature() {
        return Yii::getAlias("@app/web/uploads/person-signature/{$this->signature}");
    }

    public function getTemplatePathAliasSignatureThai() {
        return Yii::getAlias("@app/web/uploads/person-signature/{$this->signature_thai}");
    }

    public function getFullName_eng() {
        $name = isset($this->title) ? $this->title->name_eng : "";
        $name .= "{$this->first_name_eng} {$this->last_name_eng}";

        return $name;
    }

    public function getDecryptedSignatureData($type = 'eng') {
        $filePath = $type === 'thai' ? $this->templatePathAliasSignatureThai : $this->templatePathAliasSignature;

        if (!file_exists($filePath)) {
            return false;
        }

        if (\app\components\FileEncryptor::isEncryptedFile($filePath)) {
            return Yii::$app->fileEncryptor->decryptFileToString($filePath);
        }

        return file_get_contents($filePath);
    }

    public function getFullNameWithEng() {
        if (!empty(trim($this->fullNameEng))) {
            return "{$this->fullName} ($this->fullNameEng)";
        } else {
            return $this->fullName;
        }
    }

    public function setPanels($panels) {
        $this->_panels = $panels;
    }

    public function getPanels() {
        return $this->_panels;
    }

    public function getCountSubmisionCommittee() {
//        $pcount = $this->getSubmissionCommittees()->isDeleted(FALSE)->andWhere(['>=', 'submission_committee.status', 200])->andWhere(['<', 'submission_committee.status', 250])->count();
        $pcount = $this->getSubmissionCommittees()->isDeleted(FALSE)->andWhere(['submission_committee.status' => SubmissionCommittee::STATUS_ACCEPTED])->count();
        return $pcount;
    }

    public function getFullOrg() {
        $org = isset($this->organization) ? $this->organization->name : "";
        $org .= isset($this->department) ? " {$this->department->name}" : "";
        $org .= isset($this->division) ? " {$this->division->name}" : "";
        return $org;
    }

    public function getFullOrgEn() {
        $org = isset($this->organization_id) ? " <br> {$this->organization->i18nName}" : "";
        $org .= isset($this->department_id) ? "  <br>{$this->department->i18nName}" : "";
        $org .= isset($this->division_id) ? "<br>{$this->division->i18nName}" : "";
        return $org;
    }

    public function getIsExternalLabel() {
        return $this->is_external ? \Yii::t('app', 'บุคคลภายนอก') : "";
    }

    public function getCOIMeetingAgendas($meetingId) {
        $mp = MeetingPerson::find()->isDeleted(FALSE)->meeting($meetingId)->person($this->id)->one();
        if (!isset($mp)) {
            return [];
        }
        return MeetingAgenda::find()->joinWith(['submission', 'submission.project', 'submission.projectResearchers', 'submission.projectResearchers.person'])
                        ->isDeleted(FALSE)->meeting($meetingId)->hasCOI($this->id, $this->last_name)->all();
    }

    public function generateRegCode() {
        $prefix = self::PREFIX_CODE;
        $code = RunningCode::generateCode($prefix, 3);
        return str_replace(self::PREFIX_CODE, '', $code);
    }

    public function getCvPath() {
        return "uploads/cv-file";
    }

    public function getCvFile() {
        return Yii::getAlias("@app/web/{$this->cvPath}/{$this->cv_file}");
    }

    public function getCvFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->cvPath}/{$this->cv_file}");
    }

    /**
     * Whether the CV is fresh as of $asOfDate. A CV with no recorded
     * cv_updated_at (legacy data) is treated as fresh — qualification checks
     * only flag CVs that DO have a date older than the allowed window.
     *
     * @param string|null $asOfDate Y-m-d (default today)
     * @return bool
     */
    public function getIsCvFresh($asOfDate = null) {
        if (empty($this->cv_updated_at)) {
            return true;
        }
        $months = (int) \app\models\Setting::getValueOrDefault(\app\models\Setting::CV_FRESHNESS_MONTHS, 6);
        $asOf = isset($asOfDate) ? $asOfDate : date('Y-m-d');
        $cutoff = date('Y-m-d', strtotime("-{$months} months", strtotime($asOf)));
        return $this->cv_updated_at >= $cutoff;
    }

    /**
     * The CV expiry date = cv_updated_at + CV_FRESHNESS_MONTHS (item 37). Inverse
     * of the cutoff math in getIsCvFresh() — both compare against the same window,
     * so the freshness coloring stays consistent.
     *
     * @return string|null Y-m-d, or null when no cv_updated_at is recorded
     */
    public function getCvExpiryDate() {
        if (empty($this->cv_updated_at)) {
            return null;
        }
        $months = (int) \app\models\Setting::getValueOrDefault(\app\models\Setting::CV_FRESHNESS_MONTHS, 6);
        return date('Y-m-d', strtotime("+{$months} months", strtotime($this->cv_updated_at)));
    }

    /**
     * Whether this person uses electronic-stamp signing — the single
     * person-level preference ("อันเดียวกับ CV"). True when the CV was last
     * signed WITH a stamp. Drives auto-stamping of training files on upload.
     *
     * @return bool
     */
    public function usesEsignStamp() {
        return !empty($this->cv_signed_at)
                && \app\models\PersonDocumentAudit::wasLastStamped($this->id, \app\models\PersonDocumentAudit::DOC_TYPE_CV);
    }

    /**
     * True when the CV exists but is older than the allowed window (item 13.1).
     */
    public function getCvQualificationFail($asOfDate = null) {
        return !empty($this->cv_file) && !empty($this->cv_updated_at) && !$this->getIsCvFresh($asOfDate);
    }

    /**
     * True when the person has at least one training expired as of $asOfDate
     * (item 13.2). Submission-type agnostic (legacy/generic check).
     */
    public function getHasExpiredTraining($asOfDate = null) {
        return $this->getPersonTraining()->isDeleted(FALSE)->expired($asOfDate)->exists();
    }

    /**
     * Evaluate the person's training against the categories REQUIRED by a given
     * submission type (Phase 7 / matrix). For each required category:
     *   - RULE_REQUIRED → must hold at least one valid (non-expired) training of
     *     that category; missing or all-expired is a problem.
     *   - RULE_ANY_OF   → must hold at least one valid training in the category
     *     group; missing or all-expired is a problem.
     *
     * @param SubmissionType $submissionType
     * @param string|null $asOfDate Y-m-d (default today)
     * @return array list of problems, each ['category'=>int, 'rule'=>int, 'reason'=>'missing'|'expired']
     */
    public function getTrainingRequirementStatus($submissionType, $asOfDate = null) {
        $problems = [];
        if (!isset($submissionType)) {
            return $problems;
        }
        $required = $submissionType->getRequiredTrainingCategories();
        if (empty($required)) {
            return $problems;
        }
        $asOf = isset($asOfDate) ? $asOfDate : date('Y-m-d');

        // Load this person's non-deleted trainings joined to their category.
        $trainings = $this->getPersonTraining()->isDeleted(FALSE)
                        ->joinWith('trainingType')->all();
        // Group by category: count present + count valid (non-expired).
        $present = [];
        $valid = [];
        foreach ($trainings as $t) {
            if (!isset($t->trainingType) || $t->trainingType->category === null) {
                continue;
            }
            $cat = (int) $t->trainingType->category;
            $present[$cat] = isset($present[$cat]) ? $present[$cat] + 1 : 1;
            $expired = !empty($t->expire_date) && strtotime($t->expire_date) <= strtotime($asOf);
            if (!$expired) {
                $valid[$cat] = isset($valid[$cat]) ? $valid[$cat] + 1 : 1;
            }
        }
        foreach ($required as $cat => $rule) {
            $hasPresent = !empty($present[$cat]);
            $hasValid = !empty($valid[$cat]);
            if (!$hasPresent) {
                $problems[] = ['category' => $cat, 'rule' => $rule, 'reason' => 'missing'];
            } else if (!$hasValid) {
                $problems[] = ['category' => $cat, 'rule' => $rule, 'reason' => 'expired'];
            }
        }
        return $problems;
    }

    /**
     * Returns a warning-icon + tooltip listing qualification problems, or null
     * when the researcher meets the criteria. Reused by the researcher-selection
     * dropdown and gridview (items 12-13).
     *
     * When $submissionType is given, training is evaluated against the categories
     * that submission type requires (Phase 7 matrix). When null, falls back to
     * the generic "any expired training" check (backward compatible).
     *
     * @param string|null $asOfDate Y-m-d to evaluate against (default today)
     * @param SubmissionType|null $submissionType optional submission type context
     * @return string|null HTML, or null
     */
    public function getQualificationWarning($asOfDate = null, $submissionType = null) {
        $reasons = [];
        if ($this->getCvQualificationFail($asOfDate)) {
            $reasons[] = Yii::t('app', 'CV เกินกำหนดอายุ');
        }
        if (isset($submissionType)) {
            $catLabels = \app\models\TrainingType::getCategoryLabels();
            foreach ($this->getTrainingRequirementStatus($submissionType, $asOfDate) as $p) {
                $catName = isset($catLabels[$p['category']]) ? $catLabels[$p['category']] : '';
                if ($p['reason'] === 'missing') {
                    $reasons[] = Yii::t('app', 'ขาดการอบรม {cat}', ['cat' => $catName]);
                } else {
                    $reasons[] = Yii::t('app', 'การอบรม {cat} หมดอายุ', ['cat' => $catName]);
                }
            }
        } else if ($this->getHasExpiredTraining($asOfDate)) {
            $reasons[] = Yii::t('app', 'เอกสารการอบรมหมดอายุ');
        }
        if (empty($reasons)) {
            return null;
        }
        $tooltip = Yii::t('app', 'นักวิจัยขาดคุณสมบัติ') . ': ' . implode(', ', $reasons);
        return \yii\helpers\Html::tag('i', '', [
                    'class' => 'icon wb-warning red-600',
                    'style' => 'margin-left:4px;',
                    'data-toggle' => 'tooltip',
                    'title' => $tooltip,
        ]);
    }

    /**
     * HTML label for CV expiry status as of $asOfDate (items 14 & 37):
     * shows the computed expiry date (cv_updated_at + CV_FRESHNESS_MONTHS),
     * green when still fresh, red once that date has passed, grey when no data.
     */
    public function getCvFreshnessStatusHtml($asOfDate = null) {
        if (empty($this->cv_updated_at)) {
            return \yii\helpers\Html::tag('span', Yii::t('app', 'ไม่มีข้อมูล'), ['class' => 'label label-default']);
        }
        $dateText = Yii::$app->formatter->asDate($this->getCvExpiryDate());
        if ($this->getIsCvFresh($asOfDate)) {
            return \yii\helpers\Html::tag('span', $dateText, ['class' => 'label label-success']);
        }
        return \yii\helpers\Html::tag('span', $dateText, ['class' => 'label label-danger']);
    }

    /**
     * HTML chips for each training/GCP record's expiry status as of $asOfDate
     * (item 14): green when valid, red when expired.
     *
     * When $submissionType is given, also surface a red "ขาด..." chip for any
     * required training category the person holds none of (Phase 7).
     */
    public function getTrainingExpiryStatusHtml($asOfDate = null, $submissionType = null) {
        $chips = [];
        $trainings = $this->getPersonTraining()->isDeleted(FALSE)->all();
        foreach ($trainings as $t) {
            if (empty($t->expire_date)) {
                continue;
            }
            $name = isset($t->trainingType) ? $t->trainingType->name : ($t->name_thai_course ?: Yii::t('app', 'การอบรม'));
            $dateText = Yii::$app->formatter->asDate($t->expire_date);
            $cls = $t->getIsExpired($asOfDate) ? 'label label-danger' : 'label label-success';
            $chips[] = \yii\helpers\Html::tag('span', \yii\helpers\Html::encode($name) . ' : ' . $dateText, [
                        'class' => $cls,
                        'style' => 'display:inline-block;margin:1px;',
            ]);
        }
        // Missing-required categories (per submission-type matrix).
        if (isset($submissionType)) {
            $catLabels = \app\models\TrainingType::getCategoryLabels();
            foreach ($this->getTrainingRequirementStatus($submissionType, $asOfDate) as $p) {
                if ($p['reason'] === 'missing') {
                    $catName = isset($catLabels[$p['category']]) ? $catLabels[$p['category']] : '';
                    $chips[] = \yii\helpers\Html::tag('span', Yii::t('app', 'ขาด {cat}', ['cat' => $catName]), [
                                'class' => 'label label-danger',
                                'style' => 'display:inline-block;margin:1px;',
                    ]);
                }
            }
        }
        if (empty($chips)) {
            return \yii\helpers\Html::tag('span', Yii::t('app', 'ไม่มีข้อมูล'), ['class' => 'label label-default']);
        }
        return implode(' ', $chips);
    }

    public function getViewFilePdf() {
        $s = str_replace("-", "", $this->cv_file, $var);
        $info = pathinfo($s);
        $view = '';
        if (isset($this->cv_file)) {
            if (in_array($info['extension'], ['doc', 'docx'])) {
                $view = '';
            } else if (in_array($info['extension'], ['pdf'])) {
                $view = \yii\helpers\Html::a(' <i class="icon wb-zoom-in"></i> view CV ', ['person/view-file', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'view pdf file')]);
            }
        }
        return $view;
    }

    public function getDivisionName() {
        if (isset($this->division)) {
            return $this->division->i18nName;
        } else if (isset($this->department)) {
            return $this->department->i18nName;
        } else if (isset($this->organization)) {
            return $this->organization->i18nName;
        }
    }

    public function getDivisionThai() {
        if (isset($this->division)) {
            return $this->division->name;
        } else if (isset($this->department)) {
            return $this->department->name;
        } else if (isset($this->organization)) {
            return $this->organization->name;
        }
    }

    public function getDivisionEng() {
        if (isset($this->division)) {
            return $this->division->name_eng;
        } else if (isset($this->department)) {
            return $this->department->name_eng;
        } else if (isset($this->organization)) {
            return $this->organization->name_eng;
        }
    }

    public function isAssessmentVisible($submissionId) {
        $currentRoles = Yii::$app->session->get('currentRole');
        $sub = Submission::findOne($submissionId);
        $coi = $this->getSubmissionCoiPeople()->isDeleted(FALSE)->submission($submissionId)->count() == 0;
        $researcher = false;
        if ($currentRoles['role_id'] == Role::COMMITTEE) {
            $researcher = SubmissionCommittee::find()->isDeleted(false)->project($sub->project_id)->person(Yii::$app->user->identity->person->id)->exists();
            return $coi && $researcher;
        } else if ($currentRoles['role_id'] == Role::ADMIN || $currentRoles['role_id'] == Role::SECRETARY || $currentRoles['role_id'] == Role::PRESIDENT || $currentRoles['role_id'] == Role::COPRESIDENT || $currentRoles['role_id'] == Role::STAFF) {
            $researcher = true;
            return $coi && $researcher;
        }
    }

    public function isSubmissionVisible($submissionId) {
        $currentRoles = Yii::$app->session->get('currentRole');
        $sub = Submission::findOne($submissionId);
        $coi = $this->getSubmissionCoiPeople()->isDeleted(FALSE)->submission($submissionId)->count() == 0;
        $researcher = false;
        if ($currentRoles['role_id'] == Role::RESEARCHER) {
            $persons = ArrayHelper::getColumn($sub->getProjectResearchers()->isDeleted(FALSE)->all(), 'person_id');
            $consultants = ArrayHelper::getColumn($sub->getProjectConsultants()->isDeleted(FALSE)
                                    ->acknowledgeStatus(ProjectConsultant::STATUS_ACCEPTED)->all(), 'person_id');

//            $mointor = \app\models\Submission::find()->isDeleted(false)->projectViewer(\Yii::$app->user->id)->one();
            $researcher = in_array(Yii::$app->user->identity->person->id, $persons) || in_array(Yii::$app->user->identity->person->id, $consultants);
//        $researcher = true;
//        $coi = true;       
            return $researcher;
        } else if ($currentRoles['role_id'] == Role::COORDINATOR) {
            $researcher = Submission::find()->project($sub->project_id)->projectCoordinator(Yii::$app->user->id)->exists();
//            $researcher = Yii::$app->user->id == $sub->project_coordinator_id || Yii::$app->user->id == $sub->project_coordinator_2nd_id || Yii::$app->user->id == $sub->project_coordinator_3rd_id ||  Yii::$app->user->id == $sub->project_viewer_id  ;
            return $researcher;
        } else if ($currentRoles['role_id'] == Role::ADMIN || $currentRoles['role_id'] == Role::SECRETARY || $currentRoles['role_id'] == Role::PRESIDENT || $currentRoles['role_id'] == Role::COPRESIDENT || $currentRoles['role_id'] == Role::STAFF) {
            $researcher = true;
            return $coi && $researcher;
        } else if ($currentRoles['role_id'] == Role::COMMITTEE) {
            $researcher = SubmissionCommittee::find()->isDeleted(false)->project($sub->project_id)->person(Yii::$app->user->identity->person->id)->exists();
            return $coi && $researcher;
            // $persons = ArrayHelper::getColumn($sub->getSubmissionCommittees()->isDeleted(FALSE)->status('<>' . SubmissionCommittee::STATUS_REJECTED)->all(), 'person_id');
            // $researcher = in_array(Yii::$app->user->identity->person->id, $persons);
        }
//                \yii\helpers\VarDumper::dump($coi, 10, true);
//                \yii\helpers\VarDumper::dump($researcher, 10, true);
//                \yii\helpers\VarDumper::dump($persons, 10, true);
//                \yii\helpers\VarDumper::dump(\Yii::$app->user->id);
//        exit;
//        return $coi && $researcher;
    }

    public function getAvailableRoles() {
        return $this->getPersonRoles()->isDeleted(FALSE)->all();
    }

    public function getHasMoreRoles() {
        return $this->getPersonRoles()->isDeleted(FALSE)->count() > 1;
    }

    public function getI18nCurrentRoleName() {
        $currentRole = Yii::$app->session->get('currentRole');
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $currentRole['role'][$attr];
    }

    public function getHasRolePanel($roleId, $panelId) {
        $c = PersonRole::find()->joinWith(['personRolePanels'])->isDeleted(false)->role($roleId)->panel($panelId)->person($this->id)->count();
        return $c > 0;
    }

    public function getAlertProjectAgendaAnswers() {
        $enDate = new \DateTime();
        $stDate = (clone $enDate)->sub(new \DateInterval('P1Y'));
        $query = <<<q
            SELECT pt.id, pt.name, pt.min_occur, COUNT(*) AS count
            FROM project_agenda_answer paa
                INNER JOIN project_type pt ON paa.project_type_id = pt.id
                INNER JOIN submission s ON paa.submission_id = s.id
            WHERE paa.deleted = 0 AND s.deleted = 0 AND pt.is_alert = 1
                AND paa.created_at >= '{$stDate->format('Y-m-d H:i:s')}'
                AND paa.created_at <= '{$enDate->format('Y-m-d H:i:s')}'
                AND EXISTS (
                    SELECT id
                    FROM project_researcher pr
                    WHERE pr.project_id = paa.project_id
                        AND pr.deleted = 0 AND pr.person_id = {$this->id}
                        AND pr.is_leader = 1
                )
            GROUP BY pt.id, pt.name, pt.min_occur
            HAVING COUNT(*) >= pt.min_occur
q;
        $recs = \Yii::$app->db->createCommand($query)->queryAll();
        return $recs;
    }

    public function getAlertProjectAgendaAnswersHtml() {
        $answers = $this->getAlertProjectAgendaAnswers();
        if (count($answers) == 0) {
            return '';
        }
        $res = '<div class="alert alert-danger dark">';
        $res .= '<ul>';
        foreach ($answers as $answer) {
            $res .= '<li>';
            $res .= $answer['name'] . " ({$answer['count']} " . \Yii::t('app', 'ครั้ง') . ")";
            $res .= '</li>';
        }
        $res .= '</ul>';
        $res .= '</div>';
        return $res;
    }

    public function getAlertDeviationProtocols() {
        $enDate = new \DateTime();
        $status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
        $stDate = (clone $enDate)->sub(new \DateInterval('P1Y'));
        $major = DeviationEvent::MAJOR;
        $query = <<<q
            SELECT de.meeting_violation_type, COUNT(*) AS count
            FROM submission_event de
                INNER JOIN submission s ON de.submission_id = s.id
            WHERE de.deleted = 0 AND s.deleted = 0 AND de.meeting_violation_type = {$major}
                AND s.status = {$status}
                AND de.created_at >= '{$stDate->format('Y-m-d H:i:s')}'
                AND de.created_at <= '{$enDate->format('Y-m-d H:i:s')}'
                AND EXISTS (
                    SELECT id
                    FROM project_researcher pr
                    WHERE pr.project_id = s.project_id
                        AND pr.deleted = 0 AND pr.person_id = {$this->id}
                        AND pr.is_leader = 1
                )
            GROUP BY de.meeting_violation_type
            HAVING COUNT(*) >= 2
q;
        $recs = \Yii::$app->db->createCommand($query)->queryAll();
        return $recs;
    }

    public function getAlertDeviationProtocolHtml() {
        $answers = $this->getAlertDeviationProtocols();
        if (count($answers) == 0) {
            return '';
        }
        $res = '<div class="alert alert-danger dark">';
        $res .= '<ul>';
        foreach ($answers as $answer) {
            $res .= '<li>';
            $res .= DeviationEvent::violationLabels()[$answer['meeting_violation_type']] . " ({$answer['count']} " . \Yii::t('app', 'ครั้ง') . ")";
            $res .= '</li>';
        }
        $res .= '</ul>';
        $res .= '</div>';
        return $res;
    }

    /**
     * @inheritdoc
     * @return PersonQuery the active query used by this AR class.
     */
    public static function find() {
        return new PersonQuery(get_called_class());
    }

}
