<?php

namespace app\controllers;

use Yii;
use app\models\AgendaResultDocument;
use app\models\AgendaResultDocumentSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * AgendaResultDocumentController implements the CRUD actions for AgendaResultDocument model.
 */
class AgendaResultDocumentController extends RbacController {

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
     * Lists all AgendaResultDocument models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new \app\models\AgendaSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgendaResultDocument model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "AgendaResultDocument #" . $id,
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
     * Creates a new AgendaResultDocument model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new AgendaResultDocument();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new AgendaResultDocument",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new AgendaResultDocument",
                    'content' => '<span class="text-success">Create AgendaResultDocument success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new AgendaResultDocument",
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
     * Updates an existing AgendaResultDocument model.
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
                    'title' => "Update AgendaResultDocument #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "AgendaResultDocument #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update AgendaResultDocument #" . $id,
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
     * Delete an existing AgendaResultDocument model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-agenda-result-document-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing AgendaResultDocument model.
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

    public function actionSelectDocument($id) {

        $SsearchModel = new AgendaResultDocumentSearch();
        $SsearchModel->deleted = 0;
        $SsearchModel->agenda_id = $id;
        $SdataProvider = $SsearchModel->search(Yii::$app->request->queryParams);

        $DsearchModel = new \app\models\ResultDocumentSearch();
        $DsearchModel->deleted = 0;
        $DsearchModel->notInDocumentAgendaId = $id;
        $DdataProvider = $DsearchModel->search(Yii::$app->request->queryParams);

        $selectDocument = AgendaResultDocument::findOne($id);
        $agenda = \app\models\Agenda::findOne($id);

        return $this->render('select-document', [
                    'DsearchModel' => $DsearchModel,
                    'DdataProvider' => $DdataProvider,
                    'SsearchModel' => $SsearchModel,
                    'SdataProvider' => $SdataProvider,
                    'selectDocument' => $selectDocument,
                    'agenda' => $agenda,
        ]);
    }

    public function actionSelectDocuments($id, $documentId, $agendaId) {
        $request = Yii::$app->request;
        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $model = new AgendaResultDocument();
        $model->result_document_id = $documentId;
        $model->agenda_id = $agendaId;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                $rgp = AgendaResultDocument::find()->isDeleted(FALSE)->document($documentId)->agenda($agendaId)->one();
                if (!isset($rgp)) {
                    $rgp = new AgendaResultDocument();
                    $rgp->result_document_id = $documentId;
                    $rgp->agenda_id = $agendaId;
                    $rgp->save(FALSE);
                }

                return [
                    'title' => Yii::t('app', "เลือกเอกสาร", [
                    ]),
                    'forceReload' => '#crud-datatable-agenda-result-document-pjax',
                    'forceClose' => true,
                    'content' => \Yii::$app->util->errorSummary($rgp),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', "แก้ไข"), ['register', 'meetingId' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        }
    }

    /**
     * Finds the AgendaResultDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgendaResultDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AgendaResultDocument::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
