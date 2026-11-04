<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $id รหัสองค์กร
 * @property string $name องค์กร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $name_eng องค์กร (ภาษาอังกฤษ)
 * @property int $is_internal หน่วยงานภายใน
 *
 * @property Department[] $departments
 * @property Meeting[] $meetings
 * @property User $createdBy
 * @property User $updatedBy
 * @property Project[] $projects
 * @property Submission[] $submissions
 */
class Organization extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'organization';
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
            [['deleted', 'created_by', 'updated_by', 'is_internal'], 'integer'],
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
            'id' => Yii::t('app', 'รหัสองค์กร'),
            'name' => Yii::t('app', 'องค์กร'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedByUserProfile.fullName' => Yii::t('app', 'ผู้แก้ไขข้อมูล'),
            'name_eng' => Yii::t('app', 'องค์กร (ภาษาอังกฤษ)'),
            'is_internal' => Yii::t('app', 'หน่วยงานภายใน'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments() {
        return $this->hasMany(Department::className(), ['organization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetings() {
        return $this->hasMany(Meeting::className(), ['organization_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects() {
        return $this->hasMany(Project::className(), ['organization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissions() {
        return $this->hasMany(Submission::className(), ['organization_id' => 'id']);
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
     * @return OrganizationQuery the active query used by this AR class.
     */
    public static function find() {
        return new OrganizationQuery(get_called_class());
    }

}
