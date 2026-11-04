<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\models\User;
use app\models\ProjectResearcher;
use app\models\Person;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "email_queue".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $model_id รหัสข้อมูลหลัก
 * @property int $type ประเภทอีเมล์
 * @property string $mail_at ส่งเมล์เมื่อ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class EmailQueue extends \yii\db\ActiveRecord {

    const TYPE_USER_VERIFY = 1;
    const TYPE_RESEARCHER_ACK = 2;
    const TYPE_COMMITTEE_ACK = 3;
    const TYPE_SUBMISSION_RESULT = 4;
    const TYPE_COMMITTEE_C = 5;
    const TYPE_INFORM_REG_CODE = 6;
    const TYPE_COMMITTEE_REASSESS = 7;
    const TYPE_COMMITTEE_ASSESS = 8;
    const TYPE_DOC_REJECT_BY_STAFF = 9;
    const TYPE_SECRETARY_SELECTED = 10;
    const TYPE_COMMITTEE_ACKNOWLEDGED = 11;
    const TYPE_RESET_PASSWORD = 12;
    const TYPE_CONSULTANT_ACK = 13;
    const TYPE_INFORM_PROJECTLEADER_NEW_SUBMISSION = 14;
    const TYPE_INFORM_PROJECTLEADER_DOC_REJECT = 15;
    const TYPE_INFORM_PROJECTLEADER_CONTINUE_SUBMISSION = 16;
    const TYPE_INFORM_PROJECTLEADER_NEW_CERTIFIED_SUBMISSION = 17;
    const TYPE_INFO_SECRETARY_CHECK_MEETING = 18;
    const TYPE_INFO_SECRETARY_2_CHECK_MEETING = 19;
    const TYPE_INFO_STAFF_UPLOAD_RESULT_AGENDA = 20;
    const TYPE_INFO_RESULT_PROJECTLEADER = 21;
    const TYPE_INFORM_PROJECT_CODE = 22;
    const TYPE_COMMITTEE_ASSESS_DUE = 23;
    const TYPE_CO_RESEARCHER_ACK_REMINDER = 24;
    const TYPE_PROGRESS_REMINDER = 25;
    const TYPE_RENEW_REMINDER = 26;
    const TYPE_INFORM_STAFF_C_OVER90DAY = 27;
    const TYPE_INFORM_STAFF_R_OVER90DAY = 28;
    const TYPE_ALL_RESEARCHERS_ACKNOWLEDGED = 29;
    const TYPE_INFORM_STAFF_OVER30DAY = 30;
    const TYPE_COMMITTEE = 31;
    const TYPE_NEW_SUBMISSION_CREC = 32;
    const TYPE_UPDATE_RESULT_DOCUMENT_CREC = 33;
    const TYPE_UPDATE_RESOLUTION_CREC = 34;
    const TYPE_RESUBMIT_LOCAL_ISSUE_CREC = 35;
    const TYPE_INFO_PRE_CHECK_MEETING = 36;
    const TYPE_NOTIFY_CREC_RESULT_LEADER = 37;
    const TYPE_INFORM_STAFF_RESULT = 38;
    const TYPE_INFO_PRESIDENT_RESULTDOC = 39;
    const TYPE_INFO_STAFF_EDIT_RESULTDOC = 40;
    const TYPE_TRAINING_EXPIRE_REMINDER = 41;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'email_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['model_id', 'type', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['mail_at', 'created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'model_id' => Yii::t('app', 'รหัสข้อมูลหลัก'),
            'type' => Yii::t('app', 'ประเภทอีเมล์'),
            'mail_at' => Yii::t('app', 'ส่งเมล์เมื่อ'),
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
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return EmailQueueQuery the active query used by this AR class.
     */
    public static function find() {
        return new EmailQueueQuery(get_called_class());
    }

    public static function addQueue($type, $modelId) {
        $model = new EmailQueue();
        $model->type = $type;
        $model->model_id = $modelId;
        $res = $model->save();
        if ($res) {
            \Yii::$app->util->execInBackground(\Yii::$app->params['sendMailCmd']);
        } else {
            echo "Cannot save email queue\n";
            //echo VarDumper::dumpAsString($model->errors);
        }
        return $res;
    }

    public static function addQueueNoExec($type, $modelId) {
        $model = new EmailQueue();
        $model->type = $type;
        $model->model_id = $modelId;
        $res = $model->save();
        return $res;
    }

    public static function execSendMailCmd() {
        \Yii::$app->util->execInBackground(\Yii::$app->params['sendMailCmd']);
    }

    public function sendMail() {
        $res = false;
        $adminName = '=?utf-8?B?' . base64_encode(\Yii::$app->params['adminName']) . '?=';
        if ($this->type === self::TYPE_USER_VERIFY) {
            $user = User::findOne($this->model_id);
            $user->verify_token = $user->generateVerifyToken();
            $user->save(FALSE);
            $msg = Yii::$app->mailer->compose('registration-verification', [
                'user' => $user,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'ยืนยันการลงทะเบียน'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($user->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_RESEARCHER_ACK) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $pr = ProjectResearcher::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('researcher-acknowledge', [
                'submission' => $pr->submission,
                'researcher' => $pr,
            ]);
//            $docs = $pr->submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
//            foreach ($docs as $doc) {
//                $msg->attach($doc->filePath, ['fileName' => $doc->name]);
//            }
//            \yii\helpers\VarDumper::dump($msg);
            $res = $msg->setSubject(\Yii::t('app', 'ตอบรับการเป็น "ผู้ร่วมวิจัย"'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($pr->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
            if ($res) {
                $pr->mail_sent = 1;
                $pr->mail_sent_at = date('Y-m-d H:i:s');
                $pr->save(FALSE);
            }
        } else if ($this->type === self::TYPE_CONSULTANT_ACK) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $pc = ProjectConsultant::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('consultant-acknowledge', [
                'submission' => $pc->submission,
                'consultant' => $pc,
            ]);

            $res = $msg->setSubject(\Yii::t('app', 'ตอบรับการเป็น "ที่ปรึกษาโครงการวิจัย"'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($pc->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
            if ($res) {
                $pc->mail_sent = 1;
                $pc->mail_sent_at = date('Y-m-d H:i:s');
                $pc->save(FALSE);
            }
        } else if ($this->type === self::TYPE_INFO_PRESIDENT_RESULTDOC) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('president-result-doc', [
                'submission' => $submission,
            ]);
            $edit = isset($submission->president_comment) ? '(ฉบับแก้ไข)' : "";
            if (isset($submission->president_person)) {
                $email = $submission->presidentPerson->person->email;
            } else {
                $email = $submission->project->panel->chairman->email;
            }
            $res = $msg->setSubject(\Yii::t('app', 'ขอความอนุเคราะห์ลงนามหนังสือแจ้งผลพิจารณาโครงการวิจัย [{1}] [{0}] {2} ', [$submission->submissionType->name, $submission->project->project_code, $edit]))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($email)
                    ->send();
        } else if ($this->type === self::TYPE_INFORM_PROJECTLEADER_NEW_SUBMISSION) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $ld = ProjectResearcher::find()->isDeleted(FALSE)->isLeader()->andWhere(['submission_id' => $this->model_id])->one();
            $msg = Yii::$app->mailer->compose('inform-projectleader-new-submission', [
                'submission' => $ld->submission,
                'leader' => $ld,
            ]);

            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องการส่งโครงการโดยผู้ประสานงาน'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($ld->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_INFORM_PROJECTLEADER_NEW_CERTIFIED_SUBMISSION) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $ld = ProjectResearcher::find()->isDeleted(FALSE)->isLeader()->andWhere(['submission_id' => $this->model_id])->one();
            $msg = Yii::$app->mailer->compose('inform-projectleader-new-certified-submission', [
                'submission' => $ld->submission,
                'leader' => $ld,
            ]);

            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องการส่งโครงการใหม่ที่ผ่านการรับรองแล้วโดยผู้ประสานงาน'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($ld->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_INFORM_PROJECTLEADER_CONTINUE_SUBMISSION) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $submission = Submission::findOne($this->model_id);
            $ld = ProjectResearcher::find()->isDeleted(FALSE)->isLeader()->andWhere(['submission_id' => $this->model_id])->one();
            $msg = Yii::$app->mailer->compose('inform-projectleader-continue-submission', [
                'submission' => $submission,
                'leader' => $ld,
            ]);

            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องการส่งโครงการต่อเนื่องโดยผู้ประสานงาน'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email)
                    ->send();
        }else if ($this->type === self::TYPE_INFO_STAFF_EDIT_RESULTDOC) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('info-staff-edit-resultdoc', [
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', "{$submission->project->project_code} : โปรดดำเนินการแก้ไขหนังสือแจ้งผลพิจารณาตามข้อเสนอแนะประธาน"))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->responsiblePerson->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFO_RESULT_PROJECTLEADER) {
            $submission = Submission::findOne($this->model_id);
            $projectCode = isset($submission->project->project_code) ? $submission->project->project_code : "";
            $title = '';
            
            if ($submission->is_submit_by_api == 1) {
                $title = \Yii::t('app', "แจ้งผลการพิจารณาจริยธรรมการวิจัยในมนุษย์โครงการวิจัยที่ MOU กับ CREC (เลขที่โครงการ {$projectCode})");
                $msg = Yii::$app->mailer->compose('info-result-projectleader-crec', [
                    'submission' => $submission,
                ]);
            } else {
                $title = \Yii::t('app', "แจ้งผลการพิจารณาจริยธรรมการวิจัยในมนุษย์");
                $msg = Yii::$app->mailer->compose('info-result-projectleader', [
                    'submission' => $submission,
                ]);
            }
            $co = $submission->project->getProjectCoordinator()->one();
            $co2 = $submission->project->getProjectCoordinator2nd()->one();
            $co3 = $submission->project->getProjectCoordinator3rd()->one();

            $msg = $msg->setSubject($title)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email);
            if (isset($co) && !isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email));
            } elseif (isset($co) && isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email));
            } elseif (isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            } elseif (isset($co) && !isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator3rd->person->email));
            } elseif (!isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            }

            $res = $msg->send();
        } else if ($this->type === self::TYPE_INFORM_PROJECTLEADER_DOC_REJECT) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $ld = ProjectResearcher::find()->isDeleted(FALSE)->isLeader()->andWhere(['id' => $this->model_id])->one();
            $msg = Yii::$app->mailer->compose('inform-projectleader-doc-reject', [
                'submission' => $ld->submission,
                'leader' => $ld,
            ]);

            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องการส่งแก้ไขข้อมูลโครงการที่ตีกลับจากเจ้าหน้าที่โดยผู้ประสานงาน'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($ld->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_COMMITTEE_ACK) {
//            $this->ack_token = Yii::$app->util->generateToken();
//            $this->save(FALSE);
            $pr = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-acknowledge', [
                'submission' => $pr->submission,
                'committee' => $pr,
            ]);
            $title = "";
            if (isset($pr->submission->refSubmission)) {
                if ($pr->submission->refSubmission->resolution == "C") {
                    $committeeRevise = \app\models\SubmissionCommitteeRevise::find()->submission($pr->submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
                    if (isset($committeeRevise) && $committeeRevise->resolution == 'C') {
                        $title = \Yii::t('app', 'ขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ C– ({0}) เพิ่มเติมบางประเด็น  ', [$pr->submission->project->project_code]);
                    } else {
                        $title = \Yii::t('app', 'ขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ C– {0} ', [$pr->submission->project->project_code]);
                    }
                } else {
                    $title = \Yii::t('app', 'ขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ R– ({0}) ', [$pr->submission->project->project_code]);
                }
            } else {
                $title = \Yii::t('app', 'ขอความอนุเคราะห์ประเมินโครงการ {0} ', [$pr->submission->submissionType->name]);
            }


            $res = $msg->setSubject($title)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($pr->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_SUBMISSION_RESULT) {
            $submission = Submission::findOne($this->model_id);
            $ma = $submission->meetingAgenda;
            
            $co = $submission->project->getProjectCoordinator()->one();
            $co2 = $submission->project->getProjectCoordinator2nd()->one();
            $co3 = $submission->project->getProjectCoordinator3rd()->one();

            $msg = Yii::$app->mailer->compose('submission-result', [
                'submission' => $submission,
                'ma' => $ma,
            ]);
            $msg = $msg->setSubject(\Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์ภายหลังการประชุมครั้งที่ {0}', [isset($ma) ? $ma->meeting->yearNo : ""]))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email);
//            if (isset($co)) {
//                $msg = $msg->setCc($submission->project->projectCoordinator->person->email);
//            }
            if (isset($co) && !isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email));
            } elseif (isset($co) && isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email));
            } elseif (isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            }

            $res = $msg->send();
//            $res = $msg->setSubject(\Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์ภายหลังการประชุมครั้งที่ {0}', [isset($ma) ? $ma->meeting->yearNo : ""]))
//                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
//                    ->setTo($submission->projectLeader->person->email)
//                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_COMMITTEE_C) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-c', [
                'submission' => $submission,
            ]);
            $co = $submission->project->getProjectCoordinator()->one();
            $co2 = $submission->project->getProjectCoordinator2nd()->one();
            $co3 = $submission->project->getProjectCoordinator3rd()->one();

            $msg = $msg->setSubject(\Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email);
            if (isset($co) && !isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email));
            } elseif (isset($co) && isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email));
            } elseif (isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            }
//            if (isset($co)) {
//                $msg = $msg->setCc($submission->project->projectCoordinator->person->email);
//            }

            $res = $msg->send();
//            $res = $msg->setSubject(\Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์'))
//                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
//                    ->setTo($submission->projectLeader->person->email)
//                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFORM_REG_CODE) {
            $p = Person::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('inform-reg-code', [
                'person' => $p,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งได้รับสิทธิ (user) เป็นกรรมการจริยธรรมฯ'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($p->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_COMMITTEE_REASSESS) {
            $sc = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-reassess', [
                'submission' => $sc->submission,
                'sc' => $sc
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งขอให้ประเมินโครงการวิจัยที่ผู้วิจัยแก้ไขตามข้อเสนอแนะแล้ว'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($sc->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_COMMITTEE) {
            $sc = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('inform-committee-assess', [
                'submission' => $sc->submission,
                'sc' => $sc
            ]);
            $res = $msg->setSubject(\Yii::t('app', "ขอติดตามผลการพิจารณาโครงการวิจัย ({$sc->submission->submissionType->name}) {$sc->submission->project->project_code}"))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($sc->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_COMMITTEE_ASSESS) {
            $sc = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-assess', [
                'model' => $sc,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งกรรมการประเมินโครงการแล้ว'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($sc->submission->responsiblePerson->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_DOC_REJECT_BY_STAFF) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('doc-reject-by-staff', [
                'submission' => $submission,
            ]);
            $co = $submission->project->getProjectCoordinator()->one();
            $co2 = $submission->project->getProjectCoordinator2nd()->one();
            $co3 = $submission->project->getProjectCoordinator3rd()->one();

            $msg = $msg->setSubject(\Yii::t('app', 'ขอส่งเอกสารคืนเพื่อแก้ไข'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email);
            if (isset($co) && !isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email));
            } elseif (isset($co) && isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email));
            } elseif (isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            }

//            if (isset($co)) {
//                $msg = $msg->setCc($submission->project->projectCoordinator->person->email);
//            }

            $res = $msg->send();
        } else if ($this->type === self::TYPE_COMMITTEE_ACKNOWLEDGED) {
            $sc = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-acknowledged', [
                'sc' => $sc,
            ]);
//            $res = $msg->setSubject(\Yii::t('app', 'แจ้งกรรมการตอบรับ/ปฏิเสธการอ่านโครงการ'))
//                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
//                    ->setTo($sc->submission->responsiblePerson->person->email)
//                    
//                    ->send();
            $msg = $msg->setSubject(\Yii::t('app', 'แจ้งกรรมการตอบรับ/ปฏิเสธการอ่านโครงการ'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($sc->submission->responsiblePerson->person->email);
            if (isset($sc->submission->secretary_person)) {
                $msg = $msg->setCc($sc->submission->secretaryPerson->person->email);
            }
            $res = $msg->send();
        } else if ($this->type === self::TYPE_SECRETARY_SELECTED) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('secretary-selected', [
                'submission' => $submission,
            ]);
            $email = $submission->responsiblePerson->person->email;
            if (isset($submission->secretaryPerson)) {
                $email = $submission->secretaryPerson->person->email;
            }
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งขอให้เลือกประเภทการพิจารณา'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($email)
                    ->send();
        } else if ($this->type === self::TYPE_RESET_PASSWORD) {
            $user = User::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('reset-password', [
                'user' => $user,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'ตั้งรหัสผ่านใหม่'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($user->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_INFO_PRE_CHECK_MEETING) {
            $meeting = Meeting::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('info-pre-check-meeting', [
                'meeting' => $meeting,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องให้ประธานทำการตรวจสอบวาระการประชุม') . $meeting->fullName)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($meeting->checkedPresident->person->email)
                    ->setCc($meeting->checkedStaff->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFO_SECRETARY_CHECK_MEETING) {
            $meeting = Meeting::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('info-secretary-check-meeting', [
                'meeting' => $meeting,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องให้เลขาทำการตรวจสอบวาระการประชุม') . $meeting->fullName)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($meeting->checkedSecretaryFirst->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFO_SECRETARY_2_CHECK_MEETING) {
            $meeting = Meeting::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('info-secretary-2-check-meeting', [
                'meeting' => $meeting,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องให้เลขาท่านที่ 2 ทำการตรวจสอบวาระการประชุม') . $meeting->fullName)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($meeting->checkedSecretarySecond->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFORM_PROJECT_CODE) {
            $submission = Submission::findOne($this->model_id);
            $ld = ProjectResearcher::find()->isDeleted(FALSE)->isLeader()->submission($this->model_id)->one();
            $title = '';
            $projecCode = isset($submission->project->project_code) ? $submission->project->project_code : "";
            if ($submission->submission_type_id == SubmissionType::TYPE_CREC) {
                $title = \Yii::t('app', "แจ้งเลขที่โครงการวิจัยที่ MOU กับ CREC ({$projecCode})");
                $msg = Yii::$app->mailer->compose('inform-project-code-crec', [
                    'submission' => $submission,
                    'ld' => $ld
                ]);
            } else {
                $title = \Yii::t('app', 'แจ้งเลขที่โครงการ');
                $msg = Yii::$app->mailer->compose('inform-project-code', [
                    'submission' => $submission,
                    'ld' => $ld
                ]);
            }
            $res = $msg->setSubject($title)
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($ld->person->email)
                    ->send();
//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFO_STAFF_UPLOAD_RESULT_AGENDA) {
            $meeting = Meeting::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('info-staff-upload-result-agenda', [
                'meeting' => $meeting,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องให้เจ้าหน้าที่ทำการ Upload ผลการพิจารณาในแต่ละโครงการได้เลย'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($meeting->checkedStaff->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFORM_STAFF_OVER30DAY) {
            $submission = Submission::findOne($this->model_id);
            $co = $submission->project->getProjectCoordinator()->one();
            $co2 = $submission->project->getProjectCoordinator2nd()->one();
            $co3 = $submission->project->getProjectCoordinator3rd()->one();

            $msg = Yii::$app->mailer->compose('inform-over30day', [
                'submission' => $submission,
            ]);
            $msg = $msg->setSubject(\Yii::t('app', "ขอติดตามการแก้ไข/ชี้แจง ภายหลังการประชุมพิจารณาโครงการวิจัยเลขที่โครงการ ({$submission->project->project_code})"))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->projectLeader->person->email);
            if (isset($co) && !isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email));
            } elseif (isset($co) && isset($co2) && !isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email));
            } elseif (isset($co) && isset($co2) && isset($co3)) {
                $msg = $msg->setCc(array($submission->project->projectCoordinator->person->email, $submission->project->projectCoordinator2nd->person->email, $submission->project->projectCoordinator3rd->person->email));
            }
//            if (isset($co)) {
//                $msg = $msg->setCc($submission->project->projectCoordinator->person->email);
//            }

            $res = $msg->send();
        } else if ($this->type === self::TYPE_INFORM_STAFF_RESULT) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('staff-result-doc', [
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเจ้าหน้าที่จัดเตรียมและส่งหนังสือแจ้งผลให้นักวิจัย'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->responsiblePerson->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFORM_STAFF_C_OVER90DAY) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('inform-staff-c-over90day', [
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งให้เจ้าหน้าที่ทำการกำหนดวาระการประชุมให้สำหรับโครงการที่ไม่มีการแก้ไขภายในระยะเวลา 90 วัน (ผลการพิจารณา C)'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->responsiblePerson->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_INFORM_STAFF_R_OVER90DAY) {
            $submission = Submission::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('inform-staff-r-over90day', [
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งให้เจ้าหน้าที่ทำการกำหนดวาระการประชุมให้สำหรับโครงการที่ไม่มีการแก้ไขภายในระยะเวลา 90 วัน (ผลการพิจารณา R)'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($submission->responsiblePerson->person->email)
                    ->send();

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_COMMITTEE_ASSESS_DUE) {
            $committee = SubmissionCommittee::findOne($this->model_id);
            $msg = Yii::$app->mailer->compose('committee-assess-due', [
                'submission' => $committe->submission,
            ]);

//            $docs = $pr->submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
//            foreach ($docs as $doc) {
//                $msg->attach($doc->filePath, ['fileName' => $doc->name]);
//            }
//            \yii\helpers\VarDumper::dump($msg);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งครบกำหนดส่งผลประเมินโครงการวิจัย'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($committee->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_CO_RESEARCHER_ACK_REMINDER) {
            $pr = ProjectResearcher::findOne($this->model_id);
            $pendingCoResearchers = $pr->submission->getPendingProjectCoResearchers();
            $msg = Yii::$app->mailer->compose('co-researcher-ack-reminder-new', [
                'pr' => $pr,
                'pendingCoResearchers' => $pendingCoResearchers,
            ]);

//            $docs = $pr->submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
//            foreach ($docs as $doc) {
//                $msg->attach($doc->filePath, ['fileName' => $doc->name]);
//            }
//            \yii\helpers\VarDumper::dump($msg);
            $res = $msg->setSubject('แจ้งเตือนยังไม่ส่งโครงการ')
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($pr->person->email)
                    ->send();
        } else if ($this->type === self::TYPE_PROGRESS_REMINDER) {
            $submission = Submission::findOne($this->model_id);
            $leader = $submission->projectLeader;
            $msg = Yii::$app->mailer->compose('progress-reminder', [
                'person' => $leader->person,
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนรายงานความก้าวหน้าโครงการ'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($leader->person->email)
                    ->send();
            if (isset($submission->project_coordinator_id)) {
                $msg = Yii::$app->mailer->compose('progress-reminder', [
                    'person' => $submission->projectCoordinator->person,
                    'submission' => $submission,
                ]);
                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนรายงานความก้าวหน้าโครงการ'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($submission->project->projectCoordinator->person->email)
                        ->send();
            }

            $consultants = $submission->getProjectConsultants()->isDeleted(false)
                            ->acknowledgeStatus(ProjectConsultant::STATUS_ACCEPTED)->all();
            foreach ($consultants as $c) {
                $msg = Yii::$app->mailer->compose('progress-reminder', [
                    'person' => $c->person,
                    'submission' => $submission,
                ]);
                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนรายงานความก้าวหน้าโครงการ'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($c->person->email)
                        ->send();
            }

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_RENEW_REMINDER) {
            $submission = Submission::findOne($this->model_id);
            $leader = $submission->projectLeader;
            $msg = Yii::$app->mailer->compose('renew-reminder', [
                'person' => $leader->person,
                'submission' => $submission,
            ]);
            $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนการต่ออายุโครงการ'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($leader->person->email)
                    ->send();
            if (isset($submission->project_coordinator_id)) {
                $msg = Yii::$app->mailer->compose('renew-reminder', [
                    'person' => $submission->projectCoordinator->person,
                    'submission' => $submission,
                ]);
                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนการต่ออายุโครงการ'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($submission->project->projectCoordinator->person->email)
                        ->send();
            }

            $consultants = $submission->getProjectConsultants()->isDeleted(false)
                            ->acknowledgeStatus(ProjectConsultant::STATUS_ACCEPTED)->all();
            foreach ($consultants as $c) {
                $msg = Yii::$app->mailer->compose('renew-reminder', [
                    'person' => $c->person,
                    'submission' => $submission,
                ]);
                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนการต่ออายุโครงการ'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($c->person->email)
                        ->send();
            }

//            \yii\helpers\VarDumper::dump($res);
        } else if ($this->type === self::TYPE_ALL_RESEARCHERS_ACKNOWLEDGED) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $submission = Submission::findOne($this->model_id);
            $leader = $submission->projectLeader;
            $msg = Yii::$app->mailer->compose('all-researchers-acknowledged', [
                'person' => $leader->person,
                'submission' => $submission,
            ]);
            $msg = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนขอให้เข้าระบบเพื่อยืนยันการส่งโครงการ'))
                    ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                    ->setTo($leader->person->email);
            if (isset($submission->project_coordinator_id)) {
                $msg = $msg->setCc($submission->project->projectCoordinator->person->email);
            }
            $res = $msg->send();
        } else if ($this->type === self::TYPE_NEW_SUBMISSION_CREC) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $email = Setting::getValue(Setting::CREC_RESPONSIBLE_PERSON_EMAIL);
            if (empty($email)) {
                $res = true;
            } else {
                $submission = Submission::findOne($this->model_id);
                $msg = Yii::$app->mailer->compose('new-submission-crec', [
                    'submission' => $submission,
                ]);

                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องการยื่นเสนอโครงการ CREC MOU ใหม่'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($email)
                        ->send();
            }
        } else if ($this->type === self::TYPE_UPDATE_RESULT_DOCUMENT_CREC) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $email = Setting::getValue(Setting::CREC_RESPONSIBLE_PERSON_EMAIL);
            if (empty($email)) {
                $res = true;
            } else {
                $submission = Submission::findOne($this->model_id);
                $msg = Yii::$app->mailer->compose('update-result-document-crec', [
                    'submission' => $submission,
                ]);

                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องได้รับหนังสือแจ้งผลการพิจารณาโครงการ CREC MOU'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($email)
                        ->send();
            }
        } else if ($this->type === self::TYPE_UPDATE_RESOLUTION_CREC) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $email = Setting::getValue(Setting::CREC_RESPONSIBLE_PERSON_EMAIL);
            if (empty($email)) {
                $res = true;
            } else {
                $submission = Submission::findOne($this->model_id);
                $msg = Yii::$app->mailer->compose('update-resolution-crec', [
                    'submission' => $submission,
                ]);

                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องได้มติที่ประชุมการพิจารณาโครงการ CREC MOU'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($email)
                        ->send();
            }
        } else if ($this->type === self::TYPE_RESUBMIT_LOCAL_ISSUE_CREC) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $email = Setting::getValue(Setting::CREC_RESPONSIBLE_PERSON_EMAIL);
            if (empty($email)) {
                $res = true;
            } else {
                $submission = Submission::findOne($this->model_id);
                $msg = Yii::$app->mailer->compose('resubmit-local-issue-crec', [
                    'submission' => $submission,
                ]);

                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องขอให้ประเมิน Local Issue โครงการ CREC MOU ใหม่'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($email)
                        ->send();
            }
        } else if ($this->type === self::TYPE_NOTIFY_CREC_RESULT_LEADER) {
            //            $this->ack_token = Yii::$app->util->generateToken();
            //            $this->save(FALSE);
            $submission = Submission::findOne($this->model_id);
            $leader = $submission->getProjectLeader();
            if (isset($leader->person->email)) {
                $msg = Yii::$app->mailer->compose('update-result-document-crec', [
                    'submission' => $submission,
                ]);

                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเรื่องได้รับหนังสือแจ้งผลการพิจารณาโครงการ CREC MOU'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($leader->person->email)
                        ->send();
            }
        } else if ($this->type === self::TYPE_TRAINING_EXPIRE_REMINDER) {
            $training = PersonTraining::findOne($this->model_id);
            // Guard against deleted training / missing person or email.
            if (!isset($training) || $training->deleted == 1 || !isset($training->person) || empty($training->person->email)) {
                $res = true; // nothing to send; mark as processed
            } else {
                $daysLeft = null;
                if (!empty($training->expire_date)) {
                    $daysLeft = (int) floor((strtotime($training->expire_date) - strtotime(date('Y-m-d'))) / 86400);
                }
                $msg = Yii::$app->mailer->compose('training-expire-reminder', [
                    'person' => $training->person,
                    'training' => $training,
                    'daysLeft' => $daysLeft,
                ]);
                $res = $msg->setSubject(\Yii::t('app', 'แจ้งเตือนเอกสารการอบรมใกล้หมดอายุ'))
                        ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                        ->setTo($training->person->email)
                        ->send();
            }
        }
        if ($res) {
            $this->mail_at = date('Y-m-d H:i:s');
            $this->save(FALSE);
        }
        return $res;
    }

}
