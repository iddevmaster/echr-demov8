<?php

namespace app\controllers;

use Yii;
use app\models\Person;
use app\models\PersonSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\RegistrationForm;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\PersonRole;
use app\models\PersonRolePanel;
use kartik\widgets\Alert;
use app\models\RegisterForm;
use yii\web\UploadedFile;
use app\models\EmailQueue;
use app\models\ForgetPasswordForm;
use app\models\ProjectResearcher;
use app\models\Role;
use app\models\PersonTraining;
use app\models\PersonDocumentAudit;
use app\models\Setting;
use app\components\DocumentStamper;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends RbacController {

    const REGISTER_STEP1 = 1;
    const REGISTER_STEP2 = 2;
    const REGISTER_STEP3 = 3;
    const REGISTER_STEP4 = 4;

    protected $allowedActions = ['register', 'register-complete', 'check-idcard', 'verify-email', 'upload-cv', 'forget-password', 'reset-password', 'search-consultant', 'policy', 'esign', 'esign-otp'];

    public static function registerSteps() {
        return [
            self::REGISTER_STEP1 => [
                'step' => self::REGISTER_STEP1,
                'label' => \Yii::t('app', 'ข้อมูลผู้ใช้งาน'),
                'icon' => 'icon md-account-box-phone'
            ],
            self::REGISTER_STEP2 => [
                'step' => self::REGISTER_STEP2,
                'label' => \Yii::t('app', 'ข้อมูลการอบรม'),
                'icon' => 'icon md-assignment'
            ],
            self::REGISTER_STEP3 => [
                'step' => self::REGISTER_STEP3,
                'label' => \Yii::t('app', 'username และ password'),
                'icon' => 'icon md-account'
            ],
            self::REGISTER_STEP4 => [
                'step' => self::REGISTER_STEP4,
                'label' => \Yii::t('app', 'ยืนยันข้อมูล'),
                'icon' => 'icon md-check'
            ],
        ];
    }

    public static function getStepClass($step, $current) {
        if ($step['step'] == $current) {
            return 'current';
        } else if ($step['step'] < $current) {
            return 'done';
        } else {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionPolicy() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "นโยบายความเป็นส่วนตัว",
                'size' => 'large',
                'content' => $this->renderAjax('policy'),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('policy', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PersonSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    public function actionEditName($id) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request 
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขชื่อนักวิจัย"),
                    'size' => 'large',
                    'content' => $this->renderAjax('edit-name', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save(false)) {
                return [
                    'forceReload' => '#crud-datatable-project-researcher-pjax',
                    'forceClose' => true,
                    'title' => Yii::t('app', "แก้ไขชื่อนักวิจัย"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'แก้ไขชื่อนักวิจัยเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขชื่อนักวิจัย"),
                    'content' => $this->renderAjax('edit-name', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['edit-name', 'id' => $model->id]);
            } else {
                return $this->render('edit-name', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Person #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }
    private function serveSignatureFile($filePath, $fileName)
    {
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }

        if (\app\components\FileEncryptor::isEncryptedFile($filePath)) {
            $data = Yii::$app->fileEncryptor->decryptFileToString($filePath);
            if ($data === false) {
                throw new NotFoundHttpException(Yii::t('app', 'ไม่สามารถถอดรหัสไฟล์ได้'));
            }
            // Strip .enc from display filename
            $displayName = preg_replace('/\.enc$/', '', $fileName);
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($data) ?: 'image/png';
            header('Content-Description: Preview');
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $displayName . '"');
            header('Expires: 0');
            header('Pragma: public');
            header('Content-Length: ' . strlen($data));
            echo $data;
        } else {
            // Legacy unencrypted file
            header('Content-Description: Preview');
            header('Content-Type: image/png; charset=utf-8');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        }
        exit;
    }

    public function actionSignature($personId) {
        $request = Yii::$app->request;
        $model = $this->findModel($personId);
        $signature = $model->signature;
        $signatureThai = $model->signature_thai;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', "จัดการลายเซ็นต์ {name}", [
                        'name' => $model->fullName
                    ]),
                    'content' => $this->renderAjax('signature', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $file = UploadedFile::getInstance($model, 'signature');
                $fileThai = UploadedFile::getInstance($model, 'signature_thai');
                $encryptor = Yii::$app->fileEncryptor;

                if (isset($file)) {
                    $model->signature = $file;
                    $model->signature->name = uniqid() . '.' . $model->signature->extension;
                    $path = 'uploads/person-signature/';
                    $model->signature->saveAs($path . $model->signature->name);
                    // Encrypt the saved file
                    $enc = $encryptor->encryptUploadedFile($path . $model->signature->name);
                    $model->signature = $enc ? $enc['filename'] : $model->signature->name;
                } else {
                    $model->signature = $signature;
                }

                if (isset($fileThai)) {
                    $model->signature_thai = $fileThai;
                    $model->signature_thai->name = uniqid() . '.' . $model->signature_thai->extension;
                    $path = 'uploads/person-signature/';
                    $model->signature_thai->saveAs($path . $model->signature_thai->name);
                    // Encrypt the saved file
                    $enc = $encryptor->encryptUploadedFile($path . $model->signature_thai->name);
                    $model->signature_thai = $enc ? $enc['filename'] : $model->signature_thai->name;
                } else {
                    $model->signature_thai = $signatureThai;
                }

                $model->save(FALSE);
                return [
                    'size' => 'large',
                    'forceReload' => '#crud-datatable-person-role',
                    'title' => Yii::t('app', "จัดการลายเซ็นต์ {name}", [
                        'name' => $model->fullName
                    ]),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'กำหนดลายเซ็นต์เรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', "จัดการลายเซ็นต์ {name}", [
                        'name' => $model->fullName
                    ]),
                    'content' => $this->renderAjax('signature', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('signature', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Stamp + audit the CV and all training documents for a person during
     * registration (no re-auth — the user authenticated in this same flow).
     *
     * @param Person $person the persisted person (signer == owner)
     * @param bool $applyStamp whether to physically stamp the files (item 11)
     */
    protected function esignRegistrationDocuments($person, $applyStamp) {
        // CV
        if (!empty($person->cv_file)) {
            $this->esignCv($person, $person, PersonDocumentAudit::AUTH_PASSWORD, $applyStamp);
        }
        // Training documents
        $trainings = PersonTraining::find()->isDeleted(FALSE)->person($person->id)->all();
        foreach ($trainings as $t) {
            if (!empty($t->file)) {
                $this->esignTraining($t, $person, PersonDocumentAudit::AUTH_PASSWORD, $applyStamp);
            }
        }
    }

    /**
     * Stamp (optional) and record the audit trail for a person's CV.
     * Thin wrapper around EsignService (shared with PersonTrainingController).
     */
    protected function esignCv($person, $signer, $authMethod, $applyStamp) {
        \app\components\EsignService::signCv($person, $signer, $authMethod, $applyStamp);
    }

    /**
     * Persist a CV after a profile-edit save, auto-stamping the newly uploaded
     * file when the previous CV was signed with a stamp (no re-auth — the user
     * is already authenticated). When the file changed, the stale signed/original
     * state is reset so the badge reflects reality.
     *
     * @param Person $model populated person with the (possibly new) cv_file
     * @param string|null $oldCvFile the cv_file value before this save
     */
    protected function saveCvWithAutoStamp($model, $oldCvFile) {
        $changed = ($model->cv_file !== $oldCvFile);
        if (!$changed) {
            $model->save(false);
            return;
        }
        // New file uploaded: the previous original/signed state no longer applies.
        $wasStamped = PersonDocumentAudit::wasLastStamped($model->id, PersonDocumentAudit::DOC_TYPE_CV);
        $priorOriginal = $model->cv_original_file;
        $dir = Yii::getAlias('@app/web/' . $model->cvPath);
        $model->cv_original_file = null;
        if ($wasStamped) {
            // signCv captures the new file as original, stamps it, sets signed_*, saves.
            $signer = isset(Yii::$app->user->identity->person) ? Yii::$app->user->identity->person : $model;
            \app\components\EsignService::signCv($model, $signer, PersonDocumentAudit::AUTH_PASSWORD, true);
        } else {
            // Not previously stamped: just save raw and clear stale signed state.
            $model->cv_signed_at = null;
            $model->cv_signed_by = null;
            $model->save(false);
        }
        // Remove the prior served file (old stamped output) and its prior original
        // if they were superseded by the new upload; never delete the new files.
        \app\components\EsignService::cleanupSupersededFile($dir, $oldCvFile, $model->cv_file, $model->cv_original_file);
        if (!empty($priorOriginal) && $priorOriginal !== $oldCvFile) {
            \app\components\EsignService::cleanupSupersededFile($dir, $priorOriginal, $model->cv_file, $model->cv_original_file);
        }
    }

    /**
     * Stamp (optional) and record the audit trail for a training document.
     * Thin wrapper around EsignService.
     */
    protected function esignTraining($training, $signer, $authMethod, $applyStamp) {
        \app\components\EsignService::signTraining($training, $signer, $authMethod, $applyStamp);
    }

    /**
     * Electronic-signature action for the profile-edit case (items 7-9, 11).
     * Forces re-authentication (password, or OTP when ESIGN_OTP_ENABLE=1) before
     * stamping + recording the audit trail. Renders a modal.
     *
     * @param int $personId owner of the document
     * @param int $docType PersonDocumentAudit::DOC_TYPE_CV | DOC_TYPE_TRAINING
     * @param int|null $refId person_training.id for training documents
     */
    public function actionEsign($personId, $docType, $refId = null) {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'กรุณาเข้าสู่ระบบ'));
        }
        $request = Yii::$app->request;
        $person = $this->findModel($personId);
        $currentIdentity = Yii::$app->user->identity;
        $otpEnabled = (int) Setting::getValueOrDefault(Setting::ESIGN_OTP_ENABLE, 0) === 1;

        // Authorization: only the owner, staff or admin may sign.
        $currentPersonId = isset($currentIdentity->person) ? (int) $currentIdentity->person->id : null;
        $currentRole = Yii::$app->session->get('currentRole');
        $isPrivileged = isset($currentRole['role_id']) && in_array($currentRole['role_id'], [Role::ADMIN, Role::STAFF]);
        if ($currentPersonId !== (int) $personId && !$isPrivileged) {
            throw new ForbiddenHttpException(Yii::t('app', 'ไม่มีสิทธิ์ลงนามเอกสารนี้'));
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'ลงนามอิเล็กทรอนิกส์'),
                    'content' => $this->renderAjax('esign', [
                        'person' => $person,
                        'docType' => $docType,
                        'refId' => $refId,
                        'otpEnabled' => $otpEnabled,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                    Html::button(Yii::t('app', 'ลงนาม'), ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } else {
                $certify = (int) $request->post('certify') === 1;
                $applyStamp = (int) $request->post('apply_stamp') === 1;
                if (!$certify) {
                    return $this->esignModalError($person, $docType, $refId, $otpEnabled, Yii::t('app', 'กรุณายืนยันการรับรองความถูกต้องของเอกสาร'));
                }
                // Validate re-auth. Guard empty input first so Yii security does
                // not throw "Password must be a string and cannot be empty".
                $authOk = false;
                if ($otpEnabled) {
                    $otp = (string) $request->post('otp');
                    if ($otp === '') {
                        return $this->esignModalError($person, $docType, $refId, $otpEnabled, Yii::t('app', 'กรุณากรอกรหัส OTP'));
                    }
                    $authOk = $this->verifyEsignOtp($otp);
                } else {
                    $password = (string) $request->post('password');
                    if ($password === '') {
                        return $this->esignModalError($person, $docType, $refId, $otpEnabled, Yii::t('app', 'กรุณากรอกรหัสผ่าน'));
                    }
                    try {
                        $authOk = isset($currentIdentity) && $currentIdentity->validatePassword($password);
                    } catch (\Exception $e) {
                        $authOk = false;
                    }
                }
                if (!$authOk) {
                    $msg = $otpEnabled ? Yii::t('app', 'รหัส OTP ไม่ถูกต้องหรือหมดอายุ') : Yii::t('app', 'รหัสผ่านไม่ถูกต้อง');
                    return $this->esignModalError($person, $docType, $refId, $otpEnabled, $msg);
                }
                $authMethod = $otpEnabled ? PersonDocumentAudit::AUTH_OTP : PersonDocumentAudit::AUTH_PASSWORD;
                $signer = isset($currentIdentity->person) ? $currentIdentity->person : $person;
                if ((int) $docType === PersonDocumentAudit::DOC_TYPE_TRAINING) {
                    $training = PersonTraining::findOne($refId);
                    if (isset($training) && $training->person_id == $person->id) {
                        $this->esignTraining($training, $signer, $authMethod, $applyStamp);
                    }
                } else {
                    $this->esignCv($person, $signer, $authMethod, $applyStamp);
                }
                return [
                    // No forceReload: the e-sign modal is opened from pages without
                    // a #crud-datatable-person-role grid (e.g. person/update); a
                    // missing pjax container would error and hang the modal on
                    // "Loading". The success message + Close button suffice.
                    'title' => Yii::t('app', 'ลงนามอิเล็กทรอนิกส์'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ลงนามอิเล็กทรอนิกส์เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
                ];
            }
        }
        throw new NotFoundHttpException();
    }

    private function esignModalError($person, $docType, $refId, $otpEnabled, $message) {
        return [
            'title' => Yii::t('app', 'ลงนามอิเล็กทรอนิกส์'),
            'content' => '<div class="alert alert-danger dark">' . $message . '</div>' . $this->renderAjax('esign', [
                'person' => $person,
                'docType' => $docType,
                'refId' => $refId,
                'otpEnabled' => $otpEnabled,
            ]),
            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
            Html::button(Yii::t('app', 'ลงนาม'), ['class' => 'btn btn-primary', 'type' => 'submit'])
        ];
    }

    /**
     * Generate and email a one-time OTP for e-signature re-auth (edit case).
     * Stores a hash + expiry in the session.
     */
    public function actionEsignOtp() {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'กรุณาเข้าสู่ระบบ'));
        }
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $identity = Yii::$app->user->identity;
        if (!isset($identity) || !isset($identity->person) || empty($identity->person->email)) {
            return ['success' => false, 'message' => Yii::t('app', 'ไม่พบอีเมลสำหรับส่ง OTP')];
        }
        $code = (string) random_int(100000, 999999);
        Yii::$app->session->set('esign_otp_hash', Yii::$app->security->generatePasswordHash($code));
        Yii::$app->session->set('esign_otp_expire', time() + 300); // 5 minutes
        $adminName = '=?utf-8?B?' . base64_encode(\Yii::$app->params['adminName']) . '?=';
        Yii::$app->mailer->compose()
                ->setSubject(Yii::t('app', 'รหัส OTP สำหรับลงนามอิเล็กทรอนิกส์'))
                ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                ->setTo($identity->person->email)
                ->setHtmlBody(Yii::t('app', 'รหัส OTP ของท่านคือ <b>{code}</b> (มีอายุ 5 นาที)', ['code' => $code]))
                ->send();
        return ['success' => true, 'message' => Yii::t('app', 'ส่งรหัส OTP ไปยังอีเมลของท่านแล้ว')];
    }

    /** Validate the OTP entered against the session-stored hash + expiry. */
    private function verifyEsignOtp($otp) {
        $hash = Yii::$app->session->get('esign_otp_hash');
        $expire = (int) Yii::$app->session->get('esign_otp_expire');
        if (empty($hash) || empty($otp) || time() > $expire) {
            return false;
        }
        try {
            $ok = Yii::$app->security->validatePassword((string) $otp, $hash);
        } catch (\Exception $e) {
            return false;
        }
        if ($ok) {
            Yii::$app->session->remove('esign_otp_hash');
            Yii::$app->session->remove('esign_otp_expire');
        }
        return $ok;
    }

    /**
     * Creates a new Person model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new Person();
        $regForm = new RegistrationForm();
        $regForm->scenario = 'register';


        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มบุคลากร"),
                    'size' => 'large',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'regForm' => $regForm,
                        '
    . '
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load(Yii::$app->request->post()) && $regForm->load(Yii::$app->request->post()) && $model->validate() && $regForm->validate()) {

                if (!empty($regForm->username) || !empty($regForm->password) || !empty($regForm->password_repeat)) {

                    if (!empty($regForm->username)) {
                        $user = new User();
                        $user->username = $regForm->username;
                        $user->setPassword($regForm->password);
                        $user->generateAuthKey();
                        $user->status = User::STATUS_ACTIVE;
                        $user->save(false);
                        $model->user_id = $user->id;

                        $files = UploadedFile::getInstances($model, 'cv_file');
                        foreach ($files as $file) {
                            $model->cv_file = $file;
                            $model->cv_file->name = 'cv-' . uniqid() . '.' . $model->cv_file->extension;
                            $path = 'uploads/cv-file/';
                            $model->cv_file->saveAs($path . $model->cv_file->name);
                            $model->cv_file = $model->cv_file->name;
                        }
                        if (isset($model->department_id) && $model->department_id == 1) {
                            $model->is_researcher_crec = 1;
                        }
                        $model->save(false);

                        $r = \app\models\Role::findOne(\app\models\Role::RESEARCHER);
                        $auth = \Yii::$app->getAuthManager();
                        $role = $auth->getRole($r->name);
                        $ass = $auth->getAssignment($r->name, $user->id);
                        if (!$ass) {
                            $auth->assign($role, $user->id);
                        }
                        $personRole = PersonRole::find()->isDeleted(FALSE)->person($model->id)->role($r->id)->one();
                        if (!isset($personRole)) {
                            $personRole = new PersonRole();
                            $personRole->person_id = $model->id;
                            $personRole->role_id = $r->id;
                            $personRole->save();
                        }
                        // $pRole = new PersonRole();
//                        $personRole->person_id = $model->id;
//                        $personRole->save(false);
//                        $auth = \Yii::$app->getAuthManager();
//                        $role = $auth->getRole($model->role->name);
//                        $auth->assign($role, $user->id);
                    }

//                    if (!empty($personRolePanel->panel_id)) {
//                        PersonRolePanel::deleteAll('person_role_id=:qtid', [':qtid' => $personRole->id]);
//                        $futureIds = array_values($personRolePanel->panel_id);
//                        foreach ($futureIds as $fid) {
//                            $prp = new PersonRolePanel();
//                            $prp->panel_id = $fid;
//                            $prp->person_role_id = $personRole->id;
//                            $prp->save();
//                        }
//                    }
//
//                    $model = new Person();
//                    $regForm = new RegistrationForm();
//                    $regForm->scenario = 'register';
//                    $personRole = new PersonRole();
//                    $personRolePanel = new PersonRolePanel();
                    $model = new Person();
                    $regForm = new RegistrationForm();
                    $regForm->scenario = 'register';
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'เพิ่มบุคลากรเรียบร้อยแล้ว');
                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'title' => Yii::t('app', "เพิ่มบุคลากร"),
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'regForm' => $regForm,
                        ]),
                        'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else {
                    $model = new Person();
                    $regForm = new RegistrationForm();
                    $regForm->scenario = 'register';

                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'เพิ่มบุคลากรเรียบร้อยแล้ว');
                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'size' => 'large',
                        'title' => "เพิ่มบุคลากร",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'regForm' => $regForm,
                        ]),
                        'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('บันทึก', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มบุคลากร"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'regForm' => $regForm,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionUpdateInfo($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $cv = $model->cv_file;
        $regForm = new RegistrationForm();
        if (isset($model->user)) {
            $regForm->username = $model->user->username;
            $regForm->user_id = $model->user_id;
        }


        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขบุคลากร"),
                    'size' => 'large',
                    'content' => $this->renderAjax('update-info', [
                        'model' => $model,
                        'regForm' => $regForm,
                        '
    . '
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load(Yii::$app->request->post()) && $regForm->load(Yii::$app->request->post()) && $model->validate() && $regForm->validate()) {

                if (!empty($regForm->username) || !empty($regForm->password) || !empty($regForm->password_repeat)) {

                    if (isset($model->user)) {
                        $user = $model->user;
                    } else {
                        $user = new User();
                    }
                    if (!empty($regForm->username)) {

                        $user->username = $regForm->username;
                        if (!empty($regForm->password)) {
                            $user->setPassword($regForm->password);
                            $user->generateAuthKey();
                        }
                        $user->status = User::STATUS_ACTIVE;
                        $user->save(false);
                        $model->user_id = $user->id;

                        $file = UploadedFile::getInstance($model, 'cv_file');
                        if (isset($file)) {
                            $model->cv_file = $file;
                            $model->cv_file->name = 'cv-' . uniqid() . '.' . $model->cv_file->extension;
                            $path = 'uploads/cv-file/';
                            $model->cv_file->saveAs($path . $model->cv_file->name);
                            $model->cv_file = $model->cv_file->name;
                        } else {
                            $model->cv_file = $cv;
                        }
//                        if (isset($model->department_id) && $model->department_id != 1) {
//                            $model->is_researcher_crec = 0;
//                        }
                        $model->save(false);
                    }

//                    $model->save(false);
                    // $pRole = new PersonRole();
//                        $personRole->person_id = $model->id;
//                        $personRole->save(false);
//                        $auth = \Yii::$app->getAuthManager();
//                        $role = $auth->getRole($model->role->name);
//                        $auth->assign($role, $user->id);
//                    if (!empty($personRolePanel->panel_id)) {
//                        PersonRolePanel::deleteAll('person_role_id=:qtid', [':qtid' => $personRole->id]);
//                        $futureIds = array_values($personRolePanel->panel_id);
//                        foreach ($futureIds as $fid) {
//                            $prp = new PersonRolePanel();
//                            $prp->panel_id = $fid;
//                            $prp->person_role_id = $personRole->id;
//                            $prp->save();
//                        }
//                    }
//
//                    $model = new Person();
//                    $regForm = new RegistrationForm();
//                    $regForm->scenario = 'register';
//                    $personRole = new PersonRole();
//                    $personRolePanel = new PersonRolePanel();

                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'title' => Yii::t('app', "แก้ไขบุคลากร"),
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                            'id' => $model->id,
                            'regForm' => $regForm,
                        ]),
                        'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else {
                    $file = UploadedFile::getInstance($model, 'cv_file');
                    if (isset($file)) {
                        $model->cv_file = $file;
                        $model->cv_file->name = 'cv-' . uniqid() . '.' . $model->cv_file->extension;
                        $path = 'uploads/cv-file/';
                        $model->cv_file->saveAs($path . $model->cv_file->name);
                        $model->cv_file = $model->cv_file->name;
                    } else {
                        $model->cv_file = $cv;
                    }
                    $model->save(false);

                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, 'แก้ไขบุคลากรเรียบร้อยแล้ว');
                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'size' => 'large',
                        'title' => "แก้ไขข้อมูลบุคลากร",
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูลบุคลากรเรียบร้อยแล้ว') . '</div>',
                        'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขบุคลากร"),
                    'content' => $this->renderAjax('update-info', [
                        'model' => $model,
                        'regForm' => $regForm,
//                        'personRole' => $personRole,
//                        'personRolePanel' => $personRolePanel,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update-info', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $cvFile = $model->cv_file;

        $regForm = new RegistrationForm();
        if (isset($model->user)) {
            $regForm->username = $model->user->username;
            $regForm->user_id = $model->user_id;
        }
        $searchModel = new \app\models\PersonTrainingSearch();
        $searchModel->person_id = $id;
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Person #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'regForm' => $regForm,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load(Yii::$app->request->post()) && $regForm->load(Yii::$app->request->post()) && $model->validate() && $regForm->validate()) {
                if (!empty($regForm->username) || !empty($regForm->password) || !empty($regForm->password_repeat)) {

                    if (!$regForm->validate()) {
                        return [
                            'title' => "แก้ไขบุคลากร",
                            'size' => 'large',
                            'content' => $this->renderAjax('update', [
                                'model' => $model,
                                'regForm' => $regForm,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                            ]),
                            'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('บันทึก', ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                    if (isset($model->user)) {
                        $user = $model->user;
                    } else {
                        $user = new User();
                    }
                    if (!empty($regForm->username)) {

                        $user->username = $regForm->username;
                        if (!empty($regForm->password)) {
                            $user->setPassword($regForm->password);
                            $user->generateAuthKey();
                        }
                        $user->status = User::STATUS_ACTIVE;
                        $user->save(false);
                        $model->user_id = $user->id;
                        $model->save(false);
                    }
                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'size' => 'large',
                        'title' => "พนักงาน " . $model->fullName,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]),
                        'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('แก้ไข', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    $model->save(false);
                    return [
                        'forceReload' => '#crud-datatable-person-pjax',
                        'size' => 'large',
                        'title' => "พนักงาน " . $model->fullName,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]),
                        'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('แก้ไข', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else if ($model->load($request->post()) && $model->validate()) {
                // The FileInput uploads via person/upload-cv into web/tmp/; move a
                // freshly-uploaded CV into uploads/cv-file/ before persisting so
                // person/download can find it (previously this branch left cv_file
                // pointing at tmp/).
                if ($model->cv_file !== $cvFile && !empty($model->cv_file)
                        && file_exists(Yii::getAlias('@app/web/tmp/' . $model->cv_file))) {
                    @rename(Yii::getAlias('@app/web/tmp/' . $model->cv_file),
                            Yii::getAlias('@app/web/uploads/cv-file/' . $model->cv_file));
                }
                $this->saveCvWithAutoStamp($model, $cvFile);
                return [
                    'forceReload' => '#crud-datatable-person-pjax',
                    'title' => Yii::t('app', "บุคลากร {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', "บันทึก"), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขบุคลากร {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'regForm' => $regForm,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load(Yii::$app->request->post()) && $regForm->load(Yii::$app->request->post()) && $model->validate() && $regForm->validate()) {
                if (!empty($regForm->username) || !empty($regForm->password) || !empty($regForm->password_repeat)) {
                    if (isset($model->user)) {
                        $user = $model->user;
                    } else {
                        $user = new User();
                    }
                    if (!empty($regForm->username)) {

                        $user->username = $regForm->username;
                        if (!empty($regForm->password)) {
                            $user->setPassword($regForm->password);
                        }
                        $user->status = User::STATUS_ACTIVE;
                        $user->save(false);
                    }
                }
                // $model->user_id = $user->id;

                $file = UploadedFile::getInstance($model, 'cv_file');
                if (isset($file)) {
                    $model->cv_file = $file;
                    $model->cv_file->name = 'cv-' . uniqid() . '.' . $model->cv_file->extension;
//                    $model->cv_file->name = uniqid() . '.' . $model->cv_file->extension;
                    $path = 'uploads/cv-file/';
                    $model->cv_file->saveAs($path . $model->cv_file->name);
                    $model->cv_file = $model->cv_file->name;
                } else {
                    $model->cv_file = $cvFile;
                }
                $this->saveCvWithAutoStamp($model, $cvFile);
                Yii::$app->session->setFlash('success', Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว'));

                return $this->render('update', [
                            'model' => $model,
                            'regForm' => $regForm,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                            'regForm' => $regForm,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
            }
        }
    }

    /**
     * Delete an existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $user = User::find()->status(User::STATUS_ACTIVE)->user($model->user_id)->one();
        $model->deleted = 1;
        $model->save(FALSE);
        if ($user) {
            $user->status = User::STATUS_DELETED;
            $user->save(FALSE);
        }

//        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-person-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Person model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUploadCv() {
        $request = \Yii::$app->request;
        $cvFile = UploadedFile::getInstanceByName('cv_file');
        if (isset($cvFile)) {
            $fileName = uniqid() . '.' . $cvFile->extension;
            $cvFile->saveAs('tmp/' . $fileName);
            if ($request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'filename' => $fileName
                ];
            }
        }
    }

    public function actionRegister($personId = NULL, $step = self::REGISTER_STEP1) {

        $this->layout = 'register';
        $request = Yii::$app->request;
        $previousStep = $request->post('previousStep');
        $nextStep = $request->post('nextStep');
        $regForm = new RegistrationForm(['scenario' => 'register']);
        if (isset($personId)) {
            $profile = Person::findOne($personId);
            if ($step == self::REGISTER_STEP1 || $previousStep == self::REGISTER_STEP1) {
                $profile->setScenario(Person::SCENARIO_REGISTER);
            }
        } else if (isset($request->post('Person')['email'])) {
            $profile = Person::find()->isDeleted(FALSE)->andWhere([
                        'email' => $request->post('Person')['email']
                    ])->one();
            if (isset($profile->user) && $profile->user->status == User::STATUS_ACTIVE) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['person-email' => [Yii::t('app', 'มีผู้ลงทะเบียนด้วย Email นี้แล้ว')]];
                } else {
//                $profile->addError('email', 'มีผู้ลงทะเบียนด้วยหมายเลขบัตรประชาชนนี้แล้ว');
                    throw new \yii\web\HttpException(500, Yii::t('app', 'มีผู้ลงทะเบียนด้วย Email นี้แล้ว'));
                }
            }
        }
        if (!isset($profile)) {
            if ($step == self::REGISTER_STEP1 || $previousStep == self::REGISTER_STEP1) {
                $profile = new Person(['scenario' => Person::SCENARIO_REGISTER]);
            }
            $profile->role_id = \app\models\Role::RESEARCHER;
            $profile->role_ids = [\app\models\Role::RESEARCHER];
        }

        $steps = self::registerSteps();

        $profile->load($request->post());
        if (!empty($profile->role_ids)) {
            $ids = array_map('intval', (array) $profile->role_ids);
            $profile->role_id = in_array(\app\models\Role::RESEARCHER, $ids, true) ? \app\models\Role::RESEARCHER : (int) reset($ids);
        }
//        if (Yii::$app->language == 'en') {
//            $profile->first_name = $profile->first_name_eng;
//            $profile->last_name = $profile->last_name_eng;
//        }

        $oldPerson = Person::find()->isDeleted(FALSE)->email($profile->email)->one();
//         \yii\helpers\VarDumper::dump($oldPerson->attributes);
//         \yii\helpers\VarDumper::dump($oldPerson->user->attributes);
        if (isset($oldPerson->user_id)) {
            $regForm->user_id = $oldPerson->user_id;
            $regForm->username = $oldPerson->user->username;
//            \yii\helpers\VarDumper::dump($regForm->attributes);
        }
        $regForm->load($request->post());
//        if (is_int($company->id)) {
//            $company = Company::findOne($company->id);
//            $company->load($request->post());
//        }

        if ($step == self::REGISTER_STEP1) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($profile);
            }
            if (null !== $nextStep) {
                $step = $nextStep;
                if (isset($profile->department_id) && $profile->department_id == 1) {
                    $profile->is_researcher_crec = 1;
                } else {
                    $profile->is_researcher_crec = 0;
                }
                $profile->save(FALSE);
                return $this->redirect(['person/register', 'personId' => $profile->id, 'step' => $step]);
            }
        } else if ($step == self::REGISTER_STEP2) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $trainingCount = $profile->getPersonTraining()->isDeleted(FALSE)->count();
                    if ($trainingCount == 0 && $profile->role_id == \app\models\Role::RESEARCHER) {
                        return ['person-title_id' => ['กรุณาเพิ่มข้อมูลการอบรม']];
                    } else {
                        return [];
                    }
                }
                $step = $nextStep;
            }
        } else if ($step == self::REGISTER_STEP3) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($regForm);
                }
                $step = $nextStep;
            }
        } else if ($step == self::REGISTER_STEP4) {
            if (null !== $request->post('previous-step')) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step--;
            } else {
                if (null !== $request->post('next-step')) {
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [];
                    }
                    $step = self::REGISTER_STEP3 + 1;
                    if (!empty($regForm->user_id)) {
                        $user = User::findOne($regForm->user_id);
                        $user->username = $regForm->username;
                        $user->setPassword($regForm->password);
                        $user->generateAuthKey();
//                        $user->status = User::STATUS_ACTIVE;
                        $user->save(FALSE);
                    } else {
                        $user = new User();
                        $user->username = $regForm->username;
                        $user->setPassword($regForm->password);
                        $user->generateAuthKey();
                        $user->status = User::STATUS_PENDING_VERIFY;
                        $user->save(FALSE);
                    }

                    $personId = NULL;
                    if (isset($oldPerson)) {
                        $oldPerson->attributes = $profile->attributes;
                        $oldPerson->user_id = $user->id;
                        $oldPerson->save(FALSE);
                        $personId = $oldPerson->id;
                    } else {
                        $profile->user_id = $user->id;
                        $profile->save(FALSE);
                        $personId = $profile->id;
                    }

                    $selectedRoleIds = !empty($profile->role_ids) ? array_map('intval', (array) $profile->role_ids) : [(int) $profile->role_id];
                    $auth = \Yii::$app->getAuthManager();
                    foreach ($selectedRoleIds as $rid) {
                        $r = \app\models\Role::findOne($rid);
                        if (!isset($r)) {
                            continue;
                        }
                        $rbacRole = $auth->getRole($r->name);
                        if ($rbacRole && !$auth->getAssignment($r->name, $user->id)) {
                            $auth->assign($rbacRole, $user->id);
                        }
                        $personRole = PersonRole::find()->isDeleted(FALSE)->person($personId)->role($r->id)->one();
                        if (!isset($personRole)) {
                            $personRole = new PersonRole();
                            $personRole->person_id = $personId;
                            $personRole->role_id = $r->id;
                            $personRole->save();
                        }
                    }

                    if (!empty($profile->cv_file) && file_exists(\Yii::getAlias('@app/web/tmp/' . $profile->cv_file))) {
                        rename(\Yii::getAlias('@app/web/tmp/' . $profile->cv_file), \Yii::getAlias('@app/web/uploads/cv-file/' . $profile->cv_file));
                    }

                    // Electronic signature: stamp the CV + training docs and record
                    // the audit trail. No re-auth at registration (the user just set
                    // their password in this same flow).
                    $signPerson = Person::findOne($personId);
                    if (isset($signPerson)) {
                        $this->esignRegistrationDocuments($signPerson, !empty($profile->cv_apply_stamp));
                    }

                    if ($user->status == User::STATUS_PENDING_VERIFY) {
                        EmailQueue::addQueue(EmailQueue::TYPE_USER_VERIFY, $user->id);
//                        $user->sendVerifyMail();
                    }

//                    Yii::$app->mailer->compose('researcher-register', [
//                                'user' => $user,
//                                'url' => \yii\helpers\Url::to(['site/login'], TRUE),
//                            ])
//                            ->setSubject(\Yii::t('app', 'แจ้งผลการลงทะเบียนผู้วิจัย'))
//                            ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->params['adminName']])
//                            ->setTo($profile->email)
//                            ->send();

                    return $this->redirect(['register-complete']);
                }
            }
        }
        $trainigSearch = new \app\models\PersonTrainingSearch();
        $trainigSearch->person_id = $profile->id;
        $trainigSearch->deleted = 0;
        $trainingProvider = $trainigSearch->search([]);
        return $this->render('register', [
                    'regForm' => $regForm,
                    'profile' => $profile,
                    'trainingProvider' => $trainingProvider,
                    'steps' => $steps,
                    'step' => $step,
        ]);
    }

    public function actionVerifyEmail($token) {
        $this->layout = 'register';
        $user = User::find()->status(User::STATUS_PENDING_VERIFY)->verifyToken($token)->one();
        if (isset($user)) {
            $user->status = User::STATUS_ACTIVE;
            $user->save(FALSE);
        }
        return $this->render('verify-email', [
                    'user' => $user
        ]);
    }

    public function actionRegisterComplete() {
        $this->layout = 'register';
        return $this->render('register-complete');
    }

    public function actionSearch($q = null, $submissionId = null, $roleId) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $p = new Person();
        $out = ['results' => $p];
        if (!is_null($q)) {
            // Resolve the submission type so the qualification check is scoped to
            // the training categories this project type requires (Phase 7).
            $submissionType = null;
            if (!empty($submissionId)) {
                $submission = \app\models\Submission::findOne($submissionId);
                $submissionType = isset($submission) ? $submission->submissionType : null;
            }
            $people = Person::find()->joinWith(['personRoles', 'user'])->isDeleted(FALSE)
//                    ->isInProject(FALSE, $projectId)
                    ->isInSubmission(FALSE, $submissionId)
                    ->role($roleId)
                    ->active()
                    ->andWhere(['or', ['like', 'CONCAT(first_name, last_name)', $q], ['like', 'CONCAT(first_name_eng, last_name_eng)', $q]])
                    ->limit(50)
                    ->all();
            $data = ArrayHelper::toArray($people, [
                        Person::className() => [
                            'id',
                            'name' => function($data) use ($submissionType) {
                                // Append a qualification warning icon (items 12-13),
                                // scoped to the submission type when available.
                                $warn = $data->getQualificationWarning(null, $submissionType);
                                return $data->fullNameWithEng . (isset($warn) ? ' ' . $warn : '');
                            },
                        ]
            ]);

            $out['results'] = $data;
        }
        return $out;
    }

    public function actionSearchCoi($q = null, $submissionId = null, $roleId) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $p = new Person();
        $out = ['results' => $p];
        if (!is_null($q)) {
            $people = Person::find()->joinWith(['personRoles'])->isDeleted(FALSE)
//                    ->isInProject(FALSE, $projectId)
//                    ->isInSubmission(FALSE, $submissionId)
                    ->role($roleId)
                    ->andWhere(['or', ['like', 'CONCAT(first_name, last_name)', $q], ['like', 'CONCAT(first_name_eng, last_name_eng)', $q]])
                    ->limit(50)
                    ->all();
            $data = ArrayHelper::toArray($people, [
                        Person::className() => [
                            'id',
                            'name' => function($data) {
                                return $data->fullNameWithEng;
                            },
                        ]
            ]);

            $out['results'] = $data;
        }
        return $out;
    }

    public function actionSearchConsultant($q = null, $submissionId = null, $roleId) {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $p = new Person();
//        $out = ['results' => $p];
//        if (!is_null($q)) {
//            $people = Person::find()->joinWith(['projectConsultants', 'personRoles'])->isDeleted(FALSE)
//                    ->isInProjectConsultant(FALSE, $projectId)
//                    ->role($roleId)
//                    ->andWhere(['or', ['like', 'CONCAT(first_name, last_name)', $q], ['like', 'CONCAT(first_name_eng, last_name_eng)', $q]])
//                    ->limit(50)
//                    ->all();
//            $data = ArrayHelper::toArray($people, [
//                        Person::className() => [
//                            'id',
//                            'name' => function($data) {
//                                return $data->fullNameWithEng;
//                            },
//                        ]
//            ]);
//
//            $out['results'] = $data;
//        }
//        return $out;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $p = new Person();
        $out = ['results' => $p];
        if (!is_null($q)) {
            $people = Person::find()->joinWith(['personRoles'])->isDeleted(FALSE)
//                    ->isInProject(FALSE, $projectId)
                    ->isInSubmissionConsultant(FALSE, $submissionId)
                    ->role($roleId)
                    ->andWhere(['or', ['like', 'CONCAT(first_name, last_name)', $q], ['like', 'CONCAT(first_name_eng, last_name_eng)', $q]])
                    ->limit(50)
                    ->all();
            $data = ArrayHelper::toArray($people, [
                        Person::className() => [
                            'id',
                            'name' => function($data) {
                                return $data->fullNameWithEng;
                            },
                        ]
            ]);

            $out['results'] = $data;
        }
        return $out;
    }

    public function actionCheckIdcard($idcard) {
        $person = Person::find()->isDeleted(FALSE)->andWhere(['idcard_no' => $idcard])->one();

        if (!isset($person)) {
            $person = new Person();
        }
        $data = ArrayHelper::toArray($person, [
                    Person::className() => [
                        'id',
                        'title_id',
                        'title',
                        'first_name',
                        'last_name',
                        'first_name_eng',
                        'last_name_eng',
                        'idcard_no',
                        'organization_id',
                        'department_id',
                        'division_id',
                        'email',
                        'tel',
                        'mobile_no',
                        'cv_file'
                    ]
        ]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionCheckEmail($email) {
        $person = Person::find()->isDeleted(FALSE)->andWhere(['email' => $email])->one();

        if (!isset($person)) {
            $person = new Person();
        }
        $data = ArrayHelper::toArray($person, [
                    Person::className() => [
                        'id',
                        'title_id',
                        'title',
                        'first_name',
                        'last_name',
                        'first_name_eng',
                        'last_name_eng',
                        'idcard_no',
                        'organization_id',
                        'department_id',
                        'division_id',
                        'email',
                        'tel',
                        'mobile_no',
                        'cv_file'
                    ]
        ]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionRoleNameByPanel($id, $panelId) {
        $model = $this->findModel($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['role_name' => $model->getRoleNameByPanel($panelId)];
    }

    public function actionForgetPassword() {
        $this->layout = 'register';
        $model = new ForgetPasswordForm();
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->validate()) {
            $user = $model->person->user;
            $user->generatePasswordResetToken();
            $user->save();
            EmailQueue::addQueue(EmailQueue::TYPE_RESET_PASSWORD, $user->id);
            return $this->render('forget-password-complete', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('forget-password', [
                        'model' => $model,
            ]);
        }
    }

    public function actionResetPassword($token) {
        $this->layout = 'register';
        $user = User::findByPasswordResetToken($token);
        $model = new RegistrationForm(['scenario' => 'reset-password']);
        if (isset($user)) {
            $model->username = $user->username;
            $model->user_id = $user->id;
        }

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!empty($model->username) || !empty($model->password) || !empty($model->password_repeat)) {
                $user->setPassword($model->password);
                $user->removePasswordResetToken();
                $user->save(false);
                return $this->render('reset-password-complete', [
                            'model' => $model
                ]);
            }
        }
        return $this->render('reset-password', [
                    'model' => $model
        ]);
    }

    public function actionDownloadSignThai($id) {
        $model = $this->findModel($id);
        $filePath = $model->templatePathAliasSignatureThai;
        $fileName = $model->signature_thai;
        $this->serveSignatureFile($filePath, $fileName);
    }

    public function actionDownloadSign($id) {
        $model = $this->findModel($id);
        $filePath = $model->templatePathAliasSignature;
        $fileName = $model->signature;
        $this->serveSignatureFile($filePath, $fileName);
    }

    public function actionDownload($id) {
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        // $currentRole = Yii::$app->session->get('currentRole');
        // // VarDumper::dump($currentRole, 10, true);
        // if (\in_array($currentRole['role_id'], [Role::RESEARCHER])) {
        //     // VarDumper::dump(Yii::$app->user->identity->person->id);
        //     // VarDumper::dump($id != Yii::$app->user->identity->person->id);
        //     if ($id != Yii::$app->user->identity->person->id) {
        //         throw new ForbiddenHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงไฟล์'));
        //     }
        // } else if (\in_array($currentRole['role_id'], [Role::COORDINATOR])) {
        //     // VarDumper::dump(Yii::$app->user->identity->person->id);
        //     // VarDumper::dump($id != Yii::$app->user->identity->person->id);
        //     $exists = ProjectResearcher::find()->joinWith(['project'])->isDeleted(false)->person($id)
        //         ->coordinator(Yii::$app->user->identity->person->id)
        //         ->andWhere(['project.deleted' => 0])
        //         ->exists();
        //     if (!$exists) {
        //         throw new ForbiddenHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงไฟล์'));
        //     }
        // }
        // exit;
        $model = $this->findModel($id);
        $info = pathinfo($model->cvFile);
        $t = \time();
        $fileName = "cv-{$t}.{$info['extension']}";
        // $fileName = "{$model->name}.{$info['extension']}";
        //        echo $model->filePath;
        if (file_exists($model->cvFile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->cvFile));
            readfile($model->cvFile);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    public function actionViewFile($id) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->cvFile);
        $t = \time();
        $fileName = "cv-{$t}.{$info['extension']}";
//        echo $model->filePath;
        if (file_exists($model->cvPath)) {
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->cvFile));
            readfile($model->cvFile);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

}
