<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "committee_position".
 *
 * @property int $id รหัสองค์กร
 * @property string $name ตำแหน่งของกรรมการ
 * @property string $description อธิบายเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property int $status สถานะตำแหน่งกรรมการ
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property CommitteePositionForm[] $committeePositionForms
 * @property CommitteeSubmissionTypeForm[] $committeeSubmissionTypeForms
 * @property DocumentCommitteePosition[] $documentCommitteePositions
 * @property SubmissionCommittee[] $submissionCommittees
 */
class CommitteePosition extends \yii\db\ActiveRecord {

    const POSITION_LEC = 4;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'committee_position';
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
            [['name'], 'required'],
            [['deleted', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัสองค์กร'),
            'name' => Yii::t('app', 'ตำแหน่งของกรรมการ'),
            'description' => Yii::t('app', 'อธิบายเพิ่มเติม'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'status' => Yii::t('app', 'ยกเลิกสถานะกรรมการ'),
            'updatedByUserProfile.fullName' => Yii::t('app', 'ผู้แก้ไขข้อมูล'),
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
    public function getCommitteePositionForms() {
        return $this->hasMany(CommitteePositionForm::className(), ['committee_position_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommitteeSubmissionTypeForms() {
        return $this->hasMany(CommitteeSubmissionTypeForm::className(), ['committee_position_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentCommitteePositions() {
        return $this->hasMany(DocumentCommitteePosition::className(), ['committee_position_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittees() {
        return $this->hasMany(SubmissionCommittee::className(), ['committee_position_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CommitteePositionQuery the active query used by this AR class.
     */
    public function getFullName() {
        $name = $this->name . ' : ' . $this->description;

        return $name;
    }

    public static function find() {
        return new CommitteePositionQuery(get_called_class());
    }

}
