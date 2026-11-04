<?php

namespace app\controllers;

use app\components\Crec;
use Yii;
use app\models\SubmissionDocument;
use app\models\SubmissionDocumentSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Submission;
use kartik\widgets\Alert;
use yii\helpers\Json;
use Phpdocx\Utilities\MultiMerge;
use yii\data\ArrayDataProvider;
use app\models\SubmissionVolunteer;
use app\models\Volunteer;
use Exception;
use Throwable;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * SubmissionDocumentController implements the CRUD actions for SubmissionDocument model.
 */
class SubmissionDocumentController extends RbacController {

    protected $allowedActions = ['create', 'delete'];

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
     * Lists all SubmissionDocument models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubmissionDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SubmissionDocument #",
                'content' => $this->renderAjax('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionUpdatePosition() {
        $rows = Yii::$app->request->post('rows');

        if ($rows) {

            foreach ($rows as $index => $id) {

                $model = SubmissionDocument::findOne($id);

                if ($model) {
                    $model->position = $index + 1;
                    $model->save(false);
                }
            }
        }

        return $this->asJson([
                    'success' => true
        ]);
    }

    public function actionSelectCertificate($submissionId, $id, $reloadUrl = null) {
        $request = Yii::$app->request;
        $reloadUrl = $reloadUrl ?? '#crud-datatable-submission-document-pjax';

//        $model = Project::findOne($projectId);
        $model = SubmissionDocument::findOne($id);
        $model->is_certificate = ($model->is_certificate ? 0 : 1);

        if (!$model->save(FALSE)) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => $reloadUrl,
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
            return ['forceClose' => true, 'forceReload' => $reloadUrl];
        } else {
            /*
             *   Process for non-ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => $reloadUrl];
//            return $this->redirect(['index']);
        }
    }

    /**
     * Displays a single SubmissionDocument model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'size' => 'large',
                'title' => "โครงการไข้หวัดใหญ่(การศึกษาระหว่างประเทศแบบสังเกตการณ์ในผู้ใหญ่ที่เป็นไข้หวัดใหญ่" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCheckDocuments($submissionId, $ids, $reloadUrl = NULL) {
        $request = Yii::$app->request;
        $reloadUrl = $reloadUrl ?? '#crud-datatable-submission-document-pjax';

        $model = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CHECK_MULTIPLE]);
        $model->submission_id = $submissionId;
        $model->ids = $ids;
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'ตรวจเอกสาร'),
                    'size' => 'large',
                    'content' => $this->renderAjax('check-documents', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) & $model->validate()) {
                $ids = \explode(',', $model->ids);
                $models = SubmissionDocument::find()->isDeleted(false)->hasFile()->andWHere(['id' => $ids])->all();
                foreach ($models as $m) {

                    $m->status = $model->status;
                    $m->remark = $model->remark;
                    $m->save(FALSE);
                }
                $modelCrecs = SubmissionDocument::find()->isDeleted(false)->hasSdCrecId()->andWHere(['id' => $ids])->all();
                foreach ($modelCrecs as $mc) {
                    $mc->status = $model->status;
                    $mc->remark = $model->remark;
                    $mc->save(FALSE);
                    if (isset($mc->sd_crec_id)) {
                        $result = Crec::checkDocument($mc);
                        if ($result['error']) {
                            throw new Exception($result['message']);
                        }
                    }
                }


                return [
                    'forceReload' => $reloadUrl,
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'ตรวจเอกสาร'),
                    'size' => 'large',
                    'content' => $this->renderAjax('check-documents', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('check-document', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionCheckDocument($id, $mode = NULL, $submissionId = NULL, $doc = null) {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        $model->scenario = SubmissionDocument::SCENARIO_CHECK;
        $subVol = SubmissionVolunteer::find()->isDeleted(false)
                        ->submissionId($model->submission_id)
                        ->volunteerId($model->volunteer_id)->one();

        $subEvent = \app\models\SubmissionEvent::find()->isDeleted(false)
                        ->event($model->submission_event_id)
                        ->submission($model->submission_id)->one();
//        $subEvent->load($request->post());
//        $deviationEvent = \app\models\DeviationEventType::find()->isDeleted(false)->submissionEvent($model->submission_event_id)->all();
//        $deviationEvent = new \app\models\DeviationEventType;

        $currentRole = \Yii::$app->session->get('currentRole');
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'ตรวจเอกสาร'),
                    'size' => 'large',
                    'content' => $this->renderAjax('check-document', [
                        'model' => $model,
                        'subVol' => $subVol,
                        'subEvent' => $subEvent,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) & $model->validate() & (
                    !isset($subVol) || $subVol->load($request->post())
                    ) & (
                    !isset($subEvent) || $subEvent->load($request->post())
                    )) {

                $tran = Yii::$app->db->beginTransaction();
                try {
                    if (isset($subVol) && $subVol->volunteerCode != $subVol->volunteer->code) {
                        $model->name = $model->document->name . "_{$subVol->volunteerCode}";
                        $model->name_eng = $model->document->name_eng . "_{$subVol->volunteerCode}";
                        $model->volunteer_id = $subVol->volunteer_id;
                        $volunteer = $subVol->volunteer;
                        if (count($subVol->histories) > 1) {
                            $vol = new Volunteer();
                            $vol->project_id = $subVol->submission->project_id;
                            $vol->code = $subVol->volunteerCode;
                            $vol->save(false);
                            $subVol->volunteer_id = $vol->id;
                            $subVol->save(false);
                        } else {
                            $volunteer->code = $subVol->volunteerCode;
                            $volunteer->save(false);
                            $subVol->save(false);
                        }
                    } else if (isset($subVol)) {
                        $subVol->save(false);
                    }
                    $model->save(FALSE);
                    if (!empty($subEvent->deviationTypes)) {
                        $currentIds = $subEvent->deviationTypes;
                        $currentText = $subEvent->other;
                        //                \yii\helpers\VarDumper::dump($currentIds);
                        \app\models\DeviationEventType::deleteAll('submission_event_id=:qtid', [':qtid' => $model->submission_event_id]);
                        foreach ($currentIds as $fid) {
                            $fo = new \app\models\DeviationEventType();
                            $fo->deviation_type_id = $fid;
                            $fo->submission_event_id = $model->submission_event_id;
                            if ($fid == 15) {
                                $fo->other = $currentText;
                            }
                            $fo->save();
                        }
                        $subEvent->save(false);
                    }
                    if (isset($model->sd_crec_id)) {
                        $result = Crec::checkDocument($model);
                        if ($result['error']) {
                            throw new Exception($result['message']);
                        }
                    }
                    $tran->commit();
                    //                
                    //                $submission = $model->submission;
                    //                $committee = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->submission($submission->id)->one();
                    //                $committeeRevise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
                    //                $docStatus = $submission->documentStatus;
                    //                $researcherStatus = $submission->cvStatus;
                    //                \yii\helpers\VarDumper::dump($docStatus);

                    return [
                        'forceReload' => '#crud-datatable-submission-document-pjax',
                        'forceClose' => true,
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } catch (Throwable $ex) {
                    $tran->rollBack();
                    return [
                        'title' => Yii::t('app', 'ตรวจเอกสาร'),
                        'size' => 'large',
                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                            'error' => $ex->getMessage(),
                        ]) . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', 'ตรวจเอกสาร') . $id,
                    'size' => 'large',
                    'content' => $this->renderAjax('check-document', [
                        'model' => $model,
                        'subVol' => $subVol,
                        'subEvent' => $subEvent,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('check-document', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Creates a new SubmissionDocument model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEdit($id, $reloadUrl = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $oldDoc = $this->findModel($id);
//        $model->status = NULL;
        $templateFile = $model->file_name;
        $reloadUrl = $reloadUrl ?? '#crud-datatable-submission-document-pjax';

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update SubmissionDocument #" . $id,
                    'size' => 'large',
                    'content' => $this->renderAjax('edit', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate(false)) {

                if (!isset($model->document_id)) {
                    $allOldDocs = SubmissionDocument::find()->name($oldDoc->name)->submission($oldDoc->submission_id)->documents(null)->all();
                    foreach ($allOldDocs as $od) {
                        $od->name = $model->name;
                        $od->name_eng = $model->name_eng;
//                        $od->deleted = TRUE;
                        $od->save(false);
                        $oldDoc->name = $model->name;
                        $oldDoc->name_eng = $model->name_eng;
//                        $oldDoc->deleted = TRUE;
                        $oldDoc->save(FALSE);
                    }
                }
                $model->save(FALSE);

                return [
                    'forceReload' => '#crud-datatable-submission-document-pjax',
                    'size' => 'large',
                    //                    'forceClose'=>TRUE,
                    'title' => Yii::t('app', 'แก้ไขข้อมูลไฟล์เอกสาร'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูลเอกสารเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'แก้ไขข้อมูลไฟล์เอกสาร'),
                    'size' => 'large',
                    'content' => $this->renderAjax('edit', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
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

    public function actionCreate($id = NULL, $submissionId = NULL, $documentId = NULL, $submissionDocumentId = null, $volunteerId = null, $eventId = null) {
        $request = Yii::$app->request;
        $currentRole = \Yii::$app->session->get('currentRole');

        $oldDoc = null;
        $model = new SubmissionDocument();
        if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
            $model->status = SubmissionDocument::STATUS_PASS;
        }
        if (isset($id)) {
            $oldDoc = SubmissionDocument::findOne($id);
        } else {
            if (isset($documentId)) {
                $oldDocQuery = SubmissionDocument::find()->isDeleted(FALSE)
                                ->submission($submissionId)->documents($documentId);
                if (isset($volunteerId)) {
                    $oldDocQuery->volunteerId($volunteerId);
                }
                if (isset($eventId)) {
                    $oldDocQuery->submissionEventId($eventId);
                }
                $oldDoc = $oldDocQuery->one();
            }
        }
        if (isset($oldDoc)) {
            $model->submission_id = $oldDoc->submission_id;
            $model->document_id = $oldDoc->document_id;
            $model->name = $oldDoc->name;
            $model->name_eng = $oldDoc->name_eng;
            $model->remark = $oldDoc->remark;
            $model->volunteer_id = $oldDoc->volunteer_id;
            $model->submission_event_id = $oldDoc->submission_event_id;
            $model->status = NULL;
        } else {
            $model->submission_id = $submissionId;
            $model->project_id = $model->submission->project_id;
            if (isset($documentId)) {
                $model->document_id = $documentId;
                $model->name = $model->document->name;
                $model->name_eng = $model->document->name_eng;
                if (isset($volunteerId)) {
                    $model->volunteer_id = $volunteerId;
                    $model->name .= "_{$model->volunteer->code}";
                    $model->name_eng .= "_{$model->volunteer->code}";
                }
                if (isset($eventId)) {
                    $model->submission_event_id = $eventId;
                    $model->name .= "_{$model->submissionEvent->event_no}";
                    $model->name_eng .= "_{$model->submissionEvent->event_no}";
                }
            }
            if (isset($submissionDocumentId)) {
                $subDoc = SubmissionDocument::findOne($submissionDocumentId);
                $model->document_id = $subDoc->document_id;
                $model->name = $subDoc->name;
                $model->name_eng = $subDoc->name_eng;
            }
        }
        $model->project_id = $model->submission->project_id;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'แนบไฟล์เอกสาร'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $file = UploadedFile::getInstance($model, 'file_name');
                $model->file_name = $file;
                if ($model->validate()) {
                    if (isset($oldDoc)) {
                        if ($oldDoc->submission->status < Submission::STATUS_DOC_REJECTED && !isset($oldDoc->submission->ref_submission_id)) {
                            unlink($oldDoc->filePath);
                            $oldDoc->delete();
                        } else {
                            if (!isset($model->document_id)) {
                                $allOldDocs = SubmissionDocument::find()->name($oldDoc->name)->submission($oldDoc->submission_id)->documents(null)->all();
                                foreach ($allOldDocs as $od) {
                                    $od->name = $model->name;
                                    $od->name_eng = $model->name_eng;
                                    $od->deleted = TRUE;
                                    $od->save(false);
                                    $oldDoc->name = $model->name;
                                    $oldDoc->name_eng = $model->name_eng;
                                    $oldDoc->deleted = TRUE;
                                    $oldDoc->save(FALSE);
                                }
//                                $oldDoc->name = $model->name;
                            }

                            $oldDoc->deleted = TRUE;
                            $oldDoc->save(FALSE);
                        }
                    }
                    if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
                        $model->status = SubmissionDocument::STATUS_PASS;
                    }
                    $model->file_name->name = uniqid() . '.' . $model->file_name->extension;
                    \yii\helpers\FileHelper::createDirectory($model->path);
                    $model->file_name->saveAs($model->path . '/' . $model->file_name->name);
                    $model->file_name = $model->file_name->name;
                    $model->save(FALSE);

                    return [
                        'forceReload' => '#crud-datatable-submission-document-pjax',
                        //                    'forceClose'=>TRUE,
                        'title' => Yii::t('app', 'แนบไฟล์เอกสาร'),
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แนบไฟล์เอกสารเรียบร้อยแล้ว') . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => Yii::t('app', 'แนบไฟล์เอกสาร'),
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', 'แนบไฟล์เอกสาร'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
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

    public function actionSearchAmendmentDocs($submissionId) {
        $request = Yii::$app->request;

        $submission = Submission::findOne($submissionId);
        if (!isset($submission)) {
            throw new HttpException(500, \Yii::t('app', 'ไม่พบข้อมูลการยื่นโครงการ'));
        }

        $submissionDocs = $submission->getAvailableAmendmentDocs();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $submissionDocs,
        ]);
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', 'เลือกเอกสารที่ต้องการแก้ไข'),
                    'content' => $this->renderAjax('search-amendment-docs', [
                        'submission' => $submission,
                        'dataProvider' => $dataProvider,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        } else {
            
        }
    }

    /**
     * Updates an existing SubmissionDocument model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update SubmissionDocument #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionDocument #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionDocument #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
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
     * Delete an existing SubmissionDocument model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
        if (!$model->save(FALSE)) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-submission-document-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-document-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SubmissionDocument model.
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

    public function actionViewFile($id) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->file_name);
        $fileName = "{$model->name}.{$info['extension']}";
//        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $model->submission->project->project_code . '_' . $fileName . '"');
            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    public function actionDownload($id) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->file_name);
        $name = mb_substr($model->name, 0, 75, 'UTF-8');
        $fileName = "{$model->name}.{$info['extension']}";
//        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $model->submission->project->project_code . '_' . $fileName . '"');
            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    public function actionDownloadMerge($submissionId, $selections) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $sdIds = Json::decode($selections);

        $submission = Submission::findOne($submissionId);
        if ($submission->isFromCrec()) {

            $sds = SubmissionDocument::find()->isDeleted(false)->andWhere(['id' => $sdIds])->all();
            $docIds = ArrayHelper::getColumn($sds, 'sd_crec_id');
            // VarDumper::dump(ArrayHelper::getColumn($sds, 'sd_crec_id'), 10, true);
            // exit;
            $response = Crec::downloadMergeFile(Json::encode($docIds));
            // VarDumper::dump($response, 10, true);
            ;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $submission->project->project_code . '.pdf"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $response->getBody()->getSize());
            echo $response->getBody()->getContents();
            exit;
        } else {

            if (empty($sdIds)) {
                $submissionDocs = $submission->getSubmissionDocs();
            } else {

                $submissionDocs = SubmissionDocument::find()
                        ->isDeleted(false)
                        ->andWhere(['id' => $sdIds])
                        ->orderBy([
                            // document_id = NULL และ is_site = 1 ไว้ล่างสุด
                            new \yii\db\Expression('(document_id IS NULL AND is_site = 1) ASC'),
                            // NULL ไว้ท้าย
                            new \yii\db\Expression('document_id IS NULL ASC'),
                            // เรียง document_id จากน้อยไปมาก
                            'document_id' => SORT_ASC,
                        ])
                        ->all();
            }


//            $submissionDocs = $submission->getSubmissionDocs();
            $selectedDocs = [];
            $pdfFiles = [];
            foreach ($submissionDocs as $sd) {
                if (in_array($sd->id, $sdIds)) {
                    $sd->convertToPdf();
                    $pdfFiles[] = $sd->pdfFilePath;
                }
            }
            $mergedFile = \Yii::getAlias("@app/web/tmp/merge_{$submissionId}.pdf");
            $cmd = 'pdfunite';
            foreach ($pdfFiles as $file) {
                $cmd .= " {$file}";
            }
            $cmd .= " {$mergedFile}";
            // Yii::error($cmd);
            // echo $cmd;
            // exit;
            exec($cmd);
            // $merge = new MultiMerge();
            // $merge->mergePdf($pdfFiles, $mergedFile);
            //        $info = pathinfo($mergedFile);
            //        echo $model->filePath;
            $name = isset($submission->project->project_code) ? $submission->project->project_code : $submission->project->name_thai;
            if (file_exists($mergedFile)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . $name . '.pdf"');
                header('Expires: 0');
                //            header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($mergedFile));
                readfile($mergedFile);
            }
        }
        exit;
    }

    public function actionGroupDocument($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->scenario = SubmissionDocument::SCENARIO_GROUP_DOCUMENT;


//        $deviationEvent = \app\models\DeviationEventType::find()->isDeleted(false)->submissionEvent($model->submission_event_id)->all();
//        $deviationEvent = new \app\models\DeviationEventType;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'จัดกลุ่มเอกสาร'),
//                    'size' => 'large',
                    'content' => $this->renderAjax('group-document', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                if (!empty($model->groupDocumentId)) {
                    $groupDoc = SubmissionDocument::findOne($model->groupDocumentId);
                    if (isset($groupDoc->document_id)) {
                        $model->document_id = $groupDoc->document_id;
                    }
                    $model->name = $groupDoc->name;
                    $model->name_eng = $groupDoc->name_eng;
                }
                $model->save(false);
                return [
                    'forceReload' => '#project-document-history-pjax',
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'จัดกลุ่มเอกสาร'),
                    'size' => 'large',
                    'content' => $this->renderAjax('group-document', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('check-document', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionViewPdf($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $model->name,
                'content' => $this->renderAjax('view-pdf', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view-pdf', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Finds the SubmissionDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionDocument::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
