<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%group_doc}}".
 *
 * @property int $id รหัสกลุ่มเอกสาร
 * @property string $name กลุ่มเอกสาร
 * @property string $name_eng กลุ่มเอกสารภาษาอังกฤษ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionDocument[] $submissionDocuments
 */
class GroupDoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group_doc}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_eng'], 'required'],
            [['deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'name_eng'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสกลุ่มเอกสาร'),
            'name' => Yii::t('app', 'กลุ่มเอกสาร'),
            'name_eng' => Yii::t('app', 'กลุ่มเอกสารภาษาอังกฤษ'),
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments()
    {
        return $this->hasMany(SubmissionDocument::className(), ['group_doc_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return GroupDocQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupDocQuery(get_called_class());
    }
}
