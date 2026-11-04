<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sae_assess_form".
 *
 * @property int $id
 * @property int $submission_id
 * @property int $submission_committee_id
 * @property int $review_choice_id ชนิดรายงาน
 * @property string $review_choice_text ชนิดรายงานอื่นๆ
 * @property int $sae_total จำนวนอาสาสมัครที่เกิดเหตุการณ์ไม่พึงประสงค์
 * @property int $sae_for เกิดเหตุการณ์จากต่างประเทศ
 * @property int $sae_for_fatal เกิดเหตุการณ์จากต่างประเทศและเสียชีวิต
 * @property int $sae_dom เกิดเหตุการณ์ในประเทศ
 * @property int $sae_dom_fatal เกิดเหตุการณ์ในประเทศและเสียชีวิต
 * @property int $ec เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC
 * @property int $ec_fatal เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC และเสียชีวิต
 * @property int $ec_cure อาสาสมัครได้รับการรักษาจนเป็นปกติ
 * @property int $ec_not_cure อาสาสมัครไม่ได้รับการรักษา
 * @property int $ec_unknown_cure อาสาสมัครยังไม่ทราบผลการรักษา
 * @property int $ec_drug ผู้วิจัยประเมินเบื้องต้นสัมพันธ์กับยาวิจัย
 * @property int $ec_not_drug ผู้วิจัยประเมินเบื้องต้นไม่สัมพันธ์กับยาวิจัย
 * @property int $ec_unknown_drug ผู้วิจัยประเมินเบื้องต้นยังไม่ทราบผลสัมพันธ์กับยาวิจัย
 * @property int $resolution_id มติกรรมการ
 * @property string $suggestion ข้อคิดเห็นเพิ่มเติม
 * @property string $condition รับทราบโดยมีเง่ื่อนไข
 * @property string $addition ชี้แจงเพิ่มเติม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $volunteer_id อาสาสมัคร
 *
 * @property User $createdBy
 * @property Resolution $resolution
 * @property SubmissionCommittee $submissionCommittee
 * @property Submission $submission
 * @property User $updatedBy
 * @property Volunteer $volunteer
 */
class SaeAssessForm extends \yii\db\ActiveRecord {

    const YES = 1;
    const NO = 2;
    const UNKNOWN = 3;

    public $hasEcFatal;
    public $cureSelections;
    public $drugSelections;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'sae_assess_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['resolution_id'], 'required'],
            [['submission_id', 'submission_committee_id', 'review_choice_id', 'sae_total', 'sae_for', 'sae_for_fatal', 'sae_dom', 'sae_dom_fatal', 'ec', 'ec_fatal', 'ec_cure', 'ec_not_cure', 'ec_unknown_cure', 'ec_drug', 'ec_not_drug', 'ec_unknown_drug', 'resolution_id', 'deleted', 'created_by', 'updated_by', 'hasEcFatal'], 'integer'],
            [['suggestion', 'condition', 'addition', 'review_choice_text'], 'string'],
            [['created_at', 'updated_at', 'cureSelections', 'drugSelections'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['resolution_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resolution::className(), 'targetAttribute' => ['resolution_id' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['volunteer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Volunteer::className(), 'targetAttribute' => ['volunteer_id' => 'id']],
            [['review_choice_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['review_choice_id' => 'id']],
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
            'review_choice_id' => Yii::t('app', 'ชนิดรายงาน'),
            'review_choice_text' => Yii::t('app', 'ชนิดรายงานอื่นๆ'),
            'sae_total' => Yii::t('app', 'จำนวนอาสาสมัครที่เกิดเหตุการณ์ไม่พึงประสงค์'),
            'sae_for' => Yii::t('app', 'เกิดเหตุการณ์จากต่างประเทศ'),
            'sae_for_fatal' => Yii::t('app', 'เกิดเหตุการณ์จากต่างประเทศและเสียชีวิต'),
            'sae_dom' => Yii::t('app', 'เกิดเหตุการณ์ในประเทศ'),
            'sae_dom_fatal' => Yii::t('app', 'เกิดเหตุการณ์ในประเทศและเสียชีวิต'),
            'ec' => Yii::t('app', 'เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC'),
            'ec_fatal' => Yii::t('app', 'เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC และเสียชีวิต'),
            'ec_cure' => Yii::t('app', 'อาสาสมัครได้รับการรักษาจนเป็นปกติ'),
            'ec_not_cure' => Yii::t('app', 'อาสาสมัครไม่ได้รับการรักษา'),
            'ec_unknown_cure' => Yii::t('app', 'อาสาสมัครยังไม่ทราบผลการรักษา'),
            'ec_drug' => Yii::t('app', 'ผู้วิจัยประเมินเบื้องต้นสัมพันธ์กับยาวิจัย'),
            'ec_not_drug' => Yii::t('app', 'ผู้วิจัยประเมินเบื้องต้นไม่สัมพันธ์กับยาวิจัย'),
            'ec_unknown_drug' => Yii::t('app', 'ผู้วิจัยประเมินเบื้องต้นยังไม่ทราบผลสัมพันธ์กับยาวิจัย'),
            'resolution_id' => Yii::t('app', 'มติกรรมการ'),
            'suggestion' => Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม'),
            'condition' => Yii::t('app', 'รับทราบโดยมีเง่ื่อนไข'),
            'addition' => Yii::t('app', 'ชี้แจงเพิ่มเติม'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'volunteer_id' => Yii::t('app', 'อาสาสมัคร'),
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

    public function afterFind() {
        parent::afterFind();
        $this->hasEcFatal = $this->ec_fatal > 0 ? 1 : 0;
        $this->cureSelections = [];
        if (!empty($this->ec_cure)) {
            $this->cureSelections[] = self::YES;
        }
        if (!empty($this->ec_not_cure)) {
            $this->cureSelections[] = self::NO;
        }
        if (!empty($this->ec_unknown_cure)) {
            $this->cureSelections[] = self::UNKNOWN;
        }
        $this->drugSelections = [];
        if (!empty($this->ec_drug)) {
            $this->drugSelections[] = self::YES;
        }
        if (!empty($this->ec_not_drug)) {
            $this->drugSelections[] = self::NO;
        }
        if (!empty($this->ec_unknown_drug)) {
            $this->drugSelections[] = self::UNKNOWN;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVolunteer() {
        return $this->hasOne(Volunteer::className(), ['id' => 'volunteer_id']);
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
    public function getResolution() {
        return $this->hasOne(Resolution::className(), ['id' => 'resolution_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewChoice() {
        return $this->hasOne(ReviewChoice::className(), ['id' => 'review_choice_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getSaeVolunteers() {
        return $this->hasMany(SaeVolunteer::className(), [
                    'submission_id' => 'submission_id',
                    'submission_committee_id' => 'submission_committee_id',
                ])->isDeleted(false);
    }

    public function updateVounteerCount() {
        $this->ec_cure = $this->getSaeVolunteers()->isCured()->count();
        $this->ec_not_cure = $this->getSaeVolunteers()->isCured(0)->count();
        $this->ec_fatal = $this->getSaeVolunteers()->isDead()->count();
        $notDead = $this->getSaeVolunteers()->isDead(0)->count();
        $this->ec = $this->ec_fatal + $notDead;
        $this->ec_drug = $this->getSaeVolunteers()->isDrug()->count();
        $this->ec_not_drug = $this->getSaeVolunteers()->isDrug(0)->count();
        $this->sae_total = $this->getSaeVolunteers()->count();
    }

    /**
     * {@inheritdoc}
     * @return SaeAssessFormQuery the active query used by this AR class.
     */
    public static function find() {
        return new SaeAssessFormQuery(get_called_class());
    }

    public static function getHasEcFatalLabels() {
        return [
            1 => \Yii::t('app', 'มีอาสาสมัครเสียชีวิต'),
            0 => \Yii::t('app', 'ไม่มีอาสาสมัครเสียชีวิต'),
        ];
    }

    public static function getCureLabels() {
        return [
            self::YES => \Yii::t('app', 'ใช่'),
            self::NO => \Yii::t('app', 'ไม่'),
            self::UNKNOWN => \Yii::t('app', 'ยังไม่ทราบผล'),
        ];
    }

    public static function getCureField($value) {
        if ($value == self::YES) {
            return 'ec_cure';
        } elseif ($value == self::NO) {
            return 'ec_not_cure';
        } else if ($value == self::UNKNOWN) {
            return 'ec_unknown_cure';
        }
        return null;
    }

    public static function getDrugField($value) {
        if ($value == self::YES) {
            return 'ec_drug';
        } elseif ($value == self::NO) {
            return 'ec_not_drug';
        } else if ($value == self::UNKNOWN) {
            return 'ec_unknown_drug';
        }
        return null;
    }

}
