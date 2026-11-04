<?php

namespace app\controllers;

use Yii;
use app\models\ResultDocument;
use app\models\ResultDocumentSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\models\Submission;
use app\models\MeetingAgenda;
use app\models\SubmissionCommitteeRevise;
use Phpdocx\Create\CreateDocxFromTemplate;
use Phpdocx\Create\CreateDocx;

/**
 * ResultDocumentController implements the CRUD actions for ResultDocument model.
 */
class ResultDocumentController extends RbacController {

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

    /**
     * Lists all ResultDocument models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ResultDocumentSearch();
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListDocumentSelect($id) {
        $searchModel = new ResultDocumentSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $selectDocument = \app\models\AgendaResultDocument::findOne($id);

        return $this->render('list-document-select', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectDocument' => $selectDocument,
        ]);
    }

    /**
     * Displays a single ResultDocument model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($id);
            return [
                'title' => Yii::t('app', "หนังสือแจ้งผล {name}", [
                    'name' => $model->name
                ]),
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                Html::a(Yii::t('app', "แก้ไข"), ['update', 'id' => $id], ['class' => 'btn btn-primary btn-lg', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new ResultDocument();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มหนังสือแจ้งผลต้นแบบ"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $file = UploadedFile::getInstance($model, 'template_file');
                $model->template_file = $file;
                $model->template_file->name = uniqid() . '.' . $model->template_file->extension;
                $path = 'uploads/result-document-template/';
                $model->template_file->saveAs($path . $model->template_file->name);
                $model->template_file = $model->template_file->name;
                $model->save(FALSE);
                $model = new ResultDocument();
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "เพิ่มหนังสือแจ้งผลต้นแบบเรียบร้อยแล้ว"));
                return [
                    'forceReload' => '#crud-datatable-result-document-pjax',
                    'title' => Yii::t('app', "เพิ่มหนังสือแจ้งผลต้นแบบ"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มหนังสือแจ้งผลต้นแบบ"),
                    'content' => $this->renderAjax('create', [
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
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing ResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $templateFile = $model->template_file;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขหนังสือแจ้งผลต้นแบบ {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $file = UploadedFile::getInstance($model, 'template_file');
                if (isset($file)) {
                    $model->template_file = $file;
                    $model->template_file->name = uniqid() . '.' . $model->template_file->extension;
                    $path = 'uploads/result-document-template/';
                    $model->template_file->saveAs($path . $model->template_file->name);
                    $model->template_file = $model->template_file->name;
                } else {
                    $model->template_file = $templateFile;
                }
                $model->save(FALSE);
                //$model = new ResultDocument();
                return [
                    'forceReload' => '#crud-datatable-result-document-pjax',
                    'title' => Yii::t('app', "แก้ไขหนังสือแจ้งผลต้นแบบd {name}", [
                        'name' => $model->name
                    ]),
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขหนังสือแจ้งผลต้นแบบa {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('update', [
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
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing ResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-result-document-pjax',
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->redirect(['index']);
            }
        }
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-result-document-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ResultDocument model.
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

    public function actionViewTemplatePresident($id, $submissionId, $submissionReviseId = NULL, $submissionEcId = NULL, $submissionResultDocId = null) {
        $model = $this->findModel($id);
        $submission = Submission::findOne($submissionId);
        $revise = SubmissionCommitteeRevise::findOne($submissionReviseId);

// $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->templatePathAlias);
        $srdFile = false;
        if (isset($submissionResultDocId)) {
            $srd = \app\models\SubmissionResultDocument::findOne($submissionResultDocId);
            $infoFile = pathinfo($srd->document_file);
            if (in_array($infoFile['extension'], ['doc', 'docx'])) {
                $srdFile = true;
            }
        }
        if ($srdFile == true) {
            $docx = new CreateDocxFromTemplate($srd->filePath);
        } else {
            $docx = new CreateDocxFromTemplate($model->templatePathAlias);
        }

        $docx->setTemplateSymbol('$');

        $ma = $submission->meetingAgenda;
        if (isset($submission->refSubmission)) {
            $ma = $submission->refSubmission->meetingAgenda;
        }
        $endorseMa = $submission->firstEndorseMeetingAgenda;

// $parser = new \HTMLtoOpenXML\Parser();
        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'th';

        $currentDate = \Yii::$app->formatter->asDate(time(), 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate(time(), 'php:n')] . ' ' . (date('Y') + 543);
        $meetingDate = isset($ma) ? \Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:Y') + 543) : "";
        $endorseDate = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->certified_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->certified_date, 'php:Y') + 543) : "";
        $lastKeepDate = isset($submission->last_keep_date) ? \Yii::$app->formatter->asDate($submission->last_keep_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:Y') + 543) : "";
        $expireDate = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->expire_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->expire_at, 'php:Y') + 543) : "";
        $corresspondenceDate = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y') + 543) : "";
        $nextProgressDate = isset($submission->next_progress_at) ? \Yii::$app->formatter->asDate($submission->next_progress_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:Y') + 543) : "";
//        $lastKeepDate = isset($ma) ? new \DateTime($ma->meeting->start_date) : NULL;
//        $lastKeepDate = isset($lastKeepDate) ? $lastKeepDate->add(new \DateInterval('P3Y'))->format('Y-m-d') : NULL;
//        $lastKeepDate = isset($lastKeepDate) ? \Yii::$app->formatter->asDate($lastKeepDate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($lastKeepDate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($lastKeepDate, 'php:Y') + 543) : NULL;

        \Yii::$app->formatter->locale = 'en';
        $currentDateEng = \Yii::$app->formatter->asDate(time(), 'php:d F Y');
        $meetingDateEng = isset($ma->meeting->start_date) ? \Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d F Y') : "";
        $endorseDateEng = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d F Y') : "";
        $expireDateEng = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d F Y') : "";
        $nextProgressDateEng = isset($submission->next_progress_at) ? \Yii::$app->formatter->asDate($submission->next_progress_at, 'php:d F Y') : "";
        $corresspondenceDateEng = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d F Y') : "";
        $personRole = \app\models\PersonRolePanel::find()->joinWith('personRole')->isDeleted(false)->role(\app\models\Role::PRESIDENT)->panel($submission->project->panel_id)->isRegular()->orderBy('person_role_panel.id DESC')->one();

        \Yii::$app->formatter->locale = $lc;

        $researcherThai = $submission->projectLeader->person->fullNameNoTitle;
        $submissionType = $submission->submissionType->name;
        $divisionThai = $submission->projectLeader->person->divisionThai;
        $meetingNo = isset($ma) ? $ma->meeting->yearNo : "";
//        $meetingNoEng = isset($ma) ? $ma->meeting->yearNoEng : "";
        $endorseMeetingNo = isset($endorseMa) ? $endorseMa->meeting->yearNo : '';
        $agendaNo = isset($ma) ? $ma->sort_label : "";
        $subject = isset($ma) ? $ma->agenda->name : "";
        $panelId = $submission->project->panel_id;
        $panelName = $submission->project->panel->name;
        $panelNameEng = $submission->project->panel->name_eng;
        if (isset($submission->president_person)) {
            $chairman = $submission->presidentPerson->person;
            $presidentName = $submission->presidentPerson->person->fullName;
            $presidentNameEng = $submission->presidentPerson->person->fullNameEng;
        } else {
            $chairman = $submission->project->panel->chairman;
            $presidentName = $submission->project->panel->chairman->fullName;
            $presidentNameEng = $submission->project->panel->chairman->fullNameEng;
        }
//        $chairman = $submission->project->panel->chairman;
        $sponser = isset($submission->project->funding_source_id) ? $submission->project->fundingSource->name : "";
        $sponserEng = isset($submission->project->funding_source_id) ? $submission->project->fundingSource->name_eng : "";
        $sponserDescription = !empty($submission->project->funding_source_description) ? $submission->project->funding_source_description : "";
//        $presidentName = isset($personRole->personRole->person_id) ? $personRole->personRole->person->fullName : "";
//        $presidentNameEng = isset($personRole->personRole->person_id) ? $personRole->personRole->person->fullNameEng : "";
        $leader = $submission->projectLeader->person->fullNameNoTitle;
        $leaderOrg = $submission->projectLeader->person->divisionThai;
        $leaderEng = $submission->projectLeader->person->fullNameEngNoTitle;
        $leaderOrgEng = $submission->projectLeader->person->divisionEng;
        $progressNo = isset($ma) ? MeetingAgenda::find()->isDeleted(FALSE)->submission($submission->id)->agenda($ma->agenda_id)->count() : "";

        $rname = $researcherThai . ' ' . $divisionThai;


        $volunteer = $this->renderPartial('_volunteers', ['submission' => $submission]);
        $volunteer = $this->renderPartial('_wrap', ['content' => $volunteer]);

        $researchers = $submission->projectCoResearchers;
        $researcher = $this->renderPartial('_researchers', ['researchers' => $researchers, 'submission' => $submission]);
        $researcher = $this->renderPartial('_wrap', ['content' => $researcher]);
        $specialCondition = $this->renderPartial('_content', ['submission' => $submission]);
        $specialCondition = $this->renderPartial('_wrap', ['content' => $specialCondition]);
        $researcherEng = $this->renderPartial('_researchers', ['researchers' => $researchers, 'submission' => $submission, 'eng' => true]);
        $researcherEng = $this->renderPartial('_wrap', ['content' => $researcherEng]);

        $docs = $submission->project->getLatestEndorseDocuments();
        $token = Yii::$app->security->generateRandomString() . '_' . time();

        $document = '';
        $documentEng = '';
        $document = $this->renderPartial('_documents', ['docs' => $docs, 'submission' => $submission]);
        $document = $this->renderPartial('_wrap', ['content' => $document]);
        $documentEng = $this->renderPartial('_documents', ['docs' => $docs, 'eng' => true, 'submission' => $submission]);
        $documentEng = $this->renderPartial('_wrap', ['content' => $documentEng]);

        $issues = $this->renderPartial('_wrap-text', ['content' => strip_tags($submission->issue1, '<p><div><br><li><ol>')]);
        $issuesEng = $this->renderPartial('_wrap-text', ['content' => strip_tags($submission->issue1_eng, '<p><div><br><li><ol>')]);
        $issues2 = $this->renderPartial('_wrap', ['content' => strip_tags($submission->issue2, '<p><div><br><li><ol>')]);
//        $specialCondition = $this->renderPartial('_wrap', ['content' => strip_tags($submission->special_condition, '<p><div><br><li><ol>')]);
        $reviseRemark = $this->renderPartial('_wrap', ['content' => isset($revise) && !empty($revise->remark) ? $revise->remark : ""]);

        $images = $this->renderPartial('_image', ['submission' => $submission, 'type' => 'eng']);
        $images = $this->renderPartial('_wrap', ['content' => $images]);

        $imagesThai = $this->renderPartial('_image', ['submission' => $submission, 'type' => 'thai']);
        $imagesThai = $this->renderPartial('_wrap', ['content' => $imagesThai]);

        $imagesLetter = $this->renderPartial('_image-letter', ['submission' => $submission, 'type' => 'eng']);
        $imagesLetter = $this->renderPartial('_wrap', ['content' => $imagesLetter]);

        $imagesLetterThai = $this->renderPartial('_image-letter', ['submission' => $submission, 'type' => 'thai']);
        $imagesLetterThai = $this->renderPartial('_wrap', ['content' => $imagesLetterThai]);

        $meetingNoEng = $this->renderPartial('_wrap-text', ['content' => $meetingNoEng]);

// สร้าง token จาก project code + reference โดย sign ด้วย secret ของระบบ
//        $payload = $submission->project->project_code . '|' . $model->id;
//        $sig = substr(hash_hmac('sha256', $payload, Yii::$app->params['qrSecret']), 0, 16);
//
//        $verifyUrl = Yii::$app->urlManager->createAbsoluteUrl([
//            '/site/verify',
//            'code' => $submission->project->project_code,
//            'id' => $submission->id,
//            'sig' => $sig,
//        ]);
//
//        $qrPath = \app\components\QrCodeHelper::generateFile($verifyUrl, 300);
//        $qrFragment = new \Phpdocx\Elements\WordFragment($docx, 'footer');
//        $qrFragment->addImage([
//            'src' => $qrPath,
//            'width' => '70px', // ปรับขนาดให้พอดีเซลล์ฟุตเตอร์
//            'height' => '70px',
//        ]);
        $crecText = '';
        if (isset($submission->is_submit_by_api)) {
            $crecText = 'และที่ประชุมคณะกรรมการกลางพิจารณาจริยธรรมการวิจัยในคน (Central Research Ethics Committee ; CREC) ได้พิจารณา และส่งผลการพิจารณามาให้คณะกรรมการจริยธรรมในมนุษย์ มหาวิทยาลัยขอนแก่น และมีเอกสารที่ส่งมาพร้อมกันนี้)';
        }

        $resolutionEng = '';
        if ($submission->submissionType->resolution_label == "รับรอง") {
            $resolutionEng = 'Approval';
        } else {
            $resolutionEng = 'Exemption';
        }

        $variables = [
            'crec-text' => $crecText,
            'current-date-eng' => $currentDateEng,
            'current-date' => $currentDate,
//            'ref-certificate-no' => isset($submission->ref_certificate_no) ? $submission->ref_certificate_no : "",
            'fda-no' => isset($submission->project->fda_no) ? $submission->project->fda_no : "",
            'meeting-no' => $meetingNo,
            'ref-meeting-no' => isset($submission->ref_submission_id) ? $submission->refSubmission->meetingAgenda->meeting->yearNo : "",
            'agenda-no' => $agendaNo,
            'meeting-date-eng' => $meetingDateEng,
            'meeting-date' => $meetingDate,
            'agenda-date' => $meetingDate,
            'subject' => $subject,
            'project-thai' => $submission->project->name_thai,
            'submission-number' => isset($submission->submission_number) ? $submission->submission_number : "",
            'project-eng' => $submission->project->name_eng,
            'project-code' => $submission->project->project_code,
            'certificate-no' => !empty($submission->project->certificate_no) ? $submission->project->certificate_no : "",
            'researcher-thai' => $researcherThai,
            'researcher-thai-title' => $rname,
            'chairman' => $chairman->fullName,
            'resolution-type-eng' => $resolutionEng,
            'resolution-type' => $submission->submissionType->resolution_label,
            'project-type' => $projectType,
            'project-type-eng' => $projectTypeEng,
            'last-keep-date' => $lastKeepDate,
            'n-date' => $lastKeepDate,
            'chairman-eng' => $chairman->fullNameEng,
            'president-name' => $presidentName,
            'submission-president-name' => isset($submission->president_person) ? $submission->presidentPerson->person->fullName : "",
            'submission-president-name-eng' => isset($submission->president_person) ? $submission->presidentPerson->person->fullNameEng : "",
            'leader' => $leader,
            'leader-org' => $leaderOrg,
            'leader-eng' => $leaderEng,
            'leader-org-eng' => $leaderOrgEng,
            'progress-period' => $submission->progress_period,
            'endorse-date-thai' => $endorseDate,
            'expire-date-thai' => $expireDate,
            'endorse-date-eng' => $endorseDateEng,
            'expire-date-eng' => $expireDateEng,
//            'last-keep-date' => $lastKeepDate,
            'correspondence-no' => $submission->correspondence_no,
            'correspondence-date' => $corresspondenceDate,
            'next-progress-date' => $nextProgressDate,
            'next-progress-date-eng' => $nextProgressDateEng,
            'correspondence-date-eng' => $corresspondenceDateEng,
            'division-thai' => $divisionThai,
            'endorse-meeting-no' => $endorseMeetingNo,
            'panel-id' => $panelId,
            'panel-name' => $panelName,
            'panel-name-eng' => $panelNameEng,
            'progress-no' => $progressNo,
            'submission-type' => $submissionType,
            'sponser' => $sponser,
            'sponser-eng' => $sponserEng,
            'sponser-description' => $sponserDescription,
            'code-panel' => isset($submission->project->panel->ref_letter) ? $submission->project->panel->ref_letter : "",
            'assess-type' => isset($submission->assess_type) ? Submission::getAssessTypeLabel()[$submission->assess_type] : "",
        ];


        $docx->replaceVariableByText($variables);
        $docx->replaceVariableByText($variables, ['target' => 'footer']);
        $docx->replaceVariableByHTML('chairman-signature-letter-thai', 'block', $imagesLetterThai, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('chairman-signature-letter', 'block', $imagesLetter, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('chairman-signature-thai', 'block', $imagesThai, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('chairman-signature-eng', 'block', $images, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('document', 'block', $document, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('documentEng', 'block', $documentEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('researcher', 'block', $researcher, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('volunteer', 'block', $volunteer, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('researcherEng', 'block', $researcherEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues', 'block', $issues, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues-eng', 'block', $issuesEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('meeting-no-eng', 'block', $meetingNoEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues2', 'block', $issues2, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('special-condition', 'block', $specialCondition, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('revise-remark', 'block', $reviseRemark, ['isFile' => false, 'embedFonts' => true]);
//        $docx->replaceVariableByWordFragment(
//                ['qrcode' => $qrFragment],
//                ['type' => 'inline', 'target' => 'footer']   // ⭐ ต้องมี target = footer
//        );
        $code = str_replace('/', '-', $submission->project->project_code);
        $nameR = mb_substr($model->name, 0, 50, 'UTF-8');
        $file = "{$code}.docx";

// สร้างไฟล์ DOCX ชั่วคราว
        $docxPath = Yii::getAlias("@app/web/tmp/{$file}");
        $docx->createDocx($docxPath);

// แปลง DOCX เป็น PDF
        $pdfFile = str_replace('.docx', '.pdf', $file);
        $pdfPath = Yii::getAlias("@app/web/tmp/{$pdfFile}");


// ใช้ LibreOffice หรือ unoconv แปลงเป็น PDF
// $cmd = "libreoffice --headless --convert-to pdf --outdir " . Yii::getAlias("@app/web/tmp") . " " . escapeshellarg($docxPath);
// exec($cmd);
        $newDocx = new CreateDocx();
        $newDocx->transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);
//        ResultDocument::addNoApproveWatermark($pdfPath, $pdfPath);
// ตรวจสอบว่าไฟล์ PDF ถูกสร้างแล้ว
        if (file_exists($pdfPath)) {
// แสดง PDF บน browser
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $pdfFile . '"');
            header('Expires: 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($pdfPath));
            readfile($pdfPath);

// ลบไฟล์ชั่วคราว
            @unlink($docxPath);
            @unlink($pdfPath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่สามารถแปลงไฟล์เป็น PDF ได้'));
        }
        exit;
    }

    public function actionDownloadTemplate($id, $submissionId, $submissionReviseId = NULL) {
        $model = $this->findModel($id);
        $submission = Submission::findOne($submissionId);
        $revise = SubmissionCommitteeRevise::findOne($submissionReviseId);
        // $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->templatePathAlias);
        $docx = new CreateDocxFromTemplate($model->templatePathAlias);
        $docx->setTemplateSymbol('$');

        $ma = $submission->meetingAgenda;
        if (!isset($ma) && isset($submission->refSubmission)) {
            $ma = $submission->refSubmission->meetingAgenda;
        }
        $endorseMa = $submission->firstEndorseMeetingAgenda;

        // $parser = new \HTMLtoOpenXML\Parser();
        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'th';
        $currentDate = \Yii::$app->formatter->asDate(time(), 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate(time(), 'php:n')] . ' ' . (date('Y') + 543);
        $meetingDate = isset($ma) ? \Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:Y') + 543) : "";
        $endorseDate = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->certified_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->certified_date, 'php:Y') + 543) : "";
        $lastKeepDate = isset($submission->last_keep_date) ? \Yii::$app->formatter->asDate($submission->last_keep_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:Y') + 543) : "";
        $expireDate = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->expire_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->expire_at, 'php:Y') + 543) : "";
        $corresspondenceDate = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y') + 543) : "";
        $nextProgressDate = isset($submission->next_progress_at) ? \Yii::$app->formatter->asDate($submission->next_progress_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:Y') + 543) : "";
//        $lastKeepDate = isset($ma) ? new \DateTime($ma->meeting->start_date) : NULL;
//        $lastKeepDate = isset($lastKeepDate) ? $lastKeepDate->add(new \DateInterval('P3Y'))->format('Y-m-d') : NULL;
//        $lastKeepDate = isset($lastKeepDate) ? \Yii::$app->formatter->asDate($lastKeepDate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($lastKeepDate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($lastKeepDate, 'php:Y') + 543) : NULL;

        \Yii::$app->formatter->locale = 'en';
        $endorseDateEng = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d F Y') : "";
        $expireDateEng = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d F Y') : "";
        $corresspondenceDateEng = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d F Y') : "";

        \Yii::$app->formatter->locale = $lc;

        $researcherThai = $submission->project->projectLeader->person->fullName;
        $submissionType = $submission->submissionType->name;
        $divisionThai = $submission->project->projectLeader->person->divisionThai;
        $meetingNo = isset($ma) ? $ma->meeting->yearNo : "";
        $endorseMeetingNo = isset($endorseMa) ? $endorseMa->meeting->yearNo : '';
        $agendaNo = isset($ma) ? $ma->sort_label : "";
        $subject = isset($ma) ? $ma->agenda->name : "";
        $panelId = $submission->project->panel_id;
        $chairman = $submission->project->panel->chairman;

        $leader = $submission->projectLeader->person->fullName;
        $leaderOrg = $submission->projectLeader->person->divisionThai;
        $leaderEng = $submission->projectLeader->person->fullNameEng;
        $leaderOrgEng = $submission->projectLeader->person->divisionEng;

        $progressNo = isset($ma) ? MeetingAgenda::find()->isDeleted(FALSE)->submission($submission->id)->agenda($ma->agenda_id)->count() : "";


        $volunteer = $this->renderPartial('_volunteers', ['submission' => $submission]);
        $volunteer = $this->renderPartial('_wrap', ['content' => $volunteer]);
        
        $researchers = $submission->projectCoResearchers;
        $researcher = $this->renderPartial('_researchers', ['researchers' => $researchers, 'submission' => $submission]);
        $researcher = $this->renderPartial('_wrap', ['content' => $researcher]);
        $specialCondition = $this->renderPartial('_content', ['submission' => $submission]);
        $specialCondition = $this->renderPartial('_wrap', ['content' => $specialCondition]);
        $researcherEng = $this->renderPartial('_researchers', ['researchers' => $researchers, 'submission' => $submission, 'eng' => true]);
        $researcherEng = $this->renderPartial('_wrap', ['content' => $researcherEng]);


        // $vars = $templateProcessor->getVariables();
        // if (in_array('researcher', $vars)) {
        //     $templateProcessor->cloneRow('researcher', count($researchers));
        // }
        // if (in_array('researcher-eng', $vars)) {
        //     $templateProcessor->cloneRow('researcher-eng', count($researchers));
        // }
        // foreach ($researchers as $i => $r) {
        //     $index = $i + 1;
        //     $templateProcessor->setValue("researcher#{$index}", $r->person->fullName);
        //     $templateProcessor->setValue("researcher-org#{$index}", $r->person->divisionThai);
        //     $templateProcessor->setValue("researcher-eng#{$index}", $r->person->fullNameEng);
        //     $templateProcessor->setValue("researcher-org-eng#{$index}", $r->person->divisionEng);
        // }
        $docs = $submission->project->getLatestEndorseDocuments();

        $document = '';
        $documentEng = '';
        $document = $this->renderPartial('_documents', ['docs' => $docs, 'submission' => $submission]);
        $document = $this->renderPartial('_wrap', ['content' => $document]);
        $documentEng = $this->renderPartial('_documents', ['docs' => $docs, 'eng' => true, 'submission' => $submission]);
        $documentEng = $this->renderPartial('_wrap', ['content' => $documentEng]);


        // if (in_array('document', $vars)) {
        //     $templateProcessor->cloneRow('document', count($docs));
        // }
        // if (in_array('document-eng', $vars)) {
        //     $templateProcessor->cloneRow('document-eng', count($docs));
        // }
        // foreach ($docs as $i => $d) {
        //     $index = $i + 1;
        //     $templateProcessor->setValue("document#{$index}", "{$index}. {$d->certificateName}");
        //     $templateProcessor->setValue("document-eng#{$index}", "${index}. {$d->certificateNameEng}");
        // }
        //        $index = count($docs);
        //        $index++;
        //        $templateProcessor->setValue("document#{$index}", "{$index}. ประวัติผู้วิจัย");
        //        $templateProcessor->setValue("document-eng#{$index}", "{$index}. Principal Investigator’s and Co-Investigator’s Curriculum Vitae");
        // $committees = $submission->getSubmissionCommittees()->isDeleted(FALSE)->all();
        // $count = 0;
        // foreach ($committees as $i => $com) {
        //     $answers = $com->getQuestionnaireAnswers()->isDeleted(FALSE)->all();
        //     foreach ($answers as $answer) {
        //         $count++;
        //     }
        // }
        // if (in_array('committee-assess', $vars)) {
        //     $templateProcessor->cloneRow('committee-assess', $count);
        //     $i = 1;
        //     foreach ($committees as $i => $com) {
        //         $answers = $com->getQuestionnaireAnswers()->isDeleted(FALSE)->all();
        //         foreach ($answers as $answer) {
        //             $templateProcessor->setValue("committee-assess#{$i}", "{$answer->fullAnswer}");
        //             $i++;
        //         }
        //     }
        // }

        $issue1 = strip_tags($submission->issue1, '<p><div><br><img><image>');
        $issues = $this->renderPartial('_wrap', ['content' => strip_tags($submission->issue1, '<p><div><br><li><ol>')]);
        $issuesEng = $this->renderPartial('_wrap', ['content' => strip_tags($submission->issue1_eng, '<p><div><br><li><ol>')]);
        $issues2 = $this->renderPartial('_wrap', ['content' => strip_tags($submission->issue2, '<p><div><br><li><ol>')]);
        $reviseRemark = $this->renderPartial('_wrap', ['content' => isset($revise) && !empty($revise->remark) ? $revise->remark : ""]);
//        $specialCondition = $this->renderPartial('_wrap', ['content' => strip_tags($submission->special_condition, '<p><div><br><li><ol>')]);
        // require_once \Yii::getAlias('@app/components/HTMLtoOpenXML/HTMLtoOpenXML.php');
        // $templateProcessor->setValue('current-date', $currentDate);
        // $templateProcessor->setValue('meeting-no', $meetingNo);
        // $templateProcessor->setValue('ref-meeting-no', isset($submission->ref_submission_id) ? $submission->refSubmission->meetingAgenda->meeting->yearNo : "");
        // $templateProcessor->setValue('agenda-no', $agendaNo);
        // $templateProcessor->setValue('meeting-date', $meetingDate);
        // $templateProcessor->setValue('project-thai', $submission->project->name_thai);
        // $templateProcessor->setValue('project-eng', $submission->project->name_eng);
        // $templateProcessor->setValue('project-code', $submission->project->project_code);
        // $templateProcessor->setValue('researcher-thai', $researcherThai);
        // $templateProcessor->setValue('issues', !empty($submission->issue1) ? $parser->fromHTML($submission->issue1) : "");
        // $templateProcessor->setValue('issues2', !empty($submission->issue2) ? $parser->fromHTML($submission->issue2) : "");
        // $templateProcessor->setValue('revise-remark', isset($revise) && !empty($revise->remark) ? $parser->fromHTML($revise->remark) : "");
        // $templateProcessor->setValue('chairman', $chairman->fullName);
        // $templateProcessor->setValue('chairman-eng', $chairman->fullNameEng);
        // $templateProcessor->setValue('leader', $leader);
        // $templateProcessor->setValue('leader-org', $leaderOrg);
        // $templateProcessor->setValue('leader-eng', $leaderEng);
        // $templateProcessor->setValue('leader-org-eng', $leaderOrgEng);
        // $templateProcessor->setValue('progress-period', $submission->progress_period);
        // $templateProcessor->setValue('endorse-date-thai', $endorseDate);
        // $templateProcessor->setValue('expire-date-thai', $expireDate);
        // $templateProcessor->setValue('endorse-date-eng', $endorseDateEng);
        // $templateProcessor->setValue('expire-date-eng', $expireDateEng);
        // $templateProcessor->setValue('last-keep-date', $lastKeepDate);
        // $templateProcessor->setValue('correspondence-no', $submission->correspondence_no);
        // $templateProcessor->setValue('correspondence-date', $corresspondenceDate);
        // $templateProcessor->setValue('next-progress-date', $nextProgressDate);
        // $templateProcessor->setValue('correspondence-date-eng', $corresspondenceDateEng);
        // $templateProcessor->setValue('division-thai', $divisionThai);
        // $templateProcessor->setValue('endorse-meeting-no', $endorseMeetingNo);
        // $templateProcessor->setValue('panel-id', $panelId);
        // $templateProcessor->setValue('progress-no', $progressNo);
        // $templateProcessor->setValue('submission-type', $submissionType);
//         'issues', !empty($submission->issue1) ? $parser->fromHTML($submission->issue1) : ""
//         'issues2', !empty($submission->issue2) ? $parser->fromHTML($submission->issue2) : ""
// 'revise-remark', isset($revise) && !empty($revise->remark) ? $parser->fromHTML($revise->remark) : ""

        $resolutionEng = '';
        if ($submission->submissionType->resolution_label == "รับรอง") {
            $resolutionEng = 'Approval';
        } else {
            $resolutionEng = 'Exemption';
        }

        $variables = [
            'current-date' => $currentDate,
            'meeting-no' => $meetingNo,
            'ref-meeting-no' => isset($submission->ref_submission_id) ? $submission->refSubmission->meetingAgenda->meeting->yearNo : "",
            'agenda-no' => $agendaNo,
            'meeting-date' => $meetingDate,
            'agenda-date' => $meetingDate,
            'subject' => $subject,
            'project-thai' => $submission->project->name_thai,
            'project-eng' => $submission->project->name_eng,
            'project-code' => $submission->project->project_code,
            'researcher-thai' => $researcherThai,
            'chairman' => $chairman->fullName,
            'chairman-eng' => $chairman->fullNameEng,
            'leader' => $leader,
            'leader-org' => $leaderOrg,
            'leader-eng' => $leaderEng,
            'leader-org-eng' => $leaderOrgEng,
            'progress-period' => $submission->progress_period,
            'endorse-date-thai' => $endorseDate,
            'last-keep-date' => $lastKeepDate,
            'expire-date-thai' => $expireDate,
            'endorse-date-eng' => $endorseDateEng,
            'expire-date-eng' => $expireDateEng,
//            'last-keep-date' => $lastKeepDate,
            'correspondence-no' => $submission->correspondence_no,
            'correspondence-date' => $corresspondenceDate,
            'next-progress-date' => $nextProgressDate,
            'correspondence-date-eng' => $corresspondenceDateEng,
            'division-thai' => $divisionThai,
            'endorse-meeting-no' => $endorseMeetingNo,
            'panel-id' => $panelId,
            'progress-no' => $progressNo,
            'submission-type' => $submissionType,
            'resolution-type-eng' => $resolutionEng,
            'resolution-type' => $submission->submissionType->resolution_label,
        ];

        $docx->replaceVariableByText($variables);
        $docx->replaceVariableByText($variables, ['target' => 'footer']);
        $docx->replaceVariableByHTML('document', 'block', $document, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('documentEng', 'block', $documentEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('researcher', 'block', $researcher, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('volunteer', 'block', $volunteer, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('researcherEng', 'block', $researcherEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues', 'block', $issues, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues-eng', 'block', $issuesEng, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('issues2', 'block', $issues2, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('revise-remark', 'block', $reviseRemark, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('special-condition', 'block', $specialCondition, ['isFile' => false, 'embedFonts' => true]);

        $docx->createDocxAndDownload($model->template_file);

        // $file = uniqid() . ".docx";
        // $filePath = Yii::getAlias("@app/web/tmp/{$file}");
        // $templateProcessor->saveAs($filePath);
        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename="' . $model->template_file . '"');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header('Content-Length: ' . filesize($filePath));
        // readfile($filePath);
        // exit;
//        $word = \PhpOffice\PhpWord\IOFactory::load($filePath);
//        header("Content-Description: File Transfer");
//        header('Content-Disposition: attachment; filename="test.docx"');
//        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Expires: 0');
//        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
//        $xmlWriter->save("php://output");
    }

    public function actionDownload($id) {
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->templatePathAlias);
        // $fileName = "{$model->name}.{$info['extension']}";
        $fileName = $model->template_file;
        //        echo $model->filePath;
        if (file_exists($model->templatePathAlias)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->templatePathAlias));
            readfile($model->templatePathAlias);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    /**
     * Finds the ResultDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResultDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ResultDocument::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
