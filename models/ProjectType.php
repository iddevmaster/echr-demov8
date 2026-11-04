<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project_type".
 *
 * @property int $id
 * @property string $name ชื่อประเภท
 * @property int $is_alert แจ้งเตือนกรรมการหรือไม่
 * @property int $min_occur จำนวนครั้งที่เกิดต่อนักวิจัย ต่อปี แล้วต้องแจ้งเตือน
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ProjectAgendaAnswer[] $projectAgendaAnswers
 * @property ProjectQuestionChoice[] $projectQuestionChoices
 * @property User $createdBy
 * @property User $updatedBy
 */
class ProjectType extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'project_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['is_alert', 'min_occur', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ชื่อประเภท'),
            'is_alert' => Yii::t('app', 'แจ้งเตือนกรรมการ'),
            'isAlertLabel' => Yii::t('app', 'แจ้งเตือนกรรมการ'),
            'min_occur' => Yii::t('app', 'จำนวนครั้งที่เกิดต่อนักวิจัย ต่อปี แล้วต้องแจ้งเตือน'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'updatedBy.person.fullName' => Yii::t('app', 'ปรับปรุงโดย'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectAgendaAnswers() {
        return $this->hasMany(ProjectAgendaAnswer::className(), ['project_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectQuestionChoices() {
        return $this->hasMany(ProjectQuestionChoice::className(), ['project_type_id' => 'id']);
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
    
    public function getIsAlertLabel() {
        if ($this->is_alert) {
            return \Yii::t('app', 'ใช่');
        }
        return "";
    }

    /**
     * {@inheritdoc}
     * @return ProjectTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProjectTypeQuery(get_called_class());
    }

}
