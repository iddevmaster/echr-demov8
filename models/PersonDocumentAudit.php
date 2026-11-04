<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "person_document_audit".
 *
 * Records the electronic-signing audit trail for researcher documents
 * (CV and training certificates).
 *
 * @property int $id
 * @property int $person_id
 * @property int $doc_type 1=CV, 2=TRAINING
 * @property int $ref_id person_training.id for training docs
 * @property string $file_name
 * @property int $certify_confirmed
 * @property string $certify_statement
 * @property int $signed
 * @property int $signer_person_id
 * @property string $signer_name
 * @property string $signed_at
 * @property int $auth_method 1=PASSWORD, 2=OTP
 * @property int $stamp_applied
 * @property int $stamp_type 1=PDF_OVERLAY, 2=COVERSHEET
 * @property string $ip_address
 * @property string $user_agent
 * @property int $deleted
 * @property int $created_by
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 *
 * @property Person $person
 * @property Person $signer
 */
class PersonDocumentAudit extends \yii\db\ActiveRecord {

    const DOC_TYPE_CV = 1;
    const DOC_TYPE_TRAINING = 2;

    const AUTH_PASSWORD = 1;
    const AUTH_OTP = 2;

    const STAMP_PDF_OVERLAY = 1;
    const STAMP_COVERSHEET = 2;

    /** Default certification statement researchers confirm before signing. */
    const CERTIFY_STATEMENT = 'ข้าพเจ้าขอรับรองว่าเอกสารที่อัปโหลดนี้เป็นความจริงและถูกต้องทุกประการ และยินยอมให้ลงนามอิเล็กทรอนิกส์';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'person_document_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['person_id', 'doc_type'], 'required'],
            [['person_id', 'doc_type', 'ref_id', 'certify_confirmed', 'signed', 'signer_person_id', 'auth_method', 'stamp_applied', 'stamp_type', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['certify_statement'], 'string'],
            [['signed_at', 'created_at', 'updated_at'], 'safe'],
            [['file_name', 'signer_name'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 64],
            [['user_agent'], 'string', 'max' => 255],
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
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'person_id' => Yii::t('app', 'เจ้าของเอกสาร'),
            'doc_type' => Yii::t('app', 'ประเภทเอกสาร'),
            'ref_id' => Yii::t('app', 'อ้างอิงเอกสาร'),
            'file_name' => Yii::t('app', 'ชื่อไฟล์'),
            'certify_confirmed' => Yii::t('app', 'ยืนยันความถูกต้อง'),
            'certify_statement' => Yii::t('app', 'ข้อความรับรอง'),
            'signed' => Yii::t('app', 'ลงนามแล้ว'),
            'signer_person_id' => Yii::t('app', 'ผู้ลงนาม'),
            'signer_name' => Yii::t('app', 'ชื่อผู้ลงนาม'),
            'signed_at' => Yii::t('app', 'วันเวลาที่ลงนาม'),
            'auth_method' => Yii::t('app', 'วิธียืนยันตัวตน'),
            'stamp_applied' => Yii::t('app', 'ประทับตราแล้ว'),
            'stamp_type' => Yii::t('app', 'ชนิดการประทับตรา'),
            'ip_address' => Yii::t('app', 'IP'),
            'user_agent' => Yii::t('app', 'User agent'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSigner() {
        return $this->hasOne(Person::className(), ['id' => 'signer_person_id']);
    }

    /**
     * Records a fully-signed audit row for a document in a single step.
     *
     * @param int $personId owner of the document
     * @param int $docType DOC_TYPE_CV | DOC_TYPE_TRAINING
     * @param int|null $refId person_training.id for training documents
     * @param string $fileName the file as named at sign time
     * @param Person $signer the person performing the signature
     * @param int $authMethod AUTH_PASSWORD | AUTH_OTP
     * @param array $opts optional overrides: statement, confirmed, stampApplied, stampType
     * @return PersonDocumentAudit
     */
    public static function recordSigned($personId, $docType, $refId, $fileName, $signer, $authMethod, $opts = []) {
        $audit = new self();
        $audit->person_id = $personId;
        $audit->doc_type = $docType;
        $audit->ref_id = $refId;
        $audit->file_name = $fileName;
        $audit->certify_confirmed = isset($opts['confirmed']) ? (int) $opts['confirmed'] : 1;
        $audit->certify_statement = isset($opts['statement']) ? $opts['statement'] : self::CERTIFY_STATEMENT;
        $audit->signed = 1;
        $audit->signer_person_id = isset($signer) ? $signer->id : null;
        $audit->signer_name = isset($signer) ? $signer->fullName : null;
        $audit->signed_at = date('Y-m-d H:i:s');
        $audit->auth_method = $authMethod;
        $audit->stamp_applied = isset($opts['stampApplied']) ? (int) $opts['stampApplied'] : 0;
        $audit->stamp_type = isset($opts['stampType']) ? $opts['stampType'] : null;
        $request = Yii::$app->request;
        if ($request instanceof \yii\web\Request) {
            $audit->ip_address = $request->getUserIP();
            $ua = $request->getUserAgent();
            $audit->user_agent = isset($ua) ? mb_substr($ua, 0, 255) : null;
        }
        $audit->deleted = 0;
        $audit->save(false);
        return $audit;
    }

    public function getAuthMethodLabel() {
        switch ($this->auth_method) {
            case self::AUTH_PASSWORD: return Yii::t('app', 'รหัสผ่าน');
            case self::AUTH_OTP: return Yii::t('app', 'OTP');
            default: return '';
        }
    }

    /**
     * Whether the most recent (latest) audit row for a document was a stamped
     * signing. Used to decide if a newly uploaded file should be auto-stamped.
     *
     * @param int $personId
     * @param int $docType DOC_TYPE_CV | DOC_TYPE_TRAINING
     * @param int|null $refId person_training.id for training docs
     * @return bool
     */
    public static function wasLastStamped($personId, $docType, $refId = null) {
        $query = self::find()->isDeleted(FALSE)->person($personId)->docType($docType);
        if ($refId !== null) {
            $query->ref($refId);
        }
        $last = $query->orderBy('id DESC')->one();
        return isset($last) && (int) $last->stamp_applied === 1;
    }

    /**
     * {@inheritdoc}
     * @return PersonDocumentAuditQuery the active query used by this AR class.
     */
    public static function find() {
        return new PersonDocumentAuditQuery(get_called_class());
    }

}
