<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_type_extra_info".
 *
 * @property int $id รหัสข้อมูลเพิ่มเติม
 * @property int $submission_type_id ประเภทการส่งโครงการ
 * @property int $extra_info_id ข้อมูลเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property ExtraInfo $extraInfo
 * @property SubmissionType $submissionType
 * @property User $updatedBy
 */
class SubmissionTypeExtraInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'submission_type_extra_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_type_id', 'extra_info_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['extra_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExtraInfo::className(), 'targetAttribute' => ['extra_info_id' => 'id']],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสข้อมูลเพิ่มเติม'),
            'submission_type_id' => Yii::t('app', 'ประเภทการส่งโครงการ'),
            'extra_info_id' => Yii::t('app', 'ข้อมูลเพิ่มเติม'),
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtraInfo()
    {
        return $this->hasOne(ExtraInfo::className(), ['id' => 'extra_info_id']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeExtraInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubmissionTypeExtraInfoQuery(get_called_class());
    }
}
