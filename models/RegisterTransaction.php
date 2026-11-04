<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "register_transaction".
 *
 * @property integer $id
 * @property integer $meeting_id
 * @property integer $person_id
 * @property string $invited_at
 * @property string $registered_at
 * @property string $out_at
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property Meeting $meeting
 * @property Person $person
 * @property User $createdBy
 * @property User $updatedBy
 */
class RegisterTransaction extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'register_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['meeting_id', 'person_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['invited_at', 'registered_at', 'out_at', 'created_at', 'updated_at'], 'safe'],
                [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meeting_id' => 'id']],
                [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
                [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
                [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'meeting_id' => Yii::t('app', 'การประชุม'),
            'person_id' => Yii::t('app', 'ผู้เข้าร่วมประชุม'),
            'invited_at' => Yii::t('app', 'วันที่เชิญประชุม'),
            'registered_at' => Yii::t('app', 'วันเวลาเข้าห้องประชุม'),
            'out_at' => Yii::t('app', 'วันเวลาออกจากห้องประชุม'),
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
    public function getMeeting() {
        return $this->hasOne(Meeting::className(), ['id' => 'meeting_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
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

    public function getMeetingInterval() {
        if (!isset($this->out_at)) {
            return NULL;
        }
        $start = new \DateTime($this->registered_at);
        $end = new \DateTime($this->out_at);
        return $start->diff($end, TRUE);
    }
    /**
     * @inheritdoc
     * @return RegisterTransactionQuery the active query used by this AR class.
     */
    public static function find() {
        return new RegisterTransactionQuery(get_called_class());
    }

}
