<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_type".
 *
 * @property int $id รหัสประเภทการขอรับพิจารณา
 * @property string $name ประเภทการขอรับพิจารณา
 * @property int $is_new เป็นโครงการใหม่หรือไม่   0=ไม่ 1=ใช่
 * @property int $is_fullboard เป็น Fullboard หรือไม่ 0=ไม่ 1=ใช่
 * @property int $is_exemption เป็น exemption หรือไม่ 0=ไม่ 1=ใช่
 * @property string $agenda_title หัวข้อวาระ
 * @property int $submission_type_group_id ประเภทกลุ่มกานำเสนอโครงการวิจัย
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $payment ค่าตอบแทน
 * @property int $internal ใช้ภายในเท่านั้น
 * @property string $resolution_label รับรองหรือรับทราบ
 * @property int $meeting_consideration ต้องพิจารณาเพื่อเข้าที่ประชุม 
 * @property int $risk_assessment ประเมินความเสี่ยงหรือไม่ 
 * @property int $progress ระบุระยะเวลารายงานความก้าวหน้า 
 * @property int $certify ออกการรับรอง 
 * @property string $resolution มติที่ประชุมครั้งก่อน
 * @property int $committee_count จำนวนกรรมการที่ต้องอ่าน
 * @property int $add_subject ต้องเพิ่มเรื่อง 
 * @property int $close ปิดโครงการ 
 * @property int $review_choice_id วาระการประชุม
 * @property int $crec_id รหัสประเภทการส่งโครงการในระบบ CREC
 *
 * @property Agenda[] $agendas
 * @property QuestionnaireTitle[] $questionnaireTitles 
 * @property AgendaSubmissionType[] $agendaSubmissionTypes 
 * @property DocumentSubmissionType[] $documentSubmissionTypes
 * @property PersonSubmissionType[] $personSubmissionTypes
 * @property Submission[] $submissions
 * @property SubmissionTypeGroup $submissionTypeGroup
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionTypeExtraInfo[] $submissionTypeExtraInfos
 * @property SubmissionTypeVolunteerNumber[] $submissionTypeVolunteerNumbers
 * @property SubmissionTypeAssessForm[] $submissionTypeAssessForms
 * @property SubmissionTypeDuration[] $submissionTypeDurations
 * @property ReviewChoice $reviewChoice
 */
class SubmissionType extends \yii\db\ActiveRecord {

    const RES_ENDORSE = 'รับรอง';
    const RES_ACKNOWLEDGE = 'รับทราบ';
    const FORM_CONTINUE = 1;
    const FORM_SAE = 2;
    const FORM_C = 3;
    const FORM_DEVIATION = 4;
    const DURATION_APPROVE_TO_ENDORSE = 1;
    const DURATION_APPROVE_TO_MEETING = 2;
    const DURATION_MEETING_TO_ENDORSE = 3;
    
    const TYPE_PROGRESS = 7;
    const TYPE_RENEW = 8;
    const TYPE_AMENDMENT = 9;
    const TYPE_EXTERNAL_SAE = 11;
    const TYPE_CLOSING = 13;
    const TYPE_OTHER = 15;
    const TYPE_IDMC = 20;
    const TYPE_CREC = 19;
    const TYPE_C = 5;
    const TYPE_R = 6;
    
    const TYPE_INTERNAL_SAE = 10;
    const TYPE_DEVIATION = 12;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['is_new', 'is_fullboard', 'is_exemption', 'submission_type_group_id', 'deleted', 'created_by', 'updated_by', 'internal', 'meeting_consideration', 'risk_assessment', 'progress', 'certify', 'committee_count', 'add_subject', 'close', 'review_choice_id', 'crec_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment'], 'number'],
            [['name', 'agenda_title', 'resolution_label', 'resolution', 'name_eng', 'resolution_label_eng'], 'string', 'max' => 255],
            [['submission_type_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionTypeGroup::className(), 'targetAttribute' => ['submission_type_group_id' => 'id']],
            [['review_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['review_choice_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสประเภทการขอรับพิจารณา'),
            'name' => Yii::t('app', 'ประเภทการขอรับพิจารณา'),
            'name_eng' => Yii::t('app', 'ประเภทการขอรับพิจารณา(ภาษาอังกฤษ)'),
            'is_new' => Yii::t('app', 'เป็นโครงการใหม่หรือไม่   0=ไม่ 1=ใช่'),
            'is_fullboard' => Yii::t('app', 'เป็น Fullboard หรือไม่ 0=ไม่ 1=ใช่'),
            'is_exemption' => Yii::t('app', 'เป็น exemption หรือไม่ 0=ไม่ 1=ใช่'),
            'agenda_title' => Yii::t('app', 'หัวข้อวาระ'),
            'submission_type_group_id' => Yii::t('app', 'ประเภทกลุ่มกานำเสนอโครงการวิจัย'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'payment' => Yii::t('app', 'ค่าตอบแทน'),
            'internal' => Yii::t('app', 'ใช้ภายในเท่านั้น'),
            'resolution_label' => Yii::t('app', 'รับรองหรือรับทราบ'),
            'resolution_label_eng' => Yii::t('app', 'รับรองหรือรับทราบ(ภาษาอังกฤษ)'),
            'meeting_consideration' => Yii::t('app', 'ต้องพิจารณาเพื่อเข้าที่ประชุม'),
            'risk_assessment' => Yii::t('app', 'ประเมินความเสี่ยงหรือไม่'),
            'progress' => Yii::t('app', 'ระบุระยะเวลารายงานความก้าวหน้า'),
            'certify' => Yii::t('app', 'ออกการรับรอง'),
            'resolution' => Yii::t('app', 'มติที่ประชุมครั้งก่อน'),
            'committee_count' => Yii::t('app', 'จำนวนกรรมการที่ต้องอ่าน'),
            'add_subject' => Yii::t('app', 'ต้องเพิ่มเรื่อง'),
            'close' => Yii::t('app', 'ปิดโครงการ'),
            'review_choice_id' => Yii::t('app', 'วาระการประชุม'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewChoice() {
        return $this->hasOne(ReviewChoice::className(), ['id' => 'review_choice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendas() {
        return $this->hasMany(Agenda::className(), ['submission_type_id' => 'id']);
    }

    public function getAgendaSubmissionTypes() {
        return $this->hasMany(AgendaSubmissionType::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentSubmissionTypes() {
        return $this->hasMany(DocumentSubmissionType::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonSubmissionTypes() {
        return $this->hasMany(PersonSubmissionType::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissions() {
        return $this->hasMany(Submission::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionTypeGroup() {
        return $this->hasOne(SubmissionTypeGroup::className(), ['id' => 'submission_type_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingRequirements() {
        return $this->hasMany(SubmissionTypeTrainingRequirement::className(), ['submission_type_id' => 'id'])
                        ->andWhere(['submission_type_training_requirement.deleted' => 0]);
    }

    /**
     * Required training categories for this submission type, excluding NA.
     *
     * @return array map of [category => rule] where rule is
     *   SubmissionTypeTrainingRequirement::RULE_REQUIRED | RULE_ANY_OF
     */
    public function getRequiredTrainingCategories() {
        $out = [];
        foreach ($this->trainingRequirements as $req) {
            if ((int) $req->rule !== SubmissionTypeTrainingRequirement::RULE_NA) {
                $out[(int) $req->category] = (int) $req->rule;
            }
        }
        return $out;
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
    public function getSubmissionTypeExtraInfos() {
        return $this->hasMany(SubmissionTypeExtraInfo::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionTypeVolunteerNumbers() {
        return $this->hasMany(SubmissionTypeVolunteerNumber::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionTypeAssessForms() {
        return $this->hasMany(SubmissionTypeAssessForm::className(), ['submission_type_id' => 'id']);
    }

    public function getRequireDocumentSubmissTypes() {
        return $this->getDocumentSubmissionTypes()->isDeleted(FALSE)->andWhere(['is_require' => TRUE])->role(Role::RESEARCHER)->all();
    }

    public function getRequireCommitteeDocumentSubmissTypes($position, $submission = null) {
        $query = $this->getDocumentSubmissionTypes()->isDeleted(FALSE)->andWhere(['is_require' => TRUE])->role(Role::COMMITTEE)->committeePosition($position);
        if (isset($submission) && isset($submission->ref_submission_id)) {
            $firstRef = $submission->getFirstRefSubmission();
            $query->refSubmissionType($firstRef->submission_type_id);
        }
        return $query->all();
    }

    public function getQuestionnaireTitles() {
        return $this->hasMany(QuestionnaireTitle::className(), ['submission_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionTypeDurations() {
        return $this->hasMany(SubmissionTypeDuration::className(), ['submission_type_id' => 'id']);
    }

    public function getAgendaLabelList() {
        $asts = $this->getAgendaSubmissionTypes()->isDeleted(FALSE)->all();
        $res = '<ul>';
        foreach ($asts as $ast) {
            $res .= "<li>{$ast->agenda->label}</li>";
        }
        $res .= '</ul>';
        return $res;
    }

    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    public function getI18ResolutionLabel() {
        $attr = 'resolution_label' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    public function getResolutions() {
        $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
        foreach ($resolutions as $res) {
            if ($this->resolution_label == self::RES_ENDORSE) {
                $res->name = str_replace(self::RES_ACKNOWLEDGE . '/', '', $res->name);
            } else if ($this->resolution_label == self::RES_ACKNOWLEDGE) {
                $res->name = str_replace('/' . self::RES_ENDORSE, '', $res->name);
            }
        }
        return $resolutions;
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionTypeQuery(get_called_class());
    }

    public static function durationTypeLabels() {
        return [
            self::DURATION_APPROVE_TO_ENDORSE => Yii::t('app', 'รับถึงส่งใบรับรอง'),
            self::DURATION_APPROVE_TO_MEETING => Yii::t('app', 'รับถึงเข้าประชุม'),
            self::DURATION_MEETING_TO_ENDORSE => Yii::t('app', 'หลังประชุมถึงแจ้งผล'),
        ];
    }

    public static function durationTypeFunction() {
        return [
            self::DURATION_APPROVE_TO_ENDORSE => 'approveToEndorseDays',
            self::DURATION_APPROVE_TO_MEETING => 'approveToMeetingDays',
            self::DURATION_MEETING_TO_ENDORSE => 'meetingToEndorseDays',
        ];
    }

}
