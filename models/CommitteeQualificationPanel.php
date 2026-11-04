<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%committee_qualification_panel}}".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $committee_qualification_id คุณสมบัติ
 * @property int $panel_id panel
 * @property string $name คุณสมบัติตาม panel
 * @property string $name_eng
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property CommitteeQualification $committeeQualification
 * @property Panel $panel
 * @property User $createdBy
 * @property User $updatedBy
 */
class CommitteeQualificationPanel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%committee_qualification_panel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['committee_qualification_id', 'panel_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'name_eng'], 'string', 'max' => 255],
            [['committee_qualification_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommitteeQualification::className(), 'targetAttribute' => ['committee_qualification_id' => 'id']],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'committee_qualification_id' => Yii::t('app', 'คุณสมบัติ'),
            'panel_id' => Yii::t('app', 'panel'),
            'name' => Yii::t('app', 'คุณสมบัติตาม panel'),
            'name_eng' => Yii::t('app', 'Name Eng'),
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
    public function getCommitteeQualification()
    {
        return $this->hasOne(CommitteeQualification::className(), ['id' => 'committee_qualification_id']);
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
     * {@inheritdoc}
     * @return CommitteeQualificationPanelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommitteeQualificationPanelQuery(get_called_class());
    }
}
