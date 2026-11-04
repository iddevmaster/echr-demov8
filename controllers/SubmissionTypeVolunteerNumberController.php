<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionTypeVolunteerNumber;
use app\models\SubmissionTypeVolunteerNumberSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\SubmissionTypeSearch;
use app\models\VolunteerNumberSearch;

/**
 * SubmissionTypeVolunteerNumberController implements the CRUD actions for SubmissionTypeVolunteerNumber model.
 */
class SubmissionTypeVolunteerNumberController extends RbacController {

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
     * Lists all SubmissionTypeVolunteerNumber models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubmissionTypeSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubmissionTypeVolunteerNumber model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SubmissionTypeVolunteerNumber #" . $id,
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
     * Creates a new SubmissionTypeVolunteerNumber model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new SubmissionTypeVolunteerNumber();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new SubmissionTypeVolunteerNumber",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new SubmissionTypeVolunteerNumber",
                    'content' => '<span class="text-success">Create SubmissionTypeVolunteerNumber success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new SubmissionTypeVolunteerNumber",
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
     * Updates an existing SubmissionTypeVolunteerNumber model.
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
                    'title' => "Update SubmissionTypeVolunteerNumber #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionTypeVolunteerNumber #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionTypeVolunteerNumber #" . $id,
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
     * Delete an existing SubmissionTypeVolunteerNumber model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-type-volunteer-number-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SubmissionTypeVolunteerNumber model.
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

    public function actionSelectVolunteer($id) {

        $SsearchModel = new SubmissionTypeVolunteerNumberSearch();
        $SsearchModel->deleted = 0;
        $SsearchModel->submission_type_id = $id;
        $SdataProvider = $SsearchModel->search(Yii::$app->request->queryParams);

        $DsearchModel = new VolunteerNumberSearch;
        $DsearchModel->deleted = 0;
        $DsearchModel->notSubmissionTypeId = $id;
        $DdataProvider = $DsearchModel->search(Yii::$app->request->queryParams);

        $selectDocument = SubmissionTypeVolunteerNumber::findOne($id);
        $submissionType = \app\models\SubmissionType::findOne($id);

        return $this->render('select-volunteer', [
                    'DsearchModel' => $DsearchModel,
                    'DdataProvider' => $DdataProvider,
                    'SsearchModel' => $SsearchModel,
                    'SdataProvider' => $SdataProvider,
                    'selectDocument' => $selectDocument,
                    'submissionType' => $submissionType,
        ]);
    }

    public function actionSelectVolunteers($id, $volunteerId, $submissionTypeId) {
        $request = Yii::$app->request;
        $time = new \DateTime('now', new \DateTimeZone('UTC'));

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $rgp = SubmissionTypeVolunteerNumber::find()->isDeleted(FALSE)->volunteerNumber($volunteerId)->submissionType($submissionTypeId)->one();
            if (!isset($rgp)) {
                $rgp = new SubmissionTypeVolunteerNumber();
                $rgp->volunteer_number_id = $volunteerId;
                $rgp->submission_type_id = $submissionTypeId;

                $rgp->save(FALSE);
            }
            return [
                'title' => Yii::t('app', "เลือกอาสาสมัคร", [
                ]),
                'forceReload' => '#crud-datatable-submission-type-volunteer-number-pjax',
                'forceClose' => true,
                'content' => \Yii::$app->util->errorSummary($rgp),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a(Yii::t('app', "แก้ไข"), ['register', 'meetingId' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        }
    }

    /**
     * Finds the SubmissionTypeVolunteerNumber model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionTypeVolunteerNumber the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionTypeVolunteerNumber::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
