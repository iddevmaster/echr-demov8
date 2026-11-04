<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_extra_info".
 *
 * @property int $id รหัสข้อมูลเพิ่มเติม
 * @property int $submission_id การส่งโครงการ
 * @property int $extra_info_id ข้อมูลเพิ่มเติม
 * @property string $value ข้อมูล
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property ExtraInfo $extraInfo
 * @property Submission $submission
 * @property User $updatedBy
 */
class SubmissionExtraInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'submission_extra_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_id', 'extra_info_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['extra_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExtraInfo::className(), 'targetAttribute' => ['extra_info_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
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
            'submission_id' => Yii::t('app', 'การส่งโครงการ'),
            'extra_info_id' => Yii::t('app', 'ข้อมูลเพิ่มเติม'),
            'value' => Yii::t('app', 'ข้อมูล'),
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
    public function getSubmission()
    {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
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
     * @return SubmissionExtraInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubmissionExtraInfoQuery(get_called_class());
    }
}
