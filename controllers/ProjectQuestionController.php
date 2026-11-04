<?php

namespace app\controllers;

use Yii;
use app\models\ProjectQuestion;
use app\models\ProjectQuestionSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\ProjectAgendaQuestion;

/**
 * ProjectQuestionController implements the CRUD actions for ProjectQuestion model.
 */
class ProjectQuestionController extends RbacController {

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
     * Lists all ProjectQuestion models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProjectQuestionSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectQuestion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ProjectQuestion #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ProjectQuestion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new ProjectQuestion();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มคำถามเพิ่มเติมตอนประชุม"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                $model = new ProjectQuestion();
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', 'เพิ่มข้อมูลคำถามเพิ่มเติมตอนประชุมเรียบร้อยแล้ว'));
                return [
                    'forceReload' => '#crud-datatable-project-question-pjax',
                    'title' => Yii::t('app', "เพิ่มคำถามเพิ่มเติมตอนประชุม"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มคำถามเพิ่มเติมตอนประชุม"),
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

    public function actionSelectAgenda($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เลือกวาระการประชุม"),
                    'content' => $this->renderAjax('select-agenda', [
                        'model' => $model,
                    ]),
                    'size' => 'large',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                ProjectAgendaQuestion::updateAll(['deleted' => 1], [
                    'project_question_id' => $model->id,
                ]);
                foreach ($model->agendaIds as $agendaId) {
                    $paq = new ProjectAgendaQuestion();
                    $paq->project_question_id = $model->id;
                    $paq->agenda_id = $agendaId;
                    $paq->save();
                }
                return [
                    'forceReload' => '#crud-datatable-project-question-pjax',
                    'title' => Yii::t('app', "เลือกวาระการประชุม"),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'เลือกวาระการประชุมเรียบร้อยแล้ว') . '</div>',
                    'size' => 'large',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เลือกวาระการประชุม"),
                    'content' => $this->renderAjax('select-agenda', [
                        'model' => $model,
                    ]),
                    'size' => 'large',
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
     * Updates an existing ProjectQuestion model.
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
                    'title' => Yii::t('app', "แก้ไขคำถามเพิ่มเติมตอนประชุม"),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-project-question-pjax',
                    'title' => Yii::t('app', "แก้ไขคำถามเพิ่มเติมตอนประชุม"),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขคำถามเพิ่มเติมตอนประชุมเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขคำถามเพิ่มเติมตอนประชุม"),
                    'content' => $this->renderAjax('update', [
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

    /**
     * Delete an existing ProjectQuestion model.
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
                    'forceReload' => '#crud-datatable-project-question-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-project-question-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ProjectQuestion model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-project-question-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ProjectQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectQuestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProjectQuestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
