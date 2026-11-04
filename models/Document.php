<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id รหัสทะเบียนเอกสารที่ต้องส่งในแต่ละประเภทโครงการ
 * @property string $name ทะเบียนเอกสารที่ต้องส่งในแต่ละประเภทโครงการ
 * @property int $number จำนวน
 * @property int $deleted 0=ใช้งาน,1=ไม่ใช้งาน
 * @property int $created_by สร้างโดย
 * @property string $created_at สร้างเมื่อ
 * @property int $updated_by ปรับปรุงโดย
 * @property string $updated_at ปรับปรุงเมื่อ
 * @property string $template_file ไฟล์ต้นแบบ
 * @property string $template_file_eng ไฟล์ต้นแบบ
 * @property int $role_id หน้าที่สำหรับการใช้งาน
 * @property int $is_report เอกสารกำหนดให้อยู่หนังสือรับรอง
 * @property string $name_eng ชื่อภาษาอังกฤษ
 * @property string $remark รายละเอียดเพิ่มเติมเกี่ยวกับเอกสาร
 * @property string $remark_eng รายละเอียดเพิ่มเติมเกี่ยวกับเอกสารภาษาอังกฤษ
 *
 * @property Role $role
 * @property User $createdBy
 * @property User $updatedBy
 * @property DocumentSubmissionType[] $documentSubmissionTypes
 * @property SubmissionDocument[] $submissionDocuments
 */
class Document extends \yii\db\ActiveRecord {

    const LANG_DOM = 1;
    const LANG_ENG = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'document';
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
            [['number', 'deleted', 'created_by', 'updated_by', 'role_id', 'is_report'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'template_file', 'template_file_eng', 'name_eng', 'remark', 'remark_eng'], 'string', 'max' => 255],
            [['template_file', 'template_file_eng'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,gif,pdf,doc,xls,docx,xlsx,ppt,pptx,rar,zip'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'id' => Yii::t('app', 'รหัสเอกสารประกอบการวิจัย'),
            'name' => Yii::t('app', 'ชื่อเอกสารประกอบการวิจัย'),
            'number' => Yii::t('app', 'จำนวน'),
            'deleted' => Yii::t('app', '0=ใช้งาน,1=ไม่ใช้งาน'),
            'created_by' => Yii::t('app', 'สร้างโดย'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'updated_by' => Yii::t('app', 'ปรับปรุงโดย'),
            'updated_at' => Yii::t('app', 'ปรับปรุงเมื่อ'),
            'template_file' => Yii::t('app', 'ไฟล์ต้นแบบภาษาไทย'),
            'template_file_eng' => Yii::t('app', 'ไฟล์ต้นแบบภาษาอังกฤษ'),
            'role_id' => Yii::t('app', 'หน้าที่สำหรับการใช้งาน'),
            'name_eng' => Yii::t('app', 'ชื่อเอกสารประกอบการวิจัยภาษาอังกฤษ'),
            'is_report' => Yii::t('app', 'เอกสารที่ต้องกำหนดลงในหนังสือรับรอง'),
            'i18nName' => Yii::t('app', 'ชื่อเอกสารประกอบการวิจัยภาษาอังกฤษ'),
            'remark' => Yii::t('app', 'รายละเอียดเพิ่มเติมเกี่ยวกับเอกสาร'),
            'remark_eng' => Yii::t('app', 'รายละเอียดเพิ่มเติมเกี่ยวกับเอกสารภาษาอังกฤษ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
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
    public function getDocumentSubmissionTypes() {
        return $this->hasMany(DocumentSubmissionType::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissionDocuments() {
        return $this->hasMany(SubmissionDocument::className(), ['document_id' => 'id']);
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
        return "uploads/document-template";
    }

    public function getTemplateFileUrl() {
        return \yii\helpers\Url::to("@web/{$this->templatePath}/{$this->template_file}");
    }

    public function getTemplatePathAlias() {
        return Yii::getAlias("@app/web/{$this->templatePath}/{$this->template_file}");
    }

    public function getTemplateFileUrlEng() {
        return \yii\helpers\Url::to("@web/{$this->templatePath}/{$this->template_file_eng}");
    }

    public function getTemplatePathAliasEng() {
        return Yii::getAlias("@app/web/{$this->templatePath}/{$this->template_file_eng}");
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

    public function getDocumentName() {
        $name = '';
        if (isset($this->name)) {
            $name .= $this->name;
        }
        if (isset($this->name_eng)) {
            $name .= '<br>' . $this->name_eng;
        }
        if (isset($this->remark)) {
            $name .= '<br>' . $this->name_eng;
        }
        return $name;
    }
    public function getDocumentNameRemark() {
        $name = '';
        if (isset($this->name)) {
            $name .= $this->name;
        }
        if (isset($this->name_eng)) {
            $name .= '<br>' . $this->name_eng;
        }
        if (!empty($this->remark)) {
            $name .= '<br><font style="color: red">' .\Yii::t('app', 'หมายเหตุ : ') . '</font>' .  $this->i18nRemark;
        }
        return $name;
    }
    public function getI18nName() {
        $attr = 'name' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    public function getI18nRemark() {
        $attr = 'remark' . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
        return $this->{$attr};
    }

    /**
     * @inheritdoc
     * @return DocumentQuery the active query used by this AR class.
     */
    public static function find() {
        return new DocumentQuery(get_called_class());
    }

}
