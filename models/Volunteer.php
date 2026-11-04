<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "volunteer".
 *
 * @property int $id
 * @property int $project_id โครงการ
 * @property string $code เลขที่อาสาสมัคร
 * @property int $status สถานะ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ContinueAssessForm[] $continueAssessForms
 * @property SaeAssessForm[] $saeAssessForms
 * @property SubmissionDocument[] $submissionDocuments
 * @property SubmissionVolunteer[] $submissionVolunteers
 * @property User $createdBy
 * @property Project $project
 * @property User $updatedBy
 */
class Volunteer extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'volunteer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_id', 'status', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'โครงการ'),
            'code' => Yii::t('app', 'เลขที่อาสาสมัคร'),
            'status' => Yii::t('app', 'สถานะ'),
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
    public function getContinueAssessForms() {
        return $this->hasMany(ContinueAssessForm::className(), ['volunteer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaeAssessForms() {
        return $this->hasMany(SaeAssessForm::className(), ['volunteer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments() {
        return $this->hasMany(SubmissionDocument::className(), ['volunteer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionVolunteers() {
        return $this->hasMany(SubmissionVolunteer::className(), ['volunteer_id' => 'id']);
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
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return VolunteerQuery the active query used by this AR class.
     */
    public static function find() {
        return new VolunteerQuery(get_called_class());
    }

}
