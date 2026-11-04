<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "questionnaire_choice".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $questionnaire_title_id หัวข้อแบบสอบถาม
 * @property string $title หัวข้อแบบสอบถาม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property QuestionnaireAnswer[] $questionnaireAnswers
 * @property QuestionnaireTitle $questionnaireTitle
 * @property User $createdBy
 * @property User $updatedBy
 */
class QuestionnaireChoice extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'questionnaire_choice';
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
                [['questionnaire_title_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['title'], 'required'],
                [['created_at', 'updated_at'], 'safe'],
                [['title'], 'string', 'max' => 255],
                [['questionnaire_title_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionnaireTitle::className(), 'targetAttribute' => ['questionnaire_title_id' => 'id']],
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
            'questionnaire_title_id' => Yii::t('app', 'หัวข้อแบบสอบถาม'),
            'title' => Yii::t('app', 'ตัวเลือกแบบสอบถาม'),
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
    public function getQuestionnaireAnswers() {
        return $this->hasMany(QuestionnaireAnswer::className(), ['questionnaire_choice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireTitle() {
        return $this->hasOne(QuestionnaireTitle::className(), ['id' => 'questionnaire_title_id']);
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
     * @return QuestionnaireChoiceQuery the active query used by this AR class.
     */
    public static function find() {
        return new QuestionnaireChoiceQuery(get_called_class());
    }

}
