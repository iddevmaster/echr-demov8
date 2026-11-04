<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "submission_type_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property AlertPeroid[] $alertPeros
 * @property SubmissionType[] $submissionTypes
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionTypeGroup extends \yii\db\ActiveRecord {
    
    const GROUP_NEW = 1;
    const GROUP_CONT = 2;
    const GROUP_RESUBMIT = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_type_group';
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
                [['deleted', 'created_by', 'updated_by'], 'integer'],
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
            'id' => Yii::t('app', 'รหัสกลุ่มประเภทโครงการวิจัย'),
            'name' => Yii::t('app', 'กลุ่มประเภทโครงการวิจัย'),
            'name_eng' => Yii::t('app', 'กลุ่มประเภทโครงการวิจัย(ภาษาอังกฤษ)'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedByUserProfile.fullName' => Yii::t('app', 'ผู้แก้ไขข้อมูล'),
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
    public function getAlertPeros() {
        return $this->hasMany(AlertPeroid::className(), ['submission_type_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionTypes() {
        return $this->hasMany(SubmissionType::className(), ['submission_type_group_id' => 'id']);
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
    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }
    /**
     * @inheritdoc
     * @return SubmissionTypeGroupQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionTypeGroupQuery(get_called_class());
    }

}
