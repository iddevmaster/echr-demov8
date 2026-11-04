<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "submission_volunteer_number".
 *
 * @property int $id รหัส
 * @property int $value จำนวนอาสาสมัคร
 * @property int $volunteer_number_id อาสาสมัคร
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $project_id โครงการวิจัย
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property Project $project
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 * @property VolunteerNumber $volunteerNumber
 */
class SubmissionVolunteerNumber extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_volunteer_number';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['value'], 'required'],
            [['value', 'volunteer_number_id', 'submission_id', 'project_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['volunteer_number_id'], 'exist', 'skipOnError' => true, 'targetClass' => VolunteerNumber::className(), 'targetAttribute' => ['volunteer_number_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'value' => Yii::t('app', 'จำนวนอาสาสมัคร'),
            'volunteer_number_id' => Yii::t('app', 'อาสาสมัคร'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteerNumber() {
        return $this->hasOne(VolunteerNumber::className(), ['id' => 'volunteer_number_id']);
    }

    public function getLastValue() {
//        \yii\helpers\VarDumper::dump($submission->attributes);
//        \yii\helpers\VarDumper::dump($this->project);
//        exit;
        //$sub = $this->getProject()->one()->getSubmissions()->isDeleted(FALSE)->orderBy('id DESC')->one();
//        $project = Project::findOne($this->project_id);
//        \yii\helpers\VarDumper::dump($this->project_id);
//        if (!isset($project)) {
//            return NULL;
//        }
        $sub = $this->project->getSubmissions()->isDeleted(FALSE)->andWhere(['not', ['id' => $this->submission_id]])->orderBy('id DESC')->one();
        if (!isset($sub) || $sub->id == $this->submission_id) {
            return NULL;
        }
        $subNum = SubmissionVolunteerNumber::find()->isDeleted(FALSE)->submission($sub->id)->volunteerNumber($this->volunteer_number_id)->one();
        return isset($subNum) ? $subNum->value : NULL;
    }

    /**
     * @inheritdoc
     * @return SubmissionVolunteerNumberQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionVolunteerNumberQuery(get_called_class());
    }

}
