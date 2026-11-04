<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_revise".
 *
 * @property int $id รหัสกำหนดวันแก้ไข
 * @property string $remark หมายเหตุ
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property string $send_date วันที่สง
 * @property string $return_date วันที่ตอบกลับ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionRevise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'submission_revise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['submission_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['send_date', 'return_date', 'created_at', 'updated_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
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
            'id' => Yii::t('app', 'รหัสกำหนดวันแก้ไข'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'send_date' => Yii::t('app', 'วันที่สง'),
            'return_date' => Yii::t('app', 'วันที่ตอบกลับ'),
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
     * @return SubmissionReviseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubmissionReviseQuery(get_called_class());
    }
}
