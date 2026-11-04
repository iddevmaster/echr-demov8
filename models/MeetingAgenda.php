<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "meeting_agenda".
 *
 * @property int $id รหัสหน่วยงาน
 * @property int $meeting_id การประชุม
 * @property string $title หัวข้อวาระ
 * @property string $description รายละเอียด
 * @property string $conclusion มติที่ประชุม
 * @property string $summary สรุปประชุม
 * @property string $sort_label ระดับ
 * @property int $project_id โครงการวิจัย
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $parent_id ลำดับวาระ
 * @property string $approved_at วันเวลาตอบรับ
 * @property int $approved_by ผู้ตอบรับ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $agenda_id วาระการประชุม
 * @property int $sort 
 * @property int $sortable จัดเรียงได้หรือไม่
 * @property int $addable สามารถเพิ่มวาระได้
 * @property int $need_resolution ต้องระบุมติ
 * @property string $resolution มติที่ประชุม
 *
 * @property Agenda $agenda
 * @property User $approvedBy
 * @property Meeting $meeting
 * @property Project $project
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 * @property MeetingAgenda[] $meetingAgendas
 * @property AgendaResultDocument[] $agendaResultDocument
 */
class MeetingAgenda extends \yii\db\ActiveRecord {

    const AUTO_TYPE_CLOSING_PROTOCOL = 1;
    const AUTO_TYPE_CONTINUE_REPORT = 2;
    const AUTO_TYPE_EXEMPTION_RESEARCH = 3;
    const AUTO_TYPE_EXPEDITED1 = 4;
    const AUTO_TYPE_EXPEDITED2 = 5;
    const AUTO_TYPE_CORRESPONDENCE = 6;
    const AUTO_TYPE_AMENDMENT_FAST = 7;
    const AUTO_TYPE_AMENDMENT = 8;
    const AUTO_TYPE_DEVIATION = 9;
    const AUTO_TYPE_RENEW = 10;
    const AUTO_TYPE_EXPEDITED_FULL_BOARD = 11;
    const AUTO_TYPE_RESUBMIT = 12;
    const AUTO_TYPE_ASSESSMENT = 13;
    const AUTO_TYPE_SUBMISSION_DOCS = 14;
    const AUTO_TYPE_VOLUNTEER_NUMBER = 15;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'meeting_agenda';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['meeting_id', 'project_id', 'submission_id', 'parent_id', 'approved_by', 'deleted', 'created_by', 'updated_by', 'agenda_id', 'sort', 'sortable', 'addable', 'need_resolution'], 'integer'],
            [['description', 'conclusion', 'summary'], 'string'],
            [['approved_at', 'created_at', 'updated_at'], 'safe'],
            [['title', 'sort_label', 'resolution'], 'string', 'max' => 255],
            [['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['agenda_id' => 'id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approved_by' => 'id']],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meeting_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
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
            'meeting_id' => Yii::t('app', 'การประชุม'),
            'title' => Yii::t('app', 'หัวข้อวาระ'),
            'description' => Yii::t('app', 'รายละเอียด'),
            'conclusion' => Yii::t('app', 'รายละเอียดมติที่ประชุม'),
            'summary' => Yii::t('app', 'หมายเหตุ'),
            'sort_label' => Yii::t('app', 'ระดับ'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'parent_id' => Yii::t('app', 'ลำดับวาระ'),
            'approved_at' => Yii::t('app', 'วันเวลาตอบรับ'),
            'approved_by' => Yii::t('app', 'ผู้ตอบรับ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'agenda_id' => Yii::t('app', 'วาระการประชุม'),
            'sort' => Yii::t('app', 'Sort'),
            'sortable' => Yii::t('app', 'จัดเรียงได้หรือไม่'),
            'addable' => Yii::t('app', 'สามารถเพิ่มวาระได้'),
            'need_resolution' => Yii::t('app', 'ต้องระบุมติ'),
            'resolution' => Yii::t('app', 'มติที่ประชุม'),
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
    public function getAgenda() {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy() {
        return $this->hasOne(User::className(), ['id' => 'approved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting() {
        return $this->hasOne(Meeting::className(), ['id' => 'meeting_id']);
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
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(MeetingAgenda::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendas() {
        return $this->hasMany(MeetingAgenda::className(), ['parent_id' => 'id']);
    }

    public function getFullTitle() {
        $name = "{$this->sort_label} {$this->title}";
        if (isset($this->submission->submission_type_id)) {
            if ($this->submission->submission_type_id == \app\models\SubmissionType::TYPE_DEVIATION) {
                $events = \app\models\SubmissionEvent::find()->isDeleted(false)
                                ->submission($this->submission_id)->all();
                foreach ($events as $event):
                    $name .= " " . $event->code . " ";
                endforeach;
            } else if ($this->submission->submission_type_id == \app\models\SubmissionType::TYPE_INTERNAL_SAE) {
                $volunteers = \app\models\SubmissionVolunteer::find()->isDeleted(false)
                                ->submissionId($this->submission_id)->all();
                foreach ($volunteers as $volunteer):
                    $name .= " " . $volunteer->volunteer->code . " ";
                endforeach;
            }
        }
        return $name;
    }

    public function setSortLabel() {
        $sort = $this->sort;
        if (isset($this->submission_id)) {
            $sort = str_pad($this->sort, 2, '0', STR_PAD_LEFT);
        }
        $this->sort_label = (isset($this->parent) ? "{$this->parent->sort_label}." : "") . $sort;
    }

    public function setParent() {
        $parent = MeetingAgenda::find()->isDeleted(FALSE)->agenda($this->agenda_id)->meeting($this->meeting_id)->one();
        $this->parent_id = (isset($parent) ? $parent->id : NULL);
    }

    public function getCoiPeople() {
        if (!isset($this->submission)) {
            return [];
        }
        // $researchers = $this->submission->project->getProjectResearchers()->select('person_id')->isDeleted(FALSE)->all();
        // $personIds = array_unique(ArrayHelper::getColumn($researchers, 'person_id'));
        // // $lastNames = array_unique(ArrayHelper::getColumn($researchers, 'person.last_name'));
        // $lastNames = [];
        // $cois = ArrayHelper::getColumn(MeetingPerson::find()->joinWith(['person'])->select('person_id')->isDeleted(FALSE)->meeting($this->meeting_id)->personOrLastName($personIds, $lastNames)->all(), 'person_id');
        $cois = ArrayHelper::getColumn(SubmissionCoiPerson::find()->select('person_id')->isDeleted(false)->submission($this->submission_id)->all(), 'person_id');
        return Person::find()->andWhere(['id' => $cois])->all();
    }

    public function setInitDescription() {
        if (!isset($this->agenda)) {
            return;
        }
        $descs = $this->agenda->getAgendaAutoDescs()->isDeleted(FALSE)->all();
        $this->description = '';
        foreach ($descs as $desc) {

            $this->description .= "<p>" . $this->getAutoDesc($desc->auto_type) . "</p>";
        }
    }

    public function setInitSummary() {
        $this->summary = $this->childRemarkMsg;
    }

    public function getChildRemarkMsg() {
        if ($this->submission->project->is_child_project) {
            $paediatricians = $this->meeting->paediatricians;
            if (count($paediatricians) > 0) {
                $msg = \Yii::t('app', "หมายเหตุ: {0} กุมารแพทย์อยู่ในที่ประชุมขณะพิจารณาโครงการวิจัย", [implode(\Yii::t('app', 'และ'), ArrayHelper::getColumn($paediatricians, 'fullName'))]);
                $res = <<<res
                    <p class="child-remark">
                        {$msg}
                    </p>
res;
                return $res;
            }
            return "";
        }
        return "";
    }

    public function getCommitteeCountMsg() {
        $personCount = RegisterTransaction::find()->isDeleted(FALSE)->meeting($this->meeting_id)->inMeetingAt(date('Y-m-d H:i:s'))->distinct()->count('person_id');
        $msg1 = \Yii::t('app', "(จำนวนกรรมการในที่ประชุม {0} ท่าน มีสิทธิ์ออกเสียง {1} ท่าน องค์ประชุมครบตาม SOP)", [$personCount, $personCount]);
        $msg2 = \Yii::t('app', "(กรรมการที่ออกเสียง {0} ท่าน ประธานงดออกเสียงในที่ประชุม)", ['']);
        $res = <<<res
                <p class="committee-count">
                    <p>{$msg1}</p>
                    <p>{$msg2}</p>
                </p>
res;
        return $res;
    }

    public function getAutoDesc($type) {
        $lastEndorseMa = $this->lastEndorseMeetingAgenda;
        if ($type == self::AUTO_TYPE_CLOSING_PROTOCOL) {
            if (isset($lastEndorseMa)) {
                return \Yii::t('app', 'เป็นโครงการที่ผ่านการรับรองจริยธรรมการวิจัยฯแล้ว เมื่อครั้งประชุมที่ {0} วาระ {1} คณะผู้วิจัยขอแจ้งปิดโครงการวิจัย', [$lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label]) . '<u>' .
                        \Yii::t('app', 'เนื่องจากดำเนินการเสร็จสิ้นแล้ว') . '</u>';
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_CONTINUE_REPORT) {
            if (isset($lastEndorseMa)) {
                return \Yii::t('app', 'เป็นโครงการที่เข้าการประชุมครั้งที่ {0} วาระ {1} ครั้งนี้ผู้วิจัยขอรายงานความก้าวหน้าครั้งที่ {3} ช่วงเวลาที่รายงาน {4} (รายงานทุก {5} เดือน)', [
                            $lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label, '', '', $lastEndorseMa->project->progress_period
                ]);
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_EXEMPTION_RESEARCH) {
            return \Yii::t('app', 'โครงการวิจัยเรื่อง "{0}" เป็นโครงการที่เข้าข่าย Exemption Research', [$this->project->name_thai]);
        } else if ($type == self::AUTO_TYPE_EXPEDITED1) {
            return \Yii::t('app', 'เป็นโครงการวิจัยที่เข้าข่ายการพิจารณาแบบเร็วตามประกาศฯ ฉบับที่ 1877/2559');
        } else if ($type == self::AUTO_TYPE_EXPEDITED2) {
            return \Yii::t('app', 'ผู้วิจัยมีการระมัดระวังมิให้เกิดข้อเสี่ยงในด้านจริยธรรมในมนุษย์');
        } else if ($type == self::AUTO_TYPE_CORRESPONDENCE) {
            return $this->submission->correspondence_no . " " . \Yii::$app->formatter->asDate($this->submission->correspondence_at, 'long');
        } else if ($type == self::AUTO_TYPE_AMENDMENT_FAST) {
            if (isset($lastEndorseMa)) {
                return \Yii::t('app', 'เป็นโครงการที่เข้าการประชุมครั้งที่ {0} วาระที่ {1} ครั้งนี้คณะกรรมการกลางพิจารณาจริยธรรมการวิจัยในคน (CREC) ขอส่งผลการพิจารณาเอกสาร', [$lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label]);
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_AMENDMENT) {
            if (isset($lastEndorseMa)) {
                return \Yii::t('app', 'เป็นโครงการที่เข้าการประชุมครั้งที่ {0} วาระ {1} ครั้งนี้ผู้วิจัยขอแก้ไขปรับปรุง ดังนี้', [$lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label]);
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_DEVIATION) {
            if (isset($lastEndorseMa)) {
                return \Yii::t('app', 'เป็นโครงการที่เข้าการประชุมครั้งที่ {0} วาระ {1} ครั้งนี้ผู้วิจัยรายงาน Protocol deviation โครงการที่ผ่านการรับรองจากคณะกรรมการจริยธรรมการวิจัยในมนุษย์ เนื่องจาก', [$lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label]);
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_RENEW) {
            if (isset($lastEndorseMa)) {
                $statusDate = $lastEndorseMa->submission->getStatusDate(Submission::STATUS_SUBMITTED);
                return \Yii::t('app', 'โครงการนี้เคยผ่านการรับรองแล้ว เมื่อการประชุมครั้งที่ {0} วาระ {1} ผู้วิจัยขอต่ออายุโครงการมาเมื่อ {2} ซึ่งหมดอายุวันที่ {3}', [
                            $lastEndorseMa->meeting->yearNo, $lastEndorseMa->sort_label,
                            \Yii::$app->formatter->asDate($statusDate, 'long'), \Yii::$app->formatter->asDate($lastEndorseMa->project->expire_at, 'long'),
                ]);
            } else {
                return '';
            }
        } else if ($type == self::AUTO_TYPE_EXPEDITED_FULL_BOARD) {
            if (!isset($this->submission->refSubmission)) {
                return '';
            }
            $ma = $this->submission->refSubmission->meetingAgenda;
            return \Yii::t('app', 'มติ C จากการประชุมครั้งที่ {0} วาระ {1} และโครงการผ่านการรับรองแล้วเมื่อวันที่ {2}', [
                        $ma->meeting->yearNo, $ma->sort_label, \yii::$app->formatter->asDate($ma->submission->certified_date, 'long')
            ]);
        } else if ($type == self::AUTO_TYPE_RESUBMIT) {
            if (!isset($this->submission->refSubmission)) {
                return '';
            }
            $ma = $this->submission->refSubmission->meetingAgenda;
            return \Yii::t('app', 'มติ R จากการประชุมครั้งที่ {0} วาระ {1}', [
                        $ma->meeting->yearNo, $ma->sort_label
            ]);
        } else if ($type == self::AUTO_TYPE_ASSESSMENT) {
            $committees = $this->submission->getSubmissionCommittees()->isDeleted(FALSE)->all();
            $msg = "<p><strong>" . \Yii::t('app', 'ผลประเมินจากกรรมการ') . "</strong></p>";
            foreach ($committees as $i => $com) {

                if (count($committees) > 1) {
                    $msg .= "<p>" . Yii::t('app', 'กรรมการท่านที่ {0}', [$i + 1]) . "</p>";
                }
                if ($this->submission->submission_type_id == SubmissionType::TYPE_AMENDMENT || $this->submission->submission_type_id == SubmissionType::TYPE_PROGRESS || $this->submission->submission_type_id == SubmissionType::TYPE_RENEW || $this->submission->submission_type_id == SubmissionType::TYPE_EXTERNAL_SAE || $this->submission->submission_type_id == SubmissionType::TYPE_CLOSING || $this->submission->submission_type_id == SubmissionType::TYPE_OTHER || $this->submission->submission_type_id == SubmissionType::TYPE_IDMC) {
                    $continueAssess = ContinueAssessForm::find()->isDeleted(false)->submissionCommittee($com->id)->one();
                    if (isset($continueAssess)) {
                        $msg .= "<p> ชนิดของรายงานแยกตามชนิดของการพิจารณาของคณะกรรมการ :  " . $continueAssess->reviewChoice->name . "</p>";
                        if ($continueAssess->suggestion) {
                            $msg .= "<p> ข้อเสนอแนะเพิ่มเติม : " . $continueAssess->suggestion . "</p>";
                        }
                        $continueEthics = ContinueAssessFormEthics::find()->isDeleted(false)->continueAssessForm($continueAssess->id)->all();
                        $detail = "";
                        foreach ($continueEthics as $ethicC):
                            $otherC = "";
                            if ($ethicC->ethics->need_text):
                                $otherC = $ethicC->other;
                            endif;
                            $re = isset($ethicC->is_appropriate) ? ContinueAssessFormEthics::getStatusLabelsEthics()[$ethicC->is_appropriate] : "";
                            $detail .= $ethicC->ethics->name . "{$otherC}" . " กรรมการมีความเห็นว่า : " . $re;
                            $detail .= !empty($ethicC->remark) ? " หมายเหตุ : " . $ethicC->remark : "";
                            $detail .= "<br>";

                        endforeach;
                        $msg .= $detail;
                    }
                }
                if ($this->submission->submission_type_id == SubmissionType::TYPE_DEVIATION) {
                    $deviationAssess = DeviationAssessForm::find()->isDeleted(false)->submissionCommittee($com->id)->one();
                    if (isset($deviationAssess->id)) {
                        $msg .= "<p> ชนิดของรายงานแยกตามชนิดของการพิจารณาของคณะกรรมการ :  " . $deviationAssess->reviewChoice->name . " ผลการพิจารณา : " . $deviationAssess->resolution->getNameBySubmissionType($this->submission->submissionType) . "</p>";
                        if ($deviationAssess->suggestion) {
                            $msg .= "<p> ข้อเสนอแนะเพิ่มเติม : " . $deviationAssess->suggestion . "</p>";
                        }
                    } else {
                        $msg .= "";
                    }
                }
                if ($this->submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
                    $saeVolunteers = SaeVolunteer::find()->isDeleted(false)->submission($this->submission_id)->all();
                    $detail = "";
                    foreach ($saeVolunteers as $saeVolunteer):
                        $saeEthics = SaeVolunteerEthics::find()->isDeleted(false)->saeVolunteer($saeVolunteer->id)->all();
                        $detail .= "หมายเลขอาสาสมัคร : " . $saeVolunteer->volunteer->code . " ประเภทติดตาม : " . $saeVolunteer->submissionVolunteer->typeLabel . "<br>";
                        $detail .= "เสียชีวิตหรือไม่ : " . Yii::$app->util->getYesNoUnknownLabels()[$saeVolunteer->dead] . " รักษาจนเป็นปกติหรือไม่ : " . Yii::$app->util->getYesNoUnknownLabels()[$saeVolunteer->cured] . " สัมพันธ์กับยาวิจัยหรือไม่ : " . Yii::$app->util->getYesNoUnknownLabels()[$saeVolunteer->drug] . "<br>";
                        foreach ($saeEthics as $saeEthic):
                            $other = "";
                            if ($saeEthic->ethics->need_text):
                                $other = $saeEthic->other;
                            endif;
                            $detail .= $saeEthic->ethics->name . "{$other}" . " กรรมการมีความเห็นว่า : " . SaeVolunteerEthics::getStatusLabelsEthics()[$saeEthic->is_appropriate] . " " . $saeEthic->remark;
                            $detail .= "<br>";

                        endforeach;
                        if (!empty($saeVolunteer->comment)) {
                            $detail .= "ข้อคิดเห็นเพิ่มเติม : " . $saeVolunteer->comment;
                            $detail .= "<br>";
                        }
                        $detail .= "<br>";

                    endforeach;
                    $msg .= $detail;
                } else if ($this->submission->submission_type_id == SubmissionType::TYPE_DEVIATION) {
                    $deviationEvents = DeviationEvent::find()->joinWith(['submission'])->isDeleted(false)->submission($this->submission_id)->all();
                    $detail = "";
                    foreach ($deviationEvents as $deviationEvent):

                        $deviationEthics = DeviationEventEthics::find()->isDeleted(false)->deviationEvent($deviationEvent->id)->all();
                        $detail .= "หมายเลขเหตุการณ์ : " . $deviationEvent->submissionEvent->event_no . " รายละเอียดประเภทโครงการวิจัยเบี่ยงเบน : " . DeviationEvent::violationLabels()[$deviationEvent->is_major_minor_com] . "<br>";
                        foreach ($deviationEthics as $ethic):
                            $other = "";
                            if ($ethic->ethics->need_text):
                                $other = $ethic->other;
                            endif;
                            $detail .= $ethic->ethics->name . "{$other}" . " กรรมการมีความเห็นว่า : " . DeviationEventEthics::getStatusLabelsEthics()[$ethic->is_appropriate];
                            $detail .= "<br>";

                        endforeach;
                        $detail .= "<br>";
                        if (isset($deviationEvent->comment)) {
                            $detail .= 'ข้อคิดเห็นเพิ่มเติม : ' . $deviationEvent->comment;
                        }
                    endforeach;
                    $msg .= $detail;
                }

//                $answers = $com->getQuestionnaireAnswers()->isDeleted(FALSE)->all();
//                $msg .= "<ul>";
//                foreach ($answers as $answer) {
//                    $msg .= "<li>" . $answer->questionnaireTitle->title . "</li>";
//                    $msg .= "<ul>";
//                    $msg .= "<li>" . $answer->fullAnswer . "</li>";
//                    $msg .= "</ul>";
//                }
//                $msg .= "</ul>";
            }
            return $msg;
        } else if ($type == self::AUTO_TYPE_SUBMISSION_DOCS) {
            $msg = "<p><strong>" . \Yii::t('app', 'ผู้วิจัยแนบเอกสารดังนี้') . "</strong></p>";
            $docs = $this->submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
            $msg .= "<ul>";
            foreach ($docs as $doc) {
                $msg .= "<li>{$doc->nameWithVersion}</li>";
            }
            $msg .= "</ul>";
            return $msg;
        } else if ($type == self::AUTO_TYPE_VOLUNTEER_NUMBER) {
            $msg = "<p><strong>" . \Yii::t('app', 'ข้อมูลเกี่ยวกับอาสาสมัครในโครงการ') . "</strong></p>";
            $numbers = $this->submission->getSubmissionVolunteerNumbers()->isDeleted(FALSE)->all();
            $msg .= "<ul>";
            foreach ($numbers as $number) {
                $msg .= "<li>{$number->volunteerNumber->name} {$number->value} " . \Yii::t('app', 'ราย') . "</li>";
            }
            $msg .= "</ul>";
            return $msg;
        }
    }

    public function getLastEndorseMeetingAgenda() {
        $sub = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->project($this->project_id)->resolution(Submission::RESOLUTION_Y)->submissionTypeGroup(SubmissionTypeGroup::GROUP_NEW)->one();
        if (!isset($sub)) {
            return NULL;
        }
        $ma = $sub->getMeetingAgenda();
        return $ma;
    }

    /**
     * @inheritdoc
     * @return MeetingAgendaQuery the active query used by this AR class.
     */
    public static function find() {
        return new MeetingAgendaQuery(get_called_class());
    }

    public static function getNextSort($parentId) {
        $sort = MeetingAgenda::find()->isDeleted(FALSE)->parentAgenda($parentId)->max('sort');
        if (isset($sort)) {
            return $sort + 1;
        } else {
            return 1;
        }
    }

    public static function updateSort($parentId) {
        $meetingAgendas = MeetingAgenda::find()->isDeleted(FALSE)->parentAgenda($parentId)->orderBy('sort')->all();
        foreach ($meetingAgendas as $key => $ma) {
            $ma->sort = $key + 1;
            $ma->setSortLabel();
            $ma->save(FALSE);
        }
    }

}
