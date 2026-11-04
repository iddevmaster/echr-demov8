<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_type_training_requirement".
 *
 * Admin-editable matrix: for each submission type, the training requirement per
 * category (GCP / Ethics).
 *
 * @property int $id
 * @property int $submission_type_id
 * @property int $category 1=GCP, 2=ETHICS (mirrors TrainingType category)
 * @property int $rule 0=NA, 1=REQUIRED, 2=ANY_OF
 * @property int $deleted
 * @property int $created_by
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 *
 * @property SubmissionType $submissionType
 */
class SubmissionTypeTrainingRequirement extends \yii\db\ActiveRecord {

    const RULE_NA = 0;
    const RULE_REQUIRED = 1;
    const RULE_ANY_OF = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'submission_type_training_requirement';
    }

    public static function getRuleLabels() {
        return [
            self::RULE_NA => Yii::t('app', 'ไม่ต้องแนบ (NA)'),
            self::RULE_REQUIRED => Yii::t('app', 'ต้องแนบ'),
            self::RULE_ANY_OF => Yii::t('app', 'ต้องแนบอย่างใดอย่างหนึ่ง'),
        ];
    }

    public function getRuleLabel() {
        $labels = self::getRuleLabels();
        return isset($labels[$this->rule]) ? $labels[$this->rule] : '';
    }

    public function getCategoryLabel() {
        return TrainingType::getCategoryLabels()[$this->category] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['submission_type_id', 'category', 'rule'], 'required'],
            [['submission_type_id', 'category', 'rule', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_type_id' => Yii::t('app', 'ประเภทการขอรับพิจารณา'),
            'category' => Yii::t('app', 'หมวดการอบรม'),
            'rule' => Yii::t('app', 'เกณฑ์'),
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
    public function getSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionTypeTrainingRequirementQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionTypeTrainingRequirementQuery(get_called_class());
    }

}
