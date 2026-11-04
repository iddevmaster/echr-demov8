<?php

namespace app\models;

use app\components\Crec;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
use bedezign\yii2\audit\models\AuditTrail;
use DateTime;
use Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "submission".
 *
 * @property int $id รหัสหน่วยงาน
 * @property string $remark หมายเหตุ
 * @property string $certified_date วันที่รับรอง
 * @property int $status สถานะ
 * @property int $project_id โครงการวิจัย
 * @property int $organization_id องค์กร
 * @property string $full_doc_file ไฟล์รวมเอกสาร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $submission_type_id ประเภทของโครงการวิจัย
 * @property string $resolution มติที่ประชุม
 * @property int $responsible_person เจ้าหน้าที่ที่รับผิดชอบ
 * @property int $secretary_person เลขา
 * @property int $president_person ประธาน
 * @property string $responsible_date วันที่รับงาน
 * @property int $meeting_plan_date วันที่คาดว่าโครงการจะเข้าที่ประชุมโดยประมาณ
 * @property int $send_plan_date กรุณาส่งแบบประเมินก่อนวันที่
 * @property int $ref_submission_id อ้างอิงการส่ง
 * @property string $correspondence_no เลขที่หนังสือ
 * @property string $correspondence_at วันที่ออกหนังสือ
 * @property int $is_meeting ต้องเข้าพิจารณาเต็มรูปแบบ 
 * @property int $meeting_by ผู้พิจารณา 
 * @property string $meeting_at พิจารณาเมื่อ 
 * @property string $certificate_no เลขที่การรับรอง
 * @property string $expire_at วันที่หมดอายุรับรอง
 * @property int $risk_id ความเสี่ยง
 * @property int $progress_period ระยะเวลาในการติดตาม
 * @property string $next_progress_at ปิดโครงการเมื่อ 
 * @property string $subject เรื่อง
 * @property string $issue1 ประเด็นเพิ่มเติม 1
 * @property string $issue1_eng ประเด็นเพิ่มเติม 1
 * @property string $special_condition special_condition
 * @property string $issue2 ประเด็นเพิ่มเติม 2
 * @property string $remark_checkdoc_staff คอมเมนแก้ไขเอกสารจากเจ้าหน้าที่
 * @property string $remark_assessed_staff คอมเมนประเมินแก้ไขเอกสารจากเจ้าหน้าที่
 * @property int $is_legacy ส่งโครงการที่ผ่านการรับรองแล้ว
 * @property int $submission_by คนส่งงานวิจัย
 * @property int $project_coordinator_id ผู้ประสานงานโครงการ
 * @property int $project_coordinator_2nd_id ผู้ประสานงานโครงการคนที่ 2
 * @property int $project_coordinator_3rd_id ผู้ประสานงานโครงการคนที่ 3
 * @property int $project_viewer_id  viewer
 * @property int $is_accept สถานะหัวหน้าโครงการยืนยันการส่ง
 * @property int $leader_comment คอมเมันเพิ่มเติมของหัวหน้าโครงการ
 * @property string $note  note
 * @property string $event_amendment  event_amendment
 * @property int $events จำนวนเหตุการณ์ของ Deviation
 * @property string $crec_resolution มติที่ประชุม
 * @property string $crec_issue_req_detail รายละเอียดนำส่งเรื่องประเมิน
 * @property string $crec_send_plan_date กรุณาส่งแบบประเมินก่อนวันที่จาก CREC
 * @property int $is_submit_by_api สร้างโดย API
 * @property int $send_to_crec ส่ง Submission ไป CREC
 * @property int $acknowledged_crec_result 0=ไม่ต้องรับทราบ, 1=รับทราบผล, 2=รอตอบรับผลจาก CREC
 * @property int $notify_crec_result_leader 0=ไม่ส่งให้หัวหน้าโครงการ, 1=ส่งให้หัวหน้าโครงการ
 * @property string $crec_certified_date วันที่รับรองจาก CREC
 * @property string $crec_expire_at วันที่หมดอายุรับรองจาก CREC
 * @property string $crec_next_progress_at วันที่รายงานความก้าวหน้าจาก CREC
 * @property string $submission_number เลขที่รับการยื่น submission ของ crec
 * @property int $crec_progress_period ระบะเวลาในการายงานความก้าวหน้าจาก CREC
 * @property int $need_local_issue 1=ประเมิน local issue, 0=ไม่ประเมิน local issue
<<<<<<< HEAD
 * @property string $ex_text_result 
 * @property string $ex_text_result_eng
 * @property string $amendment_text_result_eng
 * @property string $amendment_text_result
 * @property string $n_text_result
 * @property string $n_date
 * @property string $t_date
 * @property string $last_keep_date
 * 
 * @property string $crec_staff เจ้าหน้าที่ CREC
 *
 * @property ProjectConsultant[] $projectConsultants
 * @property SubmissionProjectConsultant[] $submissionProjectConsultants
 * @property Meeting[] $meetings
 * @property MeetingAgenda[] $meetingAgendas
 * @property NotificationHistory[] $notificationHistories
 * @property SubmissionCoiPerson[] $submissionCoiPerson
 * @property Organization $organization
 * @property Project $project
 * @property SubmissionType $submissionType
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionCommittee[] $submissionCommittees
 * @property SubmissionDocument[] $submissionDocuments
 * @property SubmissionRevise[] $submissionRevises
 * @property SubmissionVolunteerNumber[] $submissionVolunteerNumbers
 * @property Submission $refSubmission
 * @property User $responsiblePerson
 * @property User $secretaryPerson 
 * @property User $fullboardBy 
 * @property SubmissionStatusHistory[] $submissionStatusHistories 
 * @property ProjectResearcher[] $projectResearchers 
 * @property SubmissionProjectResearcher[] $submissionProjectResearchers
 * @property SubmissionResultDocument[] $submissionResultDocuments
 * @property ProjectCodeHistory[] $projectCodeHistories
 * @property SubmissionVolunteer[] $submissionVolunteers
 * @property SubmissionEvent[] $submissionEvents
 */
class Submission extends \yii\db\ActiveRecord {

    const STATUS_PENDING_SUBMISSION = 100;
    const STATUS_NOT_APPROVE_PROJECT_RESEARCHER = 110;
    const STATUS_DOC_REJECTED = 150;
    const STATUS_DOC_REJECTED_BY_COMMITTEE = 160;
    const STATUS_WAITING_APPROVE_PROJECT_RESEARCHER = 190;
    const STATUS_SUBMITTED = 200;
    const STATUS_SUBMITTED_CON = 250;
    const STATUS_DOC_APPROVED = 300;
    const STATUS_CODE_GENERATED = 400;
    const STATUS_MEETING_APPOINTMENT = 500;
    const STATUS_SECRETARY_SELECT_TYPE = 550;
    const STATUS_SECRETARY_SELECTED = 600;
    const STATUS_COMMITTEE_SELECTED = 700;
    const STATUS_COMMITTEE_ACCEPTED = 800;
    const STATUS_COMMITTEE_ASSESSED = 900;
    const STATUS_AGENDA_ADDED = 1000;
    const STATUS_MEETING_DONE = 1100;
    //** เพิ่มสถานะสำหรับให้เจ้าหน้าที่และเลขาตรวจสอบ
    const STATUS_STAFF_APPROVE_AGENDA = 1150;
    const STATUS_WAITING_PRE_APPROVE_AGENDA = 1160;
    const STATUS_SECRETARY_APPROVE_AGENDA = 1200;
    const STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN = 1260;
    const STATUS_STAFF_UPLOAD_RESULTDOCUMENT = 1300;
    const CUSTOM_STATUS_MEETING_PENDING = 'MEETING_PENDING';
    const CUSTOM_STATUS_STEP_PENDING = 'STEP_PENDING';
    const RESOLUTION_Y = 'Y';
    const RESOLUTION_C = 'C';
    const RESOLUTION_R = 'R';
    const RESOLUTION_N = 'N';
    const RESOLUTION_W = 'W';
    const RESOLUTION_T = 'T';
    const RESOLUTION_P = 'P';
    const MODE_MEETINGPLAN = '1';
    const MODE_GENERATECODE = '2';
    const MODE_FULLFILE = '3';
    const MODE_SETAGENDA = '4';
    const MODE_SETSECRETARY = '5';
    const MODE_ACCEPTCOMMITTEE = '6';
    const MODE_ASSESSEDCOMMITTEE = '7';
    const MODE_CERTIFICATE = '8';
    const MODE_CHECKDOC = '9';
    const MODE_RESPONSIBLE = '10';
    const MODE_COORDINATOR = '11';
    const SCENARIO_ADD_AGENDA = 'add-agenda';
    const SCENARIO_CONFIRM_MEETING = 'comfirm-meeting';
    const MODE_ACCEPTCOMMITTEEBYSTAFF = '12';
    const MODE_ASSESSTYPE = '13';
    const SCENARIO_NEWSUBMISSION = 'new-submmission';
    const SCENARIO_CONTSUBMISSION = 'continue';
    const SCENARIO_GENERATE_CODE = 'generate-code';
    const SCENARIO_CREATE_CREC = 'create-crec';
    const SCENARIO_UPDATE_CREC_RESOLUTION = 'update-crec-resolution';
    const SCENARIO_STATUS = 'checkstatus';
    const SCENARIO_MEETING_PLAN = 'meetting-plan';
    const CREC_WAITING_RESULT_ACKNOWLEDGE = 2;
    const CREC_RESULT_ACKNOWLEDGED = 1;
    const TYPE_NO = 0;
    const TYPE_FULLBOARD = 1;
    const TYPE_EXPEDITE = 2;
    const TYPE_EXEMPTION = 3;

    public $panelId, $statusCommittee, $meetingId, $agendaId, $remarkCommittee, $can_meeting, $remark_meeting, $isFda, $fundingSourceId;

    public static function getAssessTypeLabel() {
        return [
            self::TYPE_NO => Yii::t('app', 'N/A'),
            self::TYPE_FULLBOARD => Yii::t('app', 'Full board'),
            self::TYPE_EXPEDITE => Yii::t('app', 'Expedited'),
            self::TYPE_EXEMPTION => Yii::t('app', 'Exemption'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission';
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
            [['subject'], 'required', 'when' => function($model) {
                    $st = SubmissionType::findOne($model->submission_type_id);
                    return isset($st) && $st->add_subject;
                }],
            [['isFda', 'panelId'], 'required', 'on' => self::SCENARIO_GENERATE_CODE],
            [['send_plan_date', 'meeting_plan_date'], 'required', 'on' => self::SCENARIO_MEETING_PLAN],
            [['status'], 'required', 'on' => self::SCENARIO_STATUS],
            [['is_meeting'], 'required', 'on' => self::SCENARIO_CONFIRM_MEETING],
            [['meetingId', 'agendaId'], 'required', 'on' => self::SCENARIO_ADD_AGENDA],
            [['project_id', 'submission_type_id', 'correspondence_no', 'correspondence_at'], 'required', 'on' => self::SCENARIO_CONTSUBMISSION],
            [['events'], 'required', 'when' => function ($model) {
                    return $model->created_at >= new DateTime('2020-10-24') && $model->submission_type_id == SubmissionType::TYPE_DEVIATION;
                }],
            [['correspondence_no', 'correspondence_at', 'submission_type_id', 'fundingSourceId'], 'required', 'on' => self::SCENARIO_NEWSUBMISSION],
            [['correspondence_no', 'correspondence_at', 'submission_type_id'], 'required', 'on' => self::SCENARIO_CREATE_CREC],
            [['crec_resolution'], 'required', 'on' => self::SCENARIO_UPDATE_CREC_RESOLUTION],
            [['certified_date', 'created_at', 'updated_at', 'project.name_thai', 'responsible_date', 'meeting_plan_date', 'send_plan_date', 'correspondence_at', 'meeting_at', 'expire_at', 'next_progress_at', 'crec_send_plan_date', 'crec_certified_date', 'crec_expire_at', 'crec_next_progress_at'], 'safe'],
            [['status', 'project_id','assess_type', 'organization_id', 'deleted', 'created_by', 'updated_by', 'submission_type_id', 'ref_submission_id', 'responsible_person', 'panelId', 'secretary_person', 'president_person', 'is_meeting', 'risk_id', 'progress_period', 'statusCommittee', 'meetingId', 'agendaId', 'can_meeting', 'is_legacy', 'submission_by', 'project_coordinator_id', 'is_accept', 'isFda', 'events', 'project_coordinator_2nd_id', 'project_coordinator_3rd_id', 'project_viewer_id', 'is_submit_by_api', 'send_to_crec', 'acknowledged_crec_result', 'notify_crec_result_leader', 'crec_progress_period', 'need_local_issue'], 'integer'],
            [['issue1', 'issue1_eng', 'issue2','special_condition', 'remark_checkdoc_staff', 'remark_assessed_staff', 'leader_comment', 'note', 
                'event_amendment', 'crec_leader_name', 'crec_leader_name_eng', 'crec_leader_site_name', 'crec_leader_site_name_eng',
                'crec_leader_org_name', 'crec_leader_org_name_eng', 'crec_resolution', 'crec_issue_req_detail', 'crec_staff', 'remark_meeting',
                'ex_text_result','ex_text_result_eng','amendment_text_result','amendment_text_result_eng','n_text_result','n_date','t_date','last_keep_date'], 'string'],
            [['remark', 'full_doc_file', 'resolution', 'correspondence_no', 'certificate_no', 'subject', 'remarkCommittee', 'submission_number'], 'string', 'max' => 255],
            [['ref_submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['ref_submission_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['project_coordinator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_id' => 'id']],
            [['project_coordinator_2nd_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_2nd_id' => 'id']],
            [['project_coordinator_3rd_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_coordinator_3rd_id' => 'id']],
            [['project_viewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['project_viewer_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
            [['responsible_person'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['responsible_person' => 'id']],
            [['secretary_person'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['secretary_person' => 'id']],
            [['president_person'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['president_person' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['meeting_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['meeting_by' => 'id']],
            [['risk_id'], 'exist', 'skipOnError' => true, 'targetClass' => Risk::className(), 'targetAttribute' => ['risk_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'remark' => Yii::t('app', 'ขอเสนอแนะ'),
            'certified_date' => Yii::t('app', 'วันที่รับรอง'),
            'status' => Yii::t('app', 'สถานะ'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
            'organization_id' => Yii::t('app', 'องค์กร'),
            'full_doc_file' => Yii::t('app', 'ไฟล์รวมเอกสาร'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'submission_type_id' => Yii::t('app', 'ประเภทของโครงการวิจัย'),
            'resolution' => Yii::t('app', 'มติที่ประชุม'),
            'crec_resolution' => Yii::t('app', 'มติที่ประชุม CREC'),
            'ref_submission_id' => Yii::t('app', 'อ้างอิงการส่ง'),
            'name' => Yii::t('app', 'ชื่อโครงการภาษาไทย ภาษาอังกฤษ หรือ หมายเลขโครงการ'),
            'responsible_person' => Yii::t('app', 'เจ้าหน้าที่ที่รับผิดชอบ'),
            'responsible_date' => Yii::t('app', 'วันที่รับงาน'),
            'meeting_plan_date' => Yii::t('app', 'วันที่คาดว่าโครงการจะเข้าที่ประชุมโดยประมาณ'),
            'send_plan_date' => Yii::t('app', 'กรุณาส่งแบบประเมินก่อนวันที่'),
            'correspondence_no' => Yii::t('app', 'เลขที่หนังสือ'),
            'correspondence_at' => Yii::t('app', 'วันที่ออกหนังสือ'),
            'isLeader' => Yii::t('app', 'หัวหน้าโครงการ'),
            'secretary_person' => Yii::t('app', 'เลขา'),
            'president_person' => Yii::t('app', 'ประธานเพื่อตรวจสอบหนังสือแจ้งผล'),
            'is_meeting' => Yii::t('app', 'ต้องเข้าพิจารณาเต็มรูปแบบ'),
            'meeting_by' => Yii::t('app', 'ผู้พิจารณา'),
            'meeting_at' => Yii::t('app', 'พิจารณาเมื่อ'),
            'panelId' => Yii::t('app', 'เลือก Panel'),
            'statusCommittee' => Yii::t('app', 'การตอบรับการอ่านงานวิจัย'),
            'responsiblePerson.person.fullName' => Yii::t('app', 'เจ้าหน้าที่ดูแลโครงการ'),
            'meetingId' => Yii::t('app', 'เลือกการประชุมที่จะบรรจุวาระ'),
            'agendaId' => Yii::t('app', 'วาระ'),
            'certificate_no' => Yii::t('app', 'เลขที่การรับรอง'),
            'expire_at' => Yii::t('app', 'วันที่หมดอายุรับรอง'),
            'risk_id' => Yii::t('app', 'ความเสี่ยง'),
            'progress_period' => Yii::t('app', 'ระยะเวลาในการติดตาม (เดือน)'),
            'submittedDate' => Yii::t('app', 'วันที่ส่งพิจารณา'),
            'meetingDate' => Yii::t('app', 'วันที่ประชุม'),
            'remarkCommittee' => Yii::t('app', 'รายละเอียดเพิ่มเติม'),
            'letterAgenda' => Yii::t('app', 'ออกจดหมายแจ้งผล'),
            'can_meeting' => Yii::t('app', 'เข้าประชุมได้หรือไม่'),
            'remark_meeting' => Yii::t('app', 'หมายเหตุการเข้าประชุม'),
            'next_progress_at' => Yii::t('app', 'วันที่ส่งความก้าวหน้าครั้งถัดไป'),
            'subject' => Yii::t('app', 'เรื่อง'),
            'issue1' => Yii::t('app', 'ผลการพิจารณาสำหรับผู้วิจัย'),
            'issue1_eng' => Yii::t('app', 'ผลการพิจารณาสำหรับผู้วิจัย (ภาษาอังกฤษ)'),
            'issue2' => Yii::t('app', 'ประเด็นเพิ่มเติม 2'),
            'projectLeader.person.fullName' => Yii::t('app', 'หัวหน้าโครงการ'),
            'typeAndRef' => Yii::t('app', 'ประเภทการขอรับพิจารณา'),
            'remark_checkdoc_staff' => Yii::t('app', 'ข้อเสนอแนะแก้ไขเอกสารจากเจ้าหน้าที่'),
            'remark_assessed_staff' => Yii::t('app', 'ข้อเสนอแนะในการแก้ไขเอกสารหลังการประเมิน'),
            'is_legacy' => Yii::t('app', 'โครงการใหม่ที่ผ่านการรับรองแล้ว'),
            'submission_by' => Yii::t('app', 'Submission By'),
            'project_coordinator_id' => Yii::t('app', 'ผู้ประสานงานโครงการ'),
            'project_coordinator_2nd_id' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 2'),
            'project_coordinator_3rd_id' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 3'),
            'project_viewer_id' => Yii::t('app', 'viewer'),
            'projectCoordinator.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการ'),
            'projectCoordinator2nd.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 2'),
            'projectCoordinator3rd.person.i18nFullName' => Yii::t('app', 'ผู้ประสานงานโครงการคนที่ 3'),
            'projectViewer.person.i18nFullName' => Yii::t('app', 'viewer'),
            'is_accept' => Yii::t('app', 'การยืนยัน'),
            'leader_comment' => Yii::t('app', 'รายละเอียดเพิ่มเติมจากหัวหน้าโครงการ'),
            'project.i18nName' => Yii::t('app', 'ชื่อโครงการวิจัย'),
            'project.name_thai' => Yii::t('app', 'ชื่อโครงการวิจัย'),
            'note' => Yii::t('app', 'หมายเหตุเพิ่มเติมสำหรับเจ้าหน้า กรรมการ เลขา'),
            'isFda' => Yii::t('app', 'ต้องรายงาน อย.'),
            'fundingSourceId' => Yii::t('app', 'แหล่งทุน'),
            'startDate' => Yii::t('app', 'วันที่เริ่ม.'),
            'endDate' => Yii::t('app', 'วันที่สิ้นสุด.'),
            'submission_type_group_id' => Yii::t('app', 'ประเภทโครงการ.'),
            'panel_id' => Yii::t('app', 'Panel.'),
            'events' => Yii::t('app', 'จำนวนเหตุการณ์ของ Deviation'),
            'event_amendment' => Yii::t('app', 'เหตุการณ์ที่เกิดขึ้นในการ Amendment'),
            'crec_issue_req_detail' => Yii::t('app', 'รายละเอียดนำส่งเรื่องประเมิน'),
            'crec_send_plan_date' => Yii::t('app', 'กรุณาส่งแบบประเมินก่อนวันที่จาก CREC'),
            'is_submit_by_api' => Yii::t('app', 'สร้างโดย API'),
            'send_to_crec' => Yii::t('app', 'ส่ง Submission ไป CREC'),
            'acknowledged_crec_result' => Yii::t('app', 'รับทราบผลประเมินจาก CREC'),
            'notify_crec_result_leader' => Yii::t('app', 'ส่งผลประเมิน CREC ให้หัวหน้าโครงการ'),
            'startYear' => Yii::t('app', 'จากปี'),
            'endYear' => Yii::t('app', 'ถึงปี'),
            'assess_type' => Yii::t('app', 'ประเภทการพิจารณา'),
            'special_condition'=>Yii::t('app', 'special condition'),
            'crec_certified_date' => Yii::t('app', 'วันที่รับรองจาก CREC'),
            'crec_expire_at' => Yii::t('app', 'วันที่หมดอายุรับรองจาก CREC'),
            'crec_next_progress_at' => Yii::t('app', 'วันที่รายงานความก้าวหน้าจาก CREC'),
            'crec_progress_period' => Yii::t('app', 'ระบุเวลาในการายงานความก้าวหน้าจาก CREC'),
            'ex_text_result' => Yii::t('app', 'ข้อเสนอแนะ Expedite , Exemption ในใบแจ้งผล'),
            'ex_text_result_eng' => Yii::t('app', 'ข้อเสนอแนะ Expedite , Exemption ในใบแจ้งผลภาษาอังกฤษ'),
            'amendment_text_result' => Yii::t('app', 'ข้อเสนอแนะ Amendment ในใบแจ้งผล'),
            'amendment_text_result_eng' => Yii::t('app', 'ข้อเสนอแนะ Amendment ในใบแจ้งผลภาษาอังกฤษ'),
            'n_text_result' => Yii::t('app', 'ข้อเพิ่มเติม ผล N'),
            'n_date' => Yii::t('app', 'ให้ติดต่อภายในวันที่'),
            't_date' => Yii::t('app', 'ให้ติดต่อภายในวันที่'),
            'last_keep_date' => Yii::t('app', 'เก็บเอกสารถึงวันที่'),
            'crec_staff' => Yii::t('app', 'เจ้าหน้าที่ CREC'),
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
            ],
            [
                'class' => \app\components\SubmissionStatusHistoryBehavior::className()
            ]
        ];
    }

    /**
     * get trails for this model and all related comment models 
     */
    public function getAuditTrails() {
        return AuditTrail::find()
                        ->orOnCondition([
                            'audit_trail.model_id' => $this->id,
                            'audit_trail.model' => get_class($this),
                        ])
                        ->orOnCondition([
                            'audit_trail.model_id' => ArrayHelper::map($this->getProject()->all(), 'id', 'id'),
                            'audit_trail.model' => 'app\models\Project',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectConsultants() {
        return $this->hasMany(ProjectConsultant::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionBy() {
        return $this->hasOne(User::className(), ['id' => 'submission_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionProjectConsultants() {
        return $this->hasMany(SubmissionProjectConsultant::className(), ['submission_id' => 'id']);
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
    public function getMeetings() {
        return $this->hasMany(Meeting::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendas() {
        return $this->hasMany(MeetingAgenda::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectCodeHistories() {
        return $this->hasMany(ProjectCodeHistory::className(), ['submission_id' => 'id']);
    }

    public function getMeetingAgenda() {
        return MeetingAgenda::find()->isDeleted(FALSE)->submission($this->id)->one();
    }

    public function getresultDocument() {
        return SubmissionResultDocument::find()->isDeleted(FALSE)->submission($this->id)->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationHistories() {
        return $this->hasMany(NotificationHistory::className(), ['submission_id' => 'id']);
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
    public function getProject() {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    public function getSubmissionCoiPerson() {
        return $this->hasMany(SubmissionCoiPerson::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getResponsiblePerson() {
        return $this->hasOne(User::className(), ['id' => 'responsible_person']);
    }

    public function getSecretaryPerson() {
        return $this->hasOne(User::className(), ['id' => 'secretary_person']);
    }

    public function getPresidentPerson() {
        return $this->hasOne(User::className(), ['id' => 'president_person']);
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
    public function getSubmissionCommittees() {
        return $this->hasMany(SubmissionCommittee::className(), ['submission_id' => 'id']);
    }

    public function getSubmissionCommitteeRevises() {
        return $this->hasMany(SubmissionCommitteeRevise::className(), ['submission_id' => 'id']);
    }

    public function getProjectResearchers() {
        return $this->hasMany(ProjectResearcher::className(), ['submission_id' => 'id']);
    }

    public function getSubmissionProjectResearchers() {
        return $this->hasMany(SubmissionProjectResearcher::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments() {
        return $this->hasMany(SubmissionDocument::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionRevises() {
        return $this->hasMany(SubmissionRevise::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionVolunteerNumbers() {
        return $this->hasMany(SubmissionVolunteerNumber::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionResultDocuments() {
        return $this->hasMany(SubmissionResultDocument::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'ref_submission_id']);
    }

    public function getSubmissionStatusHistories() {
        return $this->hasMany(SubmissionStatusHistory::className(), ['submission_id' => 'id']);
    }

    public function getMeetingBy() {
        return $this->hasOne(User::className(), ['id' => 'meeting_by']);
    }

    public function getPreviousSubmission() {
        return Submission::find()->isDeleted(FALSE)->project($this->project_id)->orderBy('id DESC')->andWhere(['<', 'id', $this->id])->one();
    }

    public function getProjectLeader() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader()->one();
    }

    public function getProjectConsultant() {
        return $this->getProjectConsultants()->isDeleted(FALSE)->one();
    }

    public function getProjectCoResearchers() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->all();
    }

    public function getPendingProjectCoResearchers() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->acknowledgeStatus(ProjectResearcher::STATUS_PENDING_ACK)->all();
    }

    public function getHasPendingProjectCoResearchers() {
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->acknowledgeStatus(ProjectResearcher::STATUS_PENDING_ACK)->exists();
    }

    public function getLastAcknowledgedResearcherDate() {
        if ($this->getHasPendingProjectCoResearchers()) {
            return null;
        }
        return $this->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->max('acknowledge_at');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionEvents() {
        return $this->hasMany(SubmissionEvent::className(), ['submission_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRisk() {
        return $this->hasOne(Risk::className(), ['id' => 'risk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionVolunteers() {
        return $this->hasMany(SubmissionVolunteer::className(), ['submission_id' => 'id']);
    }

    public function getRequireSubmissionDocuments() {
        $subDocs = $this->getSubmissionDocuments()->isDeleted(FALSE)->all();
        $docs = [];
        foreach ($subDocs as $subDoc) {
            if (isset($subDoc->documentSubmissionType) && $subDoc->documentSubmissionType->is_require) {
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

    public function getResearcherNamesList() {
        $researchers = $this->getProjectResearchers()->isDeleted(FALSE)->all();
        $res = '';
        $res .= '<ul>';
        foreach ($researchers as $researcher) {
            $res .= "<li>{$researcher->person->fullName} {$researcher->isLeaderLabel}</li>";
        }
        $res .= '</ul>';
        return $res;
    }

    public function getVolunteerNumber() {
        $volunteers = $this->getSubmissionVolunteerNumbers()->isDeleted(FALSE)->all();
        $vol = '';
        $vol .= '<ul>';
        foreach ($volunteers as $volunteer) {
            $vol .= "<li>{$volunteer->volunteerNumber->name} {$volunteer->value} คน</li>";
        }
        $vol .= '</ul>';
        return $vol;
    }

    public function getCommitteePersonSubmit() {
        $committees = $this->getSubmissionCommittees()->isDeleted(FALSE)->status('<>' . SubmissionCommittee::STATUS_REJECTED)->all();
        $com = '';
        $com .= '<ul>';
        foreach ($committees as $committee) {
            $date = Yii::$app->formatter->asDate($committee->submit_date, 'php:d/m/Y');
            $com .= "<li>{$committee->person->fullName} ({$date}) </li>";
        }
        $com .= '</ul>';
        return $com;
    }

    public function getCommitteePersonReturn() {
        $committees = $this->getSubmissionCommittees()->isDeleted(FALSE)->status('<>' . SubmissionCommittee::STATUS_REJECTED)->all();
        $com = '';
        $com .= '<ul>';
        foreach ($committees as $committee) {
            $date = Yii::$app->formatter->asDate($committee->return_date, 'php:d/m/Y');
            $com .= "<li>{$committee->person->fullName} ({$date}) </li>";
        }
        $com .= '</ul>';
        return $com;
    }

    public function getCommitteeReviseCreate() {
        $committees = $this->getSubmissionCommitteeRevises()->isDeleted(FALSE)->all();
        $com = '';
        $com .= '<ul>';
        foreach ($committees as $committee) {
            $date = Yii::$app->formatter->asDate($committee->created_at, 'php:d/m/Y');
            $com .= "<li>{$date}</li>";
        }
        $com .= '</ul>';
        return $com;
    }

    public function getLetterAgenda() {
        $meettingAgendas = $this->getMeetingAgendas()->all();
        foreach ($meettingAgendas as $meettingAgenda) {
            $letters = \app\models\AgendaResultDocument::find()->where(['agenda_id' => $meettingAgenda->agenda_id])->all();
            foreach ($letters as $letter) {
                if (isset($letter)) {
                    $info = pathinfo($letter->resultDocument->template_file);
                    if (in_array($info['extension'], ['doc', 'docx'])) {
                        $icon = 'icon fa-file-word-o';
                    } else if (in_array($info['extension'], ['pdf'])) {
                        $icon = 'icon fa-file-pdf-o';
                    } else {
                        $icon = 'icon fa-file-text-o';
                    }

                    return \yii\helpers\Html::a("<i class='font-size-20 {$icon}'></i>", \yii\helpers\Url::to("@web/uploads/letter-template/{$letter->resultDocument->template_file}"), ['target' => '_blank', 'data-pjax' => 0]);
                } else {
                    return '';
                }
            }
        }
    }

    public function notifyCoResearcher() {
        $researchers = $this->project->projectCoResearchers;
//        $emails = \yii\helpers\ArrayHelper::getColumn($researchers, 'email');
        foreach ($researchers as $researcher) {
            $msg = Yii::$app->mailer->compose('coresearcher-acceptance', [
                'submission' => $this,
                'researcher' => $researcher,
            ]);
            $docs = $this->getSubmissionDocuments()->isDeleted(FALSE)->all();
            foreach ($docs as $doc) {
                $msg->attach($doc->filePath, ['fileName' => $doc->name]);
            }
            $msg->setSubject(\Yii::t('app', 'ตอบรับการร่วมวิจัย'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->params['adminName']])
                    ->setTo($researcher->person->email)
                    ->send();
        }
    }

    public function getHasProjectCoordinator() {
        $c = $this->isDeleted(FALSE)->coordinator()->count();
        return $c == 0;
    }

    public function getIsAllComitteeAcknowledged() {
        $c = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_PENDING)->count();
        $c1 = $this->getSubmissionCommittees()->isDeleted(FALSE)->count();
        return $c == 0 and $c1 > 0;
    }

    public function getIsAllComitteeReturn() {
        $c = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_ACCEPTED)->count();
        return $c == 0;
    }

    public function getIsAllResearcherAcknowledged() {
        if (!isset($this->project)) {
            return FALSE;
        }
        $c = $this->project->getProjectResearchers()->isDeleted(FALSE)->isLeader(FALSE)->acknowledgeStatus(ProjectResearcher::STATUS_PENDING_ACK)->count();
        return $c == 0;
    }

    public function getIsAllConsultantAcknowledged() {
        if (!isset($this->project)) {
            return FALSE;
        }
        $p = $this->project->getProjectConsultants()->isDeleted(FALSE)->acknowledgeStatus(ProjectConsultant::STATUS_PENDING_ACK)->count();
        return $p == 0;
    }

    public function getDocumentStatus() {
//        $totalCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->count();
        $failCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->status(SubmissionDocument::STATUS_FAIL)->count();
        $uncheckCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->status(NULL)->count();
        return [
            'allChecked' => $uncheckCount == 0,
            'isPass' => $failCount == 0
        ];
    }

    public function getCvStatus() {
//        $totalCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->count();
        $failResearcherCount = $this->getSubmissionProjectResearchers()->joinWith(['projectResearcher'])->isDeleted(FALSE)->isPrDeleted(false)->status(SubmissionProjectResearcher::STATUS_FAIL)->count();
        $uncheckResearcherCount = $this->getSubmissionProjectResearchers()->joinWith(['projectResearcher'])->isDeleted(FALSE)->isPrDeleted(false)->status(NULL)->count();

        return [
            'allResearcherChecked' => $uncheckResearcherCount == 0,
            'isResearcherPass' => $failResearcherCount == 0
        ];
    }

    public function getCommitteeStatus() {
        $totalCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->count();
//        $rejectCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_REJECTED)->count();
        $uncheckCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(NULL)->count();
        $acceptedCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_ACCEPTED)->count();
        return [
            'allChecked' => $uncheckCount == 0,
            'isPass' => $acceptedCount >= $totalCount//$this->submissionType->committee_count
        ];
    }

    public function getCommitteeStatusAssessed() {
//        $totalCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->count();
//        $rejectCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_REJECTED)->count();
        $uncheckCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(NULL)->count();
        $assessedCount = $this->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_RETURN)->count();
        return [
            'allChecked' => $uncheckCount == 0,
            'isPass' => $assessedCount >= $this->submissionType->committee_count
        ];
    }

    public function getResolutionLabel() {
        return !empty($this->resolution) ? $this->getResolutionLabels()[$this->resolution] : "";
    }

    Public function getContactLetter() {
        $content = "";
        if (!isset($this->project->project_code) || $this->submissionType->submission_type_group_id == 2) {
            $content = 'หมายเหตุ : เป็นความอัตโนมัติส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่';
        } elseif (isset($this->project->project_code) && $this->submissionType->submission_type_group_id == 1) {
            if (isset($this->responsible_person)) {
                $content = "หมายเหตุ : เป็นความอัตโนมัติส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่สามารถติดต่อได้ที่ {$this->responsiblePerson->person->fullName} โทร  0897141913 หรือ 0897141177 ";
            } else {
                $content = "หมายเหตุ : เป็นความอัตโนมัติส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่สามารถติดต่อได้ที่ {$this->refSubmission->responsiblePerson->person->fullName} โทร  0897141913 หรือ 0897141177 ";
            }
        }
        return $content;
    }

    Public function getContactLetterEng() {
        $content = "";
        if (!isset($this->project->project_code) || $this->submissionType->submission_type_group_id == 2) {
            $content = '*Note: This is an automatically generated message. To contact us';
        } elseif (isset($this->project->project_code) && $this->submissionType->submission_type_group_id == 1) {
            if (isset($this->responsible_person)) {
                $content = "*Note: This is an automatically generated message. To contact us, please call  {$this->responsiblePerson->person->fullName}  at  0897141913 , 0897141177 ";
            } else {
                $content = "*Note: This is an automatically generated message. To contact us, please call  {$this->refSubmission->responsiblePerson->person->fullName}  at  0897141913 , 0897141177 ";
            }
        }
        return $content;
    }

    public function getResolutionLabels() {
        if ($this->is_submit_by_api) {
            return [
                self::RESOLUTION_Y => yii::t('app', $this->submissionType->resolution_label),
            ];
        }
        return[
            self::RESOLUTION_Y => yii::t('app', $this->submissionType->resolution_label),
            self::RESOLUTION_C => yii::t('app', $this->submissionType->resolution_label . 'หลังจากแก้ไขตามมติที่ประชุม'),
            self::RESOLUTION_R => yii::t('app', 'ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำกลับมาพิจารณาใหม่'),
            self::RESOLUTION_N => yii::t('app', 'ไม่' . $this->submissionType->resolution_label),
            self::RESOLUTION_W => yii::t('app', 'ถอนออกจากการพิจารณาและหรือถอนออกจากการ' . $this->submissionType->resolution_label),
            self::RESOLUTION_T => yii::t('app', 'ยุติการ' . $this->submissionType->resolution_label),
            self::RESOLUTION_P => yii::t('app', 'เปลี่ยน Panel'),
        ];
    }

    public function getStatusDate($status) {
        if ($status == Submission::STATUS_SUBMITTED) {
            $codeGeneratedAt = $this->getStatusDate(Submission::STATUS_CODE_GENERATED);
            // return $codeGeneratedAt;
            if (isset($codeGeneratedAt)) {
                $st = $this->getSubmissionStatusHistories()->status($status)
                                ->andWhere(['<', 'submission_status_history.created_at', $codeGeneratedAt])
                                ->orderBy('submission_status_history.created_at DESC')->one();
                return isset($st) ? $st->created_at : NULL;
            }
        }
        $st = $this->getSubmissionStatusHistories()->status($status)->orderBy('submission_status_history.created_at DESC')->one();

        return isset($st) ? $st->created_at : NULL;
    }

    public function getSubmittedDate() {
        return $this->getStatusDate(Submission::STATUS_SUBMITTED);
    }

    public function getMeetingDate() {
        $ma = $this->getMeetingAgenda();
        return isset($ma) ? $ma->meeting->start_date : NULL;
    }

    public function getFirstEndorseMeetingAgenda() {
        $sub = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->project($this->project_id)->resolution(Submission::RESOLUTION_Y)->resolutionLabel(SubmissionType::RES_ENDORSE)->orderBy('id DESC')->one();
        if (!isset($sub->meetingAgenda)) {
            $sub = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->project($this->project_id)->resolutionLabel(SubmissionType::RES_ENDORSE)->hasMeetingAgenda()->orderBy('id DESC')->one();
        }
        if (!isset($sub)) {
            return NULL;
        }
        $ma = $sub->getMeetingAgenda();
        return $ma;
    }

//    public function getSubmissionHistoryReport($status) {
//        $date = $this->getSubmissionStatusHistories()->andWhere(['submission_status_history.status' => $status])->one();
//        return isset($date) ? $date->created_at : NULL;
//    }

    public function getResultDocuments() {
        $currentRole = \Yii::$app->session->get('currentRole');
        $revises = SubmissionCommitteeRevise::find()->submission($this->id)->isDeleted(FALSE)->orderBy('id ASC')->all();
        $results = [];
        $count = 0;

        foreach ($revises as $rev) {
            $ards = AgendaResultDocument::find()->joinWith(['resultDocument'])->isDeleted(FALSE)->agenda(NULL)->committeeResolution($rev->resolution)->all();
            foreach ($ards as $ard) {
                $srd = $this->getSubmissionResultDocuments()->isDeleted(FALSE)->revise($rev->id)->resultDocument($ard->result_document_id)->one();

                $results[] = [
                    'id' => $count,
                    'submission_result_document_id' => isset($srd) ? $srd->id : null,
                    'result_document_id' => $ard->result_document_id,
                    'document_name' => $ard->resultDocument->name,
                    'updated_at' => isset($srd) ? $srd->updated_at : null,
                    'submission_id' => $this->id,
                    'submission_revise_id' => $rev->id,
                ];
                $count++;
            }
        }
        $ma = $this->getMeetingAgenda();
        if (isset($ma) && isset($this->resolution)) {
            $ards = AgendaResultDocument::find()->joinWith(['resultDocument'])->isDeleted(FALSE)->agenda($ma->agenda_id)->resolution($this->resolution)->all();
//            if($this->resolution == Submission::RESOLUTION_Y && isset($rev)){
//                $revise = $rev->id;
//            }else{
//                $revise = NULL;
//            }
            foreach ($ards as $ard) {
                $srd = $this->getSubmissionResultDocuments()->isDeleted(FALSE)->revise(null)->resultDocument($ard->result_document_id)->one();
                if (isset($srd)) {
                    $filtered = \array_filter($results, function ($r) use ($srd) {
                        return $r['submission_result_document_id'] == $srd->id;
                    });
                    if (count($filtered) == 0) {
                        $results[] = [
                            'id' => $count,
                            'submission_result_document_id' => isset($srd) ? $srd->id : null,
                            'result_document_id' => $ard->result_document_id,
                            'document_name' => $ard->resultDocument->name,
                            'updated_at' => isset($srd->updated_at) ? $srd->updated_at : null,
                            'submission_id' => $this->id,
                            'submission_revise_id' => NULL,
                        ];
                        $count++;
                    }
                } else {
                    $results[] = [
                        'id' => $count,
                        'submission_result_document_id' => isset($srd) ? $srd->id : null,
                        'result_document_id' => $ard->result_document_id,
                        'document_name' => $ard->resultDocument->name,
                        'updated_at' => $ard->resultDocument->updated_at,
                        'submission_id' => $this->id,
                        'submission_revise_id' => NULL,
                    ];
                    $count++;
                }
            }
        }
        $ref = $this->getFirstRefSubmission();
        if (isset($ref) && $this->resolution == Submission::RESOLUTION_Y) {

            $refMa = $ref->getMeetingAgenda();
            if (isset($refMa) && isset($this->resolution)) {
                $ards = AgendaResultDocument::find()->joinWith(['resultDocument'])->isDeleted(FALSE)->agenda($refMa->agenda_id)->resolution($this->resolution)->all();
//                \yii\helpers\VarDumper::dump($ards);
//            exit;
                //            if($this->resolution == Submission::RESOLUTION_Y && isset($rev)){
                //                $revise = $rev->id;
                //            }else{
                //                $revise = NULL;
                //            }
                foreach ($ards as $ard) {
                    $srd = $this->getSubmissionResultDocuments()->isDeleted(FALSE)->resultDocument($ard->result_document_id)->one();

                    if (isset($srd)) {
                        $filtered = \array_filter($results, function ($r) use ($srd) {
                            return $r['submission_result_document_id'] == $srd->id;
                        });
                        if (count($filtered) == 0) {
                            $results[] = [
                                'id' => $count,
                                'submission_result_document_id' => isset($srd) ? $srd->id : null,
                                'result_document_id' => $ard->result_document_id,
                                'document_name' => $ard->resultDocument->name,
                                'updated_at' => $srd->updated_at,
                                'submission_id' => $this->id,
                                'submission_revise_id' => NULL,
                            ];
                            $count++;
                        }
                    } else {
                        $results[] = [
                            'id' => $count,
                            'submission_result_document_id' => isset($srd) ? $srd->id : null,
                            'result_document_id' => $ard->result_document_id,
                            'document_name' => $ard->resultDocument->name,
                            'updated_at' => $ard->resultDocument->updated_at,
                            'submission_id' => $this->id,
                            'submission_revise_id' => NULL,
                        ];
                        $count++;
                    }
                }
            }
        }
        $docs = $this->getSubmissionResultDocuments()->isDeleted(FALSE)->notInDocuments(ArrayHelper::getColumn($results, 'result_document_id'))->all();

        foreach ($docs as $doc) {
            if (
                    (
                    isset($doc->srd_crec_id) && ($this->send_to_crec || $this->is_submit_by_api) && ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR)
                    ) || !isset($doc->srd_crec_id)
            ) {

                $results[] = [
                    'id' => $count,
                    'submission_result_document_id' => $doc->id,
                    'result_document_id' => $doc->result_document_id,
                    'document_name' => $doc->name,
                    'updated_at' => $doc->updated_at,
                    'submission_id' => $this->id,
                    'submission_revise_id' => NULL,
                ];
                $count++;
                // $filtered = \array_filter($results, function ($r) use ($doc) {
                //     return $r['submission_result_document_id'] == $doc->id;
                // });
                // if (count($filtered) == 0) {
                //     $results[] = [
                //         'id' => $count,
                //         'submission_result_document_id' => $doc->id,
                //         'result_document_id' => $doc->result_document_id,
                //         'document_name' => $doc->name,
                //         'updated_at' => $doc->updated_at,
                //         'submission_id' => $this->id,
                //         'submission_revise_id' => NULL,
                //     ];
                //     $count++;
                // }
            }
        }
//        return array_merge($results, $docs);
        return $results;
    }

    public function getTypeAndRef() {
        $name = isset($this->submission_type_id) ? $this->submissionType->i18nName : "";
        if ($this->isFromCrec() && $this->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT) {
            $name .= '<font class="orange-800">' . \Yii::t("app", " (MOU CREC)") . '</font>';
        }
        if (isset($this->refSubmission)) {
            if ($this->submission_type_id == 15) {
                $name .= \Yii::t("app", " ({0})", [$this->subject]);
            } else {
                $name .= \Yii::t("app", " (ส่งแก้ไขผล {0})", [$this->refSubmission->resolution]);
            }
        } else {
            $name .= "";
        }
        if ($this->submission_type_id == \app\models\SubmissionType::TYPE_DEVIATION) {
            $events = \app\models\SubmissionEvent::find()->isDeleted(false)
                            ->submission($this->id)->all();
            foreach ($events as $event):
                $name .= " " . $event->code . " ";
            endforeach;
        } else if ($this->submission_type_id == \app\models\SubmissionType::TYPE_INTERNAL_SAE) {
            $volunteers = \app\models\SubmissionVolunteer::find()->isDeleted(false)
                            ->submissionId($this->id)->all();
            foreach ($volunteers as $volunteer):
                $name .= " " . $volunteer->volunteer->code . " ";
            endforeach;
        }

        if (isset($this->crec_resolution)) {
            if ($this->crec_resolution == Submission::RESOLUTION_Y) {
                $rc = '<font class="blue-700">' . Submission::getResolutionLables()[$this->crec_resolution] . '</font>';
            } else {
                $rc = '<font class="red-700">' . Submission::getResolutionLables()[$this->crec_resolution] . '</font>';
            }

            $name .= '<br><span class="font-weight-900" style="font-weight: bold;">' . \Yii::t("app", "ผลพิจารณาจาก CREC : </span> {0} ", $rc);
        }

        return $name;
    }

    public function getSubmissionTypeName() {
        $res = $this->submissionType->i18nName;
        if ($this->submission_type_id == SubmissionType::TYPE_AMENDMENT || isset($this->ref_submission_id)) {
            $res .= "(" . \Yii::t('app', 'ครั้งที่') . " " . $this->submissionTypeCount . ")";
        }
        return $res;
    }

    public function getSubmissionTypeCount() {
        return Submission::find()->isDeleted(false)->project($this->project_id)->submissionType($this->submission_type_id)
                        ->andFilterCompare('submission.id', "<={$this->id}")->count();
    }

    public function getHasPanelChanged() {
        return $this->getProjectCodeHistories()->count() > 0;
    }

    public function getFirstRefSubmission() {
        $ref = $this->refSubmission;
        while (isset($ref) && isset($ref->refSubmission)) {
            $ref = $ref->refSubmission;
        }
        return $ref;
    }

    public function getSubmissionDocs($onlyUpload = false, $onlyEndorse = false) {
        $submissionDocs = [];
        $docTypes2 = DocumentSubmissionType::find()->isDeleted(FALSE)
                        ->submissionType($this->submission_type_id)
                        ->submissionTypeRole(Role::RESEARCHER)->indexBy('id')
                        ->isEvent()
                        ->orderBy([new \yii\db\Expression('document_submission_type.sort IS NULL ,document_submission_type.sort ASC')])->all();
        $subVols = [];
        $subEvents = [];
        if ($this->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
            $subVols = $this->getSubmissionVolunteers()->isDeleted(false)->all();
        } else if ($this->submission_type_id == SubmissionType::TYPE_DEVIATION) {
            $subEvents = $this->getSubmissionEvents()->isDeleted(false)->all();
        }
        foreach ($docTypes2 as $type) {
            foreach ($subVols as $subVol) {
                if ($onlyEndorse) {
                    if (!$type->document->is_report) {
                        continue;
                    }
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->volunteerId($subVol->volunteer_id)->one();
                } else {
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->volunteerId($subVol->volunteer_id)->one();
                }
                if (!isset($doc) && !$onlyUpload) {
                    $doc = new SubmissionDocument();
                    $doc->document_id = $type->document_id;
                    $doc->submission_id = $this->id;
                    $doc->name = $type->document->name . "_{$subVol->volunteer->code}";
                    $doc->name_eng = $type->document->name_eng . "_{$subVol->volunteer->code}";
                    $doc->volunteer_id = $subVol->volunteer_id;
                }
                if (isset($doc)) {
                    $submissionDocs[] = $doc;
                }
            }
            foreach ($subEvents as $subEv) {
                if ($onlyEndorse) {
                    if (!$type->document->is_report) {
                        continue;
                    }
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->submissionEventId($subEv->id)->one();
                } else {
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->submissionEventId($subEv->id)->one();
                }
                if (!isset($doc) && !$onlyUpload) {
                    $doc = new SubmissionDocument();
                    $doc->document_id = $type->document_id;
                    $doc->submission_id = $this->id;
                    $doc->name = $type->document->name . "_{$subEv->event_no}";
                    $doc->name_eng = $type->document->name_eng . "_{$subEv->event_no}";
                    $doc->submission_event_id = $subEv->id;
                }
                if (isset($doc)) {
                    $submissionDocs[] = $doc;
                }
            }
            if ($this->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE || $this->submission_type_id == SubmissionType::TYPE_DEVIATION) {
                if ($onlyEndorse) {
                    if (!$type->document->is_report) {
                        continue;
                    }
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->andWhere(['<', 'submission_document.created_at', '2020-10-24'])->one();
                } else {
                    $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                                    ->documents($type->document_id)->andWhere(['<', 'submission_document.created_at', '2020-10-24'])->one();
                }
                if (!isset($doc) && !$onlyUpload && (new DateTime($this->created_at)) < (new DateTime('2020-10-24'))) {
                    $doc = new SubmissionDocument();
                    $doc->document_id = $type->document_id;
                    $doc->submission_id = $this->id;
                    $doc->name = $type->document->name;
                    $doc->name_eng = $type->document->name_eng;
                }
                if (isset($doc)) {
                    $submissionDocs[] = $doc;
                }
            }
        }

        $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                        ->submissionType($this->submission_type_id)
                        ->submissionTypeRole(Role::RESEARCHER)->indexBy('id')
                        ->isEvent(false)
                        ->orderBy([new \yii\db\Expression('document_submission_type.sort IS NULL ,document_submission_type.sort ASC')])->all();
        foreach ($docTypes as $type) {
            if ($onlyEndorse) {
                if (!$type->document->is_report) {
                    continue;
                }
                $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)->documents($type->document_id)->one();
            } else {
                $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)->documents($type->document_id)->one();
            }
            if (!isset($doc) && !$onlyUpload) {
                $doc = new SubmissionDocument();
                $doc->document_id = $type->document_id;
                $doc->submission_id = $this->id;
                $doc->name = $type->document->name;
                $doc->name_eng = $type->document->name_eng;
            }
            if (isset($doc)) {
                $submissionDocs[] = $doc;
            }
        }

        $docs = SubmissionDocument::find()->isDeleted(FALSE)->submission($this->id)
                        ->notInDocuments(ArrayHelper::getColumn(array_merge($docTypes, $docTypes2), 'document_id'))->all();

        $submissionDocs = array_merge($submissionDocs, $docs);
        ArrayHelper::multisort($submissionDocs, ['is_site'], [\SORT_ASC]);
        return $submissionDocs;
    }

    public function getNextReSubmission() {
        return Submission::find()->isDeleted(false)->refSubmission($this->id)->one();
    }

    public function getEndorseDate() {
        if (isset($this->certified_date)) {
            return $this->certified_date;
        }
        $nextReSubmission = $this->nextReSubmission;
        while (isset($nextReSubmission)) {
            if (isset($nextReSubmission->certified_date)) {
                return $nextReSubmission->certified_date;
            } else {
                $nextReSubmission = $nextReSubmission->nextReSubmission;
            }
        }
        return null;
    }

    public function getLatestResolution() {
        $nextReSubmission = $this->nextReSubmission;
        if (!isset($nextReSubmission)) {
            return $this->resolution;
        }
        while (isset($nextReSubmission)) {
            $newNext = $nextReSubmission->nextReSubmission;
            if (!isset($newNext)) {
                return $nextReSubmission->resolution;
            }
            $nextReSubmission = $newNext;
        }
        return null;
    }

    public function getLatestProgressPeriod() {
        $nextReSubmission = $this->nextReSubmission;
        if (!isset($nextReSubmission)) {
            return $this->progress_period;
        }
        while (isset($nextReSubmission)) {
            $newNext = $nextReSubmission->nextReSubmission;
            if (!isset($newNext)) {
                return $nextReSubmission->progress_period;
            }
            $nextReSubmission = $newNext;
        }
        return null;
    }

    public function getYResultAt() {
        $nextReSubmission = $this->nextReSubmission;
        if (!isset($nextReSubmission) && $this->resolution == Submission::RESOLUTION_Y) {
            return $this->currentUploadResultAt;
        }
        while (isset($nextReSubmission)) {
            $newNext = $nextReSubmission->nextReSubmission;
            if (!isset($newNext) && $nextReSubmission->resolution == Submission::RESOLUTION_Y) {
                return $nextReSubmission->currentUploadResultAt;
            }
            $nextReSubmission = $newNext;
        }
        return null;
    }

    public function getUploadResultDate() {
//        if ($this->status == self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) {
//            $date = $this->getStatusDate(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT);
//            if (isset($date)) {
//                return $date;
//            } else {
//                $h = $this->getSubmissionStatusHistories()->orderBy('created_at DESC')->one();
//                return isset($h) ? $h->created_at : null;
//            }
//        } else {
//            return null;
//        }
        $nextReSubmission = $this->nextReSubmission;
        if (!isset($nextReSubmission)) {
            return $this->currentUploadResultAt;
        }
        while (isset($nextReSubmission)) {
            $newNext = $nextReSubmission->nextReSubmission;
            if (!isset($newNext)) {
                return $nextReSubmission->currentUploadResultAt;
            }
            $nextReSubmission = $newNext;
        }
        return null;
    }

    public function getApproveToEndorseDays() {
        $approveAt = $this->getCodeGeneratedAt();
        $endorseAt = $this->getUploadResultDate();
        if (isset($approveAt) && isset($endorseAt)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($approveAt, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($endorseAt, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
//            return "$this->id-".abs($diff->format('%a'));
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getApproveToMeetingDays() {
        $approveAt = $this->getCodeGeneratedAt();
        $meetingAt = $this->getMeetingAt();
        if (isset($approveAt) && isset($meetingAt)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($approveAt, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($meetingAt, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getMeetingToEndorseDays() {
        $meetingAt = $this->getMeetingAt();
        $endorseAt = $this->getUploadResultDate();
        if (isset($endorseAt) && isset($meetingAt)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($endorseAt, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($meetingAt, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getSubmittedAt() {
        return $this->getStatusDate(Submission::STATUS_SUBMITTED);
    }

    public function getCodeGeneratedAt() {
        return $this->getStatusDate(Submission::STATUS_CODE_GENERATED);
    }

    public function getSecretarySelectedAt() {
        return $this->getStatusDate(Submission::STATUS_SECRETARY_SELECTED);
    }

    public function getCommitteeSelectedAt() {
        return $this->getStatusDate(Submission::STATUS_COMMITTEE_SELECTED);
    }

    public function getMeetingAt() {
        return isset($this->meetingAgenda) ? $this->meetingAgenda->meeting->start_date : null;
    }

    public function getCurrentUploadResultAt() {
        $date = $this->getStatusDate(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT);
        if (isset($date)) {
            return $date;
        } else if ($this->status == self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT || isset($this->resolution)) {
            $h = $this->getSubmissionStatusHistories()->orderBy('created_at DESC')->one();
            return isset($h) ? $h->created_at : null;
        }
        return null;
    }

    public function getCodeGeneratedDays() {
        $d1 = $this->getSubmittedAt();
        $d2 = $this->getCodeGeneratedAt();
        if (isset($d1) && isset($d2)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($d1, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($d2, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getMeetingDays() {
        $d1 = $this->getSubmittedAt();
        $d2 = $this->getMeetingAt();
        if (isset($d1) && isset($d2)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($d1, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($d2, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getResultDays() {
        $d1 = $this->getMeetingAt();
        $d2 = $this->getCurrentUploadResultAt();
        if (isset($d1) && isset($d2)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($d1, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($d2, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getTotalUploadResultDays() {
        $d1 = $this->getSubmittedAt();
        $d2 = $this->getUploadResultDate();
        if (isset($d1) && isset($d2)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($d1, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($d2, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getTotalYUploadResultDays() {
        $d1 = $this->getSubmittedAt();
        $d2 = $this->getYResultAt();
        if (isset($d1) && isset($d2)) {
            $d1 = new \DateTime(Yii::$app->formatter->asDate($d1, 'php:Y-m-d'));
            $d2 = new \DateTime(Yii::$app->formatter->asDate($d2, 'php:Y-m-d'));
            $diff = $d1->diff($d2);
            return abs($diff->format('%a'));
        }
        return null;
    }

    public function getTotalSubmissionCount() {
        $c = 1;
        $nextReSubmit = $this->nextReSubmission;
        while (isset($nextReSubmit)) {
            $c++;
            $nextReSubmit = $nextReSubmit->nextReSubmission;
        }
        return $c;
    }

    public function getRequireCommitteeDocumentSubmissTypes($position) {
        $query = $this->submissionType->getDocumentSubmissionTypes()->isDeleted(FALSE)->andWhere(['is_require' => TRUE])->role(Role::COMMITTEE)->committeePosition($position);
        if ($this->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
            $firstRef = $this->getFirstRefSubmission();
        } else if (isset($this->refSubmission) && $this->refSubmission->resolution == Submission::RESOLUTION_C) {
            $firstRef = $this->refSubmission;
        }
        if (isset($firstRef)) {
            $query->refSubmissionType($firstRef->submission_type_id);
        }
        return $query->all();
    }

    public function getAvailableAmendmentDocs() {
        $submissionDocs = $this->project->getLatestEndorseDocuments();
        $uploadedDocs = $this->getSubmissionDocs(true, true);
        $results = [];
        foreach ($submissionDocs as $doc) {
            $baseFilterDocs = [];
            if (isset($doc->document_id)) {
                $baseFilterDocs = array_filter($uploadedDocs, function($baseDoc) use ($doc) {
                    return $baseDoc->document_id == $doc->document_id;
                });
            } else {
                $baseFilterDocs = array_filter($uploadedDocs, function($baseDoc) use ($doc) {
                    return trim($baseDoc->name) == trim($doc->name);
                });
            }
            if (empty($baseFilterDocs)) {
                $results[] = $doc;
            }
        }
        return $results;
    }

    public function getRequiredDocumentCount() {
        $count = DocumentSubmissionType::find()->isDeleted(FALSE)
                        ->submissionType($this->submission_type_id)
                        ->submissionTypeRole(Role::RESEARCHER)
                        ->isRequire()
                        ->isEvent(false)->count();
        $countDocVol = DocumentSubmissionType::find()->isDeleted(FALSE)
                        ->submissionType($this->submission_type_id)
                        ->submissionTypeRole(Role::RESEARCHER)
                        ->isRequire()
                        ->isEvent(true)->count();
        $countVol = 1;
        if ($this->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
            $countVol = $this->getSubmissionVolunteers()->isDeleted(false)->count();
        } else if ($this->submission_type_id == SubmissionType::TYPE_DEVIATION) {
            $countVol = $this->getSubmissionEvents()->isDeleted(false)->count();
        }

        return $count + ($countDocVol * $countVol);
    }

    public function getLastAmendmentSubmission() {
        return Submission::find()->joinWith(['submissionType'])->isDeleted(false)
                        ->project($this->project_id)
                        ->submissionType(SubmissionType::TYPE_AMENDMENT)
                        ->resolution(Submission::RESOLUTION_Y)
                        ->notId($this->id)
                        ->orderBy(['id' => \SORT_DESC])
                        ->one();
    }

    public function isFromCrec() {
        return !empty($this->crec_leader_name);
    }

    public function getCommitteeDocs($sCommitteeId, $cpId = NULL) {
        if ($this->isFromCrec() && $this->is_legacy == 0 && $this->is_submit_by_api == 1) {
            $submissionComittee = SubmissionCommittee::findOne($sCommitteeId);
            $result = Crec::getCrecSubmissionCommitteeDocumentTemplateFiles($submissionComittee);
            if ($result['error']) {
                throw new Exception($result['message']);
            }
            // VarDumper::dump($result);
            // exit;
            $submissionDocs = [];
            foreach ($result['data']['data'] as $crecCommitteeDoc) {
                $doc = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($this->id)->crecDocument($crecCommitteeDoc['document_id'])->submissionCommittee($sCommitteeId)->one();

                if (!isset($doc)) {
                    $doc = new SubmissionCommitteeDocument();
                    $doc->crec_document_id = $crecCommitteeDoc['document_id'];
                    $doc->submission_id = $this->id;
                    $doc->name = $crecCommitteeDoc['name'];
                    //                $doc->roleID = \app\models\Role::COMMITTEE;
                }

                $submissionDocs[] = $doc;
            }
            $docs = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($this->id)->submissionCommittee($sCommitteeId)->notInDocument()->notCrec()->all();
            return array_merge($submissionDocs, $docs);
//            return $submissionDocs;
        } else {
            //        $currentRole = \Yii::$app->session->get('currentRole');
            $docTypes = [];
            if (isset($cpId)) {
                if (!isset($this->ref_submission_id) || (
                        $this->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT && ($this->refSubmission->resolution == Submission::RESOLUTION_R || $this->refSubmission->resolution == Submission::RESOLUTION_N)
                        ))
                    $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                                    ->refSubmissionType(null)->submissionType($this->submission_type_id)
                                    ->submissionTypeRole(\app\models\Role::COMMITTEE)
                                    ->committeePosition($cpId)->indexBy('id')->all();
            } else {
                $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                                ->submissionType($this->submission_type_id)
                                ->submissionTypeRole(\app\models\Role::COMMITTEE)
                                ->andWhere('document_submission_type.committee_position_id IS NULL')->indexBy('id')->all();
            }
            $submissionDocs = [];
            //        \yii\helpers\VarDumper::dump($docTypes, 4, TRUE);
            //        exit;
            foreach ($docTypes as $type) {
                $doc = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($this->id)->documents($type->document_id)->submissionCommittee($sCommitteeId)->one();

                if (!isset($doc)) {
                    $doc = new SubmissionCommitteeDocument();
                    $doc->document_id = $type->document_id;
                    $doc->submission_id = $this->id;
                    $doc->name = $type->document->name;
                    //                $doc->roleID = \app\models\Role::COMMITTEE;
                }

                $submissionDocs[] = $doc;
            }

            if ($this->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
                $firstRef = $this->getFirstRefSubmission();
            } else if (isset($submission->refSubmission) && $submission->refSubmission->resolution == Submission::RESOLUTION_C) {
                $firstRef = $submission->refSubmission;
            }
            $docTypes1 = [];
            if (isset($firstRef) && isset($cpId)) {
                $docTypes1 = DocumentSubmissionType::find()->isDeleted(FALSE)->refSubmissionType($firstRef->submission_type_id)
                                ->submissionType($this->submission_type_id)->submissionTypeRole(\app\models\Role::COMMITTEE)
                                ->committeePosition($cpId)->indexBy('id')->all();
                //            \yii\helpers\VarDumper::dump($docTypes, 4, TRUE);
                //        exit;
                foreach ($docTypes1 as $type) {
                    $doc = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($this->id)->documents($type->document_id)->submissionCommittee($sCommitteeId)->one();

                    if (!isset($doc)) {
                        $doc = new SubmissionCommitteeDocument();
                        $doc->document_id = $type->document_id;
                        $doc->submission_id = $this->id;
                        $doc->name = $type->document->name;
                        //                $doc->roleID = \app\models\Role::COMMITTEE;
                    }

                    $submissionDocs[] = $doc;
                }
            }
            //\yii\helpers\VarDumper::dump($submissionDocs, 4, TRUE);
            //        exit;
            //$docs = SubmissionDocument::find()->joinWith('documentSubmissionTypes')->isDeleted(FALSE)->submission($submission->id)->submissionTypeRole(\app\models\Role::COMMITTEE)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
            //        return $submissionDocs;
            $docTypes = array_merge($docTypes, $docTypes1);
            $docs = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($this->id)->submissionCommittee($sCommitteeId)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
            return array_merge($submissionDocs, $docs);
        }
    }

    /**
     * @inheritdoc
     * @return SubmissionQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionQuery(get_called_class());
    }
    public static function getStatusLabelsResearcherCrec() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'นักวิจัยเตรียมการยื่นโครงการเข้าสู่ระบบ'),
            //ไม่ได้แก้ไข
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เจ้าหน้าที่ตีกลับเอกสาร'),
            self::STATUS_SUBMITTED => Yii::t('app', 'ระบบ CREC ส่งโครงการเข้าระบบ KKU'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสาร'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'นักวิจัย site ได้รับเลขที่โครงการ (HE)'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'เจ้าหน้าที่กำหนดวันประชุม'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'เจ้าหน้าที่เสนอเลขาฯ'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'เลขาฯเลือกกรรมการประเมิน local issue'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'กรรมการตอบรับครบทุกท่าน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'กรรมการส่งผลครบทุกท่าน'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'เจ้าหน้าที่บรรจุผลการพิจารณาของคณะกรรมการ CREC เข้าที่ประชุมคณะกรรมการ KKU'),
            //ไม่ได้แก้ไข
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'ผู้ประสานงานกดส่งหัวหน้าโครงการตรวจสอบ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'โครงการไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'คณะกรรมการพิจารณาโครงการ'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'เลขาฯตรวจสอบรายงานการประชุม'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'ประธานตรวจสอบรายงานการประชุม'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัย site ได้รับหนังสือแจ้งผลการพิจารณา'),
        ];
    }

    public static function getStatusLabelsResearcher() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'นักวิจัยเตรียมการยื่นโครงการเข้าสู่ระบบ'),
            //ไม่ได้แก้ไข
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เจ้าหน้าที่ตีกลับเอกสาร'),
            self::STATUS_SUBMITTED => Yii::t('app', 'นักวิจัยยื่นเอกสาร'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสาร'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'นักวิจัยได้รับ HE'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'เจ้าหน้าที่กำหนดวันประชุม'),
            self::STATUS_SECRETARY_SELECT_TYPE => Yii::t('app', 'เจ้าหน้าที่เสนอเลขาฯ เลือกประเภทการพิจารณา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'เจ้าหน้าที่เสนอเลขาฯเลือกกรรมการ'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'เลขาฯเลือกกรรมการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'กรรมการตอบรับครบทุกท่าน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'กรรมการส่งผลครบทุกท่าน'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'เจ้าหน้าที่บรรจุเข้าประชุมพิจารณา'),
            //ไม่ได้แก้ไข
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'ผู้ประสานงานกดส่งหัวหน้าโครงการตรวจสอบ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'โครงการไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'คณะกรรมการพิจารณาโครงการ'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'เลขาฯตรวจสอบรายงานการประชุม'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'ประธานตรวจสอบรายงานการประชุม'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'ประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผล'),
        ];
    }
    public static function getStatusLabelsResearcherContinueCrecC() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'นักวิจัยเตรียมการยื่นโครงการเข้าสู่ระบบ'),
            //ไม่ได้แก้ไข
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เจ้าหน้าที่ตีกลับเอกสาร'),
            self::STATUS_SUBMITTED => Yii::t('app', 'ระบบ CREC ส่งโครงการเข้าระบบ KKU'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสาร'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสารแล้ว'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'เจ้าหน้าที่กำหนดวันประชุม'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'เจ้าหน้าที่เสนอกรรมการพิจารณา'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'กรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสาร'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'เจ้าหน้าที่บรรจุผลการพิจารณาของคณะกรรมการ CREC เข้าที่ประชุมคณะกรรมการ KKU'),
            //ไม่ได้แก้ไข
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'ผู้ประสานงานกดส่งหัวหน้าโครงการตรวจสอบ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'โครงการไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'คณะกรรมการพิจารณาโครงการ'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ส่งผลการพิจารณาจากเลขา'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'ส่งผลการพิจารณาจากประธาน'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ส่งผลการพิจารณาจากประธาน'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัย site ได้รับหนังสือแจ้งผลการพิจารณา'),
        ];
    }
    public static function getStatusLabelsResearcherContinueC() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'นักวิจัยเตรียมการยื่นโครงการเข้าสู่ระบบ'),
            //ไม่ได้แก้ไข
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เจ้าหน้าที่ตีกลับเอกสาร'),
            self::STATUS_SUBMITTED => Yii::t('app', 'นักวิจัยยื่นเอกสาร'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสาร'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบเอกสารแล้ว'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'เจ้าหน้าที่กำหนดวันประชุม'),
            self::STATUS_SECRETARY_SELECT_TYPE => Yii::t('app', 'เจ้าหน้าที่เสนอเลขาฯ เลือกประเภทการพิจารณา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'เจ้าหน้าที่เสนอกรรมการพิจารณา'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'กรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'กรรมการส่งผลประเมิน'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'เจ้าหน้าที่บรรจุเข้าประชุมพิจารณา'),
            //ไม่ได้แก้ไข
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'ผู้ประสานงานกดส่งหัวหน้าโครงการตรวจสอบ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'โครงการไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'คณะกรรมการพิจารณาโครงการ'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ส่งผลการพิจารณาจากเลขา'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ส่งผลการพิจารณาจากประธาน'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }
    public static function getStatusLabelsCrec() {
        return [
           self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'รอพิจารณา'),
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'โครงการที่ยังส่งไม่แล้วเสร็จ'),
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารยังไม่ครบถ้วน/ไม่ถูกต้อง'),
            self::STATUS_SUBMITTED => Yii::t('app', 'รอเจ้าหน้าที่ KKU ตรวจสอบเอกสาร'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'รอออกเลข HE'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'รอกำหนดวันที่ส่งผลประเมิน local issue'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'รอเลือกเลขา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'รอเลือกกรรมการประเมิน local issue'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'รอกรรมการส่งผล  local issue'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'รอผลการพิจารณาจากคณะกรรมการ CREC'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'รอรายงานในที่ประชุมคณะกรรมการ KKU'),
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยยังรอการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากเลขา'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากประธาน'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่อัปโหลดหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'เจ้าหน้าที่อัปโหลดหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function getStatusLabels() {
        return [
//            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'รอยืนยันส่งโครงการ'),
//            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'เอกสารไม่ถูกต้องส่งกลับโดยกรรมการ'),
//            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารไม่ถูกต้อง'),
//            self::STATUS_SUBMITTED => Yii::t('app', 'รอตรวจสอบเอกสาร'),
//            self::STATUS_DOC_APPROVED => Yii::t('app', 'เอกสารผ่าน'),
//            self::STATUS_CODE_GENERATED => Yii::t('app', 'ออกเลขโครงการ แล้ว'),
//            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'กำหนดวันประชุมแล้ว'),
//            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'กำหนดเลขาแล้ว'),
//            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'กำหนดกรรมการแล้ว'),
//            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'กรรมการรับอ่านแล้ว'),
//            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'กรรมการส่งผลประเมินแล้ว'),
//            self::STATUS_AGENDA_ADDED => Yii::t('app', 'บรรจุวาระแล้ว'),
//            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้ว'),
//            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'รอพิจารณา'),

            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'โครงการที่ยังส่งไม่แล้วเสร็จ'),
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารยังไม่ครบถ้วน/ไม่ถูกต้อง'),
            self::STATUS_SUBMITTED => Yii::t('app', 'รอตรวจสอบเอกสาร'),
//            self::STATUS_SUBMITTED_CON => Yii::t('app', 'รอตรวจสอบเอกสาร'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'รอออกเลขโครงการ'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'รอกำหนดประมาณวันที่ประชุมและวันส่งประเมิน'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'รอเลือกเลขา'),
            self::STATUS_SECRETARY_SELECT_TYPE => Yii::t('app', 'รอเลขาฯ เลือกประเภทการพิจารณา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'รอเลือกกรรมการ'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'รอกรรมการส่งประเมิน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'รอบรรจุวาระการประชุม'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'รอผลพิจารณาจากคณะกรรมการ'),
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยยังรอการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากเลขา'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากประธาน'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),

        ];
    }

    public static function getStatusLabelsCon() {
        return [
            self::STATUS_SUBMITTED => Yii::t('app', 'รอตรวจสอบเอกสาร'),
        ];
    }

    public static function getStatusLabelsNopanel() {
        return [
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารยังไม่ครบถ้วน/ไม่ถูกต้อง'),
            self::STATUS_SUBMITTED => Yii::t('app', 'รอตรวจสอบเอกสาร'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'รอออกเลขโครงการ'),
        ];
    }

    public static function getStatusLabelsStaffSearch() {
        return [
            self::STATUS_CODE_GENERATED => Yii::t('app', 'รอกำหนดประมาณวันที่ประชุมและวันส่งประเมิน'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'รอเลือกเลขา'),
            self::STATUS_SECRETARY_SELECT_TYPE => Yii::t('app', 'รอเลขาฯ เลือกประเภทการพิจารณา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'รอเลือกกรรมการ'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'รอกรรมการส่งประเมิน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'รอบรรจุวาระการประชุม'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'รอผลพิจารณาจากคณะกรรมการ'),
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::CUSTOM_STATUS_MEETING_PENDING => Yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยยังรอการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => Yii::t('app', 'วิจัยไม่ผ่านการยืนยันจากหัวหน้าโครงการ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากเลขา'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'รอการตรวจสอบผลพิจารณาจากประธาน'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function getStatusLabelsSearch() {
        return [
            self::STATUS_CODE_GENERATED => Yii::t('app', 'รอกำหนดประมาณวันที่ประชุมและวันส่งประเมิน'),
            self::STATUS_MEETING_APPOINTMENT => Yii::t('app', 'รอเลือกเลขา'),
            self::STATUS_SECRETARY_SELECT_TYPE => Yii::t('app', 'รอเลขาฯ เลือกประเภทการพิจารณา'),
            self::STATUS_SECRETARY_SELECTED => Yii::t('app', 'รอเลือกกรรมการ'),
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'รอกกรรมการตอบรับพิจารณาโครงการ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'รอกรรมการส่งประเมิน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'รอบรรจุวาระการประชุม'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'รอผลพิจารณาจากคณะกรรมการ'),
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function getStatusLabelsResearcherSearch() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'โครงการที่ยังส่งไม่แล้วเสร็จ'),
            self::STATUS_SUBMITTED => Yii::t('app', 'รอตรวจสอบเอกสาร'),
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารยังไม่ครบถ้วน/ไม่ถูกต้อง'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'รอออกเลขโครงการ'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'รอผลพิจารณาจากคณะกรรมการ'),
            self::STATUS_MEETING_DONE => Yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function statusColors() {
        return [
            self::STATUS_PENDING_SUBMISSION => 'bg-yellow-600',
            self::STATUS_DOC_REJECTED => 'bg-red-600',
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => 'bg-pink-600',
            self::STATUS_COMMITTEE_ACCEPTED => 'bg-purple-600',
            self::STATUS_SUBMITTED => 'bg-deep-purple-600',
            self::STATUS_SUBMITTED_CON => 'bg-indigo-600',
            self::STATUS_CODE_GENERATED => 'bg-blue-600',
            self::STATUS_DOC_APPROVED => 'bg-light-blue-600',
            self::STATUS_MEETING_APPOINTMENT => 'bg-brown-600',
            self::STATUS_SECRETARY_SELECT_TYPE => 'bg-deep-orange-900',
            self::STATUS_SECRETARY_SELECTED => 'bg-deep-orange-600',
            self::STATUS_COMMITTEE_SELECTED => 'bg-orange-600',
            self::STATUS_COMMITTEE_ASSESSED => 'bg-green-600',
            self::STATUS_AGENDA_ADDED => 'bg-amber-500',
            self::STATUS_MEETING_DONE => 'bg-amber-400',
            self::CUSTOM_STATUS_MEETING_PENDING => 'bg-amber-300',
            self::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER => 'bg-blue-800',
            self::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => 'bg-purple-800',
            self::STATUS_STAFF_APPROVE_AGENDA => 'bg-blue-200',
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => 'bg-blue-500',
            self::STATUS_SECRETARY_APPROVE_AGENDA => 'bg-purple-200',
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => 'bg-purple-900',
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => 'bg-orange-400',
        ];
    }

    public static function getStatusCheckDoc() {
        return[
            self::STATUS_DOC_REJECTED => Yii::t('app', 'เอกสารไม่ถูกต้อง'),
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เอกสารผ่าน'),
        ];
    }

    public static function getStatusCheckDocCrec() {
        return[
            self::STATUS_DOC_APPROVED => Yii::t('app', 'เอกสารผ่าน'),
        ];
    }

    public static function getStatusCheckResult() {
        return[
            self::STATUS_MEETING_DONE => Yii::t('app', 'ผลพิจารณายังอยู่ในขั้นตอนตรวจสอบ'),
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'ตรวจสอบผลการพิจารณาแล้วโดยเจ้าหน้าที่'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ตรวจสอบผลการพิจารณาแล้วโดยเลขาแล้ว'),
        ];
    }

    public static function getStatusCheckResultSecretary() {
        return[
            self::STATUS_STAFF_APPROVE_AGENDA => Yii::t('app', 'ผลพิจารณายังอยู่ในขั้นตอนตรวจสอบ'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => Yii::t('app', 'ตรวจสอบผลการพิจารณาแล้วโดยเลขาแล้ว'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => Yii::t('app', 'ตรวจสอบผลการพิจารณาแล้วโดยประธานแล้ว'),
        ];
    }

    public static function getStatusAssessedCommittee() {
        return[
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'ส่งผลประเมินโดยโครงการต้องแก้ไขตามคำแนะนำของกรรมการ'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'ส่งผลประเมินโครงการ'),
        ];
    }

    public static function getStatusAssessedCommitteeCrec() {
        return[
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'ส่งผลประเมินโครงการ'),
        ];
    }

    public static function getStatusLabelsCommitteeSearch() {
        return [
            self::STATUS_COMMITTEE_SELECTED => Yii::t('app', 'งานวิจัยที่ต้องตอบรับ'),
            self::STATUS_COMMITTEE_ACCEPTED => Yii::t('app', 'งานวิจัยที่ต้องประเมิน'),
            self::STATUS_COMMITTEE_ASSESSED => Yii::t('app', 'งานวิจัยที่ประเมินแล้ว'),
        ];
    }

    public static function getCustomStatusValues() {
        return [
            self::CUSTOM_STATUS_MEETING_PENDING => [
                'min' => self::STATUS_MEETING_APPOINTMENT,
                'max' => self::STATUS_COMMITTEE_ASSESSED,
            ],
        ];
    }

    public static function getCustomStatusValuesSteps() {
        return [
            self::CUSTOM_STATUS_STEP_PENDING => [
                'min' => self::STATUS_CODE_GENERATED,
                'max' => self::STATUS_COMMITTEE_ASSESSED,
            ],
        ];
    }

    public static function getCustomStatusCommitteeValues() {
        return [
            self::CUSTOM_STATUS_MEETING_PENDING => [
                'min' => self::STATUS_SECRETARY_SELECT_TYPE,
                'max' => self::STATUS_COMMITTEE_ASSESSED,
            ],
        ];
    }

    public static function getResolutionLablesNew() {
        return[
            self::RESOLUTION_Y => yii::t('app', 'รับรอง'),
            self::RESOLUTION_C => yii::t('app', 'รับรอง/รับทราบหลังจากแก้ไขตามมติที่ประชุม'),
            self::RESOLUTION_R => yii::t('app', 'ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำกลับมาพิจารณาใหม่'),
            self::RESOLUTION_N => yii::t('app', 'ไม่รับรอง/รับทราบ'),
            self::RESOLUTION_W => yii::t('app', 'ถอนออกจากการพิจารณาและหรือถอนออกจากการรับรอง/รับทราบ'),
            self::RESOLUTION_T => yii::t('app', 'ยุติการรับรอง/รับทราบ'),
            self::RESOLUTION_P => yii::t('app', 'เปลี่ยน Panel'),
        ];
    }

    public static function getResolutionLables() {
        return[
            self::RESOLUTION_Y => yii::t('app', 'รับรอง/รับทราบ'),
            self::RESOLUTION_C => yii::t('app', 'รับรอง/รับทราบหลังจากแก้ไขตามมติที่ประชุม'),
            self::RESOLUTION_R => yii::t('app', 'ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำกลับมาพิจารณาใหม่'),
            self::RESOLUTION_N => yii::t('app', 'ไม่รับรอง/รับทราบ'),
            self::RESOLUTION_W => yii::t('app', 'ถอนออกจากการพิจารณาและหรือถอนออกจากการรับรอง/รับทราบ'),
            self::RESOLUTION_T => yii::t('app', 'ยุติการรับรอง/รับทราบ'),
            self::RESOLUTION_P => yii::t('app', 'เปลี่ยน Panel'),
        ];
    }

    public static function getResolutionConsiderationLables() {
        return[
            self::RESOLUTION_Y => yii::t('app', 'รับรอง/รับทราบ'),
            self::RESOLUTION_C => yii::t('app', 'รับรอง/รับทราบหลังจากแก้ไขตามที่กรรมการแนะนำ'),
        ];
    }

    public static function getResolutionConsiderationLablesCrec() {
        return[
            self::RESOLUTION_Y => yii::t('app', 'ส่งผลประเมินกรรมการให้ CREC'),
        ];
    }

    public static function getStatusLabelsResearcherNew() {
        return [
            self::STATUS_PENDING_SUBMISSION,
            self::STATUS_SUBMITTED,
            self::STATUS_DOC_REJECTED,
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_DOC_APPROVED,
            self::STATUS_CODE_GENERATED,
            self::CUSTOM_STATUS_MEETING_PENDING,
            self::STATUS_COMMITTEE_ASSESSED,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_MEETING_DONE,
            self::STATUS_STAFF_APPROVE_AGENDA,
            self::STATUS_WAITING_PRE_APPROVE_AGENDA,
            self::STATUS_SECRETARY_APPROVE_AGENDA,
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusLabelsStaffNoPanel() {
        return [
//            self::STATUS_PENDING_SUBMISSION,
            self::STATUS_DOC_REJECTED,
//            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_DOC_APPROVED,
        ];
    }

    public static function getStatusLabelsStaffNoPanelLables() {
        return [
            self::STATUS_PENDING_SUBMISSION => yii::t('app', 'โครงการที่ยังไม่แล้วเสร็จ'),
            self::STATUS_DOC_REJECTED => yii::t('app', 'เอกสารยังไม่ครบถ้วน/ไม่ถูกต้อง'),
//            self::STATUS_DOC_REJECTED_BY_COMMITTEE => yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_DOC_APPROVED => yii::t('app', 'รอออกเลขโครงการ'),
        ];
    }

    public static function getStatusSearch() {
        return [
            self::STATUS_CODE_GENERATED,
            self::STATUS_MEETING_APPOINTMENT,
            self::STATUS_SECRETARY_SELECT_TYPE,
            self::STATUS_SECRETARY_SELECTED,
            self::STATUS_COMMITTEE_SELECTED,
            self::STATUS_COMMITTEE_ACCEPTED,
            self::STATUS_COMMITTEE_ASSESSED,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_MEETING_DONE,
        ];
    }

    public static function getstatusLabelsStaffContinueNoPanel() {
        return [
            self::STATUS_DOC_REJECTED,
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_SUBMITTED,
            self::STATUS_DOC_APPROVED,
        ];
    }

    public static function getStatusLabelsStaff() {
        return [
            self::STATUS_PENDING_SUBMISSION,
            self::STATUS_SUBMITTED,
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_CODE_GENERATED,
            self::CUSTOM_STATUS_MEETING_PENDING,
            self::STATUS_COMMITTEE_ASSESSED,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_MEETING_DONE,
            self::STATUS_STAFF_APPROVE_AGENDA,
            self::STATUS_WAITING_PRE_APPROVE_AGENDA,
            self::STATUS_SECRETARY_APPROVE_AGENDA,
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusLabelsStaffLables() {
        return [
            self::STATUS_PENDING_SUBMISSION => Yii::t('app', 'โครงการที่ยังส่งไม่แล้วเสร็จ'),
            self::STATUS_SUBMITTED => yii::t('app', 'รอตรวจสอบเอกสาร (แก้ไข มติ R,มติ C,มติ N)'),
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => yii::t('app', 'กรรมการขอให้แก้ไขเอกสาร'),
            self::STATUS_CODE_GENERATED => Yii::t('app', 'รอกำหนดประมาณวันที่ประชุมและวันส่งประเมิน'),
            self::CUSTOM_STATUS_MEETING_PENDING => yii::t('app', 'อยู่ระหว่างพิจารณาโดยกรรมการ'),
            self::STATUS_COMMITTEE_ASSESSED => yii::t('app', 'รอบรรจุวาระการประชุม'),
            self::STATUS_AGENDA_ADDED => yii::t('app', 'รอผลพิจารณาจากคณะกรรมการ'),
            self::STATUS_MEETING_DONE => yii::t('app', 'พิจารณาแล้วแต่ยังไม่ได้รับการตรวจสอบ'),
            self::STATUS_STAFF_APPROVE_AGENDA => yii::t('app', 'รอการตรวจสอบผลพิจารณาจากเลขา'),
            self::STATUS_WAITING_PRE_APPROVE_AGENDA => yii::t('app', 'รอการตรวจสอบผลพิจารณาจากประธาน'),
            self::STATUS_SECRETARY_APPROVE_AGENDA => yii::t('app', 'รอเจ้าหน้าที่ตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN => Yii::t('app', 'รอประธานตรวจสอบหนังสือแจ้งผล'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function getStatusLabelsStaffContinue() {
        return [
            self::STATUS_PENDING_SUBMISSION,
            self::STATUS_DOC_REJECTED,
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_SUBMITTED,
            self::STATUS_CODE_GENERATED,
            self::CUSTOM_STATUS_MEETING_PENDING,
            self::STATUS_COMMITTEE_ASSESSED,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_MEETING_DONE,
            self::STATUS_STAFF_APPROVE_AGENDA,
            self::STATUS_WAITING_PRE_APPROVE_AGENDA,
            self::STATUS_SECRETARY_APPROVE_AGENDA,
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusStep() {
        return [
            self::STATUS_SUBMITTED,
            self::CUSTOM_STATUS_STEP_PENDING,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusStepC() {
        return [
            self::STATUS_SUBMITTED,
            self::CUSTOM_STATUS_STEP_PENDING,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusStepC160() {
        return [
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_SUBMITTED,
            self::CUSTOM_STATUS_STEP_PENDING,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusStepLabel() {
        return [
            self::STATUS_DOC_REJECTED_BY_COMMITTEE => Yii::t('app', 'นักวิจัยชี้แจงและแก้ไขเพิ่มเติม'),
            self::STATUS_SUBMITTED => Yii::t('app', 'ตรวจสอบเอกสาร'),
            self::CUSTOM_STATUS_STEP_PENDING => Yii::t('app', 'รอผลประเมินจากกรรมการ'),
            self::STATUS_AGENDA_ADDED => Yii::t('app', 'เข้าประชุมพิจารณา'),
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT => Yii::t('app', 'นักวิจัยได้รับหนังสือแจ้งผลแล้ว'),
        ];
    }

    public static function getStatusLabelsResearcherContinue() {
        return [
            self::STATUS_PENDING_SUBMISSION,
            self::STATUS_DOC_REJECTED,
            self::STATUS_DOC_REJECTED_BY_COMMITTEE,
            self::STATUS_SUBMITTED,
            self::STATUS_CODE_GENERATED,
//            self::STATUS_DOC_APPROVED,
            self::CUSTOM_STATUS_MEETING_PENDING,
            self::STATUS_COMMITTEE_ASSESSED,
            self::STATUS_AGENDA_ADDED,
            self::STATUS_MEETING_DONE,
            self::STATUS_STAFF_APPROVE_AGENDA,
            self::STATUS_WAITING_PRE_APPROVE_AGENDA,
            self::STATUS_SECRETARY_APPROVE_AGENDA,
            self::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN,
            self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT,
        ];
    }

    public static function getStatusLabelsResearcherResolution() {
        return [
            self::RESOLUTION_Y,
            self::RESOLUTION_C,
            self::RESOLUTION_R,
            self::RESOLUTION_N,
            self::RESOLUTION_W,
            self::RESOLUTION_T,
            self::RESOLUTION_P,
        ];
    }

    public static function getContinueResolutions() {
        return [
            self::RESOLUTION_Y,
            self::RESOLUTION_C,
            self::RESOLUTION_R,
        ];
    }

    public static function getCustomStatusValue($status) {
        $customStatuses = Submission::getCustomStatusValues();
        if (\yii\helpers\ArrayHelper::keyExists($status, $customStatuses)) {
            return $customStatuses[$status];
        } else {
            return NULL;
        }
    }

    public static function getCustomStatusValueStep($status) {
        $customStatuses = Submission::getCustomStatusValuesSteps();
        if (\yii\helpers\ArrayHelper::keyExists($status, $customStatuses)) {
            return $customStatuses[$status];
        } else {
            return NULL;
        }
    }

    public static function toArrayData($objects) {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties() {
        return [
            \app\models\Submission::class => [
                'id',
                'remark',
                'certified_date',
                'status',
                'project_id',
                'organization_id',
                'full_doc_file',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'submission_type_id',
                'resolution',
                'ref_submission_id',
                'responsible_person',
                'responsible_date',
                'meeting_plan_date',
                'send_plan_date',
                'correspondence_no',
                'correspondence_at',
                'secretary_person',
                'is_meeting',
                'meeting_by',
                'meeting_at',
                'certificate_no',
                'expire_at',
                'risk_id',
                'progress_period',
                'next_progress_at',
                'subject',
                'issue1',
                'issue2',
                'remark_checkdoc_staff',
                'remark_assessed_staff',
                'is_legacy',
                'submission_by',
                'project_coordinator_id',
                'leader_comment',
                'is_accept',
                'note',
                'events',
                'event_amendment',
                'project_coordinator_2nd_id',
                'project_coordinator_3rd_id',
                'project_viewer_id',
                'crec_leader_name',
                'crec_leader_name_eng',
                'crec_leader_site_name',
                'crec_leader_site_name_eng',
                'crec_leader_org_name',
                'crec_leader_org_name_eng',
                'crec_resolution',
                'crec_issue_req_detail',
                'crec_send_plan_date',
                'crec_staff',
                "projectLeader" => function ($model) {
                    return $model->projectLeader;
                },
                "projectResearchers" => function ($model) {
                    return $model->getProjectResearchers()->isDeleted(false)->all();
                },
                'ec_submission_id' => function ($model) {
                    return $model->id;
                },
                'ec_project_code' => function ($model) {
                    return $model->project->project_code;
                },
                'ec_certificate_no' => function ($model) {
                    return $model->certificate_no;
                },
                'ec_certificate_at' => function ($model) {
                    return $model->certified_date;
                },
                'ec_expire_at' => function ($model) {
                    return $model->expire_at;
                },
                'ec_upload_result_date' => function ($model) {
                    return $model->getStatusDate(self::STATUS_STAFF_UPLOAD_RESULTDOCUMENT);
                },
                'crecSubmissionTypeId' => function ($model) {
                    return $model->submissionType->crec_id;
                },
            ]
        ];
    }

}
