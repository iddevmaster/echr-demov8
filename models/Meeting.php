<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "meeting".
 *
 * @property int $id รหัส
 * @property string $title ชื่อการประชุม
 * @property string $start_date วันที่ประชุม
 * @property string $end_date วันที่ปิดประชุม
 * @property string $start_time เวลาเปิดประชุม
 * @property string $end_time เวลาปิดประชุม
 * @property string $status สถานะ
 * @property int $is_public เปิดเผยแพร่
 * @property int $department_id หน่วยงาน
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $organization_id องค์กร
 * @property int $meeting_no ครั้งที่
 * @property int $year ปี
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $panel_id Panel
 * @property int $meeting_room_id ห้องประชุม
 * @property int $checked_status สถานะการตรวจรายงานการประชุม
 * @property int $checked_staff เจ้าหน้าที่รับผิดชอบตรวจสอบรายงานการประชุม
 * @property int $checked_secretary_first เลขาหลักในการตรวจสอบรายงานการประชุม
 * @property int $checked_secretary_second เลขารองในการรวจสอบรายงานการประชุม
 * @property string $staff_checked_at วันที่เจ้าหน้าที่ตรวจสอบ
 * @property string $sec1_checked_at วันที่เลขาคนแรกตรวจสอบ
 * @property string $sec2_checked_at วันที่เลขาคนที่สองตรวจสอบ
 *
 * @property Department $department
 * @property Organization $organization
 * @property Submission $submission
 * @property Panel $panel
 * @property User $createdBy
 * @property User $updatedBy
 * @property MeetingAgenda[] $meetingAgendas
 * @property RegisterTransaction[] $registerTransactions
 * @property MeetingRoom $meetingRoom
 * @property MeetingPerson[] $meetingPeople 
 * @property User $checkedSecretaryFirst
 * @property User $checkedSecretarySecond
 * @property User $checkedStaff
 */
class Meeting extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    const STATUS_CLOSED_MEETING = '200';
    const STATUS_OPEN_MEETING = '100';
    const STATUS_CREATED_MEETING = '50';
    const CS_PENDING = 100;
    const CS_STAFF_CHECKED = 200;
    const CS_PARTLY_CHECKED = 300;
    const CS_PRE_CHECKED = 350;
    const CS_FULL_CHECKED = 400;

    public static function getStatusLabels() {
        return [
            self::STATUS_CLOSED_MEETING => Yii::t('app', 'ปิดการประชุม'),
            self::STATUS_OPEN_MEETING => Yii::t('app', 'เปิดการประชุม'),
            self::STATUS_CREATED_MEETING => Yii::t('app', 'สร้างการประชุม'),
        ];
    }

    public static function getCheckedStatusLabels() {
        return [
            self::CS_PENDING => Yii::t('app', 'รอการตรวจสอบ'),
            self::CS_STAFF_CHECKED => Yii::t('app', 'เจ้าหน้าที่ตรวจสอบแล้ว'),
            self::CS_PARTLY_CHECKED => Yii::t('app', 'เลขาตรวจสอบบางส่วนแล้ว'),
            self::CS_PRE_CHECKED => Yii::t('app', 'รอประธานตรวจสอบ'),
            self::CS_FULL_CHECKED => Yii::t('app', 'ประธานตรวจสอบแล้ว'),
        ];
    }

    public static function statusColors() {
        return [
            self::CS_PENDING => 'bg-yellow-600',
            self::CS_STAFF_CHECKED => 'bg-red-600',
            self::CS_PARTLY_CHECKED => 'bg-pink-600',
            self::CS_FULL_CHECKED => 'bg-purple-600',
        ];
    }

    public static function tableName() {
        return 'meeting';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['panel_id'], 'required'],
            [['start_date', 'end_date', 'start_time', 'end_time', 'created_at', 'updated_at', 'staff_checked_at', 'sec1_checked_at', 'sec2_checked_at', 'pre_checked_at'], 'safe'],
            [['is_public', 'department_id', 'submission_id', 'organization_id', 'meeting_no', 'year', 'deleted', 'created_by', 'updated_by', 'panel_id', 'meeting_room_id', 'checked_president', 'checked_status', 'checked_staff', 'checked_secretary_first', 'checked_secretary_second'], 'integer'],
            [['title', 'status'], 'string', 'max' => 255],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
            [['meeting_room_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeetingRoom::className(), 'targetAttribute' => ['meeting_room_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['checked_president'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['checked_president' => 'id']],
            [['checked_staff'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['checked_staff' => 'id']],
            [['checked_secretary_first'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['checked_secretary_first' => 'id']],
            [['checked_secretary_second'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['checked_secretary_second' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'title' => Yii::t('app', 'ชื่อการประชุม'),
            'start_date' => Yii::t('app', 'วันที่ประชุม'),
            'end_date' => Yii::t('app', 'วันที่ปิดประชุม'),
            'start_time' => Yii::t('app', 'เวลาเปิดประชุม'),
            'end_time' => Yii::t('app', 'เวลาปิดประชุม'),
            'status' => Yii::t('app', 'สถานะ'),
            'checked_president' => Yii::t('app', 'ประธานตรวจสอบรายงานการประชุม'),
            'pre_checked_at' => Yii::t('app', 'วันที่ประธานตรวจสอบ'),
            'is_public' => Yii::t('app', 'เปิดเผยแพร่'),
            'department_id' => Yii::t('app', 'หน่วยงาน'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'organization_id' => Yii::t('app', 'องค์กร'),
            'meeting_no' => Yii::t('app', 'ครั้งที่'),
            'year' => Yii::t('app', 'ปี'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'panel_id' => Yii::t('app', 'Panel'),
            'meeting_room_id' => Yii::t('app', 'ห้องประชุม'),
            'start' => Yii::t('app', 'วันที่เริ่มต้นข้อมูล'),
            'end' => Yii::t('app', 'วันที่สินสุดข้อมูล'),
            'fullNameWithDate' => yii::t('app', 'ชื่อการประชุม'),
            'fullName' => yii::t('app', 'ชื่อการประชุม'),
            'yearNo' => yii::t('app', 'ครั้งที่ประชุม'),
            'checked_status' => yii::t('app', 'สถานะการตรวจสอบการประชุม'),
            'checked_staff' => yii::t('app', 'เจ้าหน้าที่รับผิดชอบตรวจสอบรายงานการประชุม'),
            'checked_secretary_first' => yii::t('app', 'เลขาหลักในการตรวจสอบรายงานการประชุม'),
            'checked_secretary_second' => yii::t('app', 'เลขารองในการรวจสอบรายงานการประชุม'),
            'staff_checked_at' => Yii::t('app', 'วันที่เจ้าหน้าที่ตรวจสอบ'),
            'sec1_checked_at' => Yii::t('app', 'วันที่เลขาคนแรกตรวจสอบ'),
            'sec2_checked_at' => Yii::t('app', 'วันที่เลขาคนที่สองตรวจสอบ'),
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
    public function getDepartment() {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
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
    public function getCheckedStaff() {
        return $this->hasOne(User::className(), ['id' => 'checked_staff']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedSecretaryFirst() {
        return $this->hasOne(User::className(), ['id' => 'checked_secretary_first']);
    }

    public function getCheckedPresident() {
        return $this->hasOne(User::className(), ['id' => 'checked_president']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckedSecretarySecond() {
        return $this->hasOne(User::className(), ['id' => 'checked_secretary_second']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendas() {
        return $this->hasMany(MeetingAgenda::className(), ['meeting_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterTransactions() {
        return $this->hasMany(RegisterTransaction::className(), ['meeting_id' => 'id']);
    }

    public function getMeetingPeople() {
        return $this->hasMany(MeetingPerson::className(), ['meeting_id' => 'id']);
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
    public function getMeetingRoom() {
        return $this->hasOne(MeetingRoom::className(), ['id' => 'meeting_room_id']);
    }

    public function getFullName() {
        return \Yii::t('app', '{0} ชุดที่ {1} ครั้งที่ {2}', [$this->title, $this->panel_id, $this->yearNo]);
    }

    public function getFullNameWithDate() {
        return \Yii::t('app', '{0} ชุดที่ {1} ครั้งที่ {2} วัน-เวลา {3}', [
                    $this->title, $this->panel_id, $this->yearNo, \Yii::$app->formatter->asDateTime($this->start_date)]);
    }

    public function getYearNo() {
        return "{$this->meeting_no}/{$this->year}";
    }

    public function getYearNoWithDate() {
        return "ครั้งที่ {$this->meeting_no} ปี {$this->year}" . '<br> วันที่ ' . Yii::$app->thaiFormatter->asDate($this->start_date, 'php:d/m/Y');
    }

    public function getHasChild() {
        $mas = $this->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission()->all();
        foreach ($mas as $ma) {
            if ($ma->submission->project->is_child_project) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getTotalInterval($personId) {
//        $personId = \Yii::$app->user->identity->person->id;
        $trans = $this->getRegisterTransactions()->person($personId)->isDeleted(FALSE)->all();
        $hours = '';
        $ref = new \DateTime('00:00');
        $ref2 = clone $ref;
        foreach ($trans as $tran) {
            $interval = $tran->meetingInterval;
            if (!isset($interval)) {
                continue;
            }
            $ref->add($interval);
        }
        return $ref2->diff($ref, TRUE);
    }

    public function getTranMeeting($personId) {
        $trans = $this->getRegisterTransactions()->person($personId)->isDeleted(FALSE)->all();
        $tranMeeting = '';
        $tranMeeting .= '<ul>';
        foreach ($trans as $tran) {
            $tranMeeting .= "<li>{$tran->registered_at} - {$tran->out_at} </li>";
        }
        $tranMeeting .= '</ul>';
        return $tranMeeting;
    }

    public function getHasCOI() {
        $mps = $this->getMeetingPeople()->isDeleted(FALSE)->all();
        foreach ($mps as $mp) {
            $mas = $mp->person->getCOIMeetingAgendas($this->id);
            if (count($mas) > 0) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getPaediatricians() {
        $personIds = \yii\helpers\ArrayHelper::getColumn($this->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->isPaediatrician()->all(), 'person_id');
        return Person::find()->andWhere(['id' => $personIds])->all();
    }

    public function getChairman() {
        return $this->getMeetingPeople()->joinWith(['meeting'])->isDeleted(FALSE)->role(Role::PRESIDENT)->one();
    }

    public function getSummaryDocTemplateAlias() {
        return Yii::getAlias("@app/web/uploads/template/meeting-new.docx");
    }

    public function getPreviousMeeting() {
        return Meeting::find()->isDeleted(FALSE)->panel($this->panel_id)->year($this->year)->andWhere(['<', 'id', $this->id])->orderBy('id')->one();
    }

    public function getHasAllCheckingSecretaries() {
        if (isset($this->checked_secretary_first) && isset($this->checked_secretary_second)) {
            return true;
        }
//        else if (isset($this->checked_secretary_first)) {
//            $mas = $this->checkedSecretaryFirst->person->getCOIMeetingAgendas($this->id);
//            if (count($mas) == 0) {
//                return true;
//            }
//        }
        return false;
    }

    public function getResolutionNotNull() {
//        $totalCount = $this->getSubmissionDocuments()->isDeleted(FALSE)->count();
        $reCount = $this->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(TRUE)->resolutionNotnull()->count();
        return [
            'reChecked' => $reCount == 0,
        ];
    }

    public function getSubmissionResolutionNotNull() {
        $mas = $this->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(TRUE)->resolutionNotnull()->all();
        $p = '';
        foreach ($mas as $ma) {
            $p .= $ma->sort_label . '(' . $ma->title . ') ';
        }
        return $p;
    }

    public function getAllInspectorIds() {
        return [
            $this->checked_staff,
            $this->checked_secretary_first,
            $this->checked_secretary_second,
            $this->checked_president
        ];
    }

    /**
     * @inheritdoc
     * @return MeetingQuery the active query used by this AR class.
     */
    public static function find() {
        return new MeetingQuery(get_called_class());
    }

}
