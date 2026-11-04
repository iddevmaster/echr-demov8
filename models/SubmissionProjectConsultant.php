<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "submission_project_consultant".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $project_consultant_id ที่ปรึกษา
 * @property int $submission_id submission
 * @property int $status สถานะ
 * @property string $remark หมายเหตุ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ProjectConsultant $projectConsultant
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionProjectConsultant extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'submission_project_consultant';
    }
    const STATUS_FAIL = 100;
    const STATUS_PASS = 200;

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'createdByUserProfile.fullName',
            'updatedByUserProfile.fullName',
        ]);
    }


    public static function getStatusLabels() {
        return [
            self::STATUS_FAIL => Yii::t('app', 'ไม่ผ่าน'),
            self::STATUS_PASS => Yii::t('app', 'ผ่าน'),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_consultant_id', 'submission_id', 'status', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
            [['project_consultant_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectConsultant::className(), 'targetAttribute' => ['project_consultant_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'project_consultant_id' => Yii::t('app', 'ที่ปรึกษา'),
            'submission_id' => Yii::t('app', 'submission'),
            'status' => Yii::t('app', 'สถานะ'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
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
    public function getProjectConsultant() {
        return $this->hasOne(ProjectConsultant::className(), ['id' => 'project_consultant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
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
     * {@inheritdoc}
     * @return SubmissionProjectConsultantQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionProjectConsultantQuery(get_called_class());
    }

}
