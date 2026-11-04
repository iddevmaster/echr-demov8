<?php

namespace app\models;

use app\components\Crec;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Phpdocx\Create\CreateDocx;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "submission_document".
 *
 * @property int $id รหัส
 * @property string $name ชื่อเอกสาร
 * @property string $file_name ไฟล์เอกสาร
 * @property int $project_id โครงการวิจัย
 * @property int $status สถานะ
 * @property string $remark หมายเหตุ
 * @property int $group_doc_id ประเภทกลุ่มเอกสาร
 * @property int $document_id ประเภทเอกสาร
 * @property int $submission_id นำเสนอโครงการวิจัย
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $is_certificate 0=ใช้รับรอง,1=ไม่ใช้รับรอง
 * @property int $is_site 0=ไม่ใช่เอกสาร Site,1=เอกสาร Site
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $version เวอร์ชันเอกสาร
 * @property string $version_at วันที่เวอร์ชันเอกสาร
 * @property string $name_eng ชื่อภาษาอังกฤษ
 * @property int $volunteer_id อาสาสมัคร
 * @property int $event_no เลขเหตุการณ์ของ Deviation
 * @property int $submission_event_id เหตุการณ์
 * @property int $sd_crec_id รหัส submission_document_id ของ CREC
 *
 * @property Document $document
 * @property Project $project
 * @property Submission $submission
 * @property SubmissionEvent $submissionEvent
 * @property User $createdBy
 * @property User $updatedBy
 * @property Volunteer $volunteer
 */
class SubmissionDocument extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    const STATUS_FAIL = 100;
    const STATUS_PASS = 200;
    const CER_YES = false;
    const CER_NO = true;
    const SCENARIO_CHECK = 'check';
    const SCENARIO_GROUP_DOCUMENT = 'group-document';
    const SCENARIO_CHECK_MULTIPLE = 'check-multiple';
    const SCENARIO_CREATE_CREC = 'create-crec';
    const GROUP_1 = 1;
    const GROUP_2 = 2;
    const GROUP_3 = 3;
    const GROUP_4 = 4;

    public $groupDocumentId;
    public $ids;

    public static function getStatusLabels() {
        return [
            self::STATUS_FAIL => Yii::t('app', 'ไม่ผ่าน'),
            self::STATUS_PASS => Yii::t('app', 'ผ่าน'),
        ];
    }

    public static function getStatusLabelCer() {
        return [
            self::CER_YES => Yii::t('app', 'เอกสารรับรอง'),
            self::CER_NO => Yii::t('app', 'ไม่ใช้ในการรับรอง'),
        ];
    }

    public static function tableName() {
        return 'submission_document';
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
//            [['name', 'version', 'version_at'], 'required', 'when' => function($model) {
//                    return !isset($model->document_id);
//                }],
//            [['groupDocumentId'], 'required', 'on' => self::SCENARIO_GROUP_DOCUMENT],
            [['status'], 'required', 'on' => [self::SCENARIO_CHECK, self::SCENARIO_CHECK_MULTIPLE]],
//            [['name'], 'required'],
            [['ids','status'], 'required', 'on' => self::SCENARIO_CHECK_MULTIPLE],
            [['name','name_eng'], 'required', 'except' => [self::SCENARIO_CHECK_MULTIPLE,self::SCENARIO_CHECK]],
            [['version', 'version_at'], 'required', 'when' => function($model) {
                    if (!isset($model->document_id)) {
                        return false;
                    }
                    $doc = Document::findOne($model->document_id);
                    return $doc->is_report;
                }],
            [['file_name'], 'file', 'skipOnEmpty' => false, 'except' => [self::SCENARIO_CHECK, self::SCENARIO_CHECK_MULTIPLE, self::SCENARIO_GROUP_DOCUMENT, self::SCENARIO_CREATE_CREC]],
            [['groupDocumentId', 'project_id', 'status', 'document_id', 'submission_id','group_doc_id', 'deleted', 'is_certificate', 'created_by', 'updated_by', 'volunteer_id', 'event_no', 'submission_event_id', 'sd_crec_id', 'is_site','position'], 'integer'],
            [['created_at', 'updated_at', 'version_at', 'ids'], 'safe'],
            [['name', 'remark', 'version', 'name_eng'], 'string', 'max' => 255],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['submission_event_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionEvent::className(), 'targetAttribute' => ['submission_event_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['groupDocumentId'], 'exist', 'skipOnError' => true, 'targetClass' => SubmissionDocument::className(), 'targetAttribute' => ['groupDocumentId' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['volunteer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Volunteer::className(), 'targetAttribute' => ['volunteer_id' => 'id']],
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
            'fileIconHtml' => Yii::t('app', 'ไฟล์เอกสาร'),
            'project_id' => Yii::t('app', 'โครงการวิจัย'),
            'status' => Yii::t('app', 'สถานะ'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
            'document_id' => Yii::t('app', 'ประเภทเอกสาร'),
            'submission_id' => Yii::t('app', 'นำเสนอโครงการวิจัย'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'documentSubmissionType.isRequireLabel' => Yii::t('app', 'ต้องแนบเอกสาร'),
            'version' => Yii::t('app', 'เวอร์ชันเอกสาร'),
            'group_doc_id' => Yii::t('app', 'กลุ่มเอกสาร'),
            'version_at' => Yii::t('app', 'วันที่เวอร์ชันเอกสาร'),
            'name_eng' => Yii::t('app', 'ชื่อภาษาอังกฤษ'),
            'i18nName' => Yii::t('app', 'ชื่อเอกสารประกอบการวิจัย'),
            'volunteer_id' => Yii::t('app', 'อาสาสมัคร'),
            'event_no' => Yii::t('app', 'เลขเหตุการณ์ของ Deviation'),
            'submission_event_id' => Yii::t('app', 'เหตุการณ์'),
            'groupDocumentId' => Yii::t('app', 'เอกสารเดียวกับ'),
            'submissionVolunteer.typeLabel' => Yii::t('app', 'ประเภทการติดตาม'),
            'is_certificate' => Yii::t('app', '0=ใช้รับรอง,1=ไม่ใช้รับรอง'),
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

    public function getIsCertificate() {
        if (isset($this->is_certificate)) {
            return self::getStatusLabelCer()[$this->is_certificate];
        }
        return '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
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
    
        public function getGroupDoc() {
        return $this->hasOne(GroupDoc::className(), ['id' => 'group_doc_id']);
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

    public function getDocumentSubmissionTypes() {
        return $this->hasMany(DocumentSubmissionType::className(), ['submission_type_id' => 'submission_type_id'])
//                        ->andWhere(['document_submission_type.document_id'=>$this->document_id])
                        ->viaTable('submission', ['id' => 'submission_id']);
//                        ->andWhere('document_submission_type.document_id=submission_document.document_id');
//        return DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($this->submission->submission_type_id)->document($this->document_id)->one();
    }

    public function getDocumentSubmissionType() {
        return $this->getDocumentSubmissionTypes()->andWhere(['document_id' => $this->document_id])->one();
//        if (isset($this->submission)) {
//            return DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($this->submission->submission_type_id)->document($this->document_id)->one();
//        } else {
//            return NULL;
//        }
    }

    public function getSubmissionVolunteer() {
        return SubmissionVolunteer::find()->isDeleted(false)->submissionId($this->submission_id)->volunteerId($this->volunteer_id)->one();
    }

    public function getNameSubmissionType() {
        $res = $this->name;
        $res .= " [" . $this->submission->submissionTypeName . "]";
        return $res;
    }

    public function getPath() {
        return "uploads/submission-document/{$this->submission_id}";
    }

    public function getFilePath() {
        return Yii::getAlias("@app/web/{$this->path}/{$this->file_name}");
    }

    public function getPdfFileName() {
        $info = pathinfo($this->file_name);
        return $info['filename'] . ".pdf";
    }

    public function getPdfFilePath() {
        return Yii::getAlias("@app/web/{$this->path}/{$this->pdfFileName}");
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

    public function getFileInitialPreview() {
        if (\file_exists($this->filePath)) {
            return Url::to(['submission-document/view-file', 'id' => $this->id]);
//            return [$this->fileUrl];
        } else {
            return [];
        }
    }

    public function getViewFilePdf() {
//        $info = pathinfo($this->file_name);
        $s = str_replace("-", "", $this->file_name, $var);
        $info = pathinfo($s);
        $view = '';
        if (isset($this->file_name)) {
            if (in_array($info['extension'], ['doc', 'docx'])) {
                $view = '';
            } else if (in_array($info['extension'], ['pdf'])) {
                $view = \yii\helpers\Html::a(' <i class="icon wb-zoom-in"></i>  ', ['submission-document/view-file', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'view pdf file')]);
            }
        }
        return $view;
    }

    public function getFileIconHtml($withDate = FALSE) {
        if ($this->file_name) {
            $view = $this->viewFilePdf;
            $date = '';
            if ($withDate) {
                $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            }
            $name = "<font style='color: #C6C6D1' ><i class='font-size-20 {$this->fileIconClass}'></i>{$date} </font>";
            return \yii\helpers\Html::a($name,
                            // $this->fileUrl, 
                            Url::to(['submission-document/download', 'id' => $this->id]),
                            ['target' => '_blank', 'data-pjax' => 0]) . $view;
        } else {
            return '';
        }
        return isset($this->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$this->fileIconClass}'></i>", $this->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
    }

    public function getDownloadLink() {
        $link = '';
        if (!isset($this->sd_crec_id) && isset($this->file_name)) {
            $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            $link = \yii\helpers\Html::a("<i class='font-size-20 {$this->fileIconClass}'></i> {$date}", ['submission-document/download', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'Download File')]);
        } else if (isset($this->sd_crec_id)) {
            $link = Html::a(
                            '<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " Download File "),
                            Crec::getApiUrl(Crec::DOWNLOAD_SUBMISSION_DOCUMENT_FILE) . "&id={$this->sd_crec_id}",
                            ['target' => '_blank', 'data-pjax' => 0]
            );
        }

        return $link;
    }

    public function getDownloadLinkHistory() {
        $link = '';
        if (!isset($this->sd_crec_id) && isset($this->file_name)) {
            $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            $link = \yii\helpers\Html::a("<font style='color: #C6C6D1' ><i class='font-size-20 {$this->fileIconClass}'></i> {$date} </font>", ['submission-document/download', 'id' => $this->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'Download File')]);
        } else if (isset($this->sd_crec_id)) {
            $link = Html::a(
                            '<font style="color: #C6C6D1"><i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i></font> ' . Yii::t('app', " Download File "),
                            Crec::getApiUrl(Crec::DOWNLOAD_SUBMISSION_DOCUMENT_FILE) . "&id={$this->sd_crec_id}",
                            ['target' => '_blank', 'data-pjax' => 0]
            );
        }

        return $link;
    }

    public static function getDownloadMergeLink($submission) {
        if (!$submission->isFromCrec()) {
            $link = Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge']);
        } else {
            $link = Html::a(
                            '<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " Download File "),
                            Crec::getApiUrl(Crec::DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE),
                            ['target' => '_blank', 'data-pjax' => 0, 'class' => 'btn btn-success btn-raised btn-download-merge']
            );
            $link = Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge']);
        }

        return $link;
    }

    public function getViewLink() {
        $link = '';
        if (!isset($this->sd_crec_id) && isset($this->file_name)) {
            $link = $this->viewFilePdf;
        } else if (isset($this->sd_crec_id)) {
            $link = Html::a(
                            '<i class="icon wb-zoom-in"></i> View File ',
                            Crec::getApiUrl(Crec::VIEW_SUBMISSION_DOCUMENT_FILE) . "&id={$this->sd_crec_id}",
                            ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'View File')]
            );
        }
        return $link;
    }

    public function getFileLink() {
        return "{$this->downloadLink} {$this->viewLink}";
    }

    public function getFileIconHtmlHistory($withDate = FALSE) {
        if ($this->file_name) {
            $view = $this->viewFilePdf;
            $date = '';
            if ($withDate) {
                $date = " (" . Yii::$app->formatter->asDate($this->created_at) . ")";
            }
//            $name = "<i class='font-size-20 {$this->fileIconClass}'></i>{$date} ";
            $name = "<font style='color: #C6C6D1' ><i class='font-size-20 {$this->fileIconClass}'></i>{$date} </font>";

            return \yii\helpers\Html::a($name,
                            // $this->fileUrl, 
                            Url::to(['submission-document/download', 'id' => $this->id]),
                            ['target' => '_blank', 'data-pjax' => 0]) . $view;
        } else {
            return '';
        }
        return isset($this->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$this->fileIconClass}'></i>", $this->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
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
    public function getVolunteer() {
        return $this->hasOne(Volunteer::className(), ['id' => 'volunteer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionEvent() {
        return $this->hasOne(SubmissionEvent::className(), ['id' => 'submission_event_id']);
    }

    public function getDocumentHistories() {
        if (isset($this->document_id)) {
            return SubmissionDocument::find()->isDeleted(TRUE)
                            ->documents($this->document_id)->submission($this->submission_id)
                            ->notId($this->id)->volunteerId($this->volunteer_id)
                            ->orderBy(['submission_document.id' => SORT_DESC])->all();
        } else {
            return SubmissionDocument::find()->isDeleted(TRUE)
                            ->name($this->name)->submission($this->submission_id)
                            ->notId($this->id)->volunteerId($this->volunteer_id)
                            ->orderBy(['submission_document.id' => SORT_DESC])->all();
        }
    }

    public function getDocumentHistoriesHtml() {
        $histories = $this->documentHistories;
        $res = '';
        if (count($histories) > 0) {
            $res .= '<ul>';
            foreach ($histories as $h) {
                $res .= "<li>{$h->getDownloadLinkHistory()}</li>";
            }
            $res .= '</ul>';
        }
        return $res;
    }

    public function getNameWithVersion() {
        $name = $this->name;
        $name .= !empty($this->version) ? " {$this->version}" : "";
        $name .= isset($this->version_at) ? " " . \Yii::$app->formatter->asDate($this->version_at, 'long') : "";
        return $name;
    }

    public function getCertificateName() {
        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'th';
        $name = $this->name;
        $name .= !empty($this->version) ? " เวอร์ชั่น {$this->version}" : "";
        $name .= isset($this->version_at) ? " ฉบับลงวันที่ " . \Yii::$app->formatter->asDate($this->version_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate(time(), 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($this->version_at, 'php:Y') + 543) : "";
        \Yii::$app->formatter->locale = $lc;
        return $name;
    }

    public function getCertificateNameEng() {
        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'en';
        $name = $this->name_eng;
        $name .= !empty($this->version) ? ", version {$this->version}" : "";
        $name .= isset($this->version_at) ? ", dated " . \Yii::$app->formatter->asDate($this->version_at, 'php:d F Y') : "";
        \Yii::$app->formatter->locale = $lc;
        return $name;
    }

    public function convertToPdf() {
        $info = pathinfo($this->file_name);
        if (in_array($info['extension'], ['pdf'])) {
            return;
        }

        if (file_exists($this->pdfFilePath)) {
            return;
        }

        $docx = new CreateDocx();
        $docx->transformDocument($this->filePath, $this->pdfFilePath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);
    }

    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    public function getEndorseHistories() {
        $query = SubmissionDocument::find()->isDeleted(false)
                ->joinWith(['submission', 'submission.submissionType'])
                ->documents($this->document_id)->notId($this->id)
                ->andWhere(['or', [
                        'submission.submission_type_id' => SubmissionType::TYPE_AMENDMENT
                    ], [
                        'submission_type.submission_type_group_id' => SubmissionTypeGroup::GROUP_NEW
                    ]
                ])
                ->andFilterCompare('submission_document.file_name', "<>{$this->file_name}")
                ->andWhere(['submission.project_id' => $this->submission->project_id])
                ->andFilterCompare('submission_document.submission_id', "<{$this->submission_id}")
                ->orderBy('submission_document.id DESC');
        if (!isset($this->document_id)) {
            $query->name($this->name);
        }
        return $query->all();
    }

    public function getAvailableGroupDocuments() {
        $docs = $this->submission->project->getLatestEndorseDocuments();
        $results = [];
        foreach ($docs as $doc) {
            if ($doc->submission_id != $this->submission_id) {
                $results[] = $doc;
            }
        }
        return $results;
    }

    public function isAbleToCheck() {

        $currentRole = \Yii::$app->session->get('currentRole');
        $ec = !isset($this->sd_crec_id) && ((isset($this->submission->ref_submission_id) && $this->submission->status < Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && $this->submission->status > Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER) || ($this->status <= 200 and $this->submission->status == Submission::STATUS_SUBMITTED)) && ($currentRole['role_id'] == \app\models\Role::STAFF);
        $crec = isset($this->sd_crec_id) && $this->is_site && ((isset($this->submission->ref_submission_id) && $this->submission->status < Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && $this->submission->status > Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER) || (($this->status <= 200 and $this->submission->status == Submission::STATUS_SUBMITTED) || ($this->status < 200 and $this->submission->status > Submission::STATUS_SUBMITTED))) && ($currentRole['role_id'] == \app\models\Role::STAFF);
        return $ec || $crec;
    }

    /**
     * @inheritdoc
     * @return SubmissionDocumentQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubmissionDocumentQuery(get_called_class());
    }

    public static function updateLatestDocs($baseDocs, $docs) {
        foreach ($docs as $doc) {
            $baseFilterDocs = [];
            if (isset($doc->document_id)) {
                $baseFilterDocs = array_filter($baseDocs, function($baseDoc) use ($doc) {
                    return $baseDoc->document_id == $doc->document_id;
                });
            } else {
                $baseFilterDocs = array_filter($baseDocs, function($baseDoc) use ($doc) {
                    return trim($baseDoc->name) == trim($doc->name);
                });
            }
            if (empty($baseFilterDocs)) {
                if (!isset($doc->document_id)) {
                    $baseDocs[] = $doc;
                }
                continue;
            }
            $keys = array_keys($baseFilterDocs);
            $replace = [$keys[0] => $doc];
            $baseDocs = array_replace($baseDocs, $replace);
        }
        return $baseDocs;
    }

    public static function toArrayData($objects) {
        return \yii\helpers\ArrayHelper::toArray($objects, self::toArrayProperties());
    }

    public static function toArrayProperties() {
        return [
            \app\models\SubmissionDocument::class => [
                'id',
                'name',
                'file_name',
                'project_id',
                'status',
                'remark',
                'document_id',
                'submission_id',
                'deleted',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'version',
                'version_at',
                'name_eng',
                'volunteer_id',
                'event_no',
                'submission_event_id',
                'sd_crec_id',
                'is_site',
            ]
        ];
    }

}
