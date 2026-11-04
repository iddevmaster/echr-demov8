<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id รหัสหน่วยงาน
 * @property string $name หน่วยงาน
 * @property string $address ที่อยู่
 * @property string $tel เบอร์โทร
 * @property string $email อีเมลล์
 * @property string $website เว็บไซต์
 * @property int $organization_id องค์กร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $name_eng ชื่อภาษาอังกฤษ
 * @property int $job_category_id ประเภทอาชีพ
 * @property int $crec_id CREC ID
 *
 * @property JobCategory $jobCategory 
 * @property Organization $organization
 * @property User $createdBy
 * @property User $updatedBy
 * @property Meeting[] $meetings
 * @property Division[] $divisions 
 */
class Department extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'department';
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
            [['name'], 'required'],
            [['organization_id', 'deleted', 'created_by', 'updated_by', 'job_category_id', 'crec_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'address', 'tel', 'email', 'website', 'name_eng'], 'string', 'max' => 255],
            [['job_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobCategory::className(), 'targetAttribute' => ['job_category_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'name' => Yii::t('app', 'ชื่อหน่วยงาน'),
            'address' => Yii::t('app', 'ที่อยู่'),
            'tel' => Yii::t('app', 'เบอร์โทร'),
            'email' => Yii::t('app', 'อีเมลล์'),
            'website' => Yii::t('app', 'เว็บไซต์'),
            'organization_id' => Yii::t('app', 'องค์กร'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedByUserProfile.fullName' => Yii::t('app', 'ผู้แก้ไขข้อมูล'),
            'name_eng' => Yii::t('app', 'ชื่อหน่วยงานภาษาอังกฤษ'),
            'job_category_id' => Yii::t('app', 'ประเภทอาชีพ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization() {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
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

    public function getDivisions() {
        return $this->hasMany(Division::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetings() {
        return $this->hasMany(Meeting::className(), ['department_id' => 'id']);
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
    
    
    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    /**
     * @inheritdoc
     * @return DepartmentQuery the active query used by this AR class.
     */
    public static function find() {
        return new DepartmentQuery(get_called_class());
    }

}
