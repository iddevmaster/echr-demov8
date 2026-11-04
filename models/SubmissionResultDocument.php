<?php

namespace app\models;

use app\components\Crec;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "submission_result_document".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $name ชื่อเอกสาร
 * @property int $submission_id submission
 * @property int $result_document_id ไฟล์ต้นแบบ
 * @property string $document_file ไฟล์ที่ upload ขึ้นมา
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property int $submission_committee_revise_id 
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property int $srd_crec_id รหัส submission_result_document_id ของ CREC
 * @property int $is_site 0=ไม่ใช่เอกสาร Site,1=เอกสาร Site
 * @property string $coa_token  token
 * @property string $code  code
 * @property string $qrcode  qrcode

 *
 * @property SubmissionCommitteeRevise $submissionCommitteeRevise
 * @property ResultDocument $resultDocument
 * @property Submission $submission
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionResultDocument extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_result_document';
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
            [['document_file'], 'file', 'skipOnEmpty' => false, 'on' => self::SCENARIO_CREATE],
            [['submission_id', 'result_document_id', 'created_by', 'updated_by', 'submission_committee_revise_id', 'deleted', 'is_site', 'srd_crec_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'coa_token', 'code', 'qrcode'], 'string', 'max' => 255],
            [['submission_committee_revise_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommitteeRevise::className(), 'targetAttribute' => ['submission_committee_revise_id' => 'id']],
            [['result_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResultDocument::className(), 'targetAttribute' => ['result_document_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
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
            'name' => Yii::t('app', 'ชื่อเอกสาร'),
            'submission_id' => Yii::t('app', 'submission'),
            'result_document_id' => Yii::t('app', 'ไฟล์ต้นแบบ'),
            'document_file' => Yii::t('app', 'ไฟล์ที่ upload ขึ้นมา'),
            'code' => Yii::t('app', 'เลขที่การแจ้งผล'),
            'qrcode' => Yii::t('app', 'Qr Code'),
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
    public function getSubmissionCommitteeRevise() {
        return $this->hasOne(SubmissionCommitteeRevise::className(), ['id' => 'submission_committee_revise_id']);
    }

    public function getResultDocument() {
        return $this->hasOne(ResultDocument::className(), ['id' => 'result_document_id']);
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

    public function generateCode() {
        if (isset($this->code)) {
            return $this->code;
        }
        $beYear = Yii::$app->util->getBEYearEng(new \DateTime());
        $runNo = RunningCode::getRunningNo($beYear, 1);
        $padRunNo = str_pad($runNo, 5, '0', STR_PAD_LEFT);
        $code = "{$beYear}.{$padRunNo}";
        return $code;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUserProfile() {
        return $this->hasOne(Person::className(), ['user_id' => 'id'])
                        ->via('updatedBy');
    }

    public function getPath() {
        return "uploads/submission-result-document-file";
    }

    public function getFilePath() {
        return Yii::getAlias("@app/web/{$this->path}/{$this->document_file}");
    }

    public function getFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->path}/{$this->document_file}");
    }

    public function getFileIconClass() {
        $info = pathinfo($this->document_file);
        if (in_array($info['extension'], ['doc', 'docx'])) {
            return 'icon fa-file-word-o';
        } else if (in_array($info['extension'], ['pdf'])) {
            return 'icon fa-file-pdf-o';
        } else {
            return 'icon fa-file-text-o';
        }
    }

    public function getFileIconHtml() {
        if ($this->document_file) {
            $name = "<i class='font-size-20 {$this->fileIconClass}'></i>";
            return \yii\helpers\Html::a($name,
                            // $this->fileUrl, 
                            Url::to(['submission-result-document/download', 'id' => $this->id]),
                            ['target' => '_blank', 'data-pjax' => 0]);
        } else {
            return '';
        }
    }

    public function getDownloadLink() {
        $link = '';
        if (!isset($this->srd_crec_id) && isset($this->document_file)) {
            return $this->getFileIconHtml();
        } else if (isset($this->srd_crec_id)) {
            $link = Html::a(
                            '<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " Download File "),
                            Crec::getApiUrl(Crec::DOWNLOAD_SUBMISSION_RESULT_DOCUMENT_FILE) . "&id={$this->srd_crec_id}",
                            ['target' => '_blank', 'data-pjax' => 0]
            );
        }

        return $link;
    }

    public function getViewLink() {
        $link = '';
        if (!isset($this->srd_crec_id) && isset($this->document_file)) {
            $link = $this->viewFilePdf;
        } else if (isset($this->srd_crec_id)) {
            $link = Html::a(
                            '<i class="icon wb-zoom-in"></i> View File ',
                            Crec::getApiUrl(Crec::VIEW_SUBMISSION_RESULT_DOCUMENT_FILE) . "&id={$this->srd_crec_id}",
                            ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'View File')]
            );
        }
        return $link;
    }

    public function getViewFilePdf() {
        //        $info = pathinfo($this->file_name);

        if (isset($this->document_file)) {
            $info = pathinfo($this->getFilePath());
            if (in_array($info['extension'], ['pdf'])) {
                return \yii\helpers\Html::a(' <i class="icon wb-zoom-in"></i>  ', ['submission-result-document/view-file', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'view pdf file')]);
            }
        }
        return '';
    }

    public function getFileLink() {
        if (!isset($this->srd_crec_id) && !isset($this->document_file)) {
            return Yii::t('app', "ไม่มีไฟล์");
        }
        return "{$this->downloadLink} {$this->viewLink}";
    }

    /**
     * @inheritdoc
     * @return SubmissionResultDocumentQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionResultDocumentQuery(get_called_class());
    }

    public static function toArrayData($objects) {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties() {
        return [
            \app\models\SubmissionResultDocument::class => [
                'id',
                'submission_id',
                'result_document_id',
                'document_file',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'submission_committee_revise_id',
                'name',
                'srd_crec_id',
                'is_site',
                'resultDocument' => function ($model) {
                    return $model->resultDocument ?? null;
                }
            ]
        ];
    }

}
