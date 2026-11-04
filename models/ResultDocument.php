<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "result_document".
 *
 * @property int $id รหัสอัตโนมัติ
 * @property string $name ชื่อ
 * @property string $resolution ผลมติ
 * @property string $committee_resolution ผลมติของกรรมการ
 * @property string $template_file ไฟล์ต้นแบบ
 * @property string $remark หมายเหตุ
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 *
 * @property AgendaResultDocument[] $agendaResultDocuments
 * @property User $createdBy
 * @property User $updatedBy
 * @property SubmissionResultDocument[] $submissionResultDocuments
 */
class ResultDocument extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'result_document';
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
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'resolution', 'committee_resolution', 'template_file', 'remark', 'name_eng'], 'string', 'max' => 255],
            [['deleted'], 'string', 'max' => 1],
            //[['template_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,gif,pdf,doc,xls,docx,xlsx,ppt,pptx,rar,zip'],
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
            'id' => Yii::t('app', 'รหัสอัตโนมัติ'),
            'name' => Yii::t('app', 'ชื่อเอกสารแจ้งผลต้นแบบ'),
            'name_eng' => Yii::t('app', 'ชื่อเอกสารแจ้งผลต้นแบบ(ภาษาอังกฤษ)'),
            'resolution' => Yii::t('app', 'ผลมติ'),
            'committee_resolution' => Yii::t('app', 'ผลมติของกรรมการ'),
            'template_file' => Yii::t('app', 'ไฟล์ต้นแบบ'),
            'remark' => Yii::t('app', 'หมายเหตุ'),
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
    public function getAgendaResultDocuments() {
        return $this->hasMany(AgendaResultDocument::className(), ['result_document_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionResultDocuments() {
        return $this->hasMany(SubmissionResultDocument::className(), ['result_document_id' => 'id']);
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

    public function getTemplatePath() {
        return "uploads/result-document-template";
    }

    public function getTemplateFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->templatePath}/{$this->template_file}");
    }

    public static function addNoApproveWatermark($inputFile, $outputFile) {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => Yii::getAlias('@app/runtime'),
            'fontDir' => array_merge($fontDirs, [
                Yii::getAlias('@app/web/fonts'),
            ]),
            'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
        ]
            ],
            'default_font' => 'sarabun'
        ]);
        $pagecount = $mpdf->setSourceFile($inputFile);
        for ($i = 1; $i <= $pagecount; ++$i) {
            $tplId = $mpdf->importPage($i);
            $size = $mpdf->GetTemplateSize($tplId);
            $orientation = $size['orientation'];
            $w = $size['width'];
            $h = $size['height'];
            // $orientation = $size['w'] > $size['h'] ? 'L' : 'P';
            if ($orientation == 'L') {
                $w = $size['height'];
                $h = $size['width'];
            } else {
                $w = $size['width'];
                $h = $size['height'];
            }
            $mpdf->AddPage(
                    $orientation,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    1,
                    1,
                    1,
                    1,
                    '',
                    [$w, $h]
            );

            $mpdf->useTemplate($tplId);
            $url = 'https://crec-ec.submission-online.com/images/noApproved.png';
            Yii::$app->formatter->locale = 'en-US';
//            $text = isset($certifiedDate) ? Yii::$app->formatter->asDate($certifiedDate, 'php:d M Y') : "";
//            $ltext = Yii::t('app', 'วันที่รับรอง');
            $html = <<<h
   
         <div style="position: fixed; top: 25%; left: 20%;">    
    <img src="{$url}" width="350" style = "opacity:0.3;" />
    </div>


    
h;
            // Yii::error($html);
            $mpdf->WriteHTML($html);
            // break;
        }
        $mpdf->Output($outputFile, \Mpdf\Output\Destination::FILE);
    }

    public function getTemplatePathAlias() {
        return Yii::getAlias("@app/web/{$this->templatePath}/{$this->template_file}");
    }

    public function getFileIconClass() {
        $info = pathinfo($this->template_file);
        if (in_array($info['extension'], ['doc', 'docx'])) {
            return 'icon fa-file-word-o';
        } else if (in_array($info['extension'], ['pdf'])) {
            return 'icon fa-file-pdf-o';
        } else {
            return 'icon fa-file-text-o';
        }
    }

    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    /**
     * @inheritdoc
     * @return ResultDocumentQuery the active query used by this AR class.
     */
    public static function find() {
        return new ResultDocumentQuery(get_called_class());
    }

}
