<?php

namespace app\controllers;

use Yii;
use app\models\ProjectConsultant;
use app\models\ProjectConsultantSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ProjectConsultantController implements the CRUD actions for ProjectConsultant model.
 */
class ProjectConsultantController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['acknowledge', 'create'];

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
     * Lists all ProjectConsultant models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProjectConsultantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectConsultant model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ProjectConsultant #" . $id,
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
     * Creates a new ProjectConsultant model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionId) {
        $request = Yii::$app->request;
        $submission = \app\models\Submission::findOne($submissionId);
        $model = new ProjectConsultant();
        $model->project_id = $submission->project_id;
        $model->submission_id = $submissionId;
        $model->mail_sent = 0;
        $model->acknowledge_status = ProjectConsultant::STATUS_PENDING_ACK;
        $currentRole = \Yii::$app->session->get('currentRole');

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => \Yii::t('app', 'เพิ่มอาจารย์ที่ปรึกษา'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-consultant', 'type' => "button"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
//                if ($currentRole['role_id'] == \app\models\Role::STAFF) {
//                    $model->mail_sent = 1;
//                    $model->acknowledge_status = ProjectConsultant::STATUS_ACCEPTED;
//                    $model->acknowledge_at = date('Y-m-d H:i:s');
//                }
                if ($currentRole['role_id'] == \app\models\Role::STAFF) {
                    $model->mail_sent = 1;
                    $model->acknowledge_status = ProjectConsultant::STATUS_ACCEPTED;
                    $model->acknowledge_at = date('Y-m-d H:i:s');
                    $model->cv_file = $model->person->cv_file;
                    $model->addCoi();
                    $model->save(FALSE);
                    $spc = new \app\models\SubmissionProjectConsultant;
                    $spc->submission_id = $submissionId;
                    $spc->project_consultant_id = $model->id;
                    $spc->status = \app\models\SubmissionProjectConsultant::STATUS_PASS;
                    $spc->save(FALSE);
                } else {
                    $model->cv_file = $model->person->cv_file;
                    $model->addCoi();
                    $model->save(FALSE);
                }

                if (!$model->mail_sent) {
                    \app\models\EmailQueue::addQueue(\app\models\EmailQueue::TYPE_CONSULTANT_ACK, $model->id);
//                    $model->sendAcknowledgeMail($submission);
                }
                return [
                    'forceReload' => '#crud-datatable-project-consultant-pjax',
                    'title' => Yii::t('app', 'เพิ่มอาจารย์ที่ปรึกษาเรียบร้อยแล้ว'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'เพิ่มอาจารย์ที่ปรึกษาเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'เพิ่มอีก'), ['create', 'submissionId' => $submissionId], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => \Yii::t('app', 'เพิ่มอาจารย์ที่ปรึกษา'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-consultant', 'type' => "button"])
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
     * Updates an existing ProjectConsultant model.
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
                    'title' => "Update ProjectConsultant #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "ProjectConsultant #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update ProjectConsultant #" . $id,
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
     * Delete an existing ProjectConsultant model.
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
                    'forceReload' => '#crud-datatable-project-consultant-pjax',
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->redirect(['index']);
            }
        }
        $model->deleteCoi();
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-project-consultant-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ProjectConsultant model.
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

    public function actionAcknowledge($token, $sid, $type) {
        $this->layout = 'register';
        $pr = ProjectConsultant::find()->isDeleted(FALSE)->acknowledgeStatus(ProjectConsultant::STATUS_PENDING_ACK)->ackToken($token)->one();
        $submission = \app\models\Submission::findOne($sid);
        if (isset($pr)) {
            if ($type == 'accept') {
                $pr->acknowledge_status = ProjectConsultant::STATUS_ACCEPTED;
                $spr = \app\models\SubmissionProjectConsultant::find()->isDeleted(FALSE)->submission($pr->submission_id)->projectConsultant($pr->id)->one();
                if (!isset($spr)) {
                    $spr = new \app\models\SubmissionProjectConsultant();
                    $spr->submission_id = $pr->submission_id;
                    $spr->project_consultant_id = $pr->id;
                    $spr->save(FALSE);
                }
            } else {
                $pr->acknowledge_status = ProjectResearcher::STATUS_REJECTED;
            }
            $pr->acknowledge_by = $pr->person->user_id;
            $pr->acknowledge_at = date('Y-m-d H:i:s');
            $pr->save(FALSE);
        }
        return $this->render('acknowledge', [
                    'projectConsultant' => $pr,
                    'submission' => $submission,
        ]);
    }

    public function actionSendAckMail($id, $submissionId) {
        $request = Yii::$app->request;
        $submission = \app\models\Submission::findOne($submissionId);
        $model = ProjectConsultant::findOne($id);
        $model->sendAcknowledgeMail($submission);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceReload' => '#crud-datatable-project-consultant-pjax',
                'title' => \Yii::t('app', 'ส่งเมลยืนยันการเข้าร่วม'),
                'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ส่งเมลยืนยันการเข้าร่วมเรียบร้อยแล้ว') . "</div>",
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    public function actionAccept($id, $personId) {
        $request = Yii::$app->request;
        $model = ProjectConsultant::find()->isDeleted(false)->submission($id)->person($personId)->one();
        $model->acknowledge_status = ProjectConsultant::STATUS_ACCEPTED;
        $redirect = \yii\helpers\Url::to(['site/index']);

        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'ยืนยันการตอบรับเป็นที่ปรึกษาโครงการ'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ยืนยันการตอบรับเป็นที่ปรึกษาโครงการเรียบร้อยแล้ว')
                    . '</div><script>window.location = "' . $redirect . '";</script>',
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
            return [
                'title' => Yii::t('app', 'ยืนยันการตอบรับเป็นที่ปรึกษาโครงการ'),
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ยืนยันการตอบรับเป็นที่ปรึกษาโครงการเรียบร้อยแล้ว')
                . '</div><script>window.location = "' . $redirect . '";</script>',
//                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ProjectConsultant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectConsultant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProjectConsultant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
