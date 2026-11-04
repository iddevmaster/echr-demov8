<?php

namespace app\controllers;

use Yii;
use app\models\Document;
use app\models\DocumentSearch;
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

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends RbacController {

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
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DocumentSearch();
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListDocumentSelect($id) {
        $searchModel = new DocumentSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $selectDocument = \app\models\DocumentSubmissionType::findOne($id);

        return $this->render('list-document-select', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectDocument' => $selectDocument,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($id);
            return [
                'title' => Yii::t('app', "เอกสารประกอบงานวิจัย {name}", [
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
     * Creates a new Document model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new Document();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มเอกสารประกอบงานวิจัย"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {

                $file = UploadedFile::getInstance($model, 'template_file');
                if (isset($file)) {
                    $model->template_file = $file;
                    $model->template_file->name = 'thai-' . uniqid() . '.' . $model->template_file->extension;
                    $path = 'uploads/document-template/';
                    $model->template_file->saveAs($path . $model->template_file->name);
                    $model->template_file = $model->template_file->name;
                }
                $file_eng = UploadedFile::getInstance($model, 'template_file_eng');
                if (isset($file_eng)) {
                    $model->template_file_eng = $file_eng;
                    $model->template_file_eng->name = 'eng-' . uniqid() . '.' . $model->template_file_eng->extension;
                    $path = 'uploads/document-template/';
                    $model->template_file_eng->saveAs($path . $model->template_file_eng->name);
                    $model->template_file_eng = $model->template_file_eng->name;
                }
                $model->save(FALSE);
                $model = new Document();
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "เพิ่มเอกสารประกอบงานวิจัยเรียบร้อยแล้ว"));
                return [
                    'forceReload' => '#crud-datatable-document-pjax',
                    'title' => Yii::t('app', "เพิ่มเอกสารประกอบงานวิจัย"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มเอกสารประกอบงานวิจัย"),
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
     * Updates an existing Document model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $templateFile = $model->template_file;
        $templateFileEng = $model->template_file_eng;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขเอกสารประกอบงานวิจัย {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $file = UploadedFile::getInstance($model, 'template_file');
                if (isset($file)) {
                    $model->template_file = $file;
                    $model->template_file->name = 'thai-' . uniqid() . '.' . $model->template_file->extension;
                    $path = 'uploads/document-template/';
                    $model->template_file->saveAs($path . $model->template_file->name);
                    $model->template_file = $model->template_file->name;
                } else {
                    $model->template_file = $templateFile;
                }

                $file_eng = UploadedFile::getInstance($model, 'template_file_eng');
                if (isset($file_eng)) {
                    $model->template_file_eng = $file_eng;
                    $model->template_file_eng->name = 'eng-' . uniqid() . '.' . $model->template_file_eng->extension;
                    $path = 'uploads/document-template/';
                    $model->template_file_eng->saveAs($path . $model->template_file_eng->name);
                    $model->template_file_eng = $model->template_file_eng->name;
                } else {
                    $model->template_file_eng = $templateFileEng;
                }


                $model->save(FALSE);
                $model = new Document();
                // Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "เพิ่มเอกสารประกอบงานวิจัยเรียบร้อยแล้ว"));
                return [
                    'forceReload' => '#crud-datatable-document-pjax',
                    'title' => Yii::t('app', "แก้ไขเอกสารประกอบงานวิจัย {name}", [
                        'name' => $model->name
                    ]),
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', "บันทึก"), ['update', 'id' => $id], ['class' => 'btn btn-primary btn-lg', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขเอกสารประกอบงานวิจัย {name}", [
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
     * Delete an existing Document model.
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
                    'forceReload' => '#crud-datatable-document-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-document-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Document model.
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

    public function actionDownloadTemplate($id, $submissionId, $lang = Document::LANG_DOM, $submissionComitteeId = NULL) {
        $model = $this->findModel($id);

        $submission = Submission::findOne($submissionId);
        $ma = $submission->meetingAgenda;
        if (!isset($ma) && isset($submission->refSubmission)) {
            $ma = $submission->refSubmission->meetingAgenda;
        }
        $endorseMa = $submission->firstEndorseMeetingAgenda;

        if ($lang == Document::LANG_DOM) {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->templatePathAlias);
        } else {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->templatePathAliasEng);
        }
//        $templateProcessor->setValue('jobPosition', htmlentities('&', ENT_XML1));
        $submissionComittee = \app\models\SubmissionCommittee::findOne($submissionComitteeId);
//        \Yii::$app->formatter->locale = 'en';

        $endorseDate = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d F ') . (\Yii::$app->formatter->asDate($submission->certified_date, 'php:Y') + 543) : "";
        $returnAt = "";
        if (isset($submissionComittee->submit_date)) {
            $returnAt = new \DateTime($submissionComittee->submit_date);
            $returnAt->add(new \DateInterval('P7D'));
            $returnAt = $returnAt->format('d F ') . ($returnAt->format('Y') + 543);
        }
        $meetingNo = isset($ma) ? $ma->meeting->yearNo : "";
        $endorseMeetingNo = isset($endorseMa) ? $endorseMa->meeting->yearNo : '';

        $templateProcessor->setValue('endorse-date-thai', $endorseDate);
        $templateProcessor->setValue('endorse-meeting-no', $endorseMeetingNo);
//        $templateProcessor->setValue('project-thai', $submission->project->name_thai);
        $templateProcessor->setValue('project-thai', htmlentities($submission->project->name_thai, ENT_XML1));
//        $templateProcessor->setValue('project-thai', '&');
        $templateProcessor->setValue('position', isset($submission->project->projectLeader->person->position_id) ? $submission->project->projectLeader->person->position->name : "");
//        $templateProcessor->setValue('project-eng', $submission->project->name_eng);
        $templateProcessor->setValue('project-eng', htmlentities($submission->project->name_eng, ENT_XML1));
        $templateProcessor->setValue('project-code', $submission->project->project_code);
        $templateProcessor->setValue('researcher-thai', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->fullName : "");
        $templateProcessor->setValue('researcher-eng', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->fullNameEng : "");
        $templateProcessor->setValue('researcher', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->fullName : "");
        $templateProcessor->setValue('division', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->divisionThai : "");
        $templateProcessor->setValue('division-eng', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->divisionEng : "");
        $templateProcessor->setValue('full-org', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->fullOrg : "");
        $templateProcessor->setValue('tel', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->tel : "");
        $templateProcessor->setValue('email', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->email : "");
        $templateProcessor->setValue('division-thai', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->divisionThai : "");
//        $templateProcessor->setValue('researcher-eng', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->fullNameEng : "");
        $templateProcessor->setValue('division-eng', isset($submission->project->projectLeader->person) ? $submission->project->projectLeader->person->divisionEng : "");
        $templateProcessor->setValue('committee', isset($submissionComittee) ? $submissionComittee->person->fullName : "");
        $templateProcessor->setValue('committee-return-at', $returnAt);

        $file = uniqid() . ".docx";
        $filePath = Yii::getAlias("@app/web/tmp/{$file}");

        $templateProcessor->saveAs($filePath);
        if ($lang == Document::LANG_DOM) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $model->name . ".docx" . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $model->name . ".docx" . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        }

        exit;
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

    public function actionDownloadEng($id) {
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->templatePathAliasEng);
        // $fileName = "{$model->name}.{$info['extension']}";
        $fileName = $model->template_file_eng;
        //        echo $model->filePath;
        if (file_exists($model->templatePathAliasEng)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->templatePathAliasEng));
            readfile($model->templatePathAliasEng);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
