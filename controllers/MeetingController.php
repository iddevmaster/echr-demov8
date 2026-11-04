<?php

namespace app\controllers;

use Yii;
use app\models\Meeting;
use app\models\MeetingSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Panel;
use yii\data\ArrayDataProvider;
use app\models\Setting;
use app\models\MeetingRolePanel;
use app\models\MeetingPerson;
use app\models\MeetingPersonSearch;
use app\models\PersonRolePanel;
use app\models\PersonRole;
use app\models\Person;
use app\models\Agenda;
use app\models\MeetingAgenda;
use app\models\EmailQueue;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use app\models\Role;
use app\models\Submission;
use Phpdocx\Create\CreateDocxFromTemplate;
use yii\helpers\VarDumper;

/**
 * MeetingController implements the CRUD actions for Meeting model.
 */
class MeetingController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['submission-files', 'update', 'staff-check'];

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
     * Lists all Meeting models.
     * @return mixed
     */
    public function actionIndex() {
        $currentRole = Yii::$app->session->get('currentRole');
//        $panels = Panel::find()->andWhere(['id' => array_keys($currentRole['panels'])])->all();
//        $pendingRawData = [];
//        foreach ($panels as $p) {
//            $pendingRawData = ArrayHelper::merge($pendingRawData, $p->pendingMeetings);
//        }
//        $pendingProvider = new ArrayDataProvider([
//            'allModels' => $pendingRawData
//        ]);
//        $searchModel = new MeetingSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//                    'pendingProvider' => $pendingProvider
        ]);
    }

    public function actionStaffCheck($status = NULL) {
        $currentRole = Yii::$app->session->get('currentRole');

//        $searchModel = new MeetingSearch();
//        $searchModel->deleted = 0;
//        //$searchModel->checked_status = $status;
//        if ($currentRole['role_id'] == \app\models\Role::STAFF) {
//            $searchModel->checked_staff = \Yii::$app->user->identity->id;
//            $searchModel->checked_status = 
//        }
//        if ($currentRole['role_id'] == \app\models\Role::SECRETARY) {
//            $searchModel->checked_secretary_first = \Yii::$app->user->identity->id;
//        }
        $sub = Meeting::find()->isDeleted(FALSE); //->cSstatus($status);

        if ($currentRole['role_id'] == Role::STAFF) {
            $sub->staffCheck(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::SECRETARY) {
            $sub->secretary(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::PRESIDENT) {
            $sub->president(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::COPRESIDENT) {
            $sub->president(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::ADMIN) {
            $sub->adminCheck();
        }
        $models = $sub->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
        ]);
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('staff-check', [
//                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmissionFiles($submissionId) {
        $this->layout = 'show';
        $submission = \app\models\Submission::findOne(['id' => $submissionId]);
        $docs = $submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
        $committees = $submission->getSubmissionCommittees()->isDeleted(FALSE)->status(\app\models\SubmissionCommittee::STATUS_RETURN)->all();

        return $this->render('submission-files', [
                    'docs' => $docs,
                    'committees' => $committees,
                    'submission' => $submission
        ]);
    }

    /**
     * Displays a single Meeting model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Meeting #" . $id,
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

    public function actionUpdateData($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
                return [
//                    'forceReload' => '#crud-datatable-pjax',
                    'title' => \Yii::t('app', 'บันทึกข้อมูลการประชุม'),
                    'content' => '<div class="alert alert-success dark">' . \Yii::t('app', 'บันทึกข้อมูลการประชุมเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
//                    'forceReload' => '#crud-datatable-pjax',
                    'title' => \Yii::t('app', 'บันทึกข้อมูลการประชุม'),
                    'content' => '<div class="alert alert-danger dark">' . \Yii::t('app', 'ไม่สามารถบันทึกข้อมูลการประชุมได้') . '<br>' . Yii::$app->util->errorSummary($model) . '</div>',
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        }
    }

    /**
     * Creates a new Meeting model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date) {
        $request = Yii::$app->request;
        $d = new \DateTime($date);
        $model = new Meeting();
        $model->title = Setting::getValue(Setting::MEETING_NAME);
        $model->start_date = $date;
        $model->end_date = \Yii::$app->formatter->asDate($date, 'php:Y-m-d') . ' 17:00:00';
        $model->year = \Yii::$app->util->getBEYear($d);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => \Yii::t('app', 'สร้างการประชุม'),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $transaction = $model->getDb()->beginTransaction();
                try {
                    $model->save(FALSE);
//                    $mrps = MeetingRolePanel::find()->isDeleted(FALSE)->panel($model->panel_id)->all();
//                    foreach ($mrps as $mrp) {
//                        $mp = new MeetingPerson();
//                        $mp->meeting_id = $model->id;
//                        $mp->person_id = $mrp->person_id;
//                        $mp->role_id = $mrp->role_id;
//                        $mp->save(FALSE);
//                    }
                    $people = Person::find()->joinWith(['personRoles', 'personRoles.personRolePanels'])->isDeleted(FALSE)->panel($model->panel_id)->isRegular()->all();
                    foreach ($people as $p) {
                        $mp = new MeetingPerson();
                        $mp->meeting_id = $model->id;
                        $mp->person_id = $p->id;
                        $mp->role_name = $p->getRoleNameByPanel($model->panel_id);
//                        $mp->role_id = $p->role_id;
                        $mp->save(FALSE);
                    }

                    $agendas = Agenda::find()->isDeleted(FALSE)->parentAgenda(NULL)->all();
                    foreach ($agendas as $agenda) {
                        $this->addAgenda($model, $agenda, NULL);
                    }
                    $transaction->commit();
                } catch (Exception $ex) {
                    $transaction->rollBack();
                    throw $ex;
                }
                $script = "<script>$('#meeting-calendar').fullCalendar('refetchEvents');</script>";
                return [
//                    'forceReload' => '#crud-datatable-pjax',
                    'title' => \Yii::t('app', 'สร้างการประชุม'),
                    'content' => '<div class="alert alert-success dark">' . \Yii::t('app', 'สร้างการประชุมเรียบร้อยแล้ว') . '</div>' . $script,
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => \Yii::t('app', 'สร้างการประชุม'),
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
     * Updates an existing Meeting model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateUser($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $mpSearch = new MeetingPersonSearch();
        $mpSearch->meeting_id = $id;
        $mpSearch->deleted = 0;
        $mpProvider = $mpSearch->search(\Yii::$app->request->queryParams);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('update-user', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Meeting #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('update-user', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
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
                return $this->render('update-user', [
                            'model' => $model,
                            'mpSearch' => $mpSearch,
                            'mpProvider' => $mpProvider,
                ]);
            }
        }
    }

    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $mpSearch = new MeetingPersonSearch();
        $mpSearch->meeting_id = $id;
        $mpSearch->deleted = 0;
        $mpProvider = $mpSearch->search(\Yii::$app->request->queryParams);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Meeting #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
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
                            'mpSearch' => $mpSearch,
                            'mpProvider' => $mpProvider,
                ]);
            }
        }
    }

    public function actionReport($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $mpSearch = new MeetingPersonSearch();
        $mpSearch->meeting_id = $id;
        $mpSearch->deleted = 0;
        $mpProvider = $mpSearch->search(\Yii::$app->request->queryParams);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('report', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Meeting #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Meeting #" . $id,
                    'content' => $this->renderAjax('report', [
                        'model' => $model,
                        'mpSearch' => $mpSearch,
                        'mpProvider' => $mpProvider,
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
                return $this->render('report', [
                            'model' => $model,
                            'mpSearch' => $mpSearch,
                            'mpProvider' => $mpProvider,
                ]);
            }
        }
    }

    /**
     * Delete an existing Meeting model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
        $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->all();
        foreach ($ms as $m) {
            Submission::updateAll(['status' => Submission::STATUS_COMMITTEE_ASSESSED], ['id' => $m->submission_id]);
            \app\models\SubmissionStatusHistory::deleteAll(['submission_id' => $m->submission_id, 'status' => Submission::STATUS_AGENDA_ADDED]);
            MeetingAgenda::updateAll(['deleted' => 1], ['submission_id' => $m->submission_id]);
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
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
            return [
                'title' => Yii::t('app', 'ลบการประชุม'),
                'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ลบการประชุมเรียบร้อยแล้ว') . "<script>window.location = '" . \yii\helpers\Url::to(['meeting/index']) . "'</script></div>",
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionCheckStaff($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->checked_status = Meeting::CS_STAFF_CHECKED;
        $model->staff_checked_at = date('Y-m-d H:i:s');
        $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $reCheck = $model->getResolutionNotNull();
//            $mids = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(TRUE)->resolutionNotnull()->all();
            if ($reCheck['reChecked']) {
                if (!$model->save()) {
                    Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถแก้ไขข้อมูลได้ {error}", [
                                'error' => \Yii::$app->util->errorSummary($model),
                    ]));
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                            'content' => $this->renderAjax('@app/views/widgets/_alert'),
                            'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }

                EmailQueue::addQueue(EmailQueue::TYPE_INFO_SECRETARY_CHECK_MEETING, $model->id);
                foreach ($ms as $m) {
                    if (isset($m->submission_id) && $m->submission->status != Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) {
                        $submission = \app\models\Submission::findOne($m->submission_id);
                        $submission->status = \app\models\Submission::STATUS_STAFF_APPROVE_AGENDA;
                        $submission->save(FALSE);
                    }
                }
                return [
                    'title' => Yii::t('app', 'ตรวจสอบการประชุม'),
                    'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ตรวจสอบการประชุมเรียบร้อยแล้ว') . "<script>window.location = '" . \yii\helpers\Url::to(['update', 'id' => $model->id]) . "'</script></div>",
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'ตรวจสอบการประชุม'),
//                                        'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => "<div class='alert alert-danger'>" . Yii::t('app', "มีวาระ {$model->getSubmissionResolutionNotNull()} ที่ยังไม่ทำการกำหนดมติการประชุม") . "</div>",
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            }
        } else {

            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    public function actionCheckPre($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->checked_status = Meeting::CS_FULL_CHECKED;
        $model->pre_checked_at = date('Y-m-d H:i:s');

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $reCheck = $model->getResolutionNotNull();
//            $mids = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(TRUE)->resolutionNotnull()->all();
            if ($reCheck['reChecked']) {
                if (!$model->save()) {
                    Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถแก้ไขข้อมูลได้ {error}", [
                                'error' => \Yii::$app->util->errorSummary($model),
                    ]));
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                            'content' => $this->renderAjax('@app/views/widgets/_alert'),
                            'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                }
                EmailQueue::addQueue(EmailQueue::TYPE_INFO_STAFF_UPLOAD_RESULT_AGENDA, $model->id);
                $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->notCOI()->all();
                foreach ($ms as $m) {
                    if ($m->resolution == Submission::RESOLUTION_Y) {
                        $project = $m->project;
                        if (isset($m->agenda->project_active)) {
                            $project->is_active = $m->agenda->project_active;
                        }
                        $project->save(FALSE);
                    }
                    $submission = $m->submission;
                    if ($submission->status < \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA) {
                        $submission->status = \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
                        $submission->save(FALSE);
//                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_STAFF_RESULT, $submission->id);
                    }
                }

                return [
                    'title' => Yii::t('app', 'ตรวจสอบการประชุม'),
                    'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ตรวจสอบการประชุมเรียบร้อยแล้ว') . "<script>window.location = '" . \yii\helpers\Url::to(['update', 'id' => $model->id]) . "'</script></div>",
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'ตรวจสอบการประชุม'),
//                                        'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => "<div class='alert alert-danger'>" . Yii::t('app', "มีวาระ {$model->getSubmissionResolutionNotNull()} ที่ยังไม่ทำการกำหนดมติการประชุม") . "</div>",
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            }
        } else {

            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    public function actionCheckSecretary($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $person = Yii::$app->user->identity->person;
        $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->all();

        $coiMas = $person->getCOIMeetingAgendas($id);
        if ($model->checked_status == Meeting::CS_STAFF_CHECKED) {
            if (count($coiMas) > 0) {
                $model->checked_status = Meeting::CS_PARTLY_CHECKED;
                $model->sec1_checked_at = date('Y-m-d H:i:s');
                EmailQueue::addQueue(EmailQueue::TYPE_INFO_SECRETARY_2_CHECK_MEETING, $model->id);
            } else {
                $model->checked_status = Meeting::CS_PRE_CHECKED;
                $model->sec1_checked_at = date('Y-m-d H:i:s');
                if (isset($model->checked_president)) {
                    EmailQueue::addQueue(EmailQueue::TYPE_INFO_PRE_CHECK_MEETING, $model->id);
                }
                foreach ($ms as $m) {
                    if (isset($m->submission_id) && $m->submission->status != Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) {
                        $submission = \app\models\Submission::findOne($m->submission_id);
                        $submission->status = \app\models\Submission::STATUS_WAITING_PRE_APPROVE_AGENDA;
                        $submission->save(FALSE);
                    }
                }
//                EmailQueue::addQueue(EmailQueue::TYPE_INFO_STAFF_UPLOAD_RESULT_AGENDA, $model->id);
//                $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->notCOI()->all();
//                foreach ($ms as $m) {
//                    if ($m->resolution == Submission::RESOLUTION_Y) {
//                        $project = $m->project;
//                        if (isset($m->agenda->project_active)) {
//                            $project->is_active = $m->agenda->project_active;
//                        }
//                        $project->save(FALSE);
//                    }
//                    $submission = $m->submission;
//                    if ($submission->status < \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA) {
//                        $submission->status = \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
//                        $submission->save(FALSE);
//                    }
//                }
            }
        } else {
            $model->checked_status = Meeting::CS_PRE_CHECKED;
            $model->sec2_checked_at = date('Y-m-d H:i:s');
            if (isset($model->checked_president)) {
                EmailQueue::addQueue(EmailQueue::TYPE_INFO_PRE_CHECK_MEETING, $model->id);
            }
//            EmailQueue::addQueue(EmailQueue::TYPE_INFO_PRE_CHECK_MEETING, $model->id);
            foreach ($ms as $m) {
                if (isset($m->submission_id) && $m->submission->status != Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) {
                    $submission = \app\models\Submission::findOne($m->submission_id);
                    $submission->status = \app\models\Submission::STATUS_WAITING_PRE_APPROVE_AGENDA;
                    $submission->save(FALSE);
                }
            }
//            $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->notCOI()->all();
//            foreach ($ms as $m) {
//                if ($m->resolution == Submission::RESOLUTION_Y) {
//                    $project = $m->project;
//                    if (isset($m->agenda->project_active)) {
//                        $project->is_active = $m->agenda->project_active;
//                    }
//                    $project->save(FALSE);
//                }
//                $submission = $m->submission;
//                if ($submission->status < \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA) {
//                    $submission->status = \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
//                    $submission->save(FALSE);
//                }
//            }
        }
//        if ($model->checked_status == Meeting::CS_PRE_CHECKED) {
//            $model->checked_status = Meeting::CS_FULL_CHECKED;
//            EmailQueue::addQueue(EmailQueue::TYPE_INFO_STAFF_UPLOAD_RESULT_AGENDA, $model->id);
//            $ms = MeetingAgenda::find()->isDeleted(FALSE)->meeting($model->id)->hasSubmission(TRUE)->notCOI()->all();
//            foreach ($ms as $m) {
//                if ($m->resolution == Submission::RESOLUTION_Y) {
//                    $project = $m->project;
//                    if (isset($m->agenda->project_active)) {
//                        $project->is_active = $m->agenda->project_active;
//                    }
//                    $project->save(FALSE);
//                }
//                $submission = $m->submission;
//                if ($submission->status < \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA) {
//                    $submission->status = \app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
//                    $submission->save(FALSE);
//                }
//            }
//        }

        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถแก้ไขข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', 'ตรวจสอบการประชุม'),
                'content' => "<div class='alert alert-success'>" . Yii::t('app', 'ตรวจสอบการประชุมเรียบร้อยแล้ว') . "<script>window.location = '" . \yii\helpers\Url::to(['update', 'id' => $model->id]) . "'</script></div>",
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Delete multiple existing Meeting model.
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

    public function actionStart($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if (!isset($model->start_time)) {
            $model->start_time = date('H:i');
        }
//        if (!isset($model->checked_staff) || !isset($model->checked_secretary_first) || !isset($model->checked_secretary_second)) {
        if (!isset($model->checked_staff) || !isset($model->checked_secretary_first)) {
            throw new HttpException(500, \Yii::t('app', 'กรุณาระบุเจ้าหน้าที่ และเลขาท่านที่จะทำการตรวจสอบการประชุมให้ถูกต้องก่อน'));
        }
//
//        if (!isset($model->checked_secretary_second) && isset($model->checked_secretary_first) && count($model->checkedSecretaryFirst->person->getCOIMeetingAgendas($id)) > 0) {
//            throw new HttpException(500, \Yii::t('app', 'กรุณาระบุเลขาท่านที่ 2 ที่จะทำการตรวจสอบการประชุมก่อน'));
//        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม'),
                    'content' => $this->renderAjax('start', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if (null !== $request->post('hour') && null !== $request->post('minute')) {
                $model->start_time = $request->post('hour') . ":" . $request->post('minute');
                $model->save();
                return [
                    'forceReload' => '#meeting-toolbar-pjax',
                    'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'บันทึกเวลาเปิดประชุมเรียบร้อยแล้ว') . '</div>', //<script>window.location = window.location;</script>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม'),
                    'content' => $this->renderAjax('start', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }

//        if (!$model->save()) {
//            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
//                        'error' => \Yii::$app->util->errorSummary($model),
//            ]));
//            if ($request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return [
//                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
//                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
//                ];
//            } else {
//                return $this->redirect(['index']);
//            }
//        }
//
//        if ($request->isAjax) {
//            /*
//             *   Process for ajax request
//             */
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return [
//                'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม'),
//                'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'บันทึกเวลาเปิดประชุมเรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
//            ];
//        } else {
//            /*
//             *   Process for non-ajax request
//             */
//            return $this->redirect(['index']);
//        }
    }

    public function actionEnd($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if (!isset($model->end_time)) {
            $model->end_time = date('H:i');
            $model->checked_status = Meeting::CS_PENDING;
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม'),
                    'content' => $this->renderAjax('end', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if (null !== $request->post('hour') && null !== $request->post('minute')) {
                $model->end_time = $request->post('hour') . ":" . $request->post('minute');
                $model->save();
                return [
                    'forceReload' => '#meeting-toolbar-pjax',
                    'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม'),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'บันทึกเวลาปิดประชุมเรียบร้อยแล้ว') . '</div>', //<script>window.location = window.location;</script>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม'),
                    'content' => $this->renderAjax('end', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
//        $request = Yii::$app->request;
//        $model = $this->findModel($id);
//        $model->end_time = date('H:i:s');
//        if (!$model->save()) {
//            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
//                        'error' => \Yii::$app->util->errorSummary($model),
//            ]));
//            if ($request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return [
//                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
//                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
//                ];
//            } else {
//                return $this->redirect(['index']);
//            }
//        }
//
//        if ($request->isAjax) {
//            /*
//             *   Process for ajax request
//             */
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            $mas = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(TRUE)->all();
//            foreach ($mas as $ma) {
//                EmailQueue::addQueue(EmailQueue::TYPE_SUBMISSION_RESULT, $ma->submission_id);
//            }
//            return [
//                'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม'),
//                'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'บันทึกเวลาปิดประชุมเรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
//            ];
//        } else {
//            /*
//             *   Process for non-ajax request
//             */
//            return $this->redirect(['index']);
//        }
    }

    public function actionCalendarData($start, $end) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $personId = \Yii::$app->user->identity->person->id;

        $currentRole = \Yii::$app->session->get('currentRole');
        if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
            $meetings = Meeting::find()->isDeleted(FALSE)->betweenDate($start, $end)->all();
        } else {
            $meetings = Meeting::find()->isDeleted(FALSE)->person($personId)->betweenDate($start, $end)->all();
        }
//        $meetings = Meeting::find()->isDeleted(FALSE)->betweenDate($start, $end)->all();

        $events = [];
        foreach ($meetings as $meeting) {
            $event = new \stdClass();
            $event->id = $meeting->id;
            $event->title = "{$meeting->fullName}";
            $event->start = $meeting->start_date;
            $event->end = "2018-02-16 17:00:00";
//            $event->url = \yii\helpers\Url::to(['meeting/update', 'id' => $meeting->id]);
            if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
                $event->url = \yii\helpers\Url::to(['meeting/update', 'id' => $meeting->id]);
            } else {
                $event->url = \yii\helpers\Url::to(['meeting/update-user', 'id' => $meeting->id]);
            }
            $event->color = '#a83b24';
            if (isset($meeting->checked_status) && $meeting->checked_status <= Meeting::CS_PRE_CHECKED) {
                $event->color = '#23acb8';
            }
            if ($meeting->checked_status == Meeting::CS_FULL_CHECKED) {
                $event->color = '#2c9638';
            }
            $events[] = $event;
        }
        return $events;
    }

    public function actionCommitteeCalendarData($start, $end) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $personId = \Yii::$app->user->identity->person->id;

        $meetings = Meeting::find()->isDeleted(FALSE)->person($personId)->betweenDate($start, $end)->all();

        $events = [];
        foreach ($meetings as $meeting) {
            $event = new \stdClass();
            $event->id = $meeting->id;
            $event->title = "{$meeting->fullName}";
            $event->start = $meeting->start_date;
            $event->end = "2018-02-16 17:00:00";
            $event->url = \yii\helpers\Url::to(['meeting/view', 'id' => $meeting->id]);
            $event->color = '#a83b24';
            if (isset($meeting->checked_status) && $meeting->checked_status <= Meeting::CS_PRE_CHECKED) {
                $event->color = '#23acb8';
            }
            if ($meeting->checked_status == Meeting::CS_FULL_CHECKED) {
                $event->color = '#2c9638';
            }
            $events[] = $event;
        }
        return $events;
    }

    public function actionCommitteeReport($start = NULL, $end = NULL) {
//        Yii::$app->response->format = Response::FORMAT_JSON;
        $personId = \Yii::$app->user->identity->person->id;
//        $start = "2018-02-16 17:00:00";
//        $end = "2018-08-16 17:00:00";
//        $meetings = Meeting::find()->isDeleted(FALSE)->person($personId)->betweenDate($start, $end)->all();
//        

        $crSearch = new MeetingSearch();
        $crSearch->start = $start;
        $crSearch->end = $end;
        $crSearch->deleted = 0;
        $crSearch->Idperson = $personId;

        $crProvider = $crSearch->search(\Yii::$app->request->queryParams);
//        $subQuery = (new \yii\db\Query())
//                ->select('mp.id')
//                ->from('meeting_person mp')
//                ->innerJoin('person p', 'mp.person_id=p.id')
//                ->andWhere('mp.meeting_id = meeting.id')
//                ->andWhere(['mp.deleted' => FALSE, 'mp.person_id' => $personId]);
//        $dataProvider = new ActiveDataProvider([
//            'subQuery' => $subQuery,
//        ]);

        return $this->render('committee-report', [
                    'crProvider' => $crProvider,
                    'crSearch' => $crSearch,
        ]);
    }

    public function actionGetNextMeetingNo($panelId, $year) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $meetingNo = Meeting::find()->isDeleted(FALSE)->panel($panelId)->year($year)->max('meeting_no');
        if ($meetingNo === FALSE) {
            return ['meetingNo' => 1];
        } else {
            return ['meetingNo' => $meetingNo + 1];
        }
    }

    public function actionSummaryDoc($id) {
        $model = $this->findModel($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('TH SarabunPSK');
        $phpWord->setDefaultFontSize(14);

        $section = $phpWord->addSection();
        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText(Yii::t('app', 'การประชุม P.{0} ครั้งที่ {1} วันที่ {2} Y = รับรอง, C = รับรองหลังจากการแก้ไขตามมติที่ประชุม, R = ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำกลับมาพิจารณาใหม่ ,N = ไม่รับรอง, W = ถอนออกจากการพิจารณา และหรือถอนการรับรอง, T = ยุติการรับรอง Page {PAGE} of {NUMPAGES}.', [$model->panel_id, $model->yearNo, Yii::$app->formatter->asDate($model->start_date)]));
//        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', null, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
//        $footer->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
        $html = $this->renderPartial('_summary-doc', ['model' => $model]);
//        return $html;
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
//        $section->addText('ทดสอบ');
//        $fileResult = Yii::getAlias('@app/web/tmp/test.docx');
        $file = $model->fullName . ".docx";
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
//        $phpWord->save($fileResult);
//        return $this->renderPartial('_summary-doc', ['model' => $model]);
    }

    public function actionSummaryDocTemplateOld($id) {
        $model = $this->findModel($id);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->summaryDocTemplateAlias);
        require_once \Yii::getAlias('@app/components/HTMLtoOpenXML/HTMLtoOpenXML.php');

        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'th';
        $meetingLongDate = \Yii::$app->formatter->asDate($model->start_date, 'php:l') . " ที่ " . \Yii::$app->formatter->asDate($model->start_date, 'php:d F ') . "พ.ศ. " . (\Yii::$app->formatter->asDate($model->start_date, 'php:Y') + 543);
        $meetingDate = "วันที่ " . \Yii::$app->formatter->asDate($model->start_date, 'php:d F ') . "พ.ศ. " . (\Yii::$app->formatter->asDate($model->start_date, 'php:Y') + 543);

        \Yii::$app->formatter->locale = $lc;
        $meetingRoom = isset($model->meetingRoom) ? $model->meetingRoom->name : "";

        $people = $model->getMeetingPeople()->isDeleted(FALSE)->all();
        $templateProcessor->cloneRow('com-no', count($people));

        foreach ($people as $i => $p) {
            $rts = $p->registerTransactions;

            $index = $i + 1;
            $templateProcessor->setValue("com-no#{$index}", $index);
            $templateProcessor->setValue("com-name#{$index}", $p->person->fullName);
            $templateProcessor->setValue("com-role#{$index}", $p->role_name);
            $templateProcessor->setValue("com-attend-at#{$index}", count($rts) > 0 ? Yii::$app->formatter->asTime($rts[0]->registered_at) : "");
        }

        $quorum = '';
        $categories = \app\models\JobCategory::find()->isDeleted(FALSE)->all();
        $catCounts = [];
        foreach ($categories as $cat) {
            $catCounts[$cat->name] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->jobCategory($cat->id)->count();
        }
        $catCounts[Yii::t('app', 'บุคคลภายนอก')] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->isExternal()->count();
        foreach ($catCounts as $key => $val) {
            $quorum .= "{$key} {$val} ท่าน ";
        }
        $quorum .= 'และจำนวนกรรมการที่เข้าร่วมประชุมรวมมากกว่า 8 คน';
        $previousMeeting = $model->previousMeeting;
        $prvMeetingNo = isset($previousMeeting) ? $previousMeeting->yearNo : "";

        $agenda2s = $model->getMeetingAgendas()->isDeleted(FALSE)->sortLabel('2.')->hasSubmission(FALSE)->all();
        if (count($agenda2s) > 0) {
            $templateProcessor->cloneRow('agenda2-title', count($agenda2s));
            foreach ($agenda2s as $i => $a) {
                $index = $i + 1;
                $templateProcessor->setValue("agenda2-title#{$index}", $a->fullTitle);
                $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
                $templateProcessor->setValue("agenda2-detail#{$index}", $desc . " {$a->resolution}");
            }
        } else {
            $templateProcessor->setValue("agenda2-title", "");
            $templateProcessor->setValue("agenda2-detail", "");
        }
        $agenda3s = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(FALSE)->sortLabel('3.')->all();
        if (count($agenda3s) > 0) {
            $templateProcessor->cloneRow('agenda3-title', count($agenda3s));
            foreach ($agenda3s as $i => $a) {
                $index = $i + 1;
                $c = $a->getMeetingAgendas()->isDeleted(FALSE)->count();
                $templateProcessor->setValue("agenda3-title#{$index}", $a->fullTitle);
                $templateProcessor->setValue("agenda3-count#{$index}", $c);
            }
        }
        $agenda4s = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(FALSE)->sortLabel('4.')->all();
        if (count($agenda4s) > 0) {
            $templateProcessor->cloneRow('agenda4-title', count($agenda4s));
            foreach ($agenda4s as $i => $a) {
                $index = $i + 1;
                $c = $a->getMeetingAgendas()->isDeleted(FALSE)->count();
                $templateProcessor->setValue("agenda4-title#{$index}", $a->fullTitle);
                $templateProcessor->setValue("agenda4-count#{$index}", $c);
            }
        }
        $agenda5s = $model->getMeetingAgendas()->isDeleted(FALSE)->sortLabel('5.')->hasSubmission(FALSE)->all();

        if (count($agenda5s) > 0) {
            $templateProcessor->cloneRow('agenda5-title', count($agenda5s));
            foreach ($agenda5s as $i => $a) {
                $index = $i + 1;
                $templateProcessor->setValue("agenda5-title#{$index}", $a->fullTitle);
                $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
                $templateProcessor->setValue("agenda5-detail#{$index}", $desc . " {$a->resolution}");
            }
        } else {
            $templateProcessor->setValue("agenda5-title", "");
            $templateProcessor->setValue("agenda5-detail", "");
        }

        $agendas = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission()->orderBy('sort_label')->all();
        if (count($agendas) > 0) {
            $templateProcessor->cloneRow('agendas', count($agendas));
            $parentId = '';
            foreach ($agendas as $i => $a) {
                $index = $i + 1;
                $templateProcessor->setValue("agendas#{$index}", "");
                $templateProcessor->setValue("agenda-no#{$index}", $a->sort_label);
                $templateProcessor->setValue("project-code#{$index}", $a->submission->project->project_code);
                $templateProcessor->setValue("project-thai#{$index}", $a->submission->project->name_thai);
                $templateProcessor->setValue("project-eng#{$index}", $a->submission->project->name_eng);
                $templateProcessor->setValue("researcher#{$index}", $a->submission->projectLeader->person->fullName);
                $templateProcessor->setValue("organization#{$index}", $a->submission->projectLeader->person->divisionThai);
                $groupTitle = '';
                if ($parentId != $a->parent_id) {
                    $parentId = $a->parent_id;
                    $c = $a->parent->getMeetingAgendas()->isDeleted(FALSE)->count();
                    $groupTitle = "<p>โครงการที่ขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์</p><p style='text-align: center;'>วาระการประชุมครั้งที่ {$model->yearNo} วาระที่ {$a->parent->sort_label}</p><p>“{$a->parent->title}” จำนวน {$c} โครงการ</p>";
                    $templateProcessor->setValue("group-title#{$index}", \HTMLtoOpenXML::getInstance()->fromHTML($groupTitle));
                } else {
                    $templateProcessor->setValue("group-title#{$index}", "");
                }
                $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
//                $desc .= isset($a->description) ? $a->description : "";


                $desc .= isset($a->submission->issue1) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->submission->issue1, '<p><div><br><img><image>')) : "";
                $desc .= isset($a->submission->issue2) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->submission->issue2, '<p><div><br><img><image>')) : "";
                $templateProcessor->setValue("detail#{$index}", $desc);

                $summary = "มติที่ประชุม " . $a->submission->resolutionLabel;
                $summary .= isset($a->conclusion) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->conclusion, '<p><div><br><img><image>')) : "";
                $summary .= isset($a->summary) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->summary, '<p><div><br><img><image>')) : "";
                $templateProcessor->setValue("summary#{$index}", $summary);
            }
        }
        $chairman = $model->getChairman();

        $templateProcessor->setValue('meeting-name', $model->title);
        $templateProcessor->setValue('panel-id', $model->panel_id);
        $templateProcessor->setValue('meeting-no', $model->yearNo);
        $templateProcessor->setValue('meeting-room', $meetingRoom);
        $templateProcessor->setValue('meeting-long-date', $meetingLongDate);
        $templateProcessor->setValue('meeting-date', $meetingDate);
        $templateProcessor->setValue('quorum', $quorum);
        $templateProcessor->setValue('open-at', \Yii::$app->formatter->asTime($model->start_time));
        $templateProcessor->setValue('close-at', \Yii::$app->formatter->asTime($model->end_time));
        $templateProcessor->setValue('previous-meeting-no', $prvMeetingNo);
        $templateProcessor->setValue('chairman', isset($chairman) ? $chairman->person->fullName : "");


        $file = $model->fullName . ".docx";
        $temp = uniqid() . ".docx";
        $filePath = Yii::getAlias("@app/web/tmp/{$temp}");
        $templateProcessor->saveAs($filePath);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
//        $word = \PhpOffice\PhpWord\IOFactory::load($filePath);
//        header("Content-Description: File Transfer");
//        header('Content-Disposition: attachment; filename="test.docx"');
//        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Expires: 0');
//        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
//        $xmlWriter->save("php://output");
    }

    public function actionSummaryDocTemplate($id) {
        $model = $this->findModel($id);

        // $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($model->summaryDocTemplateAlias);
        // require_once \Yii::getAlias('@app/components/HTMLtoOpenXML/HTMLtoOpenXML.php');
        $docx = new CreateDocxFromTemplate($model->summaryDocTemplateAlias);
        $docx->setTemplateSymbol('$');

        $lc = \Yii::$app->formatter->locale;
        \Yii::$app->formatter->locale = 'th';
        $meetingLongDate = \Yii::$app->formatter->asDate($model->start_date, 'php:l') . " ที่ " . \Yii::$app->formatter->asDate($model->start_date, 'php:d F ') . "พ.ศ. " . (\Yii::$app->formatter->asDate($model->start_date, 'php:Y') + 543);
        $meetingDate = "วันที่ " . \Yii::$app->formatter->asDate($model->start_date, 'php:d F ') . "พ.ศ. " . (\Yii::$app->formatter->asDate($model->start_date, 'php:Y') + 543);

        \Yii::$app->formatter->locale = $lc;
        $meetingRoom = isset($model->meetingRoom) ? $model->meetingRoom->name : "";

        $people = $model->getMeetingPeople()->isDeleted(FALSE)->all();
        // VarDumper::dump($people);
        // exit;
        $attendees = $this->renderPartial('_attendees', ['people' => $people]);
        // $templateProcessor->cloneRow('com-no', count($people));
        // foreach ($people as $i => $p) {
        //     $rts = $p->registerTransactions;
        //     $index = $i + 1;
        //     $templateProcessor->setValue("com-no#{$index}", $index);
        //     $templateProcessor->setValue("com-name#{$index}", $p->person->fullName);
        //     $templateProcessor->setValue("com-role#{$index}", $p->role_name);
        //     $templateProcessor->setValue("com-attend-at#{$index}", count($rts) > 0 ? Yii::$app->formatter->asTime($rts[0]->registered_at) : "");
        // }

        $quorum = '';
        $categories = \app\models\JobCategory::find()->isDeleted(FALSE)->all();
        $catCounts = [];
        foreach ($categories as $cat) {
            $catCounts[$cat->name] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->jobCategory($cat->id)->count();
        }
        $catCounts[Yii::t('app', 'บุคคลภายนอก')] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->isExternal()->count();
        foreach ($catCounts as $key => $val) {
            $quorum .= "{$key} {$val} ท่าน ";
        }
        $quorum .= 'และจำนวนกรรมการที่เข้าร่วมประชุมรวมมากกว่า 8 คน';
        $previousMeeting = $model->previousMeeting;
        $prvMeetingNo = isset($previousMeeting) ? $previousMeeting->yearNo : "";

        $agenda2s = $model->getMeetingAgendas()->isDeleted(FALSE)->sortLabel('2.')->hasSubmission(FALSE)->all();
        $agenda2 = $this->renderPartial('_agenda2', ['agenda2s' => $agenda2s]);

        // if (count($agenda2s) > 0) {
        //     $templateProcessor->cloneRow('agenda2-title', count($agenda2s));
        //     foreach ($agenda2s as $i => $a) {
        //         $index = $i + 1;
        //         $templateProcessor->setValue("agenda2-title#{$index}", $a->fullTitle);
        //         $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
        //         $templateProcessor->setValue("agenda2-detail#{$index}", $desc . " {$a->resolution}");
        //     }
        // } else {
        //     $templateProcessor->setValue("agenda2-title", "");
        //     $templateProcessor->setValue("agenda2-detail", "");
        // }
        $agenda3s = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(FALSE)->sortLabel('3.')->all();
        $agenda3 = $this->renderPartial('_agenda3', ['agenda3s' => $agenda3s]);

        // if (count($agenda3s) > 0) {
        //     $templateProcessor->cloneRow('agenda3-title', count($agenda3s));
        //     foreach ($agenda3s as $i => $a) {
        //         $index = $i + 1;
        //         $c = $a->getMeetingAgendas()->isDeleted(FALSE)->count();
        //         $templateProcessor->setValue("agenda3-title#{$index}", $a->fullTitle);
        //         $templateProcessor->setValue("agenda3-count#{$index}", $c);
        //     }
        // }
        $agenda4s = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission(FALSE)->sortLabel('4.')->all();
        $agenda4 = $this->renderPartial('_agenda4', ['agenda4s' => $agenda4s]);

        // if (count($agenda4s) > 0) {
        //     $templateProcessor->cloneRow('agenda4-title', count($agenda4s));
        //     foreach ($agenda4s as $i => $a) {
        //         $index = $i + 1;
        //         $c = $a->getMeetingAgendas()->isDeleted(FALSE)->count();
        //         $templateProcessor->setValue("agenda4-title#{$index}", $a->fullTitle);
        //         $templateProcessor->setValue("agenda4-count#{$index}", $c);
        //     }
        // }
        $agenda5s = $model->getMeetingAgendas()->isDeleted(FALSE)->sortLabel('5.')->hasSubmission(FALSE)->all();
        $agenda5 = $this->renderPartial('_agenda5', ['agenda5s' => $agenda5s]);

        // if (count($agenda5s) > 0) {
        //     $templateProcessor->cloneRow('agenda5-title', count($agenda5s));
        //     foreach ($agenda5s as $i => $a) {
        //         $index = $i + 1;
        //         $templateProcessor->setValue("agenda5-title#{$index}", $a->fullTitle);
        //         $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
        //         $templateProcessor->setValue("agenda5-detail#{$index}", $desc . " {$a->resolution}");
        //     }
        // } else {
        //     $templateProcessor->setValue("agenda5-title", "");
        //     $templateProcessor->setValue("agenda5-detail", "");
        // }

        $agendas = $model->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission()->orderBy('sort_label')->all();
        $agenda = $this->renderPartial('_all-agenda', ['agendas' => $agendas]);

        // if (count($agendas) > 0) {
        //     $templateProcessor->cloneRow('agendas', count($agendas));
        //     $parentId = '';
        //     foreach ($agendas as $i => $a) {
        //         $index = $i + 1;
        //         $templateProcessor->setValue("agendas#{$index}", "");
        //         $templateProcessor->setValue("agenda-no#{$index}", $a->sort_label);
        //         $templateProcessor->setValue("project-code#{$index}", $a->submission->project->project_code);
        //         $templateProcessor->setValue("project-thai#{$index}", $a->submission->project->name_thai);
        //         $templateProcessor->setValue("project-eng#{$index}", $a->submission->project->name_eng);
        //         $templateProcessor->setValue("researcher#{$index}", $a->submission->projectLeader->person->fullName);
        //         $templateProcessor->setValue("organization#{$index}", $a->submission->projectLeader->person->divisionThai);
        //         $groupTitle = '';
        //         if ($parentId != $a->parent_id) {
        //             $parentId = $a->parent_id;
        //             $c = $a->parent->getMeetingAgendas()->isDeleted(FALSE)->count();
        //             $groupTitle = "<p>โครงการที่ขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์</p><p style='text-align: center;'>วาระการประชุมครั้งที่ {$model->yearNo} วาระที่ {$a->parent->sort_label}</p><p>“{$a->parent->title}” จำนวน {$c} โครงการ</p>";
        //             $templateProcessor->setValue("group-title#{$index}", \HTMLtoOpenXML::getInstance()->fromHTML($groupTitle));
        //         } else {
        //             $templateProcessor->setValue("group-title#{$index}", "");
        //         }
        //         $desc = isset($a->description) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->description, '<p><div><br><img><image>')) : "";
        //         //                $desc .= isset($a->description) ? $a->description : "";
        //         $desc .= isset($a->submission->issue1) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->submission->issue1, '<p><div><br><img><image>')) : "";
        //         $desc .= isset($a->submission->issue2) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->submission->issue2, '<p><div><br><img><image>')) : "";
        //         $templateProcessor->setValue("detail#{$index}", $desc);
        //         $summary = "มติที่ประชุม " . $a->submission->resolutionLabel;
        //         $summary .= isset($a->conclusion) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->conclusion, '<p><div><br><img><image>')) : "";
        //         $summary .= isset($a->summary) ? \HTMLtoOpenXML::getInstance()->fromHTML(strip_tags($a->summary, '<p><div><br><img><image>')) : "";
        //         $templateProcessor->setValue("summary#{$index}", $summary);
        //     }
        // }
        $chairman = $model->getChairman();
        $variables = [
            'meeting-name' => $model->title,
            'panel-id' => $model->panel_id,
            'meeting-no' => $model->yearNo,
            'meeting-room' => $meetingRoom,
            'meeting-long-date' => $meetingLongDate,
            'meeting-date' => $meetingDate,
            'quorum' => $quorum,
            'open-at' => \Yii::$app->formatter->asTime($model->start_time),
            'close-at' => \Yii::$app->formatter->asTime($model->end_time),
            'previous-meeting-no' => $prvMeetingNo,
            'chairman' => isset($chairman) ? $chairman->person->fullName : "",
        ];

        $docx->replaceVariableByText($variables);
        $docx->replaceVariableByText($variables, ['target' => 'footer']);
        // $docx->replaceVariableByText($variables, ['target' => 'footer']);
        $docx->replaceVariableByHTML('attendees', 'block', $attendees, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('agenda2', 'block', $agenda2, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('agenda3', 'block', $agenda3, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('agenda4', 'block', $agenda4, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('agenda5', 'block', $agenda5, ['isFile' => false, 'embedFonts' => true]);
        $docx->replaceVariableByHTML('agenda', 'block', $agenda, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('documentEng', 'block', $documentEng, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('researcher', 'block', $researcher, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('researcherEng', 'block', $researcherEng, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('issues', 'block', $issues, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('issues2', 'block', $issues2, ['isFile' => false, 'embedFonts' => true]);
        // $docx->replaceVariableByHTML('revise-remark', 'block', $reviseRemark, ['isFile' => false, 'embedFonts' => true]);
        $file = "meeting_panel_{$model->panel_id}_{$model->meeting_no}_{$model->year}.docx";
        $filePath = Yii::getAlias("@app/web/tmp/{$file}");
        $docx->createDocxAndDownload($filePath);
        // $templateProcessor->setValue('meeting-name', $model->title);
        // $templateProcessor->setValue('panel-id', $model->panel_id);
        // $templateProcessor->setValue('meeting-no', $model->yearNo);
        // $templateProcessor->setValue('meeting-room', $meetingRoom);
        // $templateProcessor->setValue('meeting-long-date', $meetingLongDate);
        // $templateProcessor->setValue('meeting-date', $meetingDate);
        // $templateProcessor->setValue('quorum', $quorum);
        // $templateProcessor->setValue('open-at', \Yii::$app->formatter->asTime($model->start_time));
        // $templateProcessor->setValue('close-at', \Yii::$app->formatter->asTime($model->end_time));
        // $templateProcessor->setValue('previous-meeting-no', $prvMeetingNo);
        // $templateProcessor->setValue('chairman', isset($chairman) ? $chairman->person->fullName : "");
        // $file = $model->fullName . ".docx";
        // $temp = uniqid() . ".docx";
        // $filePath = Yii::getAlias("@app/web/tmp/{$temp}");
        // $templateProcessor->saveAs($filePath);
        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename="' . $file . '"');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header('Content-Length: ' . filesize($filePath));
        // readfile($filePath);
        // exit;
    }

    /**
     * Finds the Meeting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Meeting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Meeting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function addAgenda($meeting, $agenda, $parent) {
        $ma = new MeetingAgenda();
        $ma->meeting_id = $meeting->id;
        $ma->agenda_id = $agenda->id;
        $ma->title = $agenda->name;
        if ($agenda->id == Agenda::PREVIOUS_MINUTES) {
            $lastMeeting = Meeting::find()->isDeleted(FALSE)->panel($meeting->panel_id)->andWhere(['<', 'id', $meeting->id])->orderBy('id DESC')->one();
            if (isset($lastMeeting)) {
                $ma->title .= \Yii::t('app', 'ครั้งที่') . " " . $lastMeeting->yearNo;
            }
        }
        $ma->sort = $agenda->sort;
        $ma->addable = $agenda->addable;
        $ma->need_resolution = $agenda->need_resolution;
//        $ma->setSortLabel();
        $ma->sort_label = (isset($parent) ? "{$parent->sort_label}." : "") . $agenda->sort;
        if (isset($parent)) {
            $ma->parent_id = $parent->id;
        }
        $ma->save(FALSE);
        $subAgendas = $agenda->getAgendas()->isDeleted(FALSE)->all();
        foreach ($subAgendas as $a) {
            $this->addAgenda($meeting, $a, $ma);
        }
    }

}
