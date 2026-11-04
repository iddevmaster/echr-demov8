<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "c_assess_form".
 *
 * @property int $id
 * @property int $submission_id
 * @property int $submission_committee_id
 * @property int $opinion สรุปความเห็นโดยรวม
 * @property string $opinion_remark ข้อคิดเห็นเพิ่มเติม
 * @property string $suggestion ข้อเสนอแนะอื่นๆ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $updatedBy
 */
class CAssessForm extends \yii\db\ActiveRecord {

    const OPINION_Y = 1;
    const OPINION_C = 2;
    const OPINION_R = 3;
    const OPINION_RC = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'c_assess_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['opinion'], 'required'],
            [['submission_id', 'submission_committee_id', 'opinion', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['opinion_remark', 'suggestion'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'submission_id' => Yii::t('app', 'Submission ID'),
            'submission_committee_id' => Yii::t('app', 'Submission Committee ID'),
            'opinion' => Yii::t('app', 'สรุปความเห็นโดยรวม'),
            'opinion_remark' => Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม'),
            'suggestion' => Yii::t('app', 'ข้อเสนอแนะอื่นๆ'),
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
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionCommittee() {
        return $this->hasOne(SubmissionCommittee::className(), ['id' => 'submission_committee_id']);
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
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return CAssessFormQuery the active query used by this AR class.
     */
    public static function find() {
        return new CAssessFormQuery(get_called_class());
    }

    public static function getOpinionLabels() {
        return [
            self::OPINION_Y => \Yii::t('app', 'ผู้วิจัยแก้ไขครบถ้วนแล้วเห็นควรออกหนังสือรับรอง'),
            self::OPINION_C => \Yii::t('app', 'ผู้วิจัยแก้ไขแล้วเป็นส่วนใหญ่แต่มีบางประเด็นที่ผู้วิจัยควรชี้แจงเพิ่มเติมเห็นควรส่งกลับไปแก้ไขอีกครั้ง'),
            self::OPINION_R => \Yii::t('app', 'ผู้วิจัยแก้ไขและชี้แจงแล้วแต่มีประเด็นที่สมควรให้ที่ประชุมพิจารณาอีกครั้ง'),
            self::OPINION_RC => \Yii::t('app', 'ผู้วิจัยแก้ไขและชี้แจงแล้วแต่มีประเด็นที่สมควรส่งกรรมการผู้ประเมิน'),
        ];
    }

    public static function getOpinion() {
        return [
            self::OPINION_Y,
            self::OPINION_C,
            self::OPINION_R,
            self::OPINION_RC,
        ];
    }

}
