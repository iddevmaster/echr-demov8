<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "review_choice".
 *
 * @property int $id
 * @property string $name ชื่อชนิดรายงาน
 * @property int $type ชนิดการพิจารณา
 * @property int $need_text ระบุข้อความหรือไม่
 * @property int $parent_id
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property ContinueAssessFormReview[] $continueAssessFormReviews
 * @property User $createdBy
 * @property ReviewChoice $parent
 * @property ReviewChoice[] $reviewChoices
 * @property User $updatedBy
 */
class ReviewChoice extends \yii\db\ActiveRecord {

    const TYPE_EXPEDITED = 1;
    const TYPE_FULL_BOARD = 2;
    //*******
    const TYPE_PROGRESS = 7;
    const TYPE_RENEW = 8;
    const TYPE_AM = 9;
    const TYPE_SAEIN = 10;
    const TYPE_SAEOUT = 11;
    const TYPE_DEV = 12;
    const TYPE_CLOSE = 13;
    const TYPE_OTHER = 15;
    const TYPE_EARLY = 21;
    const TYPE_SUSPENSION = 22;
    const TYPE_SITETER = 23;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'review_choice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['type', 'need_text', 'parent_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewChoice::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ชื่อชนิดรายงาน'),
            'type' => Yii::t('app', 'ชนิดการพิจารณา'),
            'need_text' => Yii::t('app', 'ระบุข้อความหรือไม่'),
            'parent_id' => Yii::t('app', 'Parent ID'),
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
    public function getContinueAssessFormReviews() {
        return $this->hasMany(ContinueAssessFormReview::className(), ['review_choice_id' => 'id']);
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
    public function getParent() {
        return $this->hasOne(ReviewChoice::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewChoices() {
        return $this->hasMany(ReviewChoice::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren() {
        return $this->hasMany(ReviewChoice::className(), ['parent_id' => 'id'])->isDeleted(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return ReviewChoiceQuery the active query used by this AR class.
     */
    public static function find() {
        return new ReviewChoiceQuery(get_called_class());
    }

}
