<?php

namespace app\controllers;

use Yii;
use app\models\DocumentSubmissionType;
use app\models\DocumentSubmissionTypeSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\SubmissionTypeSearch;

/**
 * DocumentSubmissionTypeController implements the CRUD actions for DocumentSubmissionType model.
 */
class DocumentSubmissionTypeController extends RbacController {

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
     * Lists all DocumentSubmissionType models.
     * @return mixed
     */
    public function actionIndex($roleId) {
        $searchModel = new SubmissionTypeSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'roleId' => $roleId,
        ]);
    }

    /**
     * Displays a single DocumentSubmissionType model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "DocumentSubmissionType #" . $id,
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
     * Creates a new DocumentSubmissionType model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($documentId = Null, $submissionTypeId = Null, $roleId = NULL) {
        $request = Yii::$app->request;
        $model = new DocumentSubmissionType();
        $model->document_id = $documentId;
        $model->submission_type_id = $submissionTypeId;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new DocumentSubmissionType",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'documentId' => $documentId,
                        'submissionTypeId' => $submissionTypeId,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save(false)) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new DocumentSubmissionType",
                    'content' => '<span class="text-success">Create DocumentSubmissionType success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new DocumentSubmissionType",
                    'content' => $this->renderAjax('create', [
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
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing DocumentSubmissionType model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $roleId = NULL,$submissionTypeId = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update DocumentSubmissionType #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'roleId' => $roleId,
                        'submissionTypeId' => $submissionTypeId,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $model->save(false);

                $rs = DocumentSubmissionType::find()->isDeleted(false)->submissionType($model->submission_type_id)
                                ->sort(">=" . $model->sort)->andWhere(['not', ['id' => $model->id]])->orderBy('sort ASC')->all();
                foreach ($rs as $cp) {
                    $cp->sort += 1;
                    $cp->save(false);
                }

                $rs = DocumentSubmissionType::find()->isDeleted(false)->submissionType($model->submission_type_id)
                                ->andWhere(['not', ['sort' => null]])->orderBy('sort ASC')->all();
                foreach ($rs as $index => $cp) {
                    $cp->sort = $index + 1;
                    $cp->save(false);
                }
                return [
                    'forceReload' => '#crud-datatable-document-submission-type-pjax',
                    'forceClose' => true,
                    'title' => Yii::t('app', "แก้ไขข้อมูลของเอกสารที่จะใช้ในประเภทโครงการ"),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update DocumentSubmissionType #" . $id,
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
     * Delete an existing DocumentSubmissionType model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-document-submission-type-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing DocumentSubmissionType model.
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

    public function actionSelectDocument($id, $roleId) {

        $SsearchModel = new DocumentSubmissionTypeSearch();
        $SsearchModel->deleted = 0;
        $SsearchModel->role_id = $roleId;
        $SsearchModel->submission_type_id = $id;
        $SdataProvider = $SsearchModel->search(Yii::$app->request->queryParams);

        $DsearchModel = new \app\models\DocumentSearch();
        $DsearchModel->deleted = 0;
//        $DsearchModel->notInDocumentSubmissionTypeId = $id;
        $DsearchModel->role_id = $roleId;
        $DdataProvider = $DsearchModel->search(Yii::$app->request->queryParams);

        $selectDocument = DocumentSubmissionType::findOne($id);
        $submissionType = \app\models\SubmissionType::findOne($id);

        return $this->render('select-document', [
                    'DsearchModel' => $DsearchModel,
                    'DdataProvider' => $DdataProvider,
                    'SsearchModel' => $SsearchModel,
                    'SdataProvider' => $SdataProvider,
                    'selectDocument' => $selectDocument,
                    'submissionType' => $submissionType,
                    'roleId' => $roleId,
        ]);
    }

    public function actionSelectDocuments($id, $documentId, $submissionTypeId, $roleId) {
        $request = Yii::$app->request;
        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $model = new DocumentSubmissionType();
        $model->document_id = $documentId;
        $model->submission_type_id = $submissionTypeId;
        $model->is_require = true;
        $model->is_event = 0;
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "กำหนดข้อมูลของเอกสารที่จะใช้ในประเภทโครงการ"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'documentId' => $documentId,
                        'submissionTypeId' => $submissionTypeId,
                        'roleId' => $roleId,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {

//                $rgp = DocumentSubmissionType::find()->isDeleted(FALSE)->document($documentId)->submissionType($submissionTypeId)->one();
//                if (!isset($rgp)) {
                $rgp = new DocumentSubmissionType();
                $rgp->document_id = $documentId;
                $rgp->submission_type_id = $submissionTypeId;
                $rgp->number = $model->number;
                $rgp->is_require = $model->is_require;
                $rgp->committee_position_id = $model->committee_position_id;
                $rgp->ref_submission_type_id = $model->ref_submission_type_id;
                $rgp->sort = $model->sort;
                $rgp->role_id = $roleId;
                $rgp->save(FALSE);
//                }

                return [
                    'title' => Yii::t('app', "เลือกเอกสาร"),
                    'forceReload' => '#crud-datatable-document-submission-type-pjax',
                    'forceClose' => true,
                    'content' => \Yii::$app->util->errorSummary($rgp),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', "แก้ไข"), ['register', 'meetingId' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        }
    }

    /**
     * Finds the DocumentSubmissionType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentSubmissionType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DocumentSubmissionType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
