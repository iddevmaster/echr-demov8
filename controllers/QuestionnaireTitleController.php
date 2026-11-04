<?php

namespace app\controllers;

use Yii;
use app\models\QuestionnaireTitle;
use app\models\QuestionnaireTitleSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\SubmissionTypeSearch;
use kartik\widgets\Alert;
use app\models\QuestionnaireChoiceSearch;

/**
 * QuestionnaireTitleController implements the CRUD actions for QuestionnaireTitle model.
 */
class QuestionnaireTitleController extends RbacController {

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
     * Lists all QuestionnaireTitle models.
     * @return mixed
     */
    public function actionIndex($id) {
        $searchModel = new QuestionnaireTitleSearch();
        $searchModel->submission_type_id = $id;
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id' => $id,
        ]);
    }

    public function actionIndexListSubmissionType() {
        $searchModel = new SubmissionTypeSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-list-submission-type', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QuestionnaireTitle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "QuestionnaireTitle #" . $id,
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
     * Creates a new QuestionnaireTitle model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionTypeId) {
        $request = Yii::$app->request;
        $model = new QuestionnaireTitle();
        $model->submission_type_id = $submissionTypeId;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มหัวข้อแบบประเมิน"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submissionTypeId' => $submissionTypeId
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                $model = new QuestionnaireTitle();
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "เพิ่มหัวข้อแบบประเมินเรียบร้อยแล้ว"));
                return [
                    'forceReload' => '#crud-datatable-questionnaire-title-pjax',
                    'title' => Yii::t('app', "เพิ่มหัวข้อแบบประเมิน"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submissionTypeId' => $submissionTypeId
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มหัวข้อแบบประเมิน"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submissionTypeId' => $submissionTypeId
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
                            'submissionTypeId' => $submissionTypeId
                ]);
            }
        }
    }

    /**
     * Updates an existing QuestionnaireTitle model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);     
        
        $prSearch = new QuestionnaireChoiceSearch();
        $prSearch->deleted = 0;
        $prSearch->questionnaire_title_id = $id;
        $prProvider = $prSearch->search(Yii::$app->request->queryParams);
        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => Yii::t('app', "แก้ไขหัวข้อแบบสอบถาม {name}", [
                        'name' => $model->title
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'prSearch' => $prSearch,
                        'prProvider' => $prProvider,                        
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $model->save(FALSE);

                return [
                    'forceReload' => '#crud-datatable-questionnaire-title-pjax',
                    'title' => Yii::t('app', "หัวข้อแบบสอบถาม {name}", [
                        'name' => $model->title 
                    ]),
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'prSearch' => $prSearch,
                        'prProvider' => $prProvider,                        
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', "บันทึก"), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขหัวข้อแบบสอบถาม {name}", [
                        'name' => $model->title
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'prSearch' => $prSearch,
                        'prProvider' => $prProvider,                        
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->validate()) {
                $model->save(FALSE);
                return $this->render('update', [
                    'model' => $model,
                    'prSearch' => $prSearch,
                    'prProvider' => $prProvider,                    
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'prSearch' => $prSearch,
                    'prProvider' => $prProvider,                    
                ]);
            }
        }
    }

    /**
     * Delete an existing QuestionnaireTitle model.
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
                    'forceReload' => '#crud-datatable-questionnaire-title-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-questionnaire-title-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing QuestionnaireTitle model.
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
     * Finds the QuestionnaireTitle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionnaireTitle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = QuestionnaireTitle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
