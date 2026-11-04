<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "questionnaire_answer".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $submission_committee_id กรรมการส่งประเมิน
 * @property int $submission_id ส่งประเมิน
 * @property int $questionnaire_title_id หัวข้อแบบสอบถาม
 * @property int $questionnaire_choice_id ตัวเลือกแบบสอบถาม
 * @property string $text_answer คำตอบสำหรับ Text
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property QuestionnaireChoice $questionnaireChoice
 * @property QuestionnaireTitle $questionnaireTitle
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class QuestionnaireAnswer extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    private $_choices;

    public static function tableName() {
        return 'questionnaire_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['submission_committee_id', 'submission_id', 'questionnaire_title_id', 'questionnaire_choice_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['text_answer'], 'string'],
            [['created_at', 'updated_at', 'choices'], 'safe'],
            [['questionnaire_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionnaireChoice::className(), 'targetAttribute' => ['questionnaire_choice_id' => 'id']],
            [['questionnaire_title_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionnaireTitle::className(), 'targetAttribute' => ['questionnaire_title_id' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'submission_committee_id' => Yii::t('app', 'กรรมการส่งประเมิน'),
            'submission_id' => Yii::t('app', 'ส่งประเมิน'),
            'questionnaire_title_id' => Yii::t('app', 'หัวข้อแบบสอบถาม'),
            'questionnaire_choice_id' => Yii::t('app', 'ตัวเลือกแบบสอบถาม'),
            'text_answer' => Yii::t('app', 'คำตอบสำหรับ Text'),
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
    public function getQuestionnaireChoice() {
        return $this->hasOne(QuestionnaireChoice::className(), ['id' => 'questionnaire_choice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireTitle() {
        return $this->hasOne(QuestionnaireTitle::className(), ['id' => 'questionnaire_title_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittee() {
        return $this->hasOne(SubmissionCommittee::className(), ['id' => 'submission_committee_id']);
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

    public function setChoices($choices) {
        $this->_choices = $choices;
    }

    public function getChoices() {
        return $this->_choices;
    }

    public function getFullAnswer() {
        $answer = isset($this->questionnaireChoice) ? $this->questionnaireChoice->title . " " : "";
        $answer .= isset($this->text_answer) ? $this->text_answer : "";
        return $answer;
    }

    /**
     * @inheritdoc
     * @return QuestionnaireAnswerQuery the active query used by this AR class.
     */
    public static function find() {
        return new QuestionnaireAnswerQuery(get_called_class());
    }

}
