<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_project_researcher".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $project_researcher_id ผู้ร่วมวิจัย
 * @property int $submission_id submission
 * @property string $status สถานะ
 * @property string $remark หมายเหตุ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ProjectResearcher $projectResearcher
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionProjectResearcher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'submission_project_researcher';
    }
    const STATUS_FAIL = 100;
    const STATUS_PASS = 200;

    public static function getStatusLabels() {
        return [
            self::STATUS_FAIL => Yii::t('app', 'ไม่ผ่าน'),
            self::STATUS_PASS => Yii::t('app', 'ผ่าน'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_researcher_id', 'submission_id', 'created_by', 'updated_by','deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'remark'], 'string', 'max' => 255],
//            [['deleted'], 'string', 'max' => 1],
            [['project_researcher_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectResearcher::className(), 'targetAttribute' => ['project_researcher_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'project_researcher_id' => Yii::t('app', 'ผู้ร่วมวิจัย'),
            'submission_id' => Yii::t('app', 'submission'),
            'status' => Yii::t('app', 'สถานะ'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectResearcher()
    {
        return $this->hasOne(ProjectResearcher::className(), ['id' => 'project_researcher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission()
    {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return SubmissionProjectResearcherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubmissionProjectResearcherQuery(get_called_class());
    }
}
