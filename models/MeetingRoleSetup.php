<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meeting_role_setup".
 *
 * @property int $id รหัส
 * @property int $person_id บุคคลากร
 * @property int $panel_id หน้าที่
 * @property int $meeting_role_id หน้าที่ในการประชุม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property MeetingRole $meetingRole
 * @property Panel $panel
 * @property Person $person
 * @property User $createdBy
 * @property User $updatedBy
 * @property RegisterTransaction[] $registerTransactions
 */
class MeetingRoleSetup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meeting_role_setup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'panel_id', 'meeting_role_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['meeting_role_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeetingRole::className(), 'targetAttribute' => ['meeting_role_id' => 'id']],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัส'),
            'person_id' => Yii::t('app', 'บุคคลากร'),
            'panel_id' => Yii::t('app', 'หน้าที่'),
            'meeting_role_id' => Yii::t('app', 'หน้าที่ในการประชุม'),
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
    public function getMeetingRole()
    {
        return $this->hasOne(MeetingRole::className(), ['id' => 'meeting_role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPanel()
    {
        return $this->hasOne(Panel::className(), ['id' => 'panel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
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
    public function getRegisterTransactions()
    {
        return $this->hasMany(RegisterTransaction::className(), ['meeting_role_setup_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return MeetingRoleSetupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MeetingRoleSetupQuery(get_called_class());
    }
}
