<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification_history".
 *
 * @property int $id รหัสหน่วยงาน
 * @property int $status สถานะ
 * @property int $alert_peroid_id ระยะแจ้งเตือน
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $person_id บุคลากร
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property AlertPeroid $alertPeroid
 * @property Person $person
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class NotificationHistory extends \yii\db\ActiveRecord
{
    const TYPE_TRAINING_EXPIRE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'alert_peroid_id', 'submission_id', 'person_id', 'person_training_id', 'notify_type', 'notify_days', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['alert_peroid_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlertPeroid::className(), 'targetAttribute' => ['alert_peroid_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'status' => Yii::t('app', 'สถานะ'),
            'alert_peroid_id' => Yii::t('app', 'ระยะแจ้งเตือน'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'person_id' => Yii::t('app', 'บุคลากร'),
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
    public function getAlertPeroid()
    {
        return $this->hasOne(AlertPeroid::className(), ['id' => 'alert_peroid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission()
    {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return NotificationHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationHistoryQuery(get_called_class());
    }
}
