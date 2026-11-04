<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%committee_qualification}}".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $name ชื่อคุณสมบัติ
 * @property string $name_eng
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CommitteeQualificationPanel[] $committeeQualificationPanels
 * @property Person[] $people
 * @property SubmissionCommittee[] $submissionCommittees
 */
class CommitteeQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%committee_qualification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'name' => Yii::t('app', 'ชื่อคุณสมบัติ'),
            'name_eng' => Yii::t('app', 'Name Eng'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getCommitteeQualificationPanels()
    {
        return $this->hasMany(CommitteeQualificationPanel::className(), ['committee_qualification_id' => 'id']);
    }
    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['committee_qualification_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittees()
    {
        return $this->hasMany(SubmissionCommittee::className(), ['committee_qualification_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CommitteeQualificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommitteeQualificationQuery(get_called_class());
    }
}
