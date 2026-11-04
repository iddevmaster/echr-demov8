<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_type_volunteer_number".
 *
 * @property int $id รหัส
 * @property string $name หัวข้ออาสาสมัคร
 * @property int $submission_type_id ประเภทการขอรับพิจารณา
 * @property int $volunteer_number_id อาสาสมัคร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property SubmissionType $submissionType
 * @property User $createdBy
 * @property User $updatedBy
 * @property VolunteerNumber $volunteerNumber
 */
class SubmissionTypeVolunteerNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'submission_type_volunteer_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_type_id', 'volunteer_number_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['volunteer_number_id'], 'exist', 'skipOnError' => true, 'targetClass' => VolunteerNumber::className(), 'targetAttribute' => ['volunteer_number_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'name' => Yii::t('app', 'หัวข้ออาสาสมัคร'),
            'submission_type_id' => Yii::t('app', 'ประเภทการขอรับพิจารณา'),
            'volunteer_number_id' => Yii::t('app', 'อาสาสมัคร'),
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
    public function getSubmissionType()
    {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteerNumber()
    {
        return $this->hasOne(VolunteerNumber::className(), ['id' => 'volunteer_number_id']);
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeVolunteerNumberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubmissionTypeVolunteerNumberQuery(get_called_class());
    }
}
