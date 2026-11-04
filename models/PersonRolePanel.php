<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "person_role_panel".
 *
 * @property int $id รหัส
 * @property int $person_role_id บุคคลกร
 * @property int $panel_id หน้าที่
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $is_regular ประจำหรือไม่
 *
 * @property Panel $panel
 * @property PersonRole $personRole
 * @property User $createdBy
 * @property User $updatedBy
 */
class PersonRolePanel extends \yii\db\ActiveRecord {

        const TYPE_FULLBOARD = 1;
    const TYPE_EXPEDITE = 2;
    const TYPE_EXEMPTION = 3;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'person_role_panel';
    }
    public static function getTypeLabel() {
        return [
            self::TYPE_EXEMPTION => Yii::t('app', 'โครงการประเภท Exemption'),
            self::TYPE_EXPEDITE => Yii::t('app', 'โครงการประเภท Expedite'),
            self::TYPE_FULLBOARD => Yii::t('app', 'โครงการประเภท Fullboard'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['person_role_id', 'panel_id', 'deleted', 'created_by', 'updated_by', 'is_regular'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['panel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Panel::className(), 'targetAttribute' => ['panel_id' => 'id']],
            [['person_role_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonRole::className(), 'targetAttribute' => ['person_role_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'person_role_id' => Yii::t('app', 'บุคคลกร'),
            'panel_id' => Yii::t('app', 'หน้าที่'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'is_regular' => Yii::t('app', 'ประจำหรือไม่'),
            'expertise' => Yii::t('app', 'ความชำนาญ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPanel() {
        return $this->hasOne(Panel::className(), ['id' => 'panel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonRole() {
        return $this->hasOne(PersonRole::className(), ['id' => 'person_role_id']);
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

    public function getMeetingRoleName() {
        $name = $this->personRole->role->name;
        if ($this->personRole->role->is_meeting_role && $this->personRole->role_id == Role::COMMITTEE) {
            $name .= $this->is_regular ? \Yii::t('app', 'ประจำ') : \Yii::t('app', 'สมทบ');
        }
        return $name;
    }
    
    public function getPanelRegularName() {
        $name = $this->panel->name;
        $name .= $this->is_regular ? " (".\Yii::t('app', 'ประจำ') . ")" : "";
        return $name;
    }

    /**
     * @inheritdoc
     * @return PersonRolePanelQuery the active query used by this AR class.
     */
    public static function find() {
        return new PersonRolePanelQuery(get_called_class());
    }

}
