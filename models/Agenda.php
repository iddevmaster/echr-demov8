<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property int $id รหัสองค์กร
 * @property string $name หัวข้อวาระการประชุม
 * @property int $submission_type_id ประเภท submission
 * @property string $label label
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $sort
 * @property int $parent_id
 * @property int $addable สามารถเพิ่มวาระได้ 
 * @property int $is_submission เป็นวาระเกี่ยวกับการส่งโครงการ
 * @property int $need_resolution ต้องระบุมติ
 * @property int $project_active
 *
 * @property Agenda $parent
 * @property Agenda[] $agendas
 * @property SubmissionType $submissionType
 * @property User $createdBy
 * @property User $updatedBy
 * @property MeetingAgenda[] $meetingAgendas
 * @property AgendaAutoDesc[] $agendaAutoDescs 
 * @property AgendaSubmissionType[] $agendaSubmissionTypes 
 * @property AgendaResultDocument[] $agendaResultDocuments 
 * @property ProjectAgendaQuestion[] $projectAgendaQuestions
 */
class Agenda extends \yii\db\ActiveRecord {

    const PREVIOUS_MINUTES = 1;
    const ANNOUNCEMENT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'agenda';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['submission_type_id', 'deleted', 'created_by', 'updated_by', 'sort', 'parent_id', 'addable', 'is_submission', 'need_resolution', 'project_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'label'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสองค์กร'),
            'name' => Yii::t('app', 'หัวข้อวาระการประชุม'),
            'submission_type_id' => Yii::t('app', 'ประเภท submission'),
            'label' => Yii::t('app', 'label'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'sort' => Yii::t('app', 'Sort'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'addable' => Yii::t('app', 'สามารถเพิ่มวาระได้'),
            'is_submission' => Yii::t('app', 'เป็นวาระเกี่ยวกับการส่งโครงการ'),
            'need_resolution' => Yii::t('app', 'ต้องระบุมติ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectAgendaQuestions() {
        return $this->hasMany(ProjectAgendaQuestion::className(), ['agenda_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Agenda::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendas() {
        return $this->hasMany(Agenda::className(), ['parent_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendas() {
        return $this->hasMany(MeetingAgenda::className(), ['agenda_id' => 'id']);
    }

    public function getAgendaAutoDescs() {
        return $this->hasMany(AgendaAutoDesc::className(), ['agenda_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendaSubmissionTypes() {
        return $this->hasMany(AgendaSubmissionType::className(), ['agenda_id' => 'id']);
    }

    public function getAgendaResultDocuments() {
        return $this->hasMany(AgendaResultDocument::className(), ['agenda_id' => 'id']);
    }

    public function getFullName() {
        return "{$this->label} {$this->name}";
    }

    /**
     * @inheritdoc
     * @return AgendaQuery the active query used by this AR class.
     */
    public static function find() {
        return new AgendaQuery(get_called_class());
    }

    public static function getCoiAgendaTitle() {
        return \Yii::t('app', 'การประกาศผลประโยชน์ทับซ้อนของกรรมการในการพิจารณาโครงการวิจัยการประชุมเพื่อขอรับรองจริยธรรมการวิจัยในมนุษย์ครั้งนี้ มีกรรมการที่เข้าร่วมประชุมพิจารณาโครงการวิจัย ที่มีผลประโยชน์ทับซ้อน ดังนี้');
    }

    public static function getExpeditedAgendas() {
        return ['3.3', '3.4', '3.9'];
    }
    
    public static function getFullboardAgendas() {
        return ['4.2', '4.3'];
    }

    public static function getInitialAgendas() {
        return ['4.1', '4.4'];
    }

    public static function getContinueAgendas() {
        return ['3.1', '3.2', '3.5', '3.6', '3.7', '3.8', '4.5', '4.6', '4.7', '4.8'];
    }

    public static function getAgendaColor() {
        return [
            'expedited' => '#ff9900',
            'fullboard' => '#9900ff',
            'initial' => '#b6d7a8',
            'continue' => '#4285f4',
        ];
    }

}
