<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_submission_type".
 *
 * @property int $id รหัสหน่วยงาน
 * @property int $submission_type_id ประเภทการขอรับพิจารณา
 * @property int $document_id ประเภทเอกสาร
 * @property int $is_require จำเป็นหรือไม่ 0=ไม่จำเป็น   1=จำเป็น
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property int $number 
 * @property int $sort 
 * @property int $is_event
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $committee_position_id ตำแหน่งกรรมการ
 * @property int $ref_submission_type_id อ้างอิงประเภท submission
 * @property int $is_api เพิ่มเอกสารจาก API
 * 
 *
 * @property Document $document
 * @property SubmissionType $submissionType
 * @property User $createdBy
 * @property User $updatedBy
 */
class DocumentSubmissionType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'document_submission_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['submission_type_id', 'document_id', 'is_require', 'deleted', 'created_by', 'updated_by', 'number', 'role_id', 'sort', 'committee_position_id', 'ref_submission_type_id','is_event', 'is_api'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['submission_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionType::className(), 'targetAttribute' => ['submission_type_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัสหน่วยงาน'),
            'submission_type_id' => Yii::t('app', 'ประเภทการขอรับพิจารณา'),
            'document_id' => Yii::t('app', 'ประเภทเอกสาร'),
            'is_require' => Yii::t('app', 'ต้องแนบเอกสาร'),
            'isRequireLabel' => Yii::t('app', 'ต้องแนบเอกสาร'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'number' => Yii::t('app', 'จำนวนเอกสาร'),
            'sort' => Yii::t('app', 'ลำดับเอกสาร'),
            'committee_position_id' => Yii::t('app', 'ตำแหน่งกรรมการ'),
            'ref_submission_type_id' => Yii::t('app', 'อ้างอิงประเภท submission'),
            'is_event' => Yii::t('app', 'อัพโหลดเอกสารตามเหตุการณ์หรือตามอาสาสมัคร'),
            'is_api' => Yii::t('app', 'เพิ่มเอกสารจาก API'),
            'isApiLabel' => Yii::t('app', 'เพิ่มเอกสารจาก API'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument() {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'submission_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommitteePosition() {
        return $this->hasOne(CommitteePosition::className(), ['id' => 'committee_position_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefSubmissionType() {
        return $this->hasOne(SubmissionType::className(), ['id' => 'ref_submission_type_id']);
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

    public function getIsRequireLabel() {
        return $this->is_require ? '<span class="label label-danger">' . Yii::t('app', 'ใช่') . '</span>' : '<span class="label label-success">' . Yii::t('app', 'ถ้าเกี่ยวข้อง') . '</span>';
    }
    public function getIsApiLabel() {
        return $this->is_api ? '<span class="label label-danger">' . Yii::t('app', 'ใช่') . '</span>' : '<span class="label label-success">' . Yii::t('app', 'ไม่') . '</span>';
    }

    /**
     * @inheritdoc
     * @return DocumentSubmissionTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new DocumentSubmissionTypeQuery(get_called_class());
    }

}
