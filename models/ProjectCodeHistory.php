<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_code_history".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $project_id โครงการ
 * @property int $submission_id submission
 * @property string $old_code รหัสโครงการเดิม
 * @property string $new_code รหัสโครงการใหม่
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 *
 * @property User $createdBy
 * @property Project $project
 * @property Submission $submission
 */
class ProjectCodeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'project_code_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['project_id', 'submission_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['old_code', 'new_code'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'project_id' => Yii::t('app', 'โครงการ'),
            'submission_id' => Yii::t('app', 'submission'),
            'old_code' => Yii::t('app', 'รหัสโครงการเดิม'),
            'new_code' => Yii::t('app', 'รหัสโครงการใหม่'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
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
    public function getProject() {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @inheritdoc
     * @return ProjectCodeHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectCodeHistoryQuery(get_called_class());
    }

}
