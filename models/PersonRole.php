<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "person_role".
 *
 * @property int $id รหัส
 * @property int $person_id บุคคลกร
 * @property int $role_id หน้าที่
 * @property int $sign สิทธิในการเซ็นต์หนังสือ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $effective_date วันที่แต่งตั้งตำแหน่ง
 * @property string $effective_number เลขที่แต่งตั้งตำแหน่ง
 * @property string $expire_date วันที่สิ้นว่าระการทำงาน
 * @property int $status สถานะดำรงตำแหน่ง
 *
 * @property Person $person
 * @property Role $role
 * @property User $createdBy
 * @property User $updatedBy
 * @property PersonRolePanel[] $personRolePanels
 */
class PersonRole extends \yii\db\ActiveRecord {

    public $panelIds;
    public $meetingPanelIds;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'person_role';
    }

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'createdByUserProfile.fullName',
            'updatedByUserProfile.fullName',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['role_id'], 'required'],
                [['person_id', 'role_id', 'sign', 'deleted', 'created_by', 'updated_by', 'status'], 'integer'],
                [['created_at', 'updated_at', 'effective_date', 'expire_date', 'panelIds', 'meetingPanelIds'], 'safe'],
                [['effective_number'], 'string', 'max' => 255],
                [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
                [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'person_id' => Yii::t('app', 'บุคคลกร'),
            'role_id' => Yii::t('app', 'สิทธิการเข้าใช้งาน'),
            'sign' => Yii::t('app', 'สิทธิในการเซ็นต์หนังสือ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'effective_date' => Yii::t('app', 'วันที่แต่งตั้งตำแหน่ง'),
            'effective_number' => Yii::t('app', 'เลขที่แต่งตั้งตำแหน่ง'),
            'expire_date' => Yii::t('app', 'วันที่สิ้นว่าระการทำงาน'),
            'status' => Yii::t('app', 'สถานะดำรงตำแหน่ง'),
            'panelNames' => Yii::t('app', 'Panel'),
            'expertise' => Yii::t('app', 'ความชำนาญ'),
            'person.countSubmisionCommittee'=> yii::t('app','จำนวนงานวิจัยที่รับอ่าน'),
        ];
    }

    public function behaviors() {
        return [
                [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
                [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function afterFind() {
        parent::afterFind();
        $this->panelIds = \yii\helpers\ArrayHelper::getColumn($this->getPersonRolePanels()->isDeleted(FALSE)->orderBy('panel_id')->all(), 'panel_id');
        $this->meetingPanelIds = \yii\helpers\ArrayHelper::getColumn($this->getPersonRolePanels()->isDeleted(FALSE)->isRegular()->orderBy('panel_id')->all(), 'panel_id');
//        $this->meetingPanelIds = \yii\helpers\ArrayHelper::getColumn($this->getMeetingRolePanels()->isDeleted(FALSE)->orderBy('panel_id')->all(), 'panel_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
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
        return $this->hasOne(User::className(), ['id' => 'updated_by'])
                        ->from(['uu' => User::tableName()]);
    }

    public function getCreatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('createdBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('updatedBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonRolePanels() {
        return $this->hasMany(PersonRolePanel::className(), ['person_role_id' => 'id']);
    }

    public function getMeetingRolePanels() {
        return $this->hasMany(MeetingRolePanel::className(), [
                    'person_id' => 'person_id',
                    'role_id' => 'role_id',
        ]);
    }

    public function getRoleNameByPanel($panelId) {
        return implode(\Yii::t('app', 'และ'), \yii\helpers\ArrayHelper::getColumn($this->getPersonRolePanels()->isDeleted(FALSE)->panel($panelId)->all(), 'meetingRoleName'));
    }

    public function getPanelNames() {
        return implode(', ', \yii\helpers\ArrayHelper::getColumn($this->getPersonRolePanels()->isDeleted(FALSE)->all(), 'panelRegularName'));
    }

    public function getRegularNames() {
        return implode(', ', \yii\helpers\ArrayHelper::getColumn($this->getMeetingRolePanels()->isDeleted(FALSE)->all(), 'panel.name'));
    }

    public function toArrayData() {
        return \yii\helpers\ArrayHelper::toArray($this, [
                    \app\models\PersonRole::className() => [
                        'id',
                        'role_id',
                        'role',
                        'person_id',
//                        'person',
                        'sign',
                        'panels' => function($model) {
                            return \yii\helpers\ArrayHelper::map($model->getPersonRolePanels()->isDeleted(FALSE)->all(), 'panel_id', 'panel.name');
                        },
                        'newPanels' => function($model) {
                            $prs = $model->getPersonRolePanels()->isDeleted(FALSE)->all();
                            $res = [];
                            foreach ($prs as $pr) {
                                $res[] = $pr->panel;
                            }
                            return $res;
                        }
                    ]
        ]);
    }

    /**
     * @inheritdoc
     * @return PersonRoleQuery the active query used by this AR class.
     */
    public static function find() {
        return new PersonRoleQuery(get_called_class());
    }

}
