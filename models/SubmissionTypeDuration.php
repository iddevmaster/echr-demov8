<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "submission_type_duration".
 *
 * @property int $id
 * @property int $submission_type_id ประเภทการส่งโครงการ
 * @property int $duration_type ประเภท
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property SubmissionType $submissionType
 * @property User $updatedBy
 */
class SubmissionTypeDuration extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'submission_type_duration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['submission_type_id', 'duration_type', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'submission_type_id' => 'ประเภทการส่งโครงการ',
            'duration_type' => 'ประเภท',
            'deleted' => '0=ใช้งาน,1=ไม่ใช้งาน',
            'created_by' => 'สร้างโดย',
            'created_at' => 'สร้างเมื่อ',
            'updated_by' => 'ปรับปรุงโดย',
            'updated_at' => 'ปรับปรุงเมื่อ',
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
    public function getSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionTypeDurationQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionTypeDurationQuery(get_called_class());
    }

}
