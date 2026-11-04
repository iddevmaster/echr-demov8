<?php

namespace app\controllers;

use Yii;
use app\models\MeetingAgenda;
use app\models\MeetingAgendaSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Agenda;
use app\models\Submission;
use yii\helpers\Json;
use kartik\widgets\Alert;
use app\models\ProjectAgendaAnswer;
use app\models\SubmissionStatusHistory;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * MeetingAgendaController implements the CRUD actions for MeetingAgenda model.
 */
class MeetingAgendaController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['list', 'update-info', 'update-sort'];

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
     * Lists all MeetingAgenda models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MeetingAgendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MeetingAgenda model.
     * @param integer $id
     * @return mixed
     */
    public function actionView() {
        $request = Yii::$app->request;
        $model = new MeetingAgenda();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "MeetingAgenda #",
                'content' => $this->renderAjax('view', ['model' => $model]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new MeetingAgenda model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($meetingId, $parentId) {
        $request = Yii::$app->request;
        $model = new MeetingAgenda();
        $model->meeting_id = $meetingId;
        $model->parent_id = $parentId;
        $model->sort = MeetingAgenda::getNextSort($parentId);
        $model->setSortLabel();
        $model->sortable = 1;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
//                    'size' => 'large',
                    'title' => \Yii::t('app', 'เพิ่มวาระ'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#agenda-list-pjax',
                    'title' => \Yii::t('app', 'เพิ่มวาระ'),
                    'content' => '<div class="alert alert-success dark">' . \Yii::t('app', 'เพิ่มวาระเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(\Yii::t('app', 'เพิ่มอีก'), ['create', 'meetingId' => $meetingId, 'parentId' => $parentId], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => \Yii::t('app', 'เพิ่มวาระ'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
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
     * Updates an existing MeetingAgenda model.
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
                    'title' => "Update MeetingAgenda #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "MeetingAgenda #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update MeetingAgenda #" . $id,
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
     * Delete an existing MeetingAgenda model.
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
                    'forceReload' => '#agenda-list-pjax',
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->redirect(['index']);
            }
        }
        MeetingAgenda::updateSort($model->parent_id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#agenda-list-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionCancelAgenda($id) {
        $ma = MeetingAgenda::findOne($id);
        $ma->deleted = 1;
        $ma->save();

        MeetingAgenda::updateSort($ma->parent_id);

        $s = $ma->submission;
        Submission::updateAll(['status' => Submission::STATUS_COMMITTEE_ASSESSED], ['id' => $ma->submission_id]);
        SubmissionStatusHistory::deleteAll(['submission_id' => $ma->submission_id, 'status' => Submission::STATUS_AGENDA_ADDED]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $redirect = \yii\helpers\Url::to(['meeting/update', 'id' => $ma->meeting_id]);
        return [
            'title' => Yii::t('app', 'ยกเลิกวาระการประชุม'),
            'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกการยกเลิกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
//            'forceClose' => true,
//            'forceReload' => '#agenda-list-pjax'
        ];  
        }

    /**
     * Delete multiple existing MeetingAgenda model.
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

    public function actionUpdateSort() {
        $meetingAgendas = \Yii::$app->request->post('meetingAgendas');
        foreach ($meetingAgendas as $key => $ma) {
            $ma = MeetingAgenda::findOne($ma['id']);
            $ma->sort = $key + 1;
            $ma->setSortLabel();
            $ma->save(FALSE);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['error' => 0, 'message' => \Yii::t('app', 'เรียงใหม่เรียบร้อยแล้ว')];
    }

    public function actionUpdateInfo($id) {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $view = '_update-info';
        if (isset($model->submission_id)) {
            $view = '_submission-update-info';
        }
        $questions = [];
        $answers = [];
        if (isset($model->agenda)) {
            $questions = $model->agenda->getProjectAgendaQuestions()->joinWith(['projectQuestion'])->questionDeleted(false)->isDeleted(false)->all();

            foreach ($questions as $q) {
                $answer = ProjectAgendaAnswer::find()->isDeleted(false)->submission($model->submission_id)
                                ->projectQuestion($q->project_question_id)->one();
                if (!isset($answer)) {
                    $answer = new ProjectAgendaAnswer();
                } else {
                    $answer->choices = ArrayHelper::getColumn(ProjectAgendaAnswer::find()->isDeleted(FALSE)
                                            ->submission($answer->submission_id)
                                            ->projectQuestion($answer->project_question_id)->all(), 'project_type_id');
                }
                $answer->project_question_id = $q->project_question_id;
                $answer->submission_id = $model->submission_id;
                $answer->project_id = $model->submission->project_id;
                $answers[] = $answer;
            }
        }
        $events = [];

        if (isset($model->submission)) {
            $model->submission->certified_date = isset($model->submission->project->certified_date) ? \Yii::$app->formatter->asDate($model->submission->project->certified_date, 'php:Y-m-d') : null;
            $model->submission->expire_at = isset($model->submission->project->expire_at) ? \Yii::$app->formatter->asDate($model->submission->project->expire_at, 'php:Y-m-d') : null;
            $model->submission->next_progress_at = isset($model->submission->project->next_progress_at) ? \Yii::$app->formatter->asDate($model->submission->project->next_progress_at, 'php:Y-m-d') : null;


            if ($model->submission->submission_type_id == \app\models\SubmissionType::TYPE_DEVIATION) {
                $events = $model->submission->getSubmissionEvents()->isDeleted(false)->indexBy('id')->all();
            }
        }
        if ($request->isGet) {
            return $this->renderAjax($view, [
                        'model' => $model,
                        'answers' => $answers,
            ]);
        } else if ($model->load($request->post()) && $model->validate() && (count($answers) == 0 || (Model::loadMultiple($answers, $request->post()) && (Model::validateMultiple($answers)))) && (count($events) == 0 || (Model::loadMultiple($events, $request->post()) && (Model::validateMultiple($events))))
        ) {
            $tran = $model->getDb()->beginTransaction();
            try {
                $model->save(FALSE);
                foreach ($answers as $answer) {
//                    \yii\helpers\VarDumper::dump($answer->attributes);
//                    \yii\helpers\VarDumper::dump($answer->choices);
//                    exit;
                    if (is_array($answer->choices)) {
                        ProjectAgendaAnswer::deleteAll([
                            'project_question_id' => $answer->project_question_id,
                            'submission_id' => $answer->submission_id,
                        ]);
                        foreach ($answer->choices as $choice) {
                            $ans = new ProjectAgendaAnswer();
                            $ans->attributes = $answer->attributes;
                            $ans->project_type_id = $choice;
                            $ans->deleted = 0;
                            $ans->save(FALSE);
                        }
                    } else {
                        $answer->project_type_id = $answer->choices;
                        $answer->save(FALSE);
                    }
                }
                foreach ($events as $event) {
                    $event->save(false);
                }
                if (isset($model->submission)) {
                    $submission = $model->submission;
                    $submission->load($request->post());
                    $submission->resolution = $model->resolution;
//                    if (!empty($submission->resolution && $submission->status > Submission::STATUS_AGENDA_ADDED)) {
//                        $submission->status = Submission::STATUS_MEETING_DONE;
//                    }
                    // if ($model->resolution == Submission::RESOLUTION_Y) {
                    //     $project = $model->project;
                    //     $project->is_active = $model->agenda->project_active;
                    //     $project->save(FALSE);
                    // }
                    if ($model->resolution == Submission::RESOLUTION_Y && !isset($model->project->certificate_no)) {
                        $project = $model->project;
//                        $project->certificate_no = $project->generateEndorseCode();
                        $project->save(false);
                    }
                    $submission->save(FALSE);
                    if (isset($submission->certified_date)) {
                        $project = $model->project;
                        $project->certified_date = $submission->certified_date;
                        $project->expire_at = $submission->expire_at;
                        $project->next_progress_at =  $model->submission->next_progress_at;
                        $project->save(FALSE);
                    }
                    if ($submission->submissionType->close && $model->resolution == Submission::RESOLUTION_Y) {
                        $project = $model->project;
                        $project->is_closed = 1;
                        $project->closed_at = date('Y-m-d H:i:s');
                        $project->save(FALSE);
                    }
                    $submission->refresh();

//                if (isset($model->submission)) {
//                    $model->submission->load($request->post());
//                    $model->submission->resolution = $model->resolution;
//                    if (!empty($model->submission->resolution)) {
//                        $model->submission->status = Submission::STATUS_MEETING_DONE;
//                    }
//                    $model->submission->save(FALSE);
//                    if (isset($model->submission->certified_date)) {
//                        $model->project->certified_date = $model->submission->certified_date;
//                        $model->project->expire_at = $model->submission->expire_at;
//                        $model->project->save(FALSE);
//                    }
//                    if ($model->submission->submissionType->close && $model->resolution == Submission::RESOLUTION_Y) {
//                        $project = $model->project;
//                        $model->project->is_closed = 1;
//                        $model->project->closed_at = date('Y-m-d H:i:s');
//                        $model->project->save(FALSE);
//                    }
//                if ($model->submission->submissionType->certify && $model->conclusion == Submission::RESOLUTION_Y) {
//                    $model->submission->certified_date = date()
//                }
                }
                $tran->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว'));
                return $this->renderAjax($view, [
                            'model' => $model,
                            'answers' => $answers,
                ]);
            } catch (Exception $ex) {
                $tran->rollBack();
                throw $ex;
            }
        } else {
            if (count($answers) > 0) {
                $questions = array_unique(ArrayHelper::getColumn($answers, 'projectQuestion.name'));
                \Yii::$app->session->setFlash('danger', \Yii::t('app', 'กรุณาระบุ') . implode(', ', $questions));
            }
            return $this->renderAjax($view, [
                        'model' => $model,
                        'answers' => $answers,
            ]);
        }
    }

    public function actionUpdateInfoStaff($id) {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $view = '_update-info';
        if (isset($model->submission_id)) {
            $view = '_submission-update-info';
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
//            return $this->render('@app/views/meeting-agenda/_submission-update-info',['model'=>$model]);
            return [
                'title' => Yii::t('app', 'ตรวจสอบผลการพิจารณา'),
                'size' => 'large',
                'content' => $this->renderAjax('@app/views/meeting-agenda/_submission-update-info-staff', ['model' => $model]),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
            ];
        } else if ($model->load($request->post()) && $model->validate()) {
            $tran = $model->getDb()->beginTransaction();
            try {
                $model->save(FALSE);

                if (isset($model->submission)) {
                    $submission = $model->submission;
                    $submission->load($request->post());
                    $submission->resolution = $model->resolution;

                    // if ($model->resolution == Submission::RESOLUTION_Y) {
                    //     $project = $model->project;
                    //     $project->is_active = $model->agenda->project_active;
                    //     $project->save(FALSE);
                    // }
                    if ($model->resolution == Submission::RESOLUTION_Y && !isset($model->project->certificate_no)) {
                        $project = $model->project;
//                        $project->certificate_no = $project->generateEndorseCode();
                        $project->save(false);
                    }
                    $submission->save(FALSE);
                    if (isset($submission->certified_date)) {
                        $project = $model->project;
                        $project->certified_date = $submission->certified_date;
                        $project->expire_at = $submission->expire_at;
                        $project->next_progress_at = $submission->next_progress_at;
                        $project->save(FALSE);
                    }
                    if ($submission->submissionType->close && $model->resolution == Submission::RESOLUTION_Y) {
                        $project = $model->project;
                        $project->is_closed = 1;
                        $project->closed_at = date('Y-m-d H:i:s');
                        $project->save(FALSE);
                    }
                }
                $tran->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว'));
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', "ตรวจสอบผลการพิจารณาโดยเจ้าหน้าที่"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'ตรวจสอบผลการพิจารณาโดยเจ้าหน้าที่เรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } catch (Exception $ex) {
                $tran->rollBack();
                throw $ex;
            }
        } else {
            return $this->renderAjax($view, [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdateInfoSecretary($id) {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $view = '_update-info';
        if (isset($model->submission_id)) {
            $view = '_submission-update-info-secretary';
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
//            return $this->render('@app/views/meeting-agenda/_submission-update-info',['model'=>$model]);
            return [
                'title' => Yii::t('app', 'ตรวจสอบผลการพิจารณา'),
                'size' => 'large',
                'content' => $this->renderAjax('@app/views/meeting-agenda/_submission-update-info-secretary', ['model' => $model]),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
            ];
        } else if ($model->load($request->post()) && $model->validate()) {
            $tran = $model->getDb()->beginTransaction();
            try {
                $model->save(FALSE);

                if (isset($model->submission)) {
                    $submission = $model->submission;
                    $submission->load($request->post());
                    $submission->resolution = $model->resolution;
                    // if ($model->resolution == Submission::RESOLUTION_Y) {
                    //     $project = $model->project;
                    //     $project->is_active = $model->agenda->project_active;
                    //     $project->save(FALSE);
                    // }
                    if ($model->resolution == Submission::RESOLUTION_Y && !isset($model->project->certificate_no)) {
                        $project = $model->project;
//                        $project->certificate_no = $project->generateEndorseCode();
                        $project->save(false);
                    }
                    $submission->save(FALSE);
                    if (isset($submission->certified_date)) {
                        $project = $model->project;
                        $project->certified_date = $submission->certified_date;
                        $project->expire_at = $submission->expire_at;
                        $project->next_progress_at = $submission->next_progress_at;
                        $project->save(FALSE);
                    }
                    if ($submission->submissionType->close && $model->resolution == Submission::RESOLUTION_Y) {
                        $project = $model->project;
                        $project->is_closed = 1;
                        $project->closed_at = date('Y-m-d H:i:s');
                        $project->save(FALSE);
                    }
                }
                $tran->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว'));
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', "ตรวจสอบผลการพิจารณาโดยเลขา"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'ตรวจสอบผลการพิจารณาโดยเลขาเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } catch (Exception $ex) {
                $tran->rollBack();
                throw $ex;
            }
        } else {
            return $this->renderAjax($view, [
                        'model' => $model,
            ]);
        }
    }

    public function actionAddCommitteeCount($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->summary .= $model->getCommitteeCountMsg();
        $model->save(FALSE);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'error' => FALSE,
            ];
        }
    }

    public function actionAddCoiAgenda($meetingId) {
        $request = Yii::$app->request;
        $model = new MeetingAgenda();
        $model->meeting_id = $meetingId;
        $model->title = Agenda::getCoiAgendaTitle();
        $parent = MeetingAgenda::find()->isDeleted(FALSE)->agenda(Agenda::ANNOUNCEMENT)->meeting($meetingId)->one();
        $model->parent_id = $parent->id;
        $model->sort = MeetingAgenda::getNextSort($model->parent_id);
        $model->setSortLabel();
        $model->sortable = 1;
        $model->description = $this->renderFile('@app/views/meeting/_coi.php', [
            'model' => $model->meeting,
        ]);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
//                    'size' => 'large',
                    'title' => \Yii::t('app', 'ผลประโยชน์ทับซ้อนของกรรมการ'),
                    'content' => $this->renderAjax('@app/views/meeting-agenda/_form-coi.php', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'เพิ่มวาระ'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#agenda-list-pjax',
                    'title' => \Yii::t('app', 'ผลประโยชน์ทับซ้อนของกรรมการ'),
                    'content' => '<div class="alert alert-success dark">' . \Yii::t('app', 'เพิ่มวาระเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => \Yii::t('app', 'ผลประโยชน์ทับซ้อนของกรรมการ'),
                    'content' => $this->renderAjax('@app/views/meeting-agenda/_form-coi.php', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'เพิ่มวาระ'), ['class' => 'btn btn-primary', 'type' => "submit"])
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

    public function actionList() {
        $out = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_POST['depdrop_parents'])) {
            $parentId = end($_POST['depdrop_parents']);
            $list = MeetingAgenda::find()->isDeleted(FALSE)->group($parentId)->hasSubmission()->orderBy('CONVERT(meeting_agenda.submission_id USING TIS620)')->all();
            // \yii\helpers\VarDumper::dump($list, 10, TRUE);

            $selected = null;
            if ($parentId != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $data) {
                    $out[] = ['id' => $data->submission_id, 'name' => $data->project->project_code];
                    if ($i == 0) {
                        $selected = $data['id'];
                    }
                }
                // Shows how you can preselect a value
                // echo Json::encode(['output' => $out, 'selected' => '']);
                // return;
                return ['output' => $out, 'selected' => ''];
            }
        }
        // echo Json::encode(['output' => '', 'selected' => '']);
        return['output' => '', 'selected' => ''];
    }

    /**
     * Finds the MeetingAgenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MeetingAgenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MeetingAgenda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
