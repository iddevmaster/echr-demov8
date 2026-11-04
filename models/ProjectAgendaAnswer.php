<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_agenda_answer".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $project_id โครงการ
 * @property int $submission_id submission
 * @property int $project_question_id คำถาม
 * @property int $project_type_id คำตอบ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property Project $project
 * @property ProjectQuestion $projectQuestion
 * @property ProjectType $projectType
 * @property Submission $submission
 * @property User $updatedBy
 */
class ProjectAgendaAnswer extends \yii\db\ActiveRecord {

    public $choices;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'project_agenda_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_id', 'submission_id', 'project_question_id', 'project_type_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'choices'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['project_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectQuestion::className(), 'targetAttribute' => ['project_question_id' => 'id']],
            [['project_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectType::className(), 'targetAttribute' => ['project_type_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'project_id' => Yii::t('app', 'โครงการ'),
            'submission_id' => Yii::t('app', 'submission'),
            'project_question_id' => Yii::t('app', 'คำถาม'),
            'project_type_id' => Yii::t('app', 'คำตอบ'),
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
    public function getProject() {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectQuestion() {
        return $this->hasOne(ProjectQuestion::className(), ['id' => 'project_question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectType() {
        return $this->hasOne(ProjectType::className(), ['id' => 'project_type_id']);
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
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectAgendaAnswerQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectAgendaAnswerQuery(get_called_class());
    }

}
