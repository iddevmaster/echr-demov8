<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "deviation_event_type".
 *
 * @property int $id
 * @property int $deviation_type_id ชนิดของการดำเนินการเบี่ยงเบน
 * @property string $other อื่นๆ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $submission_event_id เหตุการณ์
 *
 * @property User $createdBy
 * @property DeviationType $deviationType
 * @property SubmissionEvent $submissionEvent
 * @property User $updatedBy
 */
class DeviationEventType extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'deviation_event_type';
    }

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'createdByUserProfile.fullName',
            'updatedByUserProfile.fullName',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['deviation_type_id', 'deleted', 'created_by', 'updated_by', 'submission_event_id'], 'integer'],
            [['other'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deviation_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeviationType::className(), 'targetAttribute' => ['deviation_type_id' => 'id']],
            [['submission_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionEvent::className(), 'targetAttribute' => ['submission_event_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'deviation_type_id' => Yii::t('app', 'ชนิดของการดำเนินการเบี่ยงเบน'),
            'other' => Yii::t('app', 'อื่นๆ'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'submission_event_id' => Yii::t('app', 'เหตุการณ์'),
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
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviationType() {
        return $this->hasOne(DeviationType::className(), ['id' => 'deviation_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionEvent() {
        return $this->hasOne(SubmissionEvent::className(), ['id' => 'submission_event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return DeviationEventTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new DeviationEventTypeQuery(get_called_class());
    }

}
