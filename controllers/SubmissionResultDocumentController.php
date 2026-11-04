<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionResultDocument;
use app\models\SubmissionResultDocumentSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\widgets\Alert;

/**
 * SubmissionResultDocumentController implements the CRUD actions for SubmissionResultDocument model.
 */
class SubmissionResultDocumentController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['view-file'];

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
     * Lists all SubmissionResultDocument models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubmissionResultDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubmissionResultDocument model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewFile($id) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->document_file);
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

    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SubmissionResultDocument #" . $id,
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
     * Creates a new SubmissionResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = NULL, $submissionId = NULL, $documentId = NULL, $reviseId = null, $pjaxId = NULL) {
        $request = Yii::$app->request;
        if (isset($id)) {
            $model = SubmissionResultDocument::findOne($id);
        } else {
            $model = new SubmissionResultDocument(['scenario' => SubmissionResultDocument::SCENARIO_CREATE]);
//            $model = SubmissionResultDocument::find()->isDeleted(FALSE)->andWhere(['submission_id' => $submissionId, 'result_document_id' => $documentId, 'submission_committee_revise_id' => $reviseId])->one();
        }
//        if (!isset($model)) {
//            $model = new SubmissionResultDocument(['scenario' => SubmissionResultDocument::SCENARIO_CREATE]);
        $model->submission_id = $submissionId;
        $model->result_document_id = $documentId;
        $model->submission_committee_revise_id = $reviseId;
//        }

        if (!isset($model->name) && isset($model->resultDocument)) {
            $model->name = $model->resultDocument->name;
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "อัพโหลดเอกสาร"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submissionId' => $submissionId
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $file = UploadedFile::getInstance($model, 'document_file');
                if (isset($file)) {
                    $model->document_file = $file;
                }
                if ($model->validate()) {
                    if (isset($file)) {
                        $model->document_file->name = uniqid() . '.' . $model->document_file->extension;
//                    $path = 'uploads/submission-result-document-file/';
                        $model->document_file->saveAs($model->path . '/' . $model->document_file->name);
                        $model->document_file = $model->document_file->name;
                    }
                    $model->save(FALSE);
//                $model = new SubmissionResultDocument();
//                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "อัพโหลดเอกสารเรียบร้อยแล้ว"));
                    return [
                        'forceReload' => '#crud-datatable-submission-result-document-' . $pjaxId . '-pjax',
                        'title' => Yii::t('app', "อัพโหลดเอกสาร"),
                        'content' => Alert::widget([
                            'type' => Alert::TYPE_SUCCESS,
                            'body' => \Yii::t('app', 'อัพโหลดเอกสารเรียบร้อยแล้ว'),
                            'delay' => false,
                            'options' => [
                                'class' => 'dark',
                            ]
                        ]),
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => Yii::t('app', 'แนบไฟล์เอกสาร'),
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'submissionId' => $submissionId
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
                        'submissionId' => $submissionId
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
//                $file = UploadedFile::getInstance($model, 'document_file');
//                if (isset($file)) {
//                    $model->document_file = $file;
//                    $model->document_file->name = uniqid() . '.' . $model->document_file->extension;
//                    $path = 'uploads/submission-result-document-file/';
//                    $model->document_file->saveAs($path . $model->document_file->name);
//                    $model->document_file = $model->document_file->name;
//                    $model->save(FALSE);
//                }
////                $model = new SubmissionResultDocument();
////                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "อัพโหลดเอกสารเรียบร้อยแล้ว"));
//                return [
//                    'forceReload' => '#crud-datatable-submission-result-document-pjax',
//                    'title' => Yii::t('app', "อัพโหลดเอกสาร"),
//                    'content' => Alert::widget([
//                        'type' => Alert::TYPE_SUCCESS,
//                        'body' => \Yii::t('app', 'อัพโหลดเอกสารเรียบร้อยแล้ว'),
//                        'delay' => false,
//                        'options' => [
//                            'class' => 'dark',
//                        ]
//                    ]),
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) 
//                ];
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
                            'submissionId' => $submissionId
                ]);
            }
        }
    }

    /**
     * Updates an existing SubmissionResultDocument model.
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
                    'title' => "Update SubmissionResultDocument #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionResultDocument #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionResultDocument #" . $id,
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
     * Delete an existing SubmissionResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $pjaxId = NULL) {
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
                    'forceReload' => '#crud-datatable-submission-result-document-' . $pjaxId . '-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-result-document-' . $pjaxId . '-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SubmissionResultDocument model.
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

    public function actionDownload($id) {
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->filePath);
        // $fileName = "{$model->name}.{$info['extension']}";
        $fileName = $model->document_file;
        //        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
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

    /**
     * Finds the SubmissionResultDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionResultDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionResultDocument::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
