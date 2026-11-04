<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project_consultant".
 *
 * @property int $id รหัสหน่วยงาน
 * @property int $person_id ที่ปรึกษา
 * @property int $project_id โครงการวิจัย
 * @property int $submission_id submission
 * @property int $mail_sent ส่งอีเมล์หรือยัง
 * @property string $mail_sent_at ส่งเมล์เมื่อ
 * @property int $acknowledge_status ผลตอบรับร่วมวิจัย
 * @property int $acknowledge_by ผู้ตอบรับ
 * @property string $acknowledge_at ตอบรับเมื่อ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $acknowledgeBy
 * @property Person $person
 * @property Project $project
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionProjectConsultant[] $submissionProjectConsultants
 */
class ProjectConsultant extends \yii\db\ActiveRecord {

    const STATUS_PENDING_ACK = 100;
    const STATUS_REJECTED = 200;
    const STATUS_ACCEPTED = 300;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'project_consultant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['person_id', 'project_id', 'submission_id', 'mail_sent', 'acknowledge_status', 'acknowledge_by', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['mail_sent_at', 'acknowledge_at', 'created_at', 'updated_at'], 'safe'],
            [['acknowledge_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['acknowledge_by' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'person_id' => Yii::t('app', 'ที่ปรึกษา'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
            'submission_id' => Yii::t('app', 'submission'),
            'mail_sent' => Yii::t('app', 'การส่งอีเมลเพื่อยืนยันเป็นที่ปรึกษา'),
            'mail_sent_at' => Yii::t('app', 'ส่งเมล์เมื่อ'),
            'acknowledge_status' => Yii::t('app', 'การตอบรับเป็นที่ปรึกษา'),
            'acknowledge_by' => Yii::t('app', 'ผู้ตอบรับ'),
            'acknowledge_at' => Yii::t('app', 'ตอบรับเมื่อ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'acknowledgeAtLabel' => Yii::t('app', 'ตอบรับเมื่อ'),
            'documentResearcherStatusLabel' => yii::t('app', 'สถานะ'),
            'mailSentLabel' => Yii::t('app', 'การส่งอีเมลเพื่อยืนยันเป็นที่ปรึกษา'),
            'acknowledgeStatusLabel' => Yii::t('app', 'การตอบรับเป็นที่ปรึกษา'),
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
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ack_token'],
                ],
                'value' => function ($event) {
                    return \Yii::$app->util->generateToken();
                },
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcknowledgeBy() {
        return $this->hasOne(User::className(), ['id' => 'acknowledge_by']);
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
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionProjectConsultants() {
        return $this->hasMany(SubmissionProjectConsultant::className(), ['project_consultant_id' => 'id']);
    }

    public function getMailSentLabel() {
        return $this->mail_sent ? yii::t('app', 'ส่งแล้ว') : yii::t('app', 'ยังไม่ส่ง');
    }

    public function getAcknowledgeStatusLabel() {
        return isset($this->acknowledge_status) ? $this->getAckStatusLabels()[$this->acknowledge_status] : '';
    }

    /**
     * {@inheritdoc}
     * @return ProjectConsultantQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectConsultantQuery(get_called_class());
    }

    public function getAcknowledgeAtLabel() {
        if (!isset($this->acknowledge_at)) {
            return "";
        }
        $res = Yii::$app->formatter->asDatetime($this->acknowledge_at);
        if (!isset($this->acknowledgeBy)) {
            $by = Yii::t('app', 'ตอบรับโดยระบบ');
            $res .= " ({$by})";
        }
        return $res;
    }

    public function sendAcknowledgeMail($submission) {
        $this->ack_token = Yii::$app->util->generateToken();
        $this->save(FALSE);
        $msg = Yii::$app->mailer->compose('consultant-acknowledge', [
            'submission' => $submission,
            'consultant' => $this,
        ]);
        $docs = $submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
        foreach ($docs as $doc) {
            $msg->attach($doc->filePath, ['fileName' => $doc->name]);
        }
        $msg->setSubject(\Yii::t('app', 'ตอบรับการร่วมวิจัย'))
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->params['adminName']])
                ->setTo($this->person->email)
                ->send();

        $this->mail_sent = 1;
        $this->mail_sent_at = date('Y-m-d H:i:s');
        $this->save(FALSE);
    }

    public function getPreviousProjectConsultant() {
        return ProjectConsultant::find()->isDeleted(FALSE)->project($this->project_id)->person($this->person_id)->andWhere(['<', 'id', $this->id])->orderBy('id DESC')->one();
    }

    public static function getAckStatusLabels() {
        return [
            self::STATUS_PENDING_ACK => \Yii::t('app', 'รอการตอบรับ'),
            self::STATUS_REJECTED => \Yii::t('app', 'ปฏิเสธ'),
            self::STATUS_ACCEPTED => \Yii::t('app', 'ตกลง'),
        ];
    }

    public function addCoi() {
        $people = Person::find()->joinWith(['user', 'personRoles'])->isDeleted(false)->active()
                ->role([Role::COMMITTEE])
                ->lastName($this->person->last_name, $this->person->last_name_eng)
                ->all();
        foreach ($people as $p) {
            $coi = SubmissionCoiPerson::find()->isDeleted(false)->submission($this->submission_id)->person($p->id)->one();
            if (!isset($coi)) {
                $model = new SubmissionCoiPerson();
                $model->submission_id = $this->submission_id;
                $model->person_id = $p->id;
                $model->save();
            }
        }
    }

    public function deleteCoi() {
        $scps = SubmissionCoiPerson::find()->isDeleted(false)->submission($this->submission_id)->person($this->person_id)->all();
        foreach ($scps as $scp) {
            $scp->deleted = 1;
            $scp->save();
        }
    }

}
