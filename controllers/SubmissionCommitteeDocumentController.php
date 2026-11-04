<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionCommitteeDocument;
use app\models\SubmissionCommitteeDocumentSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\alert\Alert;

/**
 * SubmissionCommitteeDocumentController implements the CRUD actions for SubmissionCommitteeDocument model.
 */
class SubmissionCommitteeDocumentController extends RbacController {

    // protected $allowedActions = ['download'];

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
     * Lists all SubmissionCommitteeDocument models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubmissionCommitteeDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubmissionCommitteeDocument model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SubmissionCommitteeDocument #" . $id,
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

    /**
     * Creates a new SubmissionCommitteeDocument model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = NULL, $submissionId = NULL, $documentId = NULL, $sCommitteeId = NULL, $crecDocumentId=null, $name=null) {
        $request = Yii::$app->request;
        $oldDoc = null;
        $model = new SubmissionCommitteeDocument(['scenario' => SubmissionCommitteeDocument::SCENARIO_CREATE]);
        if (isset($id)) {
            $oldDoc = SubmissionCommitteeDocument::findOne($id);
        } else {
            if (isset($documentId)) {
                $oldDoc = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($submissionId)->documents($documentId)->submissionCommittee($sCommitteeId)->one();
            }
        }
        if (isset($oldDoc)) {
            $model->submission_id = $oldDoc->submission_id;
            $model->document_id = $oldDoc->document_id;
            $model->crec_document_id = $oldDoc->crec_document_id;
            $model->submission_committee_id = $oldDoc->submission_committee_id;
            $model->name = $oldDoc->name;
        } else {
            $model->submission_id = $submissionId;
            $model->submission_committee_id = $sCommitteeId;
            $model->project_id = $model->submission->project_id;
            if (isset($documentId)) {
                $model->document_id = $documentId;
                $model->name = $model->document->name;
            }
            if (isset($crecDocumentId)) {
                $model->crec_document_id = $crecDocumentId;
                $model->name = $name;
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
                if (isset($file)) {
                    $model->file_name = $file;
                }
                if ($model->validate()) {
                    if (isset($oldDoc)) {
                        $oldDoc->deleted = TRUE;
                        $oldDoc->save(FALSE);
                    }
                    if (isset($file)) {
                        $model->file_name->name = uniqid() . '.' . $model->file_name->extension;
                        \yii\helpers\FileHelper::createDirectory($model->path);
                        $model->file_name->saveAs($model->path . '/' . $model->file_name->name);
                        $model->file_name = $model->file_name->name;
                    }
                    $model->submission_committee_id = $sCommitteeId;
                    $model->save(FALSE);

                    return [
                        'forceReload' => '#crud-datatable-submission-committee-document-pjax',
                        'forceClose' => TRUE,
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

    /**
     * Updates an existing SubmissionCommitteeDocument model.
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
                    'title' => "Update SubmissionCommitteeDocument #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionCommitteeDocument #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionCommitteeDocument #" . $id,
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
     * Delete an existing SubmissionCommitteeDocument model.
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
                    'forceReload' => '#crud-datatable-submission-committee-document-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-committee-document-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SubmissionCommitteeDocument model.
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
        $fileName = "{$model->name}.{$info['extension']}";
//        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $model->submission->project->project_code . '_' . $fileName . '"');
//            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }

    /**
     * Finds the SubmissionCommitteeDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionCommitteeDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionCommitteeDocument::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
