<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "meeting_person".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property int $meeting_id การประชุม
 * @property int $person_id บุคคล
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $role_id หน้าที่
 * @property string $role_name ตำแหน่งในที่ประชุม 
 *
 * @property Meeting $meeting
 * @property Person $person
 * @property Role $role
 * @property User $createdBy
 * @property User $updatedBy
 */
class MeetingPerson extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'meeting_person';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['person_id', 'role_name'], 'required'],
            [['meeting_id', 'person_id', 'deleted', 'created_by', 'updated_by', 'role_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['role_name'], 'string', 'max' => 255],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::className(), 'targetAttribute' => ['meeting_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'meeting_id' => Yii::t('app', 'การประชุม'),
            'person_id' => Yii::t('app', 'บุคคล'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'role_id' => Yii::t('app', 'หน้าที่'),
            'role_name' => Yii::t('app', 'ตำแหน่งในที่ประชุม'),
            'entryLogs' => Yii::t('app', 'เวลาเข้า-ออก'),
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
    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
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
    
    public function getRegisterTransactions() {
        return RegisterTransaction::find()->isDeleted(FALSE)->meeting($this->meeting_id)->person($this->person_id)->orderBy('id')->all();
    }
    
    public function getEntryLogs() {
        $rts = $this->registerTransactions;
        if (count($rts) == 0) {
            return '';
        }
        $res = '<ul>';
        foreach ($rts as $rt) {
            $enterAt = isset($rt->registered_at) ? Yii::t("app", "เข้า {0}", [Yii::$app->formatter->asTime($rt->registered_at)]) : "";
            $exitAt = isset($rt->out_at) ? Yii::t("app", "ออก {0}", [Yii::$app->formatter->asTime($rt->out_at)]) : "";
            $res .= "<li>{$enterAt} {$exitAt}</li>";
        }
        $res .= '</ul>';
        return $res;
    }
    
    public function getCoiAgendas() {
        $mas = $this->person->getCOIMeetingAgendas($this->meeting_id);
        if (count($mas) == 0) {
            return '';
        }
        $res = '<ul>';
        foreach ($mas as $ma) {
            $res .= "<li>{$ma->fullTitle}</li>";
        }
        $res .= '</ul>';
        return $res;
    }
    
    public function getCheckRoleName() {
        $res = '';
        if (!isset($this->person->user_id)) {
            return $res;
        }
        if ($this->person->user_id == $this->meeting->checked_staff) {
            $res = Yii::t('app', 'เจ้าหน้าที่ตรวจสอบรายงานการประชุม');
        } else if ($this->person->user_id == $this->meeting->checked_secretary_first) {
            $res = Yii::t('app', 'เลขาตรวจสอบรายงานการประชุม ท่านที่ 1');
        } else if ($this->person->user_id == $this->meeting->checked_secretary_second) {
            $res = Yii::t('app', 'เลขาตรวจสอบรายงานการประชุม ท่านที่ 2');
        }else if ($this->person->user_id == $this->meeting->checked_president) {
            $res = Yii::t('app', 'ประธานตรวจสอบรายงานการประชุม');
        }
        return $res;
    }

    /**
     * @inheritdoc
     * @return MeetingPersonQuery the active query used by this AR class.
     */
    public static function find() {
        return new MeetingPersonQuery(get_called_class());
    }

}
