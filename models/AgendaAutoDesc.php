<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda_auto_desc".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $agenda_id วาระ
 * @property int $auto_type ประเภทการดึงข้อมูล
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property Agenda $agenda
 * @property User $createdBy
 * @property User $updatedBy
 */
class AgendaAutoDesc extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'agenda_auto_desc';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['agenda_id', 'auto_type', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['agenda_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'agenda_id' => Yii::t('app', 'วาระ'),
            'auto_type' => Yii::t('app', 'ประเภทการดึงข้อมูล'),
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
    public function getAgenda() {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
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
     * @inheritdoc
     * @return AgendaAutoDescQuery the active query used by this AR class.
     */
    public static function find() {
        return new AgendaAutoDescQuery(get_called_class());
    }


}
