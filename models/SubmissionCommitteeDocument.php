<?php

namespace app\models;

use app\components\Crec;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "submission_committee_document".
 *
 * @property int $id รหัส
 * @property string $name ชื่อเอกสาร
 * @property string $file_name ไฟล์เอกสาร
 * @property string $remark หมายเหตุ
 * @property int $submission_committee_id กรรมการในโครงการวิจัย
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $version เวอร์ชันเอกสาร
 * @property string $version_at วันที่เวอร์ชันเอกสาร
 * @property int $document_id ประเภทเอกสาร
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $project_id โครงการวิจัย
 * * @property int $crec_document_id รหัสเอกสารจาก CREC
 *
 * @property Document $document
 * @property Project $project
 * @property Submission $submission
 * @property SubmissionCommittee $submissionCommittee
 * @property User $createdBy
 * @property User $updatedBy
 */
class SubmissionCommitteeDocument extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'submission_committee_document';
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
            [['submission_committee_id', 'deleted', 'created_by', 'updated_by', 'project_id', 'document_id', 'submission_id', 'crec_document_id'], 'integer'],
            [['created_at', 'updated_at', 'version_at'], 'safe'],
            [['file_name'], 'file', 'skipOnEmpty' => false, 'on' => self::SCENARIO_CREATE],
            [['name', 'remark', 'version'], 'string', 'max' => 255],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['submission_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionCommittee::className(), 'targetAttribute' => ['submission_committee_id' => 'id']],
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
            'name' => Yii::t('app', 'ชื่อเอกสาร'),
            'file_name' => Yii::t('app', 'ไฟล์เอกสาร'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'submission_committee_id' => Yii::t('app', 'กรรมการในโครงการวิจัย'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'document.documentSubmissionTypes.isRequireLabel' => Yii::t('app', 'ต้องแนบเอกสาร'),
            'version' => Yii::t('app', 'เวอร์ชันเอกสาร'),
            'version_at' => Yii::t('app', 'วันที่เวอร์ชันเอกสาร'),
            'crec_document_id' => Yii::t('app', 'รหัสเอกสารจาก CREC'),
        ];
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

    public function getDocument() {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject() {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
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
    public function getSubmissionCommittee() {
        return $this->hasOne(SubmissionCommittee::className(), ['id' => 'submission_committee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getDocumentSubmissionTypes() {
        return $this->hasMany(DocumentSubmissionType::className(), ['submission_type_id' => 'submission_type_id'])
//                        ->andWhere(['document_submission_type.document_id'=>$this->document_id])
                        ->viaTable('submission', ['id' => 'submission_id']);
//                        ->andWhere('document_submission_type.document_id=submission_document.document_id');
//        return DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($this->submission->submission_type_id)->document($this->document_id)->one();
    }

    public function getDocumentSubmissionType() {
        return $this->getDocumentSubmissionTypes()->document($this->document_id)->committeePosition($this->submissionCommittee->committee_position_id)->one();
//        if (isset($this->submission)) {
//            return DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($this->submission->submission_type_id)->document($this->document_id)->one();
//        } else {
//            return NULL;
//        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPath() {
        return "uploads/submission-committee-document/{$this->submission_id}";
    }

    public function getFilePath() {
        return Yii::getAlias("@app/web/{$this->path}/{$this->file_name}");
    }

    public function getFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->path}/{$this->file_name}");
    }

    public function getFileIconClass() {
        $info = pathinfo($this->file_name);
        if (in_array($info['extension'], ['doc', 'docx'])) {
            return 'icon fa-file-word-o';
        } else if (in_array($info['extension'], ['pdf'])) {
            return 'icon fa-file-pdf-o';
        } else {
            return 'icon fa-file-text-o';
        }
    }

    public function getFileIconHtml($withDate = FALSE) {
        if ($this->file_name) {
            $date = '';
            if ($withDate) {
                $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            }
            $name = "<i class='font-size-20 {$this->fileIconClass}'></i>{$date}";
            return \yii\helpers\Html::a($name, 
            // $this->fileUrl, 
            Url::to(['submission-committee-document/download', 'id' => $this->id]), 
            ['target' => '_blank', 'data-pjax' => 0]);
        } else {
            return '';
        }
        return isset($this->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$this->fileIconClass}'></i>", $this->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
    }

    public function getDocumentHistories() {
        return SubmissionCommitteeDocument::find()->isDeleted(TRUE)->documents($this->document_id)->submission($this->submission_id)->submissionCommittee($this->submission_committee_id)->notId($this->id)->orderBy(['submission_committee_document.id' => SORT_DESC])->all();
    }

    public function getDocumentHistoriesHtml() {
        $histories = $this->documentHistories;
        $res = '';
        if (isset($this->document_id)) {
            $res .= '<ul>';
            foreach ($histories as $h) {
                $res .= "<li>{$h->getFileIconHtml(TRUE)}</li>";
            }
            $res .= '</ul>';
        }
        return $res;
    }

    public function canDownloadTemplate() {
        return isset($this->document->template_file) || isset($this->crec_document_id);
    }

    public function getDownloadLink($sCommitteeId = NULL)
    {
        $link = '';
        if (!isset($this->crec_document_id) && isset($this->document->template_file)) {
            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), ['document/download-template', 'id' => $this->document_id, 'submissionId' => $this->submission_id, 'submissionComitteeId' => $sCommitteeId], ['data-pjax' => 0, 'target' => '_blank']);
        } else if (isset($this->crec_document_id)) {
            $link = Html::a(
                '<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " ดาวน์โหลดแบบฟอร์ม "),
                Crec::getApiUrl(Crec::DOWNLOAD_DOCUMENT_TEMPLATE) . "&documentId={$this->crec_document_id}&ecSubmissionId={$this->submission_id}",
                ['target' => '_blank', 'data-pjax' => 0]
            );
        }

        return $link;
    }

    public static function toArrayData($objects)
    {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties()
    {
        return [
            \app\models\SubmissionCommitteeDocument::class => [
                'id',
                'name',
                'file_name',
                'remark',
                'submission_committee_id',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'version',
                'version_at',
                'document_id',
                'submission_id',
                'project_id',
                'sd_ec_id' => 'id',
                'crec_document_id',
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return SubmissionCommitteeDocumentQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionCommitteeDocumentQuery(get_called_class());
    }

}
