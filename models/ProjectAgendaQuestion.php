<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_agenda_question".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $agenda_id วาระ
 * @property int $project_question_id คำถาม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property Agenda $agenda
 * @property ProjectQuestion $projectQuestion
 */
class ProjectAgendaQuestion extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'project_agenda_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['agenda_id', 'project_question_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['agenda_id' => 'id']],
            [['project_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectQuestion::className(), 'targetAttribute' => ['project_question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'agenda_id' => Yii::t('app', 'วาระ'),
            'project_question_id' => Yii::t('app', 'คำถาม'),
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
    public function getAgenda() {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectQuestion() {
        return $this->hasOne(ProjectQuestion::className(), ['id' => 'project_question_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectAgendaQuestionQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectAgendaQuestionQuery(get_called_class());
    }

}
