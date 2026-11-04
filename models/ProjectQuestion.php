<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project_question".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $name คำถาม
 * @property int $answer_type 1=single choice, 2=multi choices
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ProjectAgendaAnswer[] $projectAgendaAnswers
 * @property ProjectAgendaQuestion[] $projectAgendaQuestions
 * @property User $createdBy
 * @property User $updatedBy
 * @property ProjectQuestionChoice[] $projectQuestionChoices
 */
class ProjectQuestion extends \yii\db\ActiveRecord {

    const TYPE_SINGLE_CHOICE = 1;
    const TYPE_MULTI_CHOICES = 2;
    
    public $agendaIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'project_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['answer_type', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'agendaIds'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'name' => Yii::t('app', 'คำถาม'),
            'answer_type' => Yii::t('app', 'ประเภทคำตอบ'),
            'answerTypeLabel' => Yii::t('app', 'ประเภทคำตอบ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedBy.person.fullName' => Yii::t('app', 'ปรับปรุงโดย'),
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
    
    public function afterFind() {
        parent::afterFind();
        $this->agendaIds = ArrayHelper::getColumn($this->getProjectAgendaQuestions()->isDeleted(FALSE)->orderBy('agenda_id')->all(), 'agenda_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectAgendaAnswers() {
        return $this->hasMany(ProjectAgendaAnswer::className(), ['project_question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectAgendaQuestions() {
        return $this->hasMany(ProjectAgendaQuestion::className(), ['project_question_id' => 'id']);
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
    public function getProjectQuestionChoices() {
        return $this->hasMany(ProjectQuestionChoice::className(), ['project_question_id' => 'id']);
    }

    public function getAnswerTypeLabel() {
        return ProjectQuestion::answerTypeLabels()[$this->answer_type];
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuestionQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectQuestionQuery(get_called_class());
    }

    public static function answerTypeLabels() {
        return [
            self::TYPE_SINGLE_CHOICE => \Yii::t('app', 'หนึ่งตัวเลือกเท่านั้น'),
            self::TYPE_MULTI_CHOICES => \Yii::t('app', 'หลายตัวเลือก'),
        ];
    }

}
