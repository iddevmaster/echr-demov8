<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "panel".
 *
 * @property integer $id
 * @property string $name
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Project[] $projects
 */
class Panel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'panel';
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
            [['name'], 'required'],
            [['deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'name_eng'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสกลุ่มของผู้ปฏิบัติงาน'),
            'name' => Yii::t('app', 'กลุ่มของผู้ปฏิบัติงาน'),
            'name_eng' => Yii::t('app', 'กลุ่มของผู้ปฏิบัติงาน (ภาษาอังกฤษ)'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getProjects() {
        return $this->hasMany(Project::className(), ['panel_id' => 'id']);
    }

    public function getPendingMeetings() {
        $subQuery = (new \yii\db\Query())
                ->select(['id'])
                ->from('meeting m')
                ->andWhere('m.panel_id = p.panel_id')
                ->andWhere('m.start_date = s.meeting_plan_date');
        $rows = (new \yii\db\Query())
                ->select(['s.meeting_plan_date', 'p.panel_id', 'pn.name as panel', 'COUNT(*) AS submission_count'])
                ->from('submission s')
                ->leftJoin('project p', 's.project_id = p.id')
                ->leftJoin('panel pn', 'p.panel_id = pn.id')
                ->andWhere(['s.deleted' => FALSE])
                ->andWhere(['p.panel_id' => $this->id])
                ->andWhere(['>=', 's.status', Submission::STATUS_MEETING_APPOINTMENT])
                ->andWhere(['<', 's.status', Submission::STATUS_AGENDA_ADDED])
                ->andWhere(['not exists', $subQuery])
                ->groupBy(['s.meeting_plan_date', 'p.panel_id', 'pn.name'])
                ->all();
        return $rows;
//        Submission::find()->joinWith(['project'])->isDeleted(FALSE)->panel($this->id)
//                ->andWhere(['>=', 'status', Submission::STATUS_MEETING_APPOINTMENT])
//                ->andWhere(['<', 'status', Submission::STATUS_AGENDA_ADDED]);
    }

    public function getChairman() {
        return Person::find()->joinWith(['personRoles', 'personRoles.personRolePanels'])->isDeleted(FALSE)->role(Role::PRESIDENT)->panel($this->id)->isRegular()->one();
    }

    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    public function toArrayData() {
        return \yii\helpers\ArrayHelper::toArray($this, [
                    \app\models\Panel::className() => [
                        'id',
                        'name',
                        'name_eng',
                        'deleted',
                        'created_by',
                        'created_at',
                        'updated_by',
                        'updated_at',
                        'i18nName' => function($model) {
                            return $model->i18nName;
                        }
                    ]
        ]);
    }

    /**
     * @inheritdoc
     * @return PanelQuery the active query used by this AR class.
     */
    public static function find() {
        return new PanelQuery(get_called_class());
    }

}
