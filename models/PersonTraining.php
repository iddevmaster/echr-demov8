<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "person_training".
 *
 * @property int $id รหัส
 * @property int $person_id ผู้เข้าอบรม
 * @property string $name_thai_course ชื่อหลักสูตรภาษาไทย
 * @property string $name_eng_course ชื่อหลักสูตรภาษาอังกฤษ
 * @property string $start_date วันที่เริ่มหลักสูตร
 * @property string $end_date วันที่สินสุดหลักสูตร
 * @property string $remark รายละเอียดเพิ่มเติม
 * @property string $file ไฟล์เพิ่มเติม
 * @property int $training_type_id ประเภทการอบรม
 * @property string $expire_date วันหมดอายุการอบรม
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Person $person
 */
class PersonTraining extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'person_training';
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
            [['person_id', 'name_thai_course'], 'required'],
            [['training_type_id'], 'required', 'message' => Yii::t('app', 'กรุณาเลือกประเภทการอบรม')],
            [['person_id', 'training_type_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['start_date', 'end_date', 'expire_date', 'created_at', 'updated_at', 'original_file'], 'safe'],
            [['remark'], 'string'],
            [['file'], 'file', 'skipOnEmpty' => true],
            [['name_thai_course', 'name_eng_course'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'รหัส'),
            'person_id' => Yii::t('app', 'ผู้เข้าอบรม'),
            'name_thai_course' => Yii::t('app', 'ชื่อหลักสูตรภาษาไทย'),
            'name_eng_course' => Yii::t('app', 'ชื่อหลักสูตรภาษาอังกฤษ'),
            'start_date' => Yii::t('app', 'วันที่อบรม'),
            'end_date' => Yii::t('app', 'วันที่สิ้นสุดหลักสูตร'),
            'remark' => Yii::t('app', 'รายละเอียดเพิ่มเติม'),
            'file' => Yii::t('app', 'ไฟล์แนบประกอบการอบรม'),
            'training_type_id' => Yii::t('app', 'ประเภทการอบรม'),
            'expire_date' => Yii::t('app', 'วันหมดอายุ'),
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
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getTrainingType() {
        return $this->hasOne(TrainingType::className(), ['id' => 'training_type_id']);
    }

    /**
     * Computes the expiry date from the training (start) date plus the
     * validity period (years) of the selected training type.
     * Returns null when there is no type, no validity period, or no start date.
     *
     * @return string|null Y-m-d formatted date, or null
     */
    public function computeExpireDate() {
        if (empty($this->training_type_id) || empty($this->start_date)) {
            return null;
        }
        $type = $this->trainingType;
        if (!isset($type) || empty($type->validity_years)) {
            return null;
        }
        $start = strtotime($this->start_date);
        if ($start === false) {
            return null;
        }
        return date('Y-m-d', strtotime('+' . (int) $type->validity_years . ' years', $start));
    }

    /**
     * Recompute expiry server-side so it is always correct regardless of
     * how the record was created (registration, admin add, import, ...).
     */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->expire_date = $this->computeExpireDate();
        return true;
    }

    /**
     * @param string|null $asOfDate Y-m-d to compare against (default today)
     * @return bool true when the training is expired as of $asOfDate
     */
    public function getIsExpired($asOfDate = null) {
        if (empty($this->expire_date)) {
            return false;
        }
        $asOf = isset($asOfDate) ? strtotime($asOfDate) : strtotime(date('Y-m-d'));
        return strtotime($this->expire_date) <= $asOf;
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

    public function getPath() {
        return "uploads/training-file/{$this->person_id}";
    }

    public function getFilePath() {
        return Yii::getAlias("@app/web/{$this->path}/{$this->file}");
    }

    public function getFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->path}/{$this->file}");
    }

    public function getFileIconClass() {
        $info = pathinfo($this->file);
        if (in_array($info['extension'], ['doc', 'docx'])) {
            return 'icon fa-file-word-o';
        } else if (in_array($info['extension'], ['pdf'])) {
            return 'icon fa-file-pdf-o';
        } else {
            return 'icon fa-file-text-o';
        }
    }

    public function getViewFilePdf() {
//        $info = pathinfo($this->file);
        $s = str_replace("-", "", $this->file, $var);
        $info = pathinfo($s);
        $view = '';
        if (isset($this->file)) {
            if (in_array($info['extension'], ['doc', 'docx'])) {
                $view = '';
            } else if (in_array($info['extension'], ['pdf'])) {
                $view = \yii\helpers\Html::a(' <i class="icon wb-zoom-in"></i>  ', ['person-training/view-file', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'view pdf file')]);
            }
        }
        return $view;
    }

    public function getFileIconHtml($withDate = FALSE) {
        if ($this->file) {
            $date = '';
            if ($withDate) {
                $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            }
            $name = "<i class='font-size-20 {$this->fileIconClass}'></i>{$date}";
            return \yii\helpers\Html::a($name,
                            // $this->fileUrl, 
                            Url::to(['person-training/download', 'id' => $this->id]),
                            ['target' => '_blank', 'data-pjax' => 0]);
        } else {
            return '';
        }
        return isset($this->file) ? \yii\helpers\Html::a("<i class='font-size-20 {$this->fileIconClass}'></i>", $this->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
    }

    /**
     * @inheritdoc
     * @return PersonTrainingQuery the active query used by this AR class.
     */
    public static function find() {
        return new PersonTrainingQuery(get_called_class());
    }

}
