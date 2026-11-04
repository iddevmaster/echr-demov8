<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda_result_document".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $agenda_id วาระการประชุม
 * @property int $result_document_id ไฟล์ต้นแบบ
 * @property string $remark หมายเหตุ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property Agenda $agenda
 * @property ResultDocument $resultDocument
 * @property User $createdBy
 * @property User $updatedBy
 */
class AgendaResultDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agenda_result_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agenda_id', 'result_document_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
            [['deleted'], 'string', 'max' => 1],
            [['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['agenda_id' => 'id']],
            [['result_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResultDocument::className(), 'targetAttribute' => ['result_document_id' => 'id']],
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
            'agenda_id' => Yii::t('app', 'วาระการประชุม'),
            'result_document_id' => Yii::t('app', 'ไฟล์ต้นแบบ'),
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
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultDocument()
    {
        return $this->hasOne(ResultDocument::className(), ['id' => 'result_document_id']);
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
     * @return AgendaResultDocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AgendaResultDocumentQuery(get_called_class());
    }
}
