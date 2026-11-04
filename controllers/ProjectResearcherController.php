<?php

namespace app\controllers;

use Yii;
use app\models\ProjectResearcher;
use app\models\ProjectResearcherSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Submission;
use app\models\EmailQueue;
use app\models\SubmissionProjectResearcher;

/**
 * ProjectResearcherController implements the CRUD actions for ProjectResearcher model.
 */
class ProjectResearcherController extends RbacController {

    protected $allowedActions = ['acknowledge', 'create', 'delete'];

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
     * Lists all ProjectResearcher models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProjectResearcherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdatePosition() {
        $rows = Yii::$app->request->post('rows');

        if ($rows) {

            foreach ($rows as $index => $id) {

                $model = ProjectResearcher::findOne($id);

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

    /**
     * Displays a single ProjectResearcher model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ProjectResearcher #" . $id,
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
     * Creates a new ProjectResearcher model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionId, $staff = NULL) {
        $request = Yii::$app->request;
        $submission = Submission::findOne($submissionId);
        $model = new ProjectResearcher();
        $model->project_id = $submission->project_id;
        $model->submission_id = $submissionId;
        $model->mail_sent = 0;
        $model->acknowledge_status = ProjectResearcher::STATUS_PENDING_ACK;
        $currentRole = \Yii::$app->session->get('currentRole');

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => \Yii::t('app', 'Investigators'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submission' => $submission
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-researcher', 'type' => "button"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                if ($model->is_leader) {
                    ProjectResearcher::updateAll(['is_leader' => FALSE], [
                        'deleted' => FALSE,
                        'submission_id' => $model->submission_id,
                    ]);
                    $model->mail_sent = 1;
                    $model->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                    $model->acknowledge_at = date('Y-m-d H:i:s');
                }
                $model->cv_file = $model->person->cv_file;
                $model->addCoi();
                $model->save(FALSE);
                if ($staff == 1 || $model->submission->is_legacy) {
                    $model->mail_sent = 1;
                    $model->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                    $model->acknowledge_at = date('Y-m-d H:i:s');
                    $model->cv_file = $model->person->cv_file;
                    $model->save(FALSE);
                    $spr = new SubmissionProjectResearcher;
                    $spr->submission_id = $submissionId;
                    $spr->project_researcher_id = $model->id;
                    $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                    $spr->save(FALSE);
                    $model->addCoi();
                }

                if (!$model->is_leader && !$model->mail_sent) {
                    EmailQueue::addQueue(EmailQueue::TYPE_RESEARCHER_ACK, $model->id);
//                    $model->sendAcknowledgeMail($submission);
                }
                if (isset($staff)) {
                    return [
                        'forceReload' => '#crud-datatable-project-researcher-pjax',
                        'forceClose' => true,
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'forceReload' => '#crud-datatable-project-researcher-pjax',
                        'title' => Yii::t('app', 'เพิ่มนักวิจัยเรียบร้อยแล้ว'),
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'เพิ่มนักวิจัยเรียบร้อยแล้ว') . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a(Yii::t('app', 'เพิ่มอีก'), ['create', 'submissionId' => $submissionId], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else {
                return [
                    'title' => \Yii::t('app', 'เพิ่มนักวิจัย'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'submission' => $submission
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-researcher', 'type' => "button"])
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
                            'submission' => $submission
                ]);
            }
        }
    }

    public function actionChange($submissionId, $id) {
        $request = Yii::$app->request;
        $submission = Submission::findOne($submissionId);
        $cp = ProjectResearcher::find()->isDeleted(false)->submission($submissionId)->isLeader()->one();
        $cp->is_leader = 0;
        $cp->save(false);

        $model = ProjectResearcher::findOne($id);
        $model->is_leader = 1;
        $model->save(false);

        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-project-researcher-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-project-researcher-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing ProjectResearcher model.
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
                    'title' => \Yii::t('app', 'แก้ไขนักวิจัย'),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-project-researcher-pjax',
                    'title' => \Yii::t('app', 'แก้ไขนักวิจัย'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขนักวิจัยเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => \Yii::t('app', 'แก้ไขนักวิจัย'),
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
     * Delete an existing ProjectResearcher model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;

        $spr = SubmissionProjectResearcher::find()->projectResearcher($model->id)->one();
        if (isset($spr)) {
            $spr->deleted = 1;
            $spr->save(false);
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-project-researcher-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-project-researcher-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ProjectResearcher model.
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
        $index = strrpos($token, '_');
//        $tokenParts = explode('_', $token);
        $timePart = substr($token, $index + 1);
        $now = time();
        $diff = $now - $timePart;
        $pr = null;
        $submission = Submission::findOne($sid);
        $pr = ProjectResearcher::find()->ackToken($token)->one();
        $message = '';
        if (isset($pr)) {
            if ($pr->deleted) {
                $message = \Yii::t('app', '<div class="alert alert-danger dark">หัวหน้าโครงการวิจัยได้ลบรายการผู้ร่วมนี้ของท่านออกจากโครงการวิจัย โปรดตรวจสอบในระบบออนไลน์อีกครั้ง หรือติดต่อเจ้าหน้าที่ศูนย์ฯ</div>');
            } else if ($pr->acknowledge_status != ProjectResearcher::STATUS_PENDING_ACK) {
                if ($pr->acknowledge_status == ProjectResearcher::STATUS_ACCEPTED) {
                    $message = \Yii::t('app', '<div class="alert alert-success dark">ท่านได้ตอบรับโครงการวิจัยแล้ว</div>');
                } else {
                    $message = \Yii::t('app', '<div class="alert alert-warning dark">ท่านได้ปฏิเสธโครงการวิจัยแล้ว</div>');
                }
            } else {
                if ($type == 'accept') {
                    $message = \Yii::t('app', '<div class="alert alert-success dark">ท่านตกลงเข้าร่วมวิจัย</div>');
                    $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                    $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($pr->submission_id)->projectResearcher($pr->id)->one();
                    if (!isset($spr)) {
                        $spr = new SubmissionProjectResearcher();
                        $spr->submission_id = $pr->submission_id;
                        $spr->project_researcher_id = $pr->id;
                        $spr->save(FALSE);
                    }
                    $pr->addCoi();
                } else {
                    $message = \Yii::t('app', '<div class="alert alert-warning dark">ท่านปฏิเสธเข้าร่วมวิจัย</div>');

                    $pr->acknowledge_status = ProjectResearcher::STATUS_REJECTED;
                }
                $pr->acknowledge_by = $pr->person->user_id;
                $pr->acknowledge_at = date('Y-m-d H:i:s');
                $pr->save(FALSE);
                $ld = \app\models\ProjectResearcher::find()->isDeleted(false)->submission($pr->submission_id)->isLeader()->one();
                if (!$pr->submission->getHasPendingProjectCoResearchers() && (!empty($ld->id)) && ($pr->submission->status == Submission::STATUS_PENDING_SUBMISSION)) {
                    EmailQueue::addQueue(EmailQueue::TYPE_ALL_RESEARCHERS_ACKNOWLEDGED, $pr->submission_id);
                }
            }
        } else {
            $message = \Yii::t('app', '<div class="alert alert-danger dark">โปรดตรวจสอบการตอบรับในระบบออนไลน์อีกครั้ง หรือติดต่อเจ้าหน้าที่ศูนย์ฯ</div>');
        }
//        if ($diff / 86400 > 3) {
//            $message = \Yii::t('app', 'รหัสหมดอายุ');
//        } else {
//            
//        }
        return $this->render('acknowledge', [
                    'projectResearcher' => $pr,
                    'submission' => $submission,
                    'message' => $message,
        ]);
    }

    public function actionSendAckMail($id, $submissionId) {
        $request = Yii::$app->request;
        $submission = Submission::findOne($submissionId);
        $model = ProjectResearcher::findOne($id);
        $model->sendAcknowledgeMail($submission);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceReload' => '#crud-datatable-project-researcher-pjax',
                'title' => \Yii::t('app', 'ส่งเมลยืนยันการเข้าร่วม'),
                'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ส่งเมลยืนยันการเข้าร่วมเรียบร้อยแล้ว') . "</div>",
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    public function actionAccept($id, $personId) {
        $request = Yii::$app->request;
        $model = ProjectResearcher::find()->isDeleted(false)->submission($id)->person($personId)->one();
//        $model = ProjectResearcher::find()->isDeleted(false)->submission($id)->isLeader()->one();$pr->addCoi();
//        $prNew = ProjectResearcher::find()->isDeleted(false)->submission($id)->person($personId)->one();
        $model->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
        $model->addCoi();

        $sp = new SubmissionProjectResearcher();
        $sp->project_researcher_id = $model->id;
        $sp->submission_id = $model->submission_id;
        $sp->save(false);
//                                $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);

        $redirect = \yii\helpers\Url::to(['submission/project-submission', 'submissionId' => $model->submission_id]);
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการเรียบร้อยแล้ว')
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
                'title' => Yii::t('app', 'ตอบรับร่วมโครงการ'),
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการเรียบร้อยแล้ว')
                . '</div><script>window.location = "' . $redirect . '";</script>', //                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ProjectResearcher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectResearcher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProjectResearcher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
