<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_status_history".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $submission_id การยื่นโครงการ
 * @property int $status สถานะ
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property string $remark_checkdoc_staff เจ้าหน้าที่หมายเหตุการตีกลับเอกสาร
 *
 * @property User $createdBy
 * @property Submission $submission
 */
class SubmissionStatusHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_status_history';
    }

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'createdByUserProfile.fullName',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['submission_id', 'status', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['remark_checkdoc_staff'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'submission_id' => Yii::t('app', 'การยื่นโครงการ'),
            'status' => Yii::t('app', 'สถานะ'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'remark_checkdoc_staff' => Yii::t('app', 'เจ้าหน้าที่หมายเหตุการตีกลับเอกสาร'),
            'createdByUserProfile.fullName' => Yii::t('app', 'บันทึกโดย'),
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
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
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    public function getCreatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('createdBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    /**
     * @inheritdoc
     * @return SubmissionStatusHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionStatusHistoryQuery(get_called_class());
    }

}
