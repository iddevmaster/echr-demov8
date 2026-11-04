<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property int $is_meeting_role เป็นตำแหน่งในการประชุมหรือไม่
 *
 * @property Document[] $documents 
 * @property MeetingPerson[] $meetingPeople 
 * @property PersonRole[] $personRoles 
 * @property Person[] $people
 * @property User $createdBy
 * @property User $updatedBy
 */
class Role extends \yii\db\ActiveRecord {

    const ADMIN = 1;
    const RESEARCHER = 2;
    const COMMITTEE = 3;
    const COPRESIDENT = 4;
    const SECRETARY = 5;
    const PRESIDENT = 6;
    const STAFF = 7;
    const COORDINATOR = 8;
    const WEB_API = 11;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['deleted', 'created_by', 'updated_by', 'is_meeting_role'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'name_eng'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'รหัสอัตโนมัติ',
            'name' => 'ชื่อหน้าที่',
            'name_eng' => 'ชื่อหน้าที่ (ภาษาอังกฤษ)',
            'deleted' => '0=ใช้งาน,1=ไม่ใช้งาน',
            'created_by' => 'สร้างโดย',
            'created_at' => 'สร้างเมื่อ',
            'updated_by' => 'ปรับปรุงโดย',
            'updated_at' => 'ปรับปรุงเมื่อ',
            'is_meeting_role' => Yii::t('app', 'เป็นตำแหน่งในการประชุมหรือไม่'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingPeople() {
        return $this->hasMany(MeetingPerson::className(), ['role_id' => 'id']);
    }

    public function getPersonRoles() {
        return $this->hasMany(PersonRole::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople() {
        return $this->hasMany(Person::className(), ['role_id' => 'id']);
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

    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    /**
     * @inheritdoc
     * @return RoleQuery the active query used by this AR class.
     */
    public static function find() {
        return new RoleQuery(get_called_class());
    }

    public static function roleLabels() {
        return [
            self::RESEARCHER => 'นักวิจัย',
            self::COMMITTEE => 'กรรมการ',
            self::COPRESIDENT => 'รองประธานคณะกรรมการ',
            self::SECRETARY => 'เลขานุการ',
            self::PRESIDENT => 'ประธานคณะกรรมการ',
            self::STAFF => 'เจ้าหน้าที่',
        ];
    }

    public static function meetingRoles() {
        return [
            self::COMMITTEE,
            self::COPRESIDENT,
            self::SECRETARY,
            self::PRESIDENT,
        ];
    }

    public static function getRegisterRoles() {
        return [
            self::RESEARCHER => Yii::t('app', 'นักวิจัย'),
            self::COORDINATOR => Yii::t('app', 'ผู้ประสานงานโครงการ'),
        ];
    }

}
