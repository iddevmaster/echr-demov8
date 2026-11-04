<?php

namespace app\controllers;

use app\models\CommitteePosition;
use Yii;
use app\models\SubmissionCommittee;
use app\models\SubmissionCommitteeSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\Submission;
use app\models\EmailQueue;

/**
 * SubmissionCommitteeController implements the CRUD actions for SubmissionCommittee model.
 */
class SubmissionCommitteeController extends RbacController {

    protected $allowedActions = ['comment'];

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
     * Lists all SubmissionCommittee models.
     * @return mixed
     */
    public function actionIndex($submissionId) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionCommitteeSearch();
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $searchModel->person_id = \Yii::$app->user->identity->person->id;
        }
        $searchModel->deleted = 0;
        $searchModel->status = SubmissionCommittee::STATUS_RETURN;
        $searchModel->submission_id = $submissionId;


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportCommitteeSelectMeeting() {
        $searchModel = new \app\models\MeetingSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report-committee-select-meeting', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport($submissionId = NULL) {
        $searchModel = new SubmissionCommitteeSearch();
        $submission = Submission::find()->isDeleted(FALSE)->orderBy('id desc')->one();

        if (isset($submission)) {
            $searchModel->submission_id = $submission->id;
        }
        $searchModel->deleted = 0;
        $searchModel->status = SubmissionCommittee::STATUS_RETURN;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'submission' => $submission,
        ]);
    }

    /**
     * Displays a single SubmissionCommittee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'size' => 'large',
                'title' => "",
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

    /**
     * Creates a new SubmissionCommittee model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new SubmissionCommittee();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new SubmissionCommittee",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new SubmissionCommittee",
                    'content' => '<span class="text-success">Create SubmissionCommittee success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new SubmissionCommittee",
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
     * Updates an existing SubmissionCommittee model.
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
                    'title' => "Update SubmissionCommittee #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionCommittee #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionCommittee #" . $id,
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
     * Delete an existing SubmissionCommittee model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
//        $submission = Submission::findOne(['id' => $model->submission_id]);
//        if ($submission->getIsAllComitteeReturn()) {
//            $submission->status = Submission::STATUS_COMMITTEE_ACCEPTED;
//        } else {
//            $submission->status = Submission::STATUS_COMMITTEE_SELECTED;
//        }
//        $submission->save();
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-submission-committee-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-committee-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SubmissionCommittee model.
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

    public function actionSelectCommittees($personId, $projectId = Null, $submissionId = NULL) {
        $request = Yii::$app->request;
        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $model = new SubmissionCommittee(['scenario' => SubmissionCommittee::SCENARIO_SELECT]);
        $model->submission_id = $submissionId;
//        $committee = SubmissionCommittee::find()->isDeleted(FALSE)->submission($submissionId)->person($personId)->one();
        $submission = Submission::find()->isDeleted(FALSE)->andWhere(['id' => $submissionId])->one();
        if ($submission->isFromCrec()) {
            $model->committee_position_id = CommitteePosition::POSITION_LEC;
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "กำหนดกรรมการ"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $model->person_id = $personId;
                $model->project_id = $projectId;
                $model->submission_id = $submissionId;
                $model->status = SubmissionCommittee::STATUS_PENDING;
                $model->save(FALSE);
                $submission->status = Submission::STATUS_COMMITTEE_SELECTED;
                $submission->save(FALSE);
                EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_ACK, $model->id);
//                $submission->status = Submission::STATUS_SECRETARY_APPROVE_COMMITTEE;
//                $submission->save(FALSE);
//                EmailQueue::addQueue(EmailQueue::TYPE_APPROVE_COMMITTEE, $submission->id);
//                    $model->sendAcknowledgeMail($submission);


                return [
                    'title' => Yii::t('app', "เลือกเจ้าหน้าที่", [
                    ]),
                    'forceReload' => '#crud-datatable-submission-committee-pjax',
                    'forceClose' => true,
                    'content' => '',
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "กำหนดกรรมการ"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }


//        $request = Yii::$app->request;
//        $time = new \DateTime('now', new \DateTimeZone('UTC'));
//        if ($request->isAjax) {
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            $committee = SubmissionCommittee::find()->isDeleted(FALSE)->submission($submissionId)->person($personId)->one();
//            $submission = Submission::find()->isDeleted(FALSE)->andWhere(['id' => $submissionId])->one();
//            if (!isset($committee)) {
//                $committee = new SubmissionCommittee();
//                $committee->person_id = $personId;
//                $committee->project_id = $projectId;
//                $committee->submission_id = $submissionId;
//                $committee->status = SubmissionCommittee::STATUS_PENDING;
//                $committee->save(FALSE);
//                $submission->status = Submission::STATUS_COMMITTEE_SELECTED;
//                $submission->save(FALSE);
//                EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_ACK, $committee->id);
////                $committee->sendAcknowledgeMail($submission);
//            }
//            return [
//                'title' => Yii::t('app', "เลือกกรรมการที่จะมาอ่านงานวิจัย", [
//                ]),
//                'forceReload' => '#crud-datatable-submission-committee-pjax',
//                'forceClose' => true,
//                'content' => \Yii::$app->util->errorSummary($committee),
//                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
//            ];
//        }
    }

    /**
     * Finds the SubmissionCommittee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionCommittee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionCommittee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
