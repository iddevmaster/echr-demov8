<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "questionnaire_title".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $submission_type_id รหัสประเภทการส่ง submission
 * @property int $questionnaire_type ประเภทแบบสอบถามการประเมิน
 * @property string $title หัวข้อแบบสอบถามการประเมิน
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $order ลำดับ
 *
 * @property QuestionnaireAnswer[] $questionnaireAnswers
 * @property QuestionnaireChoice[] $questionnaireChoices
 * @property SubmissionType $submissionType
 * @property User $createdBy
 * @property User $updatedBy
 */
class QuestionnaireTitle extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    const TYPE_MULTI_CHOICES = 2;
    const TYPE_SINGLE_CHOICE = 1;
    const TYPE_TEXT_CHOICE = 3;
    
    public static function getTypeLabels() {
        return [
            self::TYPE_MULTI_CHOICES => Yii::t('app', 'Checkbox'),
            self::TYPE_SINGLE_CHOICE => Yii::t('app', 'Radio'),
            self::TYPE_TEXT_CHOICE => Yii::t('app', 'Text'),
        ];
    }
    public static function tableName() {
        return 'questionnaire_title';
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
                [['submission_type_id', 'questionnaire_type', 'deleted', 'created_by', 'updated_by','order'], 'integer'],
                [['title'], 'required'],
                [['created_at', 'updated_at'], 'safe'],
                [['title'], 'string', 'max' => 255],
                [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
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
            'submission_type_id' => Yii::t('app', 'รหัสประเภทการส่ง submission'),
            'questionnaire_type' => Yii::t('app', 'ประเภทตัวเลือกแบบประเมิน'),
            'title' => Yii::t('app', 'หัวข้อแบบประเมิน'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'order' => Yii::t('app', 'ลำดับ'),
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
    public function getQuestionnaireAnswers() {
        return $this->hasMany(QuestionnaireAnswer::className(), ['questionnaire_title_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireChoices() {
        return $this->hasMany(QuestionnaireChoice::className(), ['questionnaire_title_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
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
     * @inheritdoc
     * @return QuestionnaireTitleQuery the active query used by this AR class.
     */
    public static function find() {
        return new QuestionnaireTitleQuery(get_called_class());
    }

}
