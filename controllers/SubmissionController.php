<?php

namespace app\controllers;

use app\components\Crec;
use Yii;
use app\models\Submission;
use app\models\SubmissionSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\DocumentSubmissionType;
use app\models\SubmissionDocument;
use app\models\Project;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\SubmissionTypeGroup;
use app\models\SubmissionType;
use app\models\ProjectResearcher;
use app\models\ProjectResearcherSearch;
use yii\data\ArrayDataProvider;
use app\models\SubmissionDocumentSearch;
use yii\data\ActiveDataProvider;
use app\models\Role;
use app\models\DocumentSubmissionTypeSearch;
use app\models\SubmissionVolunteerNumber;
use app\models\SubmissionTypeVolunteerNumber;
use yii\base\Model;
use app\models\SubmissionStatusHistorySearch;
use app\models\SubmissionCommitteeDocument;
use app\models\QuestionnaireAnswer;
use app\models\QuestionnaireChoice;
use app\models\QuestionnaireTitle;
use app\controllers\QuestionnaireAnswerController;
use app\models\MeetingAgenda;
use app\models\SubmissionProjectResearcher;
use app\models\EmailQueue;
use kartik\widgets\Alert;
use app\models\ChangePanelForm;
use app\models\ProjectCodeHistory;
use app\models\SubmissionCommitteeRevise;
use yii\web\HttpException;
use app\models\SubmissionVolunteerSearch;
use app\models\SubmissionVolunteer;
use app\models\SubmissionEvent;
use app\models\SubmissionEventSearch;
use Exception;
use Throwable;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use Phpdocx\Create\CreateDocx;

/**
 * SubmissionController implements the CRUD actions for Submission model.
 */
class SubmissionController extends RbacController {

    protected $allowedActions = ['project-submission-show', 'project-submission', 'submission-report', 'delete', 'index-not-isleader', 'index-isconsultant', 'coordinator', 'new-certified', 'general', 'upload-result', 'index-nostaff', 'certificate', 'submission-note', 'meeting-plan', 'set-secretary'];

    /**
     * @inheritdoc
     */
    const NEW_STEP1 = 1;
    const NEW_STEP2 = 2;
    const NEW_STEP3 = 3;
    const NEW_STEP4 = 4;
    const NEW_STEP5 = 5;
    const CONT_STEP1 = 1;
    const CONT_STEP2 = 2;
    const CONT_STEP3 = 3;
    const CONT_STEP4 = 4;
    const CONT_STEP5 = 5;
    const CONT_STEP6 = 6;
    const NEW_CERTIFIED_STEP1 = 1;
    const NEW_CERTIFIED_STEP2 = 2;
    const NEW_CERTIFIED_STEP3 = 3;
    const NEW_CERTIFIED_STEP4 = 4;
    const NEW_CERTIFIED_STEP5 = 5;

    public static function newSteps() {
        return [
            self::NEW_STEP1 => [
                'step' => self::NEW_STEP1,
                'label' => Yii::t('app', 'ข้อมูลทั่วไป'),
                'icon' => 'icon md-settings'
            ],
            self::NEW_STEP2 => [
                'step' => self::NEW_STEP2,
                'label' => Yii::t('app', 'เอกสารงานวิจัย'),
                'icon' => 'icon md-card'
            ],
            self::NEW_STEP3 => [
                'step' => self::NEW_STEP3,
                'label' => Yii::t('app', 'ผู้ร่วมวิจัย'),
                'icon' => 'icon md-accounts-alt'
            ],
            self::NEW_STEP4 => [
                'step' => self::NEW_STEP4,
                'label' => Yii::t('app', 'อาจารย์ที่ปรึกษา'),
                'icon' => 'icon md-account'
            ],
            self::NEW_STEP5 => [
                'step' => self::NEW_STEP5,
                'label' => Yii::t('app', 'ยืนยันการส่งโครงการวิจัย'),
                'icon' => 'icon md-check'
            ],
        ];
    }

    public static function newCertifiedSteps() {
        return [
            self::NEW_CERTIFIED_STEP1 => [
                'step' => self::NEW_CERTIFIED_STEP1,
                'label' => Yii::t('app', 'ข้อมูลทั่วไป'),
                'icon' => 'icon md-settings'
            ],
            self::NEW_CERTIFIED_STEP2 => [
                'step' => self::NEW_CERTIFIED_STEP2,
                'label' => Yii::t('app', 'เอกสารงานวิจัย'),
                'icon' => 'icon md-card'
            ],
            self::NEW_CERTIFIED_STEP3 => [
                'step' => self::NEW_CERTIFIED_STEP3,
                'label' => Yii::t('app', 'ผู้ร่วมวิจัย'),
                'icon' => 'icon md-accounts-alt'
            ],
            self::NEW_CERTIFIED_STEP4 => [
                'step' => self::NEW_CERTIFIED_STEP4,
                'label' => Yii::t('app', 'อาจารย์ที่ปรึกษา'),
                'icon' => 'icon md-account'
            ],
            self::NEW_CERTIFIED_STEP5 => [
                'step' => self::NEW_CERTIFIED_STEP5,
                'label' => Yii::t('app', 'ยืนยันการส่งโครงการวิจัย'),
                'icon' => 'icon md-check'
            ],
        ];
    }

    public static function contSteps() {
        return [
            self::CONT_STEP1 => [
                'step' => self::CONT_STEP1,
                'label' => 'เลือกโครงการ/ประเภทโครงการ',
                'icon' => 'icon md-settings'
            ],
//            self::CONT_STEP2 => [
//                'step' => self::CONT_STEP2,
//                'label' => 'จำนวนอาสาสมัคร',
//                'icon' => 'icon md-account'
//            ],
            self::CONT_STEP2 => [
                'step' => self::CONT_STEP2,
                'label' => Yii::t('app', 'เอกสารงานวิจัย'),
                'icon' => 'icon md-card'
            ],
            self::CONT_STEP3 => [
                'step' => self::CONT_STEP3,
                'label' => Yii::t('app', 'ผู้ร่วมวิจัย'),
                'icon' => 'icon md-accounts-alt'
            ],
            self::CONT_STEP4 => [
                'step' => self::CONT_STEP4,
                'label' => Yii::t('app', 'อาจารย์ที่ปรึกษา'),
                'icon' => 'icon md-account'
            ],
            self::CONT_STEP5 => [
                'step' => self::CONT_STEP5,
                'label' => Yii::t('app', 'ยืนยันการส่งโครงการวิจัย'),
                'icon' => 'icon md-check'
            ],
        ];
    }

    public static function getStepClass($step, $current) {
        if ($step['step'] == $current) {
            return 'current';
        } else if ($step['step'] < $current) {
            return 'done';
        } else {
            return '';
        }
    }

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
     * Lists all Submission models.
     * @return mixed
     */
    public function actionSubmissionReport() {
        $searchModel = new SubmissionSearch();
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $submission = Submission::find()->isDeleted(FALSE)->all();

        return $this->render('submission-report', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
//                    'submission' => $submission,
        ]);
    }

    public function actionSendEdit($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $currentRole = \Yii::$app->session->get('currentRole');
        if ($currentRole['role_id'] == Role::COORDINATOR) {
            $model->status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
            EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECTLEADER_NEW_SUBMISSION, $model->id);
        } else {
            $model->status = Submission::STATUS_SUBMITTED;
        }
//        $committee = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->submission($model->id)->one();
//        $committee->status = \app\models\SubmissionCommittee::STATUS_ACCEPTED;
//        $committee->save(FALSE);
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'forceReload' => '#submission-status-pjax',
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
                'title' => Yii::t('app', 'ส่งเอกสารแก้ไข'),
                'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'ส่งเอกสารแก้ไขเรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionIndexResubmissionNew() {
        $searchModelEdit = new SubmissionSearch();
        $searchModelEdit->deleted = 0;
        $searchModelEdit->submission_type_group_id = SubmissionTypeGroup::GROUP_NEW;
        $searchModelEdit->statusEdit = 'Edit';
        $searchModelEdit->is_leader = \Yii::$app->user->identity->person->id;
        $dataProviderEdit = $searchModelEdit->search(Yii::$app->request->queryParams);

        $searchModelC = new SubmissionSearch();
        $searchModelC->deleted = 0;
        $searchModelC->submission_type_group_id = SubmissionTypeGroup::GROUP_NEW;
        $searchModelC->resolutionRe = 'Resubmission';
        $searchModelC->is_leader = \Yii::$app->user->identity->person->id;
        $dataProviderC = $searchModelC->search(Yii::$app->request->queryParams);


        return $this->render('index-resubmission-new', [
                    'searchModelEdit' => $searchModelEdit,
                    'dataProviderEdit' => $dataProviderEdit,
                    'searchModelC' => $searchModelC,
                    'dataProviderC' => $dataProviderC,
        ]);
    }

    public function actionIndexResubmissionCont() {
        $searchModelEdit = new SubmissionSearch();
        $searchModelEdit->deleted = 0;
        $searchModelEdit->submission_type_group_id = SubmissionTypeGroup::GROUP_CONT;
        $searchModelEdit->statusEdit = 'Edit';
        $searchModelEdit->is_leader = \Yii::$app->user->identity->person->id;
        $dataProviderEdit = $searchModelEdit->search(Yii::$app->request->queryParams);

        $searchModelC = new SubmissionSearch();
        $searchModelC->deleted = 0;
        $searchModelC->submission_type_group_id = SubmissionTypeGroup::GROUP_CONT;
        $searchModelC->resolutionRe = 'Resubmission';
        $searchModelC->is_leader = \Yii::$app->user->identity->person->id;
        $dataProviderC = $searchModelC->search(Yii::$app->request->queryParams);


        return $this->render('index-resubmission-cont', [
                    'searchModelEdit' => $searchModelEdit,
                    'dataProviderEdit' => $dataProviderEdit,
                    'searchModelC' => $searchModelC,
                    'dataProviderC' => $dataProviderC,
        ]);
    }

    public function actionSubmissionReportDuration($submissionTypeGroupId) {
        $searchModel = new SubmissionSearch();
        $searchModel->deleted = 0;
        $searchModel->submission_type_group_id = $submissionTypeGroupId;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submission-report-duration', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
//                    'submission' => $submission,
                    'submissionTypeGroupId' => $submissionTypeGroupId
        ]);
    }

    public function actionProjectSubmission($submissionId, $sCommitteeId = NULL) {
        $currentRole = \Yii::$app->session->get('currentRole');

        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
        }

        $model = $this->findModel($submissionId);
        $project = Project::find()->isDeleted(FALSE)->where(['id' => $model->project_id])->one();
        $submission = $this->findModel($submissionId);
        $submission->meeting_plan_date = isset($submission->meeting_plan_date) ? Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:Y-m-d') : null;
        $submission->send_plan_date = isset($submission->send_plan_date) ? Yii::$app->formatter->asDate($submission->send_plan_date, 'php:Y-m-d') : null;
        if ($model->status == Submission::STATUS_CODE_GENERATED) {
            $mode = Submission::MODE_MEETINGPLAN;
        } elseif ($model->status == Submission::STATUS_MEETING_APPOINTMENT) {
            $mode = Submission::MODE_SETSECRETARY;
        }
        $request = Yii::$app->request;


        $pResearchersearchModel = new ProjectResearcherSearch();
        $pResearchersearchModel->deleted = 0;
        $pResearchersearchModel->project_id = $submission->project_id;
        $pResearchersearchModel->submission_id = $submissionId;
        $pResearcherdataProvider = $pResearchersearchModel->search(Yii::$app->request->queryParams);
//        $pResearcherdataProvider->query->orderBy([
//            'position' => SORT_ASC
//        ]);
        $pConsultantsearchModel = new \app\models\ProjectConsultantSearch();
        $pConsultantsearchModel->deleted = 0;
        $pConsultantsearchModel->project_id = $submission->project_id;
        $pConsultantsearchModel->submission_id = $submissionId;
        $pConsultantdataProvider = $pConsultantsearchModel->search(Yii::$app->request->queryParams);


//        $docsearchModel = new SubmissionDocumentSearch();
//        $docsearchModel->deleted = 0;
//        $docsearchModel->submission_id = $submission->id;
//        $docdataProvider = $docsearchModel->search(Yii::$app->request->queryParams);
//        $docs = $submission->getSubmissionDocs(true);
//        $docdataProvider = new ArrayDataProvider([
//            'models' => $docs,
//            'key' => 'id',
//        ]);

        $docs = $submission->getSubmissionDocs(true);
        usort($docs, function ($a, $b) {
            $aVal = $a->position;
            $bVal = $b->position;

            // เงื่อนไขพิเศษ:
            // document_id = NULL และ is_site = 1 ให้อยู่ล่างสุด
            $aLast = (is_null($a->document_id) && $a->is_site == 1);
            $bLast = (is_null($b->document_id) && $b->is_site == 1);
            if ($aLast && !$bLast) {
                return 1;
            }
            if (!$aLast && $bLast) {
                return -1;
            }

            // เอาค่าว่าง/null ไว้ท้ายสุด
            if ($aVal === null || $aVal === '') {
                return 1;
            }
            if ($bVal === null || $bVal === '') {
                return -1;
            }

            // เรียง position จากน้อยไปมาก
            return $aVal <=> $bVal;
        });

        $docdataProvider = new ArrayDataProvider([
            'allModels' => $docs,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        $comsearchModel = new \app\models\SubmissionCommitteeSearch();
        $comsearchModel->deleted = 0;
        $comsearchModel->submission_id = $submission->id;
        $comsearchModel->personDeleted = 0;

        $comdataProvider = $comsearchModel->search(Yii::$app->request->queryParams);

        $PsearchModel = new \app\models\PersonRoleSearch();
        $PsearchModel->deleted = 0;
// $PsearchModel->panel_id = $submission->project->panel_id;
        $PsearchModel->role_id = Role::COMMITTEE;
        $PsearchModel->notInSubmissionId = $submission->id;
        $PsearchModel->coiId = 1;
//  $DsearchModel->notInPersonRoleId = $id;
        $PsearchModel->personDeleted = 0;
        $PdataProvider = $PsearchModel->search(Yii::$app->request->queryParams);
        $PdataProvider->pagination = ['pageSize' => 5];


        $hissearchModel = new SubmissionStatusHistorySearch();
        $hissearchModel->submission_id = $submission->id;
        $hisdataProvider = $hissearchModel->search(Yii::$app->request->queryParams);

        $coisearchModel = new \app\models\SubmissionCoiPersonSearch();
        $coisearchModel->submission_id = $submission->id;
        $coisearchModel->deleted = 0;
        $coidataProvider = $coisearchModel->search(Yii::$app->request->queryParams);

        $committeesearchModel = new \app\models\SubmissionCommitteeSearch();
        $committeesearchModel->submission_id = $submission->id;
        $committeesearchModel->deleted = 0;
        $committeesearchModel->personDeleted = 0;

        $ma = MeetingAgenda::find()->isDeleted(false)->submission($submission->id)->one();
        $answers = [];
        if (isset($ma->agenda)) {
            $questions = $ma->agenda->getProjectAgendaQuestions()->isDeleted(false)->all();

            foreach ($questions as $q) {
                $answer = \app\models\ProjectAgendaAnswer::find()->isDeleted(false)->submission($ma->submission_id)
                                ->projectQuestion($q->project_question_id)->one();
                if (!isset($answer)) {
                    $answer = new \app\models\ProjectAgendaAnswer();
                } else {
                    $answer->choices = ArrayHelper::getColumn(\app\models\ProjectAgendaAnswer::find()->isDeleted(FALSE)
                                            ->submission($answer->submission_id)
                                            ->projectQuestion($answer->project_question_id)->all(), 'project_type_id');
                }
                $answer->project_question_id = $q->project_question_id;
                $answer->submission_id = $ma->submission_id;
                $answer->project_id = $ma->submission->project_id;
                $answers[] = $answer;
            }
        }


        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $committeesearchModel->person_id = \Yii::$app->user->identity->person->id;
        }
//        $committeesearchModel->status = \app\models\SubmissionCommittee::STATUS_RETURN;

        $committeedataProvider = $committeesearchModel->search(Yii::$app->request->queryParams);

        if ($model->load($request->post())) {
            if ($mode == Submission::MODE_SETSECRETARY) {
                if (empty($model->secretary_person)) {
                    Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "กรุณาเลือกเลขาประจำโครงการ"));
                    $model->addError('status', Yii::t('app', 'กรุณาเลือกเลขาประจำโครงการ'));
                } else {
                    $model->status = Submission::STATUS_SECRETARY_SELECTED;
                    $model->save(FALSE);
                    EmailQueue::addQueue(EmailQueue::TYPE_SECRETARY_SELECTED, $model->id);
                }
            }
            if ($mode == Submission::MODE_MEETINGPLAN) {
//                    echo "MEEt PLAN";
                if ($model->submissionType->is_fullboard) {
                    $model->status = Submission::STATUS_MEETING_APPOINTMENT;
                } else {
                    $model->status = Submission::STATUS_SECRETARY_SELECTED;
                }
                $model->save(FALSE);
            }
            if ($request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#submission-status-pjax',
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        }

        return $this->render('project-submission', [
                    'submission' => $submission,
                    'pResearchersearchModel' => $pResearchersearchModel,
                    'pResearcherdataProvider' => $pResearcherdataProvider,
                    'pConsultantsearchModel' => $pConsultantsearchModel,
                    'pConsultantdataProvider' => $pConsultantdataProvider,
//                    'docsearchModel' => $docsearchModel,
                    'docdataProvider' => $docdataProvider,
                    'comsearchModel' => $comsearchModel,
                    'comdataProvider' => $comdataProvider,
                    'PsearchModel' => $PsearchModel,
                    'PdataProvider' => $PdataProvider,
                    'hissearchModel' => $hissearchModel,
                    'hisdataProvider' => $hisdataProvider,
                    'committeesearchModel' => $committeesearchModel,
                    'committeedataProvider' => $committeedataProvider,
                    'coisearchModel' => $coisearchModel,
                    'coidataProvider' => $coidataProvider,
                    'project' => $project,
                    'ma' => $ma,
                    'answers' => $answers,
        ]);
    }

    public function actionProjectSubmissionShow($submissionId, $sCommitteeId = NULL) {
        if (!Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
        }

        $model = $this->findModel($submissionId);
        $project = Project::find()->isDeleted(FALSE)->where(['id' => $model->project_id])->one();
        $submission = $this->findModel($submissionId);
        if ($model->status == Submission::STATUS_CODE_GENERATED) {
            $mode = Submission::MODE_MEETINGPLAN;
        } elseif ($model->status == Submission::STATUS_MEETING_APPOINTMENT) {
            $mode = Submission::MODE_SETSECRETARY;
        }
        $request = Yii::$app->request;


        $pResearchersearchModel = new ProjectResearcherSearch();
        $pResearchersearchModel->deleted = 0;
        $pResearchersearchModel->project_id = $submission->project_id;
        $pResearchersearchModel->submission_id = $submissionId;
        $pResearcherdataProvider = $pResearchersearchModel->search(Yii::$app->request->queryParams);

        $pConsultantsearchModel = new \app\models\ProjectConsultantSearch();
        $pConsultantsearchModel->deleted = 0;
        $pConsultantsearchModel->project_id = $submission->project_id;
        $pConsultantsearchModel->submission_id = $submissionId;
        $pConsultantdataProvider = $pConsultantsearchModel->search(Yii::$app->request->queryParams);


        $docsearchModel = new SubmissionDocumentSearch();
        $docsearchModel->deleted = 0;
        $docsearchModel->submission_id = $submission->id;
        $docdataProvider = $docsearchModel->search(Yii::$app->request->queryParams);

        $comsearchModel = new \app\models\SubmissionCommitteeSearch();
        $comsearchModel->deleted = 0;
        $comsearchModel->submission_id = $submission->id;
        $comdataProvider = $comsearchModel->search(Yii::$app->request->queryParams);

        $PsearchModel = new \app\models\PersonRoleSearch();
        $PsearchModel->deleted = 0;
// $PsearchModel->panel_id = $submission->project->panel_id;
        $PsearchModel->role_id = Role::COMMITTEE;
        $PsearchModel->notInSubmissionId = $submission->id;
//  $DsearchModel->notInPersonRoleId = $id;
        $PdataProvider = $PsearchModel->search(Yii::$app->request->queryParams);

        $hissearchModel = new SubmissionStatusHistorySearch();
        $hissearchModel->submission_id = $submission->id;
        $hisdataProvider = $hissearchModel->search(Yii::$app->request->queryParams);

        $coisearchModel = new \app\models\SubmissionCoiPersonSearch();
        $coisearchModel->submission_id = $submission->id;
        $coisearchModel->deleted = 0;
        $coidataProvider = $coisearchModel->search(Yii::$app->request->queryParams);

        $committeesearchModel = new \app\models\SubmissionCommitteeSearch();
        $committeesearchModel->submission_id = $submission->id;
        $committeesearchModel->deleted = 0;
        $committeesearchModel->status = \app\models\SubmissionCommittee::STATUS_RETURN;
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $committeesearchModel->person_id = \Yii::$app->user->identity->person->id;
        }
        $committeedataProvider = $committeesearchModel->search(Yii::$app->request->queryParams);

        if ($model->load($request->post())) {
            if ($mode == Submission::MODE_MEETINGPLAN) {
//                    echo "MEEt PLAN";
                if ($model->submissionType->is_fullboard) {
                    $model->status = Submission::STATUS_MEETING_APPOINTMENT;
                } else {
                    $model->status = Submission::STATUS_SECRETARY_SELECTED;
                }
                $model->save(FALSE);
            }
            if ($request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#submission-status-pjax',
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        }

        return $this->render('project-submission-show', [
                    'submission' => $submission,
                    'pResearchersearchModel' => $pResearchersearchModel,
                    'pResearcherdataProvider' => $pResearcherdataProvider,
                    'pConsultantsearchModel' => $pConsultantsearchModel,
                    'pConsultantdataProvider' => $pConsultantdataProvider,
                    'docsearchModel' => $docsearchModel,
                    'docdataProvider' => $docdataProvider,
                    'comsearchModel' => $comsearchModel,
                    'comdataProvider' => $comdataProvider,
                    'PsearchModel' => $PsearchModel,
                    'PdataProvider' => $PdataProvider,
                    'hissearchModel' => $hissearchModel,
                    'hisdataProvider' => $hisdataProvider,
                    'committeesearchModel' => $committeesearchModel,
                    'committeedataProvider' => $committeedataProvider,
                    'coisearchModel' => $coisearchModel,
                    'coidataProvider' => $coidataProvider,
                    'project' => $project
        ]);
    }

    public function actionIndexNostaff($status = NULL, $resolution = NULL, $panelId = NULL, $typeGroup = NULL, $committeeStatus = NULL, $staff = NULL, $isLegacy = NULL, $hasProjectCode = NULL) {
        $currentRole = \Yii::$app->session->get('currentRole');

        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;
        if ($currentRole['role_id'] != Role::RESEARCHER) {
            $searchModel->coiPersonId = $user;
        }

        $searchModel->noResubmit = 1;
        if ($isLegacy == 1) {
            $searchModel->is_legacy = $isLegacy;
        } elseif ($isLegacy == 2) {
            $searchModel->is_legacy = 0;
        }
//        $searchModel->status = $status;
        $searchModel->deleted = 0;
        $searchModel->resolution = $resolution;
        $searchModel->submission_type_group_id = $typeGroup;
//        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $user = \Yii::$app->user->identity->person->id;
//            $searchModel->is_leader = 1;
            if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                $searchModel->created_by = Yii::$app->user->id;
            } else {
                $searchModel->is_leader = $user;
            }
            $searchModel->status = $status;
//            $searchModel->deleted = FALSE;
        }
        if ($currentRole['role_id'] == Role::COORDINATOR) {
            $searchModel->project_coordinator_id = \Yii::$app->user->identity->id;
            $searchModel->status = $status;
        }
        if ($currentRole['role_id'] == Role::STAFF or $currentRole['role_id'] == Role::ADMIN) {
//            if ($status == Submission::STATUS_SUBMITTED) {
//                $staff = \Yii::$app->user->identity->id;
//                $searchModel->responsible_person = $staff;
//            }
            if (isset($hasProjectCode)) {
                $searchModel->hasProjectCode = $hasProjectCode;
            }
            $searchModel->status = $status;
            if (!isset($staff)) {// & ($status == Submission::STATUS_SUBMITTED || $status == Submission::STATUS_DOC_APPROVED || $status == Submission::STATUS_DOC_REJECTED)) {
                $searchModel->responsible_person = -1;
            } else {
                $searchModel->responsible_person = $staff;
            }
        }
        if ($currentRole['role_id'] == Role::SECRETARY) {
            $staff = \Yii::$app->user->identity->id;
            $searchModel->secretary_person = $staff;
            $searchModel->status = $status;
        }
//        if ($currentRole['role_id'] == Role::ADMIN) {
//            $searchModel->status = $status;
//        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $committee = \Yii::$app->user->identity->person->id;
            $searchModel->committeeId = $committee;
            $searchModel->committeeStatus = $committeeStatus;
            $searchModel->status = $status;
//            $searchModel->committeeDeleted = FALSE;
        }

        if (isset($panelId)) {
            $searchModel->panel_id = $panelId;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        \yii\helpers\VarDumper::dump($currentRole['role_id']);
//        \yii\helpers\VarDumper::dump($searchModel->attributes, 10, TRUE);
//        exit;
        $query = $dataProvider->query;
//        $query->join = [
//                ['INNER JOIN', 'project_researcher', 'project_researcher.project_id = project.id '],
//        ];
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index-nostaff', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'status' => $status,
                    'resolution' => $resolution,
        ]);
    }

    public function actionIndex($url = null, $type = null, $status = NULL, $resolution = NULL, $panelId = NULL, $typeGroup = NULL, $committeeStatus = NULL, $staff = NULL, $isLegacy = NULL, $hasProjectCode = NULL, $accept = NULL, $secretary = NULL) {
        $currentRole = \Yii::$app->session->get('currentRole');

        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;
        if ($currentRole['role_id'] != Role::RESEARCHER && $currentRole['role_id'] != Role::COORDINATOR) {
            $searchModel->coiPersonId = $user;
        }

        $searchModel->noResubmit = 1;
        if ($isLegacy == 1) {
            $searchModel->is_legacy = $isLegacy;
        } elseif ($isLegacy == 2) {
            $searchModel->is_legacy = 0;
        }

//        $searchModel->status = $status;
        $searchModel->deleted = 0;
        $searchModel->resolution = $resolution;
        $searchModel->submission_type_group_id = $typeGroup;
        $searchModel->submission_type_id = $type;
//        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $user = \Yii::$app->user->identity->person->id;
//            $searchModel->is_leader = 1;
            if (isset($accept)) {
                $searchModel->accept = $accept;
            } else {
                if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                    $searchModel->created_by = Yii::$app->user->id;
                } else {
                    $searchModel->is_leader = $user;
                }
                $searchModel->status = $status;
//            $searchModel->deleted = FALSE;
            }
        }
        if ($currentRole['role_id'] == Role::COORDINATOR) {

            if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                if ($typeGroup == 2) {
                    $searchModel->project_coordinator_id = \Yii::$app->user->identity->id;
                } else {
                    $searchModel->created_by = Yii::$app->user->id;
                }
            } else {
                $searchModel->project_coordinator_id = \Yii::$app->user->identity->id;
            }
            $searchModel->status = $status;
        }
        if ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::PRESIDENT || ($currentRole['role_id'] == Role::SECRETARY && $secretary == NULL)) {
//            if ($status == Submission::STATUS_SUBMITTED) {
//                $staff = \Yii::$app->user->identity->id;
//                $searchModel->responsible_person = $staff;
//            }
//            if ($status == Submission::STATUS_PENDING_SUBMISSION || $status == Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER || $status == Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) {
//                $searchModel->created_by = Yii::$app->user->id;
//            }
            if ($currentRole['role_id'] == Role::STAFF && isset($staff)) {
                $staff = $staff;
            }
            if (isset($hasProjectCode)) {
                $searchModel->hasProjectCode = $hasProjectCode;
            }
            $searchModel->status = $status;

            if (!isset($staff)) {// & ($status == Submission::STATUS_SUBMITTED || $status == Submission::STATUS_DOC_APPROVED || $status == Submission::STATUS_DOC_REJECTED)) {
                $searchModel->responsible_person = -1;
            } else {
                $searchModel->responsible_person = $staff;
            }
        }

        if ($currentRole['role_id'] == Role::SECRETARY && isset($secretary)) {
            $staff = \Yii::$app->user->identity->id;
            $searchModel->secretary_person = $staff;
            $searchModel->status = $status;
            $searchModel->secretary = 1;
        }
//        if ($currentRole['role_id'] == Role::ADMIN) {
//            $searchModel->status = $status;
//        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $committee = \Yii::$app->user->identity->person->id;
            $searchModel->committeeId = $committee;
            $searchModel->committeeStatus = $committeeStatus;
            $searchModel->status = $status;
//            $searchModel->committeeDeleted = FALSE;
        }

        if (isset($panelId)) {
            $searchModel->panel_id = $panelId;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        \yii\helpers\VarDumper::dump($currentRole['role_id']);
//        \yii\helpers\VarDumper::dump($searchModel->attributes, 10, TRUE);
//        exit;
        $query = $dataProvider->query;
//        $query->join = [
//                ['INNER JOIN', 'project_researcher', 'project_researcher.project_id = project.id '],
//        ];
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

//        echo Yii::$app->request->url;
//        exit;

        if ($currentRole['role_id'] == Role::COMMITTEE && \Yii::$app->devicedetect->isMobile()) {
            return $this->render('index-committee-mobile', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'status' => $status,
                        'resolution' => $resolution,
                        'staff' => $staff,
                        'typeGroup' => $typeGroup,
                        'type' => $type
            ]);
        }
        if ($currentRole['role_id'] == Role::RESEARCHER && \Yii::$app->devicedetect->isMobile()) {
            return $this->render('index-researcher-mobile', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'status' => $status,
                        'resolution' => $resolution,
                        'staff' => $staff,
                        'typeGroup' => $typeGroup,
                        'type' => $type
            ]);
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'status' => $status,
                    'resolution' => $resolution,
                    'staff' => $staff,
                    'typeGroup' => $typeGroup,
                    'type' => $type,
                    'url' => $url,
        ]);
    }

    public function actionIndexNotIsleader() {
        $currentRole = \Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;

//        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $searchModel->researcherPersonId = $user;
        }
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = $dataProvider->query;
        return $this->render('index-not-isleader', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexIsmonitor() {
        $currentRole = \Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;
        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
            $searchModel->project_viewer_id = \Yii::$app->user->id;
        }
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = $dataProvider->query;
        return $this->render('index-ismonitor', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexIsconsultant() {
        $currentRole = \Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;
//        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $searchModel->consultantPersonId = $user;
        }
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = $dataProvider->query;
        return $this->render('index-isconsultant', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexIsResearcher() {
        $currentRole = \Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionSearch();
        $user = \Yii::$app->user->identity->person->id;
//        $searchModel->coiPersonId = $user;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $searchModel->researcherPersonId = $user;
        }
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = $dataProvider->query;
        return $this->render('index-isresearcher', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexCon($status = NULL, $submissionTypeId = NULL) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $submission = Submission::find()->joinWith('submissionType')->submissionTypeCon($status)->one();
        $searchModel = new SubmissionSearch();


        $searchModel->consideration = $status;
        if (isset($submission)) {
            $searchModel->submission_type_id = $submission->submission_type_id;
        }
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = $dataProvider->query;
//        $query->join = [
//                ['INNER JOIN', 'project_researcher', 'project_researcher.project_id = project.id '],
//        ];
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index-con', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'submission' => $submission,
        ]);
    }

    /**
     * Displays a single Submission model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Submission #" . $id,
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

    public function actionUpdate($projectId = NULL, $id = NULL, $mode = NULL, $panelId = NULL, $isNow = FALSE, $committeeId = FALSE) {
        $currentRole = \Yii::$app->session->get('currentRole');

        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($mode == Submission::MODE_GENERATECODE) {
            $model->scenario = Submission::SCENARIO_GENERATE_CODE;
        }
        $model->statusCommittee = \app\models\SubmissionCommittee::STATUS_ACCEPTED;
        $model->can_meeting = 1;
        $project = Project::find()->isDeleted(FALSE)->where(['id' => $model->project_id])->one();
        $agenda = new \app\models\MeetingAgenda;
        if ($currentRole['role_id'] == \app\models\Role::COMMITTEE) {
            $committee = \app\models\SubmissionCommittee::find()->where(['submission_id' => $id, 'person_id' => $currentRole['person_id'], 'deleted' => 0])->one();
        } else {
            $committee = \app\models\SubmissionCommittee::findOne($committeeId);
        }
        if ($mode == Submission::MODE_ASSESSEDCOMMITTEE) {
            $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
        }
        if ($mode == Submission::MODE_CHECKDOC) {
            $model->scenario = Submission::SCENARIO_STATUS;
//            $model->status = Submission::STATUS_DOC_APPROVED;
        }

//        echo "Mode: {$mode}";
        if ($request->isAjax) {
//            echo "Ajax";
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
//                echo "isGet";
                return [
                    'title' => Yii::t('app', '{name}', [
                        'name' => $model->project->name_thai
                    ]),
                    'size' => 'large',
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'mode' => $mode,
                        'panelId' => $panelId,
                        'isNow' => $isNow,
                        'project' => $project
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
//                echo "is Post mode {$mode}";
                if ($mode == Submission::MODE_GENERATECODE) {
                    if ($model->validate()) {
//                    echo "GEN CODE";
                        $project->panel_id = $model->panelId;
                        $project->generateHECode();
                        $project->is_fda = $model->isFda;
                        $project->save(FALSE);

                        $model->status = Submission::STATUS_CODE_GENERATED;

                        $model->responsible_person = Yii::$app->user->identity->id;
                        $model->save(FALSE);
                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECT_CODE, $model->id);
                        $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                        return [
                            'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                            'forceReload' => '#submission-status-pjax',
                        ];
                    } else {
                        return [
                            'title' => Yii::t('app', '{name}', [
                                'name' => $model->project->name_thai
                            ]),
                            'size' => 'large',
                            'content' => $this->renderAjax('update', [
                                'model' => $model,
                                'mode' => $mode,
                                'panelId' => $panelId,
                                'isNow' => $isNow,
                                'project' => $project
                            ]),
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                }
                if ($mode == Submission::MODE_ASSESSTYPE) {
                    if ($model->validate()) {
                        $model->status = Submission::STATUS_SECRETARY_SELECTED;
                        $model->save(FALSE);
//                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECT_CODE, $model->id);
                        $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                        return [
                            'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                            'forceReload' => '#submission-status-pjax',
                        ];
                    } else {
                        return [
                            'title' => Yii::t('app', '{name}', [
                                'name' => $model->project->i18nName
                            ]),
                            'size' => 'large',
                            'content' => $this->renderAjax('update', [
                                'model' => $model,
                                'mode' => $mode,
                                'panelId' => $panelId,
                                'isNow' => $isNow,
                                'project' => $project
                            ]),
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                }


                if ($mode == Submission::MODE_ACCEPTCOMMITTEE) {
//                    echo "ACC COM";
                    if (!isset($model->statusCommittee)) {
                        $model->statusCommittee = \app\models\SubmissionCommittee::STATUS_PENDING;
                    }
                    $committee->status = $model->statusCommittee;
                    $committee->can_meeting = $model->can_meeting;
                    $committee->remark = $model->remarkCommittee;
                    $committee->remark_meeting = $model->remark_meeting;
                    $committee->submit_date = date('Y-m-d H:i:s');
                    $committee->save(FALSE);

                    \app\models\Alert::addCommitteeAcknowledge($committee);
                    EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_ACKNOWLEDGED, $committee->id);

                    if ($currentRole['role_id'] == \app\models\Role::COMMITTEE) {
                        $redirect = \yii\helpers\Url::to(['site/index']);
                        if ($committee->submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
                            $s = \app\models\SubmissionCommittee::find()->joinWith('submission.submissionType')->isDeleted(false)->status(\app\models\SubmissionCommittee::STATUS_PENDING)->person($committee->person_id)->andWhere(['submission_type.submission_type_group_id' => 1, 'submission.status' => Submission::STATUS_COMMITTEE_SELECTED])->count();
                        } else {
                            $s = \app\models\SubmissionCommittee::find()->joinWith('submission.submissionType')->isDeleted(false)->status(\app\models\SubmissionCommittee::STATUS_PENDING)->person($committee->person_id)->andWhere(['submission_type.submission_type_group_id' => 2, 'submission.status' => Submission::STATUS_COMMITTEE_SELECTED])->count();
                        }

                        if ($s > 0) {
                            return [
                                'title' => Yii::t('app', 'ตอบรับการอ่าน'),
                                'forceReload' => '#crud-datatable-submission-pjax',
                                'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'บันทึกเรียบร้อยแล้ว'),
//                                . '</div><script>window.location = "' . $redirect . '";</script>',
                                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                            ];
                        } else {
                            return [
                                'title' => Yii::t('app', 'ตอบรับการอ่าน'),
                                'forceReload' => '#crud-datatable-submission-pjax',
                                'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'บันทึกเรียบร้อยแล้ว')
                                . '</div><script>window.location = "' . $redirect . '";</script>',
                                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                            ];
                        }
                    } else {
                        $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                        return [
                            'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                            'forceReload' => '#submission-status-pjax',
                        ];
                    }
//                \yii\helpers\VarDumper::dump($docStatus);
                }


                if ($mode == Submission::MODE_CHECKDOC) {
//                    echo "CHECK";
// $submission = $model->submission;
                    if (empty($model->status)) {
//throw new HttpException(500, Yii::t('app', 'โครงการวิจัยนี้มีการบรรจุวาระไปแล้ว กรุณาตรวจสอบ หรือลองโหลดหน้าใหม่อีกครั้ง'));
                        return [
                            'title' => Yii::t('app', 'ข้อผิดพลาด'),
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'กรุณาเลือกสถานะการตรวจเอกสาร') . '</div>',
                            //                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    $committees = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->submission($model->id)->all();
                    $committeeRevise = \app\models\SubmissionCommitteeRevise::find()->submission($model->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
                    if ($model->is_legacy == 0) {
                        if ($model->status == Submission::STATUS_DOC_APPROVED) {
                            $docCheck = $model->getDocumentStatus();
                            $researcherCheck = $model->getCvStatus();
                            if (($docCheck['isPass'] && $docCheck['allChecked']) && ($researcherCheck['isResearcherPass'] && $researcherCheck['allResearcherChecked'])) {
                                if ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_NEW) {
                                    if (isset($model->project->project_code)) {
                                        if ($model->is_submit_by_api && !$model->need_local_issue) {
                                            $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
                                            $model->responsible_person = Yii::$app->user->identity->id;
                                        } else {
                                            if (isset($model->refSubmission) and ($model->refSubmission->resolution == Submission::RESOLUTION_C || $model->refSubmission->resolution == Submission::RESOLUTION_R || $model->refSubmission->resolution == Submission::RESOLUTION_N) and (!isset($committeeRevise))) {
                                                if ($model->refSubmission->resolution == Submission::RESOLUTION_C) {
                                                    $model->status = Submission::STATUS_SECRETARY_SELECTED;
                                                    $model->responsible_person = Yii::$app->user->identity->id;
                                                } elseif ($model->refSubmission->resolution == Submission::RESOLUTION_R || $model->refSubmission->resolution == Submission::RESOLUTION_N) {
                                                    $model->status = Submission::STATUS_CODE_GENERATED;
                                                    $model->responsible_person = Yii::$app->user->identity->id;
                                                }
                                            } elseif (isset($committeeRevise)) {
                                                $model->status = Submission::STATUS_COMMITTEE_ACCEPTED;
                                                $model->responsible_person = Yii::$app->user->identity->id;
                                                foreach ($committees as $committee) {
                                                    if (($committee->resolution == Submission::RESOLUTION_C) || ($committee->submission->submission_type_id == SubmissionType::TYPE_CREC)) {
                                                        $committee->status = \app\models\SubmissionCommittee::STATUS_ACCEPTED;
                                                        $committee->save(FALSE);
                                                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_COMMITTEE_REASSESS, $committee->id);
                                                    } else {
                                                        $committee->status = \app\models\SubmissionCommittee::STATUS_RETURN;
                                                        $committee->save(FALSE);
                                                    }
                                                }
                                                EmailQueue::execSendMailCmd();
                                                //                                    $committeeRevise->researcher_receive_date = date('Y-m-d H:i:s');
                                                //                                    $committeeRevise->save(FALSE);
                                            } else {
                                                $model->status = Submission::STATUS_CODE_GENERATED;
                                                $model->responsible_person = Yii::$app->user->identity->id;
                                            }
                                        }
                                    } else {
                                        $model->status = Submission::STATUS_DOC_APPROVED;
                                    }
                                } else {
                                    if ($model->is_submit_by_api && !$model->need_local_issue) {
                                        $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
                                        $model->responsible_person = Yii::$app->user->identity->id;
                                    } else {
                                        if (isset($model->refSubmission) and ($model->refSubmission->resolution == Submission::RESOLUTION_C || $model->refSubmission->resolution == Submission::RESOLUTION_R || $model->refSubmission->resolution == Submission::RESOLUTION_N) and (!isset($committeeRevise))) {
                                            if ($model->refSubmission->resolution == Submission::RESOLUTION_C) {
                                                $model->status = Submission::STATUS_SECRETARY_SELECTED;
                                                $model->responsible_person = Yii::$app->user->identity->id;
                                            } elseif ($model->refSubmission->resolution == Submission::RESOLUTION_R || $model->refSubmission->resolution == Submission::RESOLUTION_N) {
                                                $model->status = Submission::STATUS_CODE_GENERATED;
                                                $model->responsible_person = Yii::$app->user->identity->id;
                                            }
                                        } elseif (isset($committeeRevise)) {

                                            $model->status = Submission::STATUS_COMMITTEE_ACCEPTED;
                                            $model->responsible_person = Yii::$app->user->identity->id;
                                            foreach ($committees as $committee) {
                                                if (($committee->resolution == Submission::RESOLUTION_C) || $committee->submission->isFromCrec()) {
                                                    $committee->status = \app\models\SubmissionCommittee::STATUS_ACCEPTED;
                                                    $committee->save(FALSE);
                                                    EmailQueue::addQueueNoExec(EmailQueue::TYPE_COMMITTEE_REASSESS, $committee->id);
                                                } else {
                                                    $committee->status = \app\models\SubmissionCommittee::STATUS_RETURN;
                                                    $committee->save(FALSE);
                                                }
                                                //                                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_COMMITTEE_REASSESS, $committee->id);
                                            }
                                            EmailQueue::execSendMailCmd();
                                            //                                    $committeeRevise->researcher_receive_date = date('Y-m-d H:i:s');
                                            //                                    $committeeRevise->save(FALSE);
                                        } else {
                                            $model->status = Submission::STATUS_CODE_GENERATED;
                                            $model->responsible_person = Yii::$app->user->identity->id;
                                        }
                                    }

//                            $submission->status = Submission::STATUS_CODE_GENERATED;
//                            $submission->responsible_person = Yii::$app->user->identity->id;
                                }
                            } else {
                                return [
                                    'forceReload' => '#submission-status-pjax',
                                    'title' => Yii::t('app', "ยังตรวจสอบเอกสาร,ข้อมูลผู้ร่วมวิจัยไม่ครบถ้วนหรือมีบางรายการไม่ผ่านกรุณาตรวจสอบอีกครั้ง"),
                                    'content' => Alert::widget([
                                        'type' => Alert::TYPE_DANGER,
                                        'body' => \Yii::t('app', 'ยังตรวจสอบเอกสาร,ข้อมูลผู้ร่วมวิจัยไม่ครบถ้วนหรือมีบางรายการไม่ผ่านกรุณาตรวจสอบอีกครั้ง'),
                                        'delay' => false,
                                        'options' => [
                                            'class' => 'dark',
                                        ]
                                    ]),
                                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                ];
                            }
                        } else {

                            if (isset($model->refSubmission) and ( $model->refSubmission->resolution == Submission::RESOLUTION_C || $model->refSubmission->resolution == Submission::RESOLUTION_R || $model->refSubmission->resolution == Submission::RESOLUTION_N) and ( isset($committeeRevise))) {
                                $model->status = Submission::STATUS_DOC_REJECTED_BY_COMMITTEE;
                            } else {
                                $model->status = Submission::STATUS_DOC_REJECTED;
                            }
                            $model->save(FALSE);
                            EmailQueue::addQueue(EmailQueue::TYPE_DOC_REJECT_BY_STAFF, $model->id);
                        }
                    } else {

                        if ($model->status == Submission::STATUS_DOC_APPROVED) {

                            $model->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
                            $model->resolution = submission::RESOLUTION_Y;
                            $model->responsible_person = Yii::$app->user->identity->id;
                            if ($project->load($request->post())) {
                                if (empty($project->project_code)) {
                                    return [
                                        'title' => Yii::t('app', 'ข้อผิดพลาด'),
                                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'กรุณาระบุเลขที่โครงการ') . '</div>',
                                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                    ];
                                }
                                if (empty($project->panel_id)) {
                                    return [
                                        'title' => Yii::t('app', 'ข้อผิดพลาด'),
                                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'กรุณาระบุ Panel') . '</div>',
                                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                    ];
                                }
                                $project->project_code = $project->project_code;
                                $project->panel_id = $project->panel_id;
                                if ($model->resolution == Submission::RESOLUTION_Y && isset($model->refSubmission->meetingAgenda)) {
                                    if (isset($model->refSubmission->meetingAgenda->agenda->project_active)) {
                                        $project->is_active = $model->refSubmission->meetingAgenda->agenda->project_active;
                                    }
                                    $project->save(FALSE);
                                }
                                $project->save(FALSE);
                            }
                        } else {
                            $model->status = Submission::STATUS_DOC_REJECTED;
                            $model->save(FALSE);
                            EmailQueue::addQueue(EmailQueue::TYPE_DOC_REJECT_BY_STAFF, $model->id);
                        }
                    }

//$submission->responsible_person = $currentRole['user_id'];
                    $model->save(FALSE);
                    $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                    return [
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                        'forceReload' => '#submission-status-pjax',
                    ];
//                    return [
//                        'forceReload' => '#submission-status-pjax',
//                        'forceClose' => true,
//                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
//                    ];
                }

                if ($mode == Submission::MODE_ASSESSEDCOMMITTEE) {
//                    echo "ASSESS";
                    if (empty($model->status)) {
                        $model->addError('status', Yii::t('app', 'กรุณาเลือกสถานะส่งผลการประเมินกรรมการ หากต้องการยกเลิกกรุณากดปิด'));
                        return [
                            'title' => Yii::t('app', '{name}', [
                                'name' => $model->project->name_thai
                            ]),
                            'size' => 'large',
                            'content' => $this->renderAjax('update', [
                                'model' => $model,
                                'mode' => $mode,
                                'panelId' => $panelId,
                                'isNow' => $isNow,
                                'project' => $project
                            ]),
                            //                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                    if ($model->status == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE) {
                        if ($model->is_meeting == \app\models\SubmissionCommittee::CANMEEING_YES_FULL) {
//                            $committees = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->submission($model->id)->all();
//                            foreach ($committees as $committee){
//                                $committee->status = \app\models\SubmissionCommittee::STATUS_ACCEPTED;
//                                $committee->save(FALSE);
//                            }
                            $model->status = Submission::STATUS_DOC_REJECTED_BY_COMMITTEE;
                        } else {
                            $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
                        }
                        $model->save(FALSE);

                        EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_C, $model->id);
                    } elseif ($model->status == Submission::STATUS_COMMITTEE_ASSESSED) {
                        $tran = Yii::$app->db->beginTransaction();
                        try {
                            if (($model->assess_type != Submission::TYPE_FULLBOARD || isset($model->ref_submission_id)) and $model->is_meeting == \app\models\SubmissionCommittee::CANMEEING_YES_FULL and $model->resolution == Submission::RESOLUTION_Y) {
                                $model->status = Submission::STATUS_SECRETARY_APPROVE_AGENDA;
                                $model->save(FALSE);
                                //                            EmailQueue::addQueue(EmailQueue::TYPE_SUBMISSION_RESULT, $model->id);                            
                            } else {
                                $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
                                $model->save(FALSE);
                            }
                            if ($model->isFromCrec()) {
                                Yii::warning($model->crec_leader_name, 'committee-assessed-before');
                                $result = Crec::updateLocalIssueResult($model);
                                Yii::warning(VarDumper::dumpAsString($result), 'committee-assessed-after');

                                if ($result['error']) {
                                    throw new Exception($result['message']);
                                }
                            }
                            $tran->commit();
                        } catch (Throwable $ex) {
                            $tran->rollBack();
                            Yii::error($ex->getMessage(), 'committee-assessed-throwable');
                            return [
                                'title' => Yii::t('app', 'กรรมการพิจารณาโครงการ'),
                                'size' => 'large',
                                'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                    'error' => $ex->getMessage(),
                                ]) . '</div>',
                                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                            ];
                        }
                    }

                    $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                    return [
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                        'forceReload' => '#submission-status-pjax',
                    ];
                }
                if ($mode == Submission::MODE_SETAGENDA) {
//                    echo "AGENDA";
//                    if ($model->validate()) {
//                        echo "vailadte";
//                    $exists = MeetingAgenda::find()->isDeleted(false)->submission($id)->exists();
                    if ($model->status >= Submission::STATUS_AGENDA_ADDED) {
//throw new HttpException(500, Yii::t('app', 'โครงการวิจัยนี้มีการบรรจุวาระไปแล้ว กรุณาตรวจสอบ หรือลองโหลดหน้าใหม่อีกครั้ง'));
                        return [
                            'title' => Yii::t('app', 'ข้อผิดพลาด'),
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'โครงการวิจัยนี้มีการบรรจุวาระไปแล้ว กรุณาตรวจสอบ หรือลองโหลดหน้าใหม่อีกครั้ง') . '</div>',
                            //                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    if (empty($model->agendaId)) {
                        return [
                            'title' => Yii::t('app', 'ข้อผิดพลาด'),
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'ท่านยังไม่เลือกวาระโปรดเลือกวาระด้วยนะคะ') . '</div>',
                            //                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    if (empty($model->meetingId)) {
                        return [
                            'title' => Yii::t('app', 'ข้อผิดพลาด'),
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'ท่านยังไม่เลือกการประชุมโปรดเลือกการประชุมด้วยนะคะ') . '</div>',
                            //                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    $agenda->meeting_id = $model->meetingId;
                    $agenda->project_id = $model->project_id;
                    $agenda->submission_id = $id;
                    $agenda->agenda_id = $model->agendaId;
                    $agenda->title = $model->project->project_code;
                    $agenda->sortable = 1;
                    $agenda->need_resolution = 1;
                    $parent = MeetingAgenda::find()->isDeleted(FALSE)->agenda($agenda->agenda_id)->meeting($agenda->meeting_id)->one();
                    $agenda->parent_id = $parent->id;
                    $agenda->sort = $agenda->getNextSort($agenda->parent_id);
                    $agenda->setSortLabel();
                    $agenda->setInitDescription();
                    $agenda->setInitSummary();

                    $agenda->save(FALSE);
                    $model->status = Submission::STATUS_AGENDA_ADDED;
                    $model->save(FALSE);
                    if ($isNow) {
                        return [
                            'forceReload' => '#submission-status-pjax',
                            'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        return [
                            'forceReload' => '#crud-datatable-submission-pjax',
                            'forceClose' => true,
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
//                    } else {
////                        echo 'invalidate';
//                        return [
//                            'title' => $title,
//                            'content' => $this->renderAjax('update', [
//                                'model' => $model,
//                                'mode' => $mode
//                            ]),
//                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                            Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
//                        ];
//                    }
                }

                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', '{name}', [
                        'name' => $model->project->name_thai
                    ]),
                    'size' => 'large',
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'mode' => $mode,
                        'panelId' => $panelId,
                        'isNow' => $isNow,
                        'project' => $project
                    ]),
//                    'forceReload' => '#crud-datatable-submission-pjax',
//                    'forceClose' => true,
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post())) {
                if ($mode == Submission::MODE_COORDINATOR) {
                    $model->save(FALSE);
//                    EmailQueue::addQueue(EmailQueue::TYPE_SECRETARY_SELECTED, $model->id);
                }
                if ($mode == Submission::MODE_MEETINGPLAN) {
//                    echo "MEEt PLAN";
                    if ($model->submissionType->is_fullboard) {
                        $model->status = Submission::STATUS_MEETING_APPOINTMENT;
                    } else {
                        $model->status = Submission::STATUS_SECRETARY_SELECTED;
                    }
                    $model->save(FALSE);
                }
                if ($mode == Submission::MODE_CERTIFICATE) {
//                    echo "CER";
                    $project = $model->project;
                    $project->certificate_no = $model->certificate_no;
                    $project->certified_date = date('Y-m-d H:i:s');
                    $project->expire_at = $model->expire_at;
                    $project->next_progress_at = $model->next_progress_at;
                    $project->progress_period = $model->progress_period;
                    $project->save(FALSE);

                    $model->save(FALSE);
                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "บันทึกข้อมูล"));
                }
                return $this->render('update', [
                            'model' => $model,
                            'mode' => $mode,
                            'panelId' => $panelId,
                            'isNow' => $isNow,
                            'project' => $project
                ]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                            'mode' => $mode,
                            'panelId' => $panelId,
                            'isNow' => $isNow,
                            'project' => $project
                ]);
            }
        }
    }

    /**
     * Creates a new Submission model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new Submission();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Submission",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Submission",
                    'content' => '<span class="text-success">Create Submission success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new Submission",
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
     * Updates an existing Submission model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * Delete an existing Submission model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionResponsible($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->responsible_person = Yii::$app->user->identity->id;
        $model->responsible_date = date('Y-m-d H:i:s');
        $model->status = Submission::STATUS_SUBMITTED;
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถแก้ไขข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return ['forceClose' => true, 'forceReload' => '#crud-datatable-submission-pjax'];
            }
        }
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
//            return $this->redirect(['project-submission', 'submissionId' => $model->id]);
            $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
            return [
                'title' => Yii::t('app', 'รับผิดชอบโครงการต่อเนื่อง'),
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'กำหนดรับผิดชอบโครงการต่อเนื่องเรียบร้อยแล้ว') . '</div><script>window.location = "' . $redirect . '";</script>',
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    public function actionStaffAccept($id) {

        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = Submission::STATUS_COMMITTEE_ACCEPTED;
//        $model->responsible_date = date('Y-m-d H:i:s');
        // $model->save(FALSE);
        $tran = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save()) {
                if ($request->isAjax) {
                    // Yii::warning('Error: ' . \yii\helpers\VarDumper::dumpAsString($model->errors), 'staff-accept');
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => Yii::t('app', 'กรรมการพิจารณาโครงการ'),
                        'size' => 'large',
                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                            'error' => \implode(', ', $model->firstErrors),
                        ]) . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            // if (!empty($model->crec_leader_name)) {
            //     Yii::warning($model->crec_leader_name, 'staff-accept-before');
            //     $result = Crec::updateLocalIssueResult($model);
            //     Yii::warning(VarDumper::dumpAsString($result), 'staff-accept-after');
            //     if ($result['error']) {
            //         throw new Exception($result['message']);
            //     }
            // }
            $tran->commit();
            if ($request->isAjax) {
                /*
                 *   Process for ajax request
                 */
                Yii::$app->response->format = Response::FORMAT_JSON;
                $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                return [
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                    'forceReload' => '#submission-status-pjax',
                ];
//                return ['forceClose' => true, 'forceReload' => '#submission-status-pjax'];
            }
        } catch (Throwable $ex) {
            $tran->rollBack();
            Yii::error($ex->getMessage(), 'staff-accept-throwable');
            return [
                'title' => Yii::t('app', 'กรรมการพิจารณาโครงการ'),
                'size' => 'large',
                'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                    'error' => $ex->getMessage(),
                ]) . '</div>',
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    public function actionPmAcceptAgain($id) {

        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
//        $model->responsible_date = date('Y-m-d H:i:s');
        $model->save(FALSE);
        if (!$model->save()) {
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['forceClose' => true, 'forceReload' => '#submission-status-pjax'];
            }
        }
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#submission-status-pjax'];
        }
    }

    public function actionCoordinator($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $project = Project::findOne(['id' => $model->project_id]);

        if ($project->load($request->post()) && $project->save(false)) {
            $submissions = Submission::find()->isDeleted(false)->project($model->project_id)->all();

            foreach ($submissions as $sm) {
                $sm->project_coordinator_id = $project->project_coordinator_id;
                $sm->project_coordinator_2nd_id = $project->project_coordinator_2nd_id;
                $sm->project_coordinator_3rd_id = $project->project_coordinator_3rd_id;
                $sm->project_viewer_id = $project->project_viewer_id;
                $sm->save(FALSE);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
            return [
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                'forceReload' => '#submission-status-pjax',
            ];

//            return ['forceClose' => true, 'forceReload' => '#submission-status-pjax'];
        } else {
            return $this->render('coordinator', [
                        'model' => $model,
                        'project' => $project,
                        'action' => Url::to(['submission/coordinator', 'id' => $model->id]),
            ]);
        }
    }

    public function actionCertificate($id) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        if ($currentRole['role_id'] != Role::STAFF || $currentRole['role_id'] != Role::ADMIN) {
//            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
//        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($model->load($request->post())) {
            $project = $model->project;
            $project->certificate_no = $model->certificate_no;
            $project->certified_date = $model->certified_date;
            $project->expire_at = $model->expire_at;
            $project->next_progress_at = $model->next_progress_at;
            $project->progress_period = $model->progress_period;
            $project->save(FALSE);
            $model->save(false);
            Yii::$app->response->format = Response::FORMAT_JSON;
//            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "บันทึกเรียบร้อยแล้ว"));
            return [
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div>',
                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            return $this->render('certificate', [
                        'model' => $model,
            ]);
        }
    }

    public function actionMeetingPlan($id) {
        $request = Yii::$app->request;
        $model = Submission::findOne($id);
//        $model->scenario = Submission::SCENARIO_MEETING_PLAN;

        if (isset($model->crec_send_plan_date)) {
            $model->send_plan_date = $model->crec_send_plan_date;
        }
        if ($model->submissionType->is_fullboard) {
            $model->status = Submission::STATUS_MEETING_APPOINTMENT;
        } else {
            $model->status = Submission::STATUS_SECRETARY_SELECTED;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->meeting_plan_date)) {
                Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "กรุณาเลือกวันที่ประมาณการณ์ส่งแบบประเมิน"));
                $model->addError('status', Yii::t('app', 'กรุณาเลือกวันที่ประมาณการณ์ส่งแบบประเมิน'));
            } else {
                $model->save(false);

                Yii::$app->response->format = Response::FORMAT_JSON;
                $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                return [
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                    'forceReload' => '#submission-status-pjax',
                ];
            }
        } else {
            return $this->render('meeting-plan', [
                        'model' => $model,
                        'action' => Url::to(['submission/meeting-plan', 'id' => $model->id])
            ]);
        }
    }

    public function actionSetSecretary($id) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        if ($currentRole['role_id'] != Role::STAFF || $currentRole['role_id'] != Role::ADMIN) {
//            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
//        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($model->load($request->post())) {
            if (empty($model->secretary_person)) {
                Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "กรุณาเลือกเลขาประจำโครงการ"));
                $model->addError('status', Yii::t('app', 'กรุณาเลือกเลขาประจำโครงการ'));
            } else {
                if ($model->status == Submission::STATUS_MEETING_APPOINTMENT) {
                    $model->status = Submission::STATUS_SECRETARY_SELECT_TYPE;
                }
                $model->save(FALSE);
                EmailQueue::addQueue(EmailQueue::TYPE_SECRETARY_SELECTED, $model->id);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
//            Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "บันทึกเรียบร้อยแล้ว"));
            $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
            return [
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            return $this->render('set-secretary', [
                        'model' => $model,
            ]);
        }
    }

    public function actionPmAccept($id, $ind = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = Submission::STATUS_SUBMITTED;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "หัวหน้าโครงการยืนยันการส่งโครงการ"),
                    'content' => $this->renderAjax('pm-accept', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                if (isset($ind)) {
                    $reload = '#crud-datatable-submission-pjax';
                } else {
                    $reload = '#submission-status-pjax';
                }
                return [
                    'forceReload' => $reload,
                    'title' => Yii::t('app', "หัวหน้าโครงการยืนยันการส่งโครงการ"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'หัวหน้าโครงการยืนยันการส่งเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "หัวหน้าโครงการยืนยันการส่งโครงการ"),
                    'content' => $this->renderAjax('pm-accept', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionSubmissionNote($id) {
        $currentRole = \Yii::$app->session->get('currentRole');
        if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if (!isset($model->note)) {
            $model->note = Yii::t('app', '* หมายเหตุจากเจ้าหน้าที่<br><br>* หมายเหตุจากเลขา<br><br>* หมายเหตุจากกรรมการ<br><br>');
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request 
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "บันทึกหมายเหตุเพิ่มเติมสำหรับเจ้าหน้า กรรมการ เลขาที่เกี่ยวข้องกับโครงการ"),
                    'size' => 'large',
                    'content' => $this->renderAjax('submission-note', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', "note"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'บันทึกหมายเหตุเพิ่มเติมสำหรับเจ้าหน้า กรรมการ เลขาที่เกี่ยวข้องกับโครงการเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "หัวหน้าโครงการยืนยันการส่งโครงการ"),
                    'content' => $this->renderAjax('submission-note', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['submission-note', 'id' => $model->id]);
            } else {
                return $this->render('submission-note', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionChangeResponsible($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการ {name}", [
                        'name' => $model->project->name_thai
                    ]),
                    'content' => $this->renderAjax('change-responsible', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการ"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'เปลี่ยนแปลงผู้รับผิดชอบโครงการเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการ"),
                    'content' => $this->renderAjax('change-responsible', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionChangeResponsibleAll($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการทั้งหมดของเจ้าหน้าที่"),
                    'content' => $this->renderAjax('change-responsible-all', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $staffs = Submission::find()->isDeleted(FALSE)->staff($id)->all();
                foreach ($staffs as $staff) {
                    $staff->responsible_person = $model->responsible_person;
                    $staff->save(false);
                }
                return [
                    'forceReload' => '#crud-datatable-person-role-pjax',
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการทั้งหมด"),
                    'content' => Alert::widget([
                        'type' => Alert::TYPE_SUCCESS,
                        'body' => \Yii::t('app', 'เปลี่ยนแปลงผู้รับผิดชอบโครงการทั้งหมดเรียบร้อยแล้ว'),
                        'delay' => false,
                        'options' => [
                            'class' => 'dark',
                        ]
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เปลี่ยนแปลงผู้รับผิดชอบโครงการทั้งหมด"),
                    'content' => $this->renderAjax('change-responsible-all', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

    public function actionGeneral($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $project = Project::findOne(['id' => $model->project_id]);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', "แก้ไขข้อมูลทั่วไป"),
                    'content' => $this->renderAjax('general', [
                        'model' => $model,
                        'project' => $project
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg  pull-right', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $project->load($request->post())) {
                $thaiChange = $project->getDirtyAttributes(['name_thai']);
                $engChange = $project->getDirtyAttributes(['name_eng']);
                if (!empty($thaiChange) && !empty($engChange)) {
                    $project->name_changed = Project::NAME_BOTH_CHANGED;
                } else if (!empty($thaiChange)) {
                    if ($project->name_changed == Project::NAME_NOT_CHANGED) {
                        $project->name_changed = Project::NAME_THAI_CHANGED;
                    } else if ($project->name_changed == Project::NAME_ENG_CHANGED) {
                        $project->name_changed = Project::NAME_BOTH_CHANGED;
                    }
                } else if (!empty($engChange)) {
                    if ($project->name_changed == Project::NAME_NOT_CHANGED) {
                        $project->name_changed = Project::NAME_ENG_CHANGED;
                    } else if ($project->name_changed == Project::NAME_THAI_CHANGED) {
                        $project->name_changed = Project::NAME_BOTH_CHANGED;
                    }
                }
                $model->save(FALSE);
                $project->save(FALSE);
                $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);
                return [
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                    'forceReload' => '#submission-status-pjax',
                ];
//                return [
//                    'forceReload' => '#submission-general-pjax',
//                    'title' => Yii::t('app', "แก้ไขข้อมูลทั่วไป"),
//                    'content' => Alert::widget([
//                        'type' => Alert::TYPE_SUCCESS,
//                        'body' => \Yii::t('app', 'แก้ไขข้อมูลทั่วไปเรียบร้อยแล้ว'),
//                        'delay' => false,
//                        'options' => [
//                            'class' => 'dark',
//                        ]
//                    ]),
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
//                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขข้อมูลทั่วไป"),
                    'content' => $this->renderAjax('general', [
                        'model' => $model,
                        'project' => $project
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('general', [
                            'model' => $model,
                            'project' => $project
                ]);
            }
        }
    }

    public function actionActive($projectId, $reload = NULL) {
        $reload = isset($reload) ? $reload : '#crud-datatable-submission-pjax';
        $request = Yii::$app->request;
        $model = Project::findOne($projectId);
        $model->is_active = 1;
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceClose' => true,
                    'forceReload' => $reload,
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
            return ['forceClose' => true, 'forceReload' => $reload];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id, $reload = NULL) {
        $reload = isset($reload) ? $reload : '#crud-datatable-submission-pjax';
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
                    'forceReload' => $reload,
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
            return ['forceClose' => true, 'forceReload' => $reload];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionAssessedCommittee($id, $mode, $subCommitteeId) {
        $request = Yii::$app->request;
        $modelSubmission = Submission::findOne($id);
        $model = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->where(['submission_id' => $id, 'id' => $subCommitteeId])->one();
        $model->scenario = \app\models\SubmissionCommittee::SCENARIO_ASSESS;
        $committeeSubmission = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->where(['submission_id' => $id, 'id' => $subCommitteeId])->one();
        $submissionDocs = SubmissionDocument::find()->isDeleted(FALSE)->andWhere(['submission_id' => $id])->all();
        $project = Project::find()->isDeleted(FALSE)->andWhere(['id' => $model->project_id])->one();
        $submissionResearcherDocs = SubmissionProjectResearcher::find()->isDeleted(FALSE)->andWhere(['submission_id' => $id])->all();
//        $oldCommitteeRevise = \app\models\SubmissionCommitteeRevise::find()->isDeleted(FALSE)->submission($id)->one();
        $committeeRevise = new \app\models\SubmissionCommitteeRevise;
        if ($model->submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == SubmissionType::TYPE_DEVIATION) {
            $modelSubmission->send_to_crec = 0;
        }
        $model->can_meeting = 0;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                if ($model->submission->submissionType->meeting_consideration) {
//                    $model->scenario = Submission::SCENARIO_CONFIRM_MEETING;
                    return [
                        'title' => \Yii::t('app', 'พิจารณาเข้าประชุม'),
                        'size' => 'large',
                        'content' => $this->renderAjax('confirm-meeting', [
                            'model' => $model,
                            'committeeRevise' => $committeeRevise,
                            'modelSubmission' => $modelSubmission
                        ]),
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else {
                    $model->return_date = date('Y-m-d H:i:s');
                    $model->status = \app\models\SubmissionCommittee::STATUS_RETURN;
                    $model->save(FALSE);
//                    $committeeStatusCheck = $model->getCommitteeStatusAssessed();
//                    if ($committeeStatusCheck['isPass']) {
//                        $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
//                        $model->save(FALSE);
//                    }
                    EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_ASSESS, $model->id);
                    $url = \yii\helpers\Url::to(['site/index']);
                    $js = <<<js
                        <script type="text/javascript">
                            setTimeout(function() {
                                window.location = '{$url}';
                            }, 1000);
                        </script>
js;
                    return [
// 'forceReload' => '#submission-btn-pjax',
                        'title' => "ส่งแบบประเมินเรียบร้อยแล้ว",
                        'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ส่งแบบประเมินเรียบร้อยแล้ว') . '</div>' . $js,
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else if ($model->load($request->post()) && $committeeRevise->load(Yii::$app->request->post()) && $model->validate()) {

                if ($model->submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == SubmissionType::TYPE_DEVIATION) {
                    if ($modelSubmission->load(Yii::$app->request->post())) {
                        $modelSubmission->save(FALSE);
                    }
                }
                if ($model->resolution == Submission::RESOLUTION_C) {
                    $committeeRevise->submission_id = $model->submission->id;
                    $committeeRevise->resolution = $model->resolution;
                    $committeeRevise->is_meeting = $model->is_meeting;
                    $committeeRevise->submission_committee_id = $committeeSubmission->id;
                    $committeeRevise->save(FALSE);

                    $model->return_date = date('Y-m-d H:i:s');
                    $model->is_meeting = $model->is_meeting;
                    $model->resolution = $model->resolution;
                    $model->status = \app\models\SubmissionCommittee::STATUS_RETURN;
                    $model->save(FALSE);
//                    $committeeSubmission->return_date = date('Y-m-d H:i:s');
//                    $committeeSubmission->status = \app\models\SubmissionCommittee::STATUS_RETURN;
//                    $committeeSubmission->save(FALSE);
//                    if ($model->is_meeting) {
//                        // $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
//                       
//                        $model->status = \app\models\SubmissionCommittee::STATUS_RETURN_C;
//                        $model->save(FALSE);
//                    } else {
//                        // $model->status = Submission::STATUS_DOC_REJECTED_BY_COMMITTEE;
//                       // $model->meeting_by = \Yii::$app->user->id;
//                        $model->resolution = NULL;
//                       // $model->meeting_at = date('Y-m-d H:i:s');
//                        $model->status = \app\models\SubmissionCommittee::STATUS_RETURN_C;
//                        $model->save(FALSE);
////                        if (!empty($submissionDocs)) {
////                            foreach ($submissionDocs as $submissionDoc) {
////                                $submissionDoc->status = NULL;
////                                $submissionDoc->save(FALSE);
////                            }
////                        }
////                        if (!empty($submissionResearcherDocs)) {
////                            foreach ($submissionResearcherDocs as $submissionResearcherDoc) {
////                                $submissionResearcherDoc->status = NULL;
////                                $submissionResearcherDoc->save(FALSE);
////                            }
////                        }
////                        EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_C, $model->id);
//                    }
//                    $model->save(FALSE);
//                    if ($oldCommitteeRevise) {
//                        $oldCommitteeRevise->deleted = 1;
//                        $oldCommitteeRevise->save(FALSE);
//                    }
                } elseif ($model->resolution == Submission::RESOLUTION_Y) {
//                    if ($model->is_meeting) {
//                        $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
//                    } else {
//                        $model->status = Submission::STATUS_MEETING_DONE;
//                    }
//                    $model->certified_date = date('Y-m-d H:i:s');
//                    $model->resolution = Submission::RESOLUTION_Y;
//                    $model->meeting_by = \Yii::$app->user->id;
//                    $model->meeting_at = date('Y-m-d H:i:s');
//                    $model->save(FALSE);
//
//                    //   $project->certificate_no = $model->certificate_no;
////                    $project->certified_date = date('Y-m-d H:i:s');
////                    $project->expire_at = $model->expire_at;
////                    $project->next_progress_at = $model->next_progress_at;
////                    $project->progress_period = $model->progress_period;
////                    $project->save(FALSE);
//
//                    $model->return_date = date('Y-m-d H:i:s');
//                    $model->status = \app\models\SubmissionCommittee::STATUS_RETURN;
//                    $model->save(FALSE);

                    $committeeRevise->submission_id = $model->submission->id;
                    $committeeRevise->is_meeting = $model->is_meeting;
                    $committeeRevise->resolution = $model->resolution;
                    $committeeRevise->submission_committee_id = $model->id;
                    $committeeRevise->save(FALSE);

                    $model->return_date = date('Y-m-d H:i:s');
                    $model->is_meeting = $model->is_meeting;
                    $model->resolution = $model->resolution;
                    $model->status = \app\models\SubmissionCommittee::STATUS_RETURN;
                    $model->save(FALSE);
//                    $committeeSubmission->return_date = date('Y-m-d H:i:s');
//                    $committeeSubmission->status = \app\models\SubmissionCommittee::STATUS_RETURN;
//                    $committeeSubmission->save(FALSE);
//                    if ($model->status == Submission::STATUS_MEETING_DONE) {
//                        EmailQueue::addQueue(EmailQueue::TYPE_SUBMISSION_RESULT, $model->id);
//                    }
                }
//                if ($model->is_meeting) {
//                    $model->status = Submission::STATUS_COMMITTEE_ASSESSED;
//                } else {
//                    $model->status = Submission::STATUS_MEETING_DONE;
//                }
//                $model->meeting_by = \Yii::$app->user->id;
//                $model->meeting_at = date('Y-m-d H:i:s');
//                $model->save(FALSE);
//                $committeeSubmission->return_date = date('Y-m-d H:i:s');
//                $committeeSubmission->status = \app\models\SubmissionCommittee::STATUS_RETURN;
//                $committeeSubmission->save(FALSE);
                EmailQueue::addQueue(EmailQueue::TYPE_COMMITTEE_ASSESS, $model->id);
                $url = \yii\helpers\Url::to(['site/index']);
                $js = <<<js
                        <script type="text/javascript">
                            setTimeout(function() {
                                window.location = '{$url}';
                            }, 1000);
                        </script>
js;
                return ['forceReload' => '#submission-btn-pjax',
                    'title' => "ส่งแบบประเมินเรียบร้อยแล้ว",
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'ส่งแบบประเมินเรียบร้อยแล้ว') . '</div>' . $js,
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                if ($model->submission->submissionType->meeting_consideration) {
//                    $model->scenario = Submission::SCENARIO_CONFIRM_MEETING;
                    return [
                        'title' => \Yii::t('app', 'พิจารณาเข้าประชุม'),
                        'content' => $this->renderAjax('confirm-meeting', [
                            'model' => $model,
                            'committeeRevise' => $committeeRevise,
                            'modelSubmission' => $modelSubmission
                        ]),
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
            /*
             *   Process for ajax request
             */
//            if ($mode == Submission::MODE_ASSESSEDCOMMITTEE) {
//            if ($model->submissionType->meeting_consideration) {
//                
//            }
//            
////            }
//            Yii::$app->response->format = Response::FORMAT_JSON;
//            return ['forceReload' => '#submission-btn-pjax',
//                'title' => "ส่งแบบประเมินเรียบร้อยแล้ว",
//                'content' => '<span class="text-success">' . Yii::t('app', 'ส่งแบบประเมินเรียบร้อยแล้ว') . '</span>',
//                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
//            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionCreateContinueCrec($id) {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = $this->findModel($id);
            $model->send_to_crec = 1;
            Yii::warning($model->crec_leader_name, 'before-create-continue-to-crec');
            $result = Crec::createContinueEc($model);
            Yii::warning(VarDumper::dumpAsString($result), 'after-create-continue-to-crec');

            if ($result['error']) {
                // throw new Exception($result['message']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'ยืนยันการส่งข้อมูลไปยัง CREC'),
                    'size' => 'large',
                    'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาดหรือไม่พบโครงการในระบบ {error}', [
                        'error' => $result['error']['message'],
                    ]) . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
            $preSub = $model->getPreviousSubmission();
            if (isset($preSub)) {
                $model->crec_leader_name = $preSub->crec_leader_name;
                $model->crec_leader_name_eng = $preSub->crec_leader_name_eng;
                $model->crec_leader_site_name = $preSub->crec_leader_site_name;
                $model->crec_leader_site_name_eng = $preSub->crec_leader_site_name_eng;
                $model->crec_leader_org_name = $preSub->crec_leader_org_name;
                $model->crec_leader_org_name_eng = $preSub->crec_leader_org_name_eng;
            }
            $model->save(false);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', 'ยืนยันการส่งข้อมูลไปยัง CREC'),
                'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'ส่งข้อมูลไปยัง CREC เรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
            ];
        }
    }

    public function actionSendResult($id) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $request = Yii::$app->request;
        $tran = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $model->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;

            if ($model->resolution == Submission::RESOLUTION_Y && isset($model->refSubmission->meetingAgenda)) {
                $project = $model->project;
                if ($model->refSubmission->meetingAgenda->agenda->project_active) {
                    $project->is_active = $model->refSubmission->meetingAgenda->agenda->project_active;
                }
                $project->save(FALSE);
            }
            if ($model->is_submit_by_api) {
                if ($model->isFromCrec() && $model->resolution == Submission::RESOLUTION_Y) {
                    Yii::warning($model->crec_leader_name, 'upload-result-before');
                    $result = Crec::updateResult($model);
                    Yii::warning(VarDumper::dumpAsString($result), 'committee-assessed-after');
                    if ($result['error']) {
                        throw new Exception($result['message']);
                    }
                }
            } else {
                if ($model->send_to_crec) {
                    Yii::warning($model->crec_leader_name, 'before-create-continue-to-crec');
                    $result = Crec::createContinueEc($model);
                    Yii::warning(VarDumper::dumpAsString($result), 'after-create-continue-to-crec');

                    if ($result['error']) {
                        // throw new Exception($result['message']);
                        $tran->rollBack();
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => Yii::t('app', 'ยืนยันการส่งหนังสือแจ้งผลให้ตรวจสอบ'),
                            'size' => 'large',
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                'error' => $result['error']['message'],
                            ]) . '</div>',
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    $preSub = $model->getPreviousSubmission();
                    if (isset($preSub)) {
                        $model->crec_leader_name = $preSub->crec_leader_name;
                        $model->crec_leader_name_eng = $preSub->crec_leader_name_eng;
                        $model->crec_leader_site_name = $preSub->crec_leader_site_name;
                        $model->crec_leader_site_name_eng = $preSub->crec_leader_site_name_eng;
                        $model->crec_leader_org_name = $preSub->crec_leader_org_name;
                        $model->crec_leader_org_name_eng = $preSub->crec_leader_org_name_eng;
                        $model->save(false);
                    }
                }
            }
            $tran->commit();
            EmailQueue::addQueue(EmailQueue::TYPE_INFO_RESULT_PROJECTLEADER, $model->id);
        } catch (Throwable $ex) {
            $tran->rollBack();
            Yii::error($ex->getMessage(), 'send-result-throwable');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', 'ยืนยันการส่งหนังสือแจ้งผลให้ตรวจสอบ'),
                'size' => 'large',
                'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                    'error' => $ex->getMessage(),
                ]) . '</div>',
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
        if (!$model->save()) {

            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                    'forceReload' => '#submission-status-pjax',
                    'content' => $this->renderAjax('@app/views/widgets/_alert'),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->redirect(['index']);
            }
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', 'ยืนยันการส่งหนังสือแจ้งผลให้ตรวจสอบ'),
                'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'ยืนยันการส่งหนังสือแจ้งผลให้ตรวจสอบเรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionPresidentApproveResultDocuments($typeGroup = NULL, $panelId = NULL) {
        $currentRole = \Yii::$app->session->get('currentRole');

        $request = Yii::$app->request;

        if ($request->isPost) {
            $typeGroupGet = Yii::$app->request->get('typeGroup');
            $decisions = $request->post('decisions', []);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $approvedCount = 0;
                $rejectedCount = 0;

                foreach ($decisions as $submissionId => $decision) {
                    $action = $decision['action'] ?? 'pending';

                    if ($action === 'pending') {
                        continue;
                    }
                    $model = $this->findModel($submissionId);
                    if (!isset($model->send_to_crec)) {
                        $model->send_to_crec = 0;
                    }
                    $submission = $this->findModel($model->id);
                    if ($model->deleted || $model->status != Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN) {
                        continue;
                    }
                    if ($currentRole['role_id'] == Role::PRESIDENT && $model->president_person != Yii::$app->user->identity->id) {
                        continue;
                    }
                    if ($action === 'approve') {
                        $model->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;

                        if ($model->resolution == Submission::RESOLUTION_Y && isset($model->refSubmission->meetingAgenda)) {
                            $project = $model->project;
                            if ($model->refSubmission->meetingAgenda->agenda->project_active) {
                                $project->is_active = $model->refSubmission->meetingAgenda->agenda->project_active;
                            }
                            $project->save(FALSE);
                        }

                        if ($model->is_submit_by_api) {
                            if ($model->isFromCrec() && $model->resolution == Submission::RESOLUTION_Y) {
                                Yii::warning($model->crec_leader_name, 'upload-result-before');
                                $result = Crec::updateResult($model);
                                Yii::warning(VarDumper::dumpAsString($result), 'committee-assessed-after');
                                if ($result['error']) {
                                    throw new Exception($result['message']);
                                }
                            }
                        } else {
                            if ($model->send_to_crec) {
                                Yii::warning($model->crec_leader_name, 'before-create-continue-to-crec');
                                $result = Crec::createContinueEc($model);
                                Yii::warning(VarDumper::dumpAsString($result), 'after-create-continue-to-crec');

                                if ($result['error']) {
                                    // throw new Exception($result['message']);
                                    $tran->rollBack();
                                    Yii::$app->response->format = Response::FORMAT_JSON;
                                    return [
                                        'title' => Yii::t('app', 'Upload เอกสารแจ้งผล'),
                                        'size' => 'large',
                                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                            'error' => $result['error']['message'],
                                        ]) . '</div>',
                                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                    ];
                                }
                                $preSub = $model->getPreviousSubmission();
                                if (isset($preSub)) {
                                    $model->crec_leader_name = $preSub->crec_leader_name;
                                    $model->crec_leader_name_eng = $preSub->crec_leader_name_eng;
                                    $model->crec_leader_site_name = $preSub->crec_leader_site_name;
                                    $model->crec_leader_site_name_eng = $preSub->crec_leader_site_name_eng;
                                    $model->crec_leader_org_name = $preSub->crec_leader_org_name;
                                    $model->crec_leader_org_name_eng = $preSub->crec_leader_org_name_eng;
                                    $model->save(false);
                                }
                            }
                        }

                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFO_RESULT_PROJECTLEADER, $model->id);

                        $ardProvider = new ArrayDataProvider([
                            'allModels' => $submission->getResultDocuments()
                        ]);
                        $dataProvider = $ardProvider;
                        $resultDocuments = $dataProvider->getModels();

                        $pdfFiles = [];
                        foreach ($resultDocuments as $r) {

//                            if (isset($r['submission_result_document_id'])) {
//                                continue;
//                            }
                            $rd = \app\models\ResultDocument::findOne($r['result_document_id']);
                            $srdFile = false;
                            if (!empty($r['submission_result_document_id'])) {
                                $srd = \app\models\SubmissionResultDocument::findOne($r['submission_result_document_id']);
                                $infoFile = pathinfo($srd->document_file);
                                if (in_array($infoFile['extension'], ['docx'])) {
                                    $srdFile = true;
                                    $srd->deleted = 1;
                                    $srd->save(false);
                                }
                            }
                            if ($srdFile === true) {
                                $docx = new \Phpdocx\Create\CreateDocxFromTemplate($srd->filePath);
                            } else {
                                $docx = new \Phpdocx\Create\CreateDocxFromTemplate($rd->templatePathAlias);
                            }

//                            $docx = new \Phpdocx\Create\CreateDocxFromTemplate($rd->templatePathAlias);

                            $docx->setTemplateSymbol('$');

                            $ma = $submission->meetingAgenda;
                            if (isset($submission->refSubmission)) {
                                $ma = $submission->refSubmission->meetingAgenda;
                            }
                            $endorseMa = $submission->firstEndorseMeetingAgenda;

// $parser = new \HTMLtoOpenXML\Parser();
                            $lc = \Yii::$app->formatter->locale;
                            \Yii::$app->formatter->locale = 'th';

                            $currentDate = \Yii::$app->formatter->asDate(time(), 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate(time(), 'php:n')] . ' ' . (date('Y') + 543);
                            $meetingDate = isset($ma) ? \Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:Y') + 543) : "";
                            $endorseDate = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->certified_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->certified_date, 'php:Y') + 543) : "";
                            $expireDate = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->expire_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->expire_at, 'php:Y') + 543) : "";
                            $corresspondenceDate = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y') + 543) : "";
                            $nextProgressDate = isset($submission->next_progress_at) ? \Yii::$app->formatter->asDate($submission->next_progress_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->next_progress_at, 'php:Y') + 543) : "";
                            $lastKeepDate = isset($submission->last_keep_date) ? \Yii::$app->formatter->asDate($submission->last_keep_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->last_keep_date, 'php:Y') + 543) : "";

//                            $lastKeepDate = isset($ma) ? new \DateTime($ma->meeting->start_date) : NULL;
//                            $lastKeepDate = isset($lastKeepDate) ? $lastKeepDate->add(new \DateInterval('P3Y'))->format('Y-m-d') : NULL;
//                            $lastKeepDate = isset($lastKeepDate) ? \Yii::$app->formatter->asDate($lastKeepDate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($lastKeepDate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($lastKeepDate, 'php:Y') + 543) : NULL;

                            \Yii::$app->formatter->locale = 'en';
                            $currentDateEng = \Yii::$app->formatter->asDate(time(), 'php:d F Y');
                            $meetingDateEng = isset($ma->meeting->start_date) ? \Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d F Y') : "";
                            $endorseDateEng = isset($submission->certified_date) ? \Yii::$app->formatter->asDate($submission->certified_date, 'php:d F Y') : "";
                            $expireDateEng = isset($submission->expire_at) ? \Yii::$app->formatter->asDate($submission->expire_at, 'php:d F Y') : "";
                            $nextProgressDateEng = isset($submission->next_progress_at) ? \Yii::$app->formatter->asDate($submission->next_progress_at, 'php:d F Y') : "";
                            $corresspondenceDateEng = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d F Y') : "";
                            $personRole = \app\models\PersonRolePanel::find()->joinWith('personRole')->isDeleted(false)->role(\app\models\Role::PRESIDENT)->panel($submission->project->panel_id)->isRegular()->orderBy('person_role_panel.id DESC')->one();

                            \Yii::$app->formatter->locale = $lc;

                            $researcherThai = $submission->projectLeader->person->fullNameNoTitle;
                            $submissionType = $submission->submissionType->name;
                            $divisionThai = $submission->project->projectLeader->person->divisionThai;
                            $meetingNo = isset($ma) ? $ma->meeting->yearNo : "";
//        $meetingNoEng = isset($ma) ? $ma->meeting->yearNoEng : "";
                            $endorseMeetingNo = isset($endorseMa) ? $endorseMa->meeting->yearNo : '';
                            $agendaNo = isset($ma) ? $ma->sort_label : "";
                            $subject = isset($ma) ? $ma->agenda->name : "";
                            $panelId = $submission->project->panel_id;
                            $panelName = $submission->project->panel->name;
                            $panelNameEng = $submission->project->panel->name_eng;
                            if (isset($submission->president_person)) {
                                $chairman = $submission->presidentPerson->person;
                                $presidentName = $submission->presidentPerson->person->fullName;
                                $presidentNameEng = $submission->presidentPerson->person->fullNameEng;
                            } else {
                                $chairman = $submission->project->panel->chairman;
                                $presidentName = $submission->project->panel->chairman->fullName;
                                $presidentNameEng = $submission->project->panel->chairman->fullNameEng;
                            }
//        $chairman = $submission->project->panel->chairman;
                            $sponser = isset($submission->project->funding_source_id) ? $submission->project->fundingSource->name : "";
                            $sponserEng = isset($submission->project->funding_source_id) ? $submission->project->fundingSource->name_eng : "";
                            $sponserDescription = !empty($submission->project->funding_source_description) ? $submission->project->funding_source_description : "";
//        $presidentName = isset($personRole->personRole->person_id) ? $personRole->personRole->person->fullName : "";
//        $presidentNameEng = isset($personRole->personRole->person_id) ? $personRole->personRole->person->fullNameEng : "";
                            $leader = $submission->projectLeader->person->fullNameNoTitle;
                            $leaderOrg = $submission->projectLeader->person->divisionThai;
                            $leaderEng = $submission->projectLeader->person->fullNameEngNoTitle;
                            $leaderOrgEng = $submission->projectLeader->person->divisionEng;
                            $progressNo = isset($ma) ? MeetingAgenda::find()->isDeleted(FALSE)->submission($submission->id)->agenda($ma->agenda_id)->count() : "";

                            $rname = $researcherThai . ' ' . $divisionThai;
                            
                            $volunteer = $this->renderPartial('@app/views/result-document/_volunteers', ['submission' => $submission]);
                            $volunteer = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $volunteer]);

                            
                            $researchers = $submission->projectCoResearchers;
                            $researcher = $this->renderPartial('@app/views/result-document/_researchers', ['researchers' => $researchers, 'submission' => $submission]);
                            $researcher = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $researcher]);
                            $specialCondition = $this->renderPartial('@app/views/result-document/_content', ['submission' => $submission]);
                            $specialCondition = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $specialCondition]);
                            $researcherEng = $this->renderPartial('@app/views/result-document/_researchers', ['researchers' => $researchers, 'submission' => $submission, 'eng' => true]);
                            $researcherEng = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $researcherEng]);

                            $docs = $submission->project->getLatestEndorseDocuments();
                            $token = Yii::$app->security->generateRandomString() . '_' . time();
                            $codeRd = Yii::$app->util->getGenerateCode();

                            $document = '';
                            $documentEng = '';
                            $document = $this->renderPartial('@app/views/result-document/_documents', ['docs' => $docs, 'submission' => $submission, 'submissionEcId' => $submissionEcId]);
                            $document = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $document]);
                            $documentEng = $this->renderPartial('@app/views/result-document/_documents', ['docs' => $docs, 'eng' => true]);
                            $documentEng = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $documentEng]);

                            $issues = $this->renderPartial('@app/views/result-document/_wrap', ['content' => strip_tags($submission->issue1, '<p><div><br><li><ol>')]);
                            $issuesEng = $this->renderPartial('@app/views/result-document/_wrap', ['content' => strip_tags($submission->issue1_eng, '<p><div><br><li><ol>')]);
                            $issues2 = $this->renderPartial('@app/views/result-document/_wrap', ['content' => strip_tags($submission->issue2, '<p><div><br><li><ol>')]);
//                            $specialCondition = $this->renderPartial('@app/views/result-document/_wrap', ['content' => strip_tags($submission->special_condition, '<p><div><br><li><ol>')]);
                            $reviseRemark = $this->renderPartial('@app/views/result-document/_wrap', ['content' => isset($revise) && !empty($revise->remark) ? $revise->remark : ""]);

                            $images = $this->renderPartial('@app/views/result-document/_image', ['submission' => $submission, 'type' => 'eng']);
                            $images = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $images]);

                            $imagesThai = $this->renderPartial('@app/views/result-document/_image', ['submission' => $submission, 'type' => 'thai']);
                            $imagesThai = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $imagesThai]);

                            $imagesLetter = $this->renderPartial('@app/views/result-document/_image-letter', ['submission' => $submission, 'type' => 'eng']);
                            $imagesLetter = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $imagesLetter]);

                            $imagesLetterThai = $this->renderPartial('@app/views/result-document/_image-letter', ['submission' => $submission, 'type' => 'thai']);
                            $imagesLetterThai = $this->renderPartial('@app/views/result-document/_wrap', ['content' => $imagesLetterThai]);

                            $meetingNoEng = $this->renderPartial('@app/views/result-document/_wrap-text', ['content' => $meetingNoEng]);

// สร้าง token จาก project code + reference โดย sign ด้วย secret ของระบบ
                            $payload = $submission->project->project_code . '|' . $r['id'];
                            $sig = substr(hash_hmac('sha256', $payload, Yii::$app->params['qrSecret']), 0, 8);

                            $verifyUrl = Yii::$app->urlManager->createAbsoluteUrl([
                                '/site/coa-check',
                                'code' => $submission->project->project_code,
                                'id' => $submission->id,
                                'sig' => $sig,
                            ]);

                            $qrPath = \app\components\QrCodeHelper::generateFile($verifyUrl, 300);
                            $qrFragment = new \Phpdocx\Elements\WordFragment($docx, 'footer');
                            $qrFragment->addImage([
                                'src' => $qrPath,
                                'width' => '70px', // ปรับขนาดให้พอดีเซลล์ฟุตเตอร์
                                'height' => '70px',
                            ]);

                            $resolutionEng = '';
                            if ($submission->submissionType->resolution_label == "รับรอง") {
                                $resolutionEng = 'Approval';
                            } else {
                                $resolutionEng = 'Exemption';
                            }

                            $variables = [
                                'current-date-eng' => $currentDateEng,
                                'current-date' => $currentDate,
//            'ref-certificate-no' => isset($submission->ref_certificate_no) ? $submission->ref_certificate_no : "",
                                'fda-no' => isset($submission->project->fda_no) ? $submission->project->fda_no : "",
                                'meeting-no' => $meetingNo,
                                'ref-meeting-no' => isset($submission->ref_submission_id) ? $submission->refSubmission->meetingAgenda->meeting->yearNo : "",
                                'agenda-no' => $agendaNo,
                                'meeting-date-eng' => $meetingDateEng,
                                'meeting-date' => $meetingDate,
                                'agenda-date' => $meetingDate,
                                'subject' => $subject,
                                'project-thai' => $submission->project->name_thai,
                                'submission-number' => isset($submission->submission_number) ? $submission->submission_number : "",
                                'project-eng' => $submission->project->name_eng,
                                'project-code' => $submission->project->project_code,
                                'certificate-no' => !empty($submission->project->certificate_no) ? $submission->project->certificate_no : "",
                                'researcher-thai' => $researcherThai,
                                'researcher-thai-title' => $rname,
                                'chairman' => $chairman->fullName,
                                'project-type' => $projectType,
                                'project-type-eng' => $projectTypeEng,
                                'chairman-eng' => $chairman->fullNameEng,
                                'president-name' => $presidentName,
                                'submission-president-name' => isset($submission->president_person) ? $submission->presidentPerson->person->fullName : "",
                                'submission-president-name-eng' => isset($submission->president_person) ? $submission->presidentPerson->person->fullNameEng : "",
                                'leader' => $leader,
                                'resolution-type-eng' => $resolutionEng,
                                'resolution-type' => $submission->submissionType->resolution_label,
                                'leader-org' => $leaderOrg,
                                'leader-eng' => $leaderEng,
                                'leader-org-eng' => $leaderOrgEng,
                                'progress-period' => $submission->progress_period,
                                'endorse-date-thai' => $endorseDate,
                                'expire-date-thai' => $expireDate,
                                'endorse-date-eng' => $endorseDateEng,
                                'expire-date-eng' => $expireDateEng,
                                'last-keep-date' => $lastKeepDate,
                                'correspondence-no' => $submission->correspondence_no,
                                'correspondence-date' => $corresspondenceDate,
                                'next-progress-date' => $nextProgressDate,
                                'next-progress-date-eng' => $nextProgressDateEng,
                                'correspondence-date-eng' => $corresspondenceDateEng,
                                'division-thai' => $divisionThai,
                                'endorse-meeting-no' => $endorseMeetingNo,
                                'panel-id' => $panelId,
                                'panel-name' => $panelName,
                                'panel-name-eng' => $panelNameEng,
                                'progress-no' => $progressNo,
                                'submission-type' => $submissionType,
                                'sponser' => $sponser,
                                'sponser-eng' => $sponserEng,
                                'sponser-description' => $sponserDescription,
                                'code-rd' => $codeRd,
                                'code-panel' => isset($submission->project->panel->ref_letter) ? $submission->project->panel->ref_letter : "",
                                'assess-type' => isset($submission->assess_type) ? Submission::getAssessTypeLabel()[$submission->assess_type] : "",
                            ];


                            $docx->replaceVariableByText($variables);
                            $docx->replaceVariableByText($variables, ['target' => 'footer']);
                            $docx->replaceVariableByHTML('chairman-signature-letter-thai', 'block', $imagesLetterThai, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('chairman-signature-letter', 'block', $imagesLetter, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('chairman-signature-thai', 'block', $imagesThai, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('chairman-signature-eng', 'block', $images, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('document', 'block', $document, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('documentEng', 'block', $documentEng, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('researcher', 'block', $researcher, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('volunteer', 'block', $volunteer, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('researcherEng', 'block', $researcherEng, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('issues', 'block', $issues, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('issues-eng', 'block', $issuesEng, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('meeting-no-eng', 'block', $meetingNoEng, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('issues2', 'block', $issues2, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('special-condition', 'block', $specialCondition, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByHTML('revise-remark', 'block', $reviseRemark, ['isFile' => false, 'embedFonts' => true]);
                            $docx->replaceVariableByWordFragment(
                                    ['qrcode' => $qrFragment],
                                    ['type' => 'inline', 'target' => 'footer']   // ⭐ ต้องมี target = footer
                            );

                            $code = str_replace('/', '-', $submission->project->project_code);
                            $nameR = mb_substr($rd->name, 0, 50, 'UTF-8');
                            $file = "{$code}_{$r['id']}.docx";

                            // สร้างไฟล์ DOCX ชั่วคราว
                            $docxPath = Yii::getAlias("@app/web/tmp/{$file}");
                            $docx->createDocx($docxPath);

                            // แปลง DOCX เป็น PDF
                            $pdfFile = str_replace('.docx', '.pdf', $file);
                            $pdfPath = Yii::getAlias("@app/web/uploads/submission-result-document-file/{$pdfFile}");


                            // ใช้ LibreOffice หรือ unoconv แปลงเป็น PDF
                            // $cmd = "libreoffice --headless --convert-to pdf --outdir " . Yii::getAlias("@app/web/tmp") . " " . escapeshellarg($docxPath);
                            // exec($cmd);
//                            $lockedOk = false;
//                            if (!isset($r['submission_result_document_id'])) {
//                                $newDocx = new CreateDocx();
//                                $newDocx->transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);
////
////                                try {
////                                    $lockedOk = Yii::$app->pdfLocker->lock($pdfPath);
////                                } catch (\Exception $e) {
////                                    Yii::error("PDF lock failed for {$pdfPath}: " . $e->getMessage(), __METHOD__);
////                                    $lockedOk = false;
////                                }
//                            }
//                            $lockedOk = false;
//                            if (!isset($r['submission_result_document_id'])) {
//                                $newDocx = new CreateDocx();
//                                $newDocx->transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);
//
//                                try {
//                                    $lockedOk = Yii::$app->pdfLocker->lock($pdfPath);
//                                } catch (\Exception $e) {
//                                    Yii::error("PDF lock failed for {$pdfPath}: " . $e->getMessage(), __METHOD__);
//                                    $lockedOk = false;
//                                }
//                            }

                            if (!isset($r['submission_result_document_id'])) {
                                $newDocx = new CreateDocx();
                                $newDocx->transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);

                                $nr = new \app\models\SubmissionResultDocument();
//                            $nr->attributes = $r->attributes;
                                $nr->submission_id = $submission->id;
                                $nr->result_document_id = $r['result_document_id'];
                                $nr->name = $r['document_name'];
                                $nr->deleted = 0;
                                $nr->document_file = $pdfFile;
                                $nr->coa_token = $sig;
                                $nr->code = $codeRd;
                                $nr->qrcode = $qrPath;
//                                $nr->is_locked = $lockedOk ? 1 : 0;

                                $nr->save(false);
                            }
                            if ($srdFile === true) {

                                $newDocx = new CreateDocx();
                                $newDocx->transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);

                                $nr = new \app\models\SubmissionResultDocument();
//                            $nr->attributes = $r->attributes;
                                $nr->submission_id = $submission->id;
                                $nr->result_document_id = $r['result_document_id'];
                                $nr->name = $r['document_name'];
                                $nr->deleted = 0;
                                $nr->document_file = $pdfFile;
                                $nr->coa_token = $sig;
                                $nr->code = $codeRd;
                                $nr->qrcode = $qrPath;
//                                $nr->is_locked = $lockedOk ? 1 : 0;

                                $nr->save(false);
                            }
                        }


                        if ($model->save(false)) {
                            $approvedCount++;
                        }
                    } elseif ($action === 'reject') {
                        $leaderComment = trim($decision['president_comment'] ?? '');
                        if (empty($leaderComment)) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', Yii::t('app', 'กรุณาระบุเหตุผลในการส่งคืนแก้ไข'));
                            return $this->redirect(['president-approve-result-documents', 'typeGroup' => $model->submissionType->submission_type_group_id, 'panelId' => $model->project->panel_id]);
                        }
                        $model->status = Submission::STATUS_SECRETARY_APPROVE_AGENDA;
                        $model->president_comment = $leaderComment;
                        if ($model->save(false)) {
                            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFO_STAFF_EDIT_RESULTDOC, $model->id);
                            $rejectedCount++;
                        }
                    }
                }

                EmailQueue::execSendMailCmd();
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว (อนุมัติ {approved} รายการ, ส่งคืน {rejected} รายการ)', [
                            'approved' => $approvedCount,
                            'rejected' => $rejectedCount,
                ]));
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', Yii::t('app', 'เกิดข้อผิดพลาด: ') . $e->getMessage());
            }

            return $this->redirect(['president-approve-result-documents', 'typeGroup' => $model->submissionType->submission_type_group_id, 'panelId' => $model->project->panel_id]);
        }

        // GET - render page
        $query = Submission::find()
                ->joinWith(['submissionType', 'project'])
                ->submissionTypeGroup($typeGroup)
                ->isDeleted(false)
                ->andWhere(['submission.status' => Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN]);

        if ($currentRole['role_id'] == Role::PRESIDENT) {
            $query->andWhere(['submission.president_person' => Yii::$app->user->identity->id]);
        }

        $allSubmissions = $query->orderBy(['submission.id' => SORT_ASC])->all();

        $acknowledgeSubmissions = [];
        $endorseSubmissions = [];

        foreach ($allSubmissions as $submission) {
            if ($submission->submissionType->resolution_label === SubmissionType::RES_ACKNOWLEDGE || ($submission->submissionType->resolution_label === SubmissionType::RES_ENDORSE && $submission->resolution != Submission::RESOLUTION_Y)) {
                $acknowledgeSubmissions[] = $submission;
            } elseif ($submission->submissionType->resolution_label === SubmissionType::RES_ENDORSE && $submission->resolution === Submission::RESOLUTION_Y) {
                $endorseSubmissions[] = $submission;
            }
        }

        // Rejected submissions (sent back for editing)
        $rejectedQuery = Submission::find()
                ->joinWith(['submissionType', 'project'])
                ->submissionTypeGroup($typeGroup)
                ->isDeleted(false)
                ->andWhere(['not', ['submission.president_comment' => null]])
                ->andWhere(['submission.status' => Submission::STATUS_SECRETARY_APPROVE_AGENDA])
                ->orderBy(['submission.updated_at' => SORT_DESC])
                ->limit(50);

         if ($currentRole['role_id'] == Role::PRESIDENT) {
            $query->andWhere(['submission.president_person' => Yii::$app->user->identity->id]);
        }

        $rejectedSubmissions = $rejectedQuery->all();

        return $this->render('president-approve-result-documents', [
                    'acknowledgeSubmissions' => $acknowledgeSubmissions,
                    'endorseSubmissions' => $endorseSubmissions,
                    'rejectedSubmissions' => $rejectedSubmissions,
                    'totalCount' => count($allSubmissions),
        ]);
    }

    public function actionPresidentResult($id, $reload = NULL) {
//        $reload = isset($reload) ? $reload : '#crud-datatable-submission-pjax';
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN;
        if (!isset($model->president_person)) {
            $model->president_person = $model->project->panel->chairman->user_id;
        }

        $redirect = \yii\helpers\Url::to(['project-submission', 'submissionId' => $model->id]);

        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถจัดการข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceReload' => $reload,
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
            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFO_PRESIDENT_RESULTDOC, $model->id);
            EmailQueue::execSendMailCmd();
            return [
                'content' => '<div class="alert alert-success dark">' . Yii::t('app', "บันทึกเรียบร้อยแล้ว") . '</div><script>window.location = "' . $redirect . '";</script>',
                'forceReload' => '#submission-status-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionUploadResult($id) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $request = Yii::$app->request;
        $tran = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);



        if (!isset($model->send_to_crec)) {
            $model->send_to_crec = 0;
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', "แจ้งการ Upload เอกสาร"),
                    'content' => $this->renderAjax('upload-result', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg  pull-right', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                try {
                    if (empty($model->certified_date) && $model->resolution == Submission::RESOLUTION_Y && $model->submissionType->resolution_label == SubmissionType::RES_ENDORSE) {
                        return [
                            'title' => Yii::t('app', 'Upload เอกสารแจ้งผล'),
                            'size' => 'large',
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                'error' => Yii::t('app', "กรุณาระบุวันที่รับรอง"),
                            ]) . '</div>',
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
//                        Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "กรุณาระบุวันที่รับรอง"));
                    }
                    if (empty($model->expire_at) && $model->resolution == Submission::RESOLUTION_Y && $model->submissionType->resolution_label == SubmissionType::RES_ENDORSE) {
                        return [
                            'title' => Yii::t('app', 'Upload เอกสารแจ้งผล'),
                            'size' => 'large',
                            'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                'error' => Yii::t('app', "กรุณาระบุวันที่หมดอายุรับรอง"),
                            ]) . '</div>',
                            'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
//                        Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "กรุณาระบุวันที่หมดอายุรับรอง"));
                    }
                    $model->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
                    if ($model->resolution == Submission::RESOLUTION_Y && isset($model->refSubmission->meetingAgenda)) {
                        $project = $model->project;
                        if (isset($model->refSubmission->meetingAgenda->agenda->project_active)) {
                            $project->is_active = $model->refSubmission->meetingAgenda->agenda->project_active;
                            if ($model->refSubmission->meetingAgenda->agenda->project_active == 0) {
                                $project->is_closed = 1;
                            }
                        }
                        $project->save(FALSE);
                    }

                    if (!$model->save()) {

                        Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                                    'error' => \Yii::$app->util->errorSummary($model),
                        ]));
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                            'forceReload' => '#submission-status-pjax',
                            'content' => $this->renderAjax('@app/views/widgets/_alert'),
                            'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                        ];
                    }
                    //            if ($model->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
                    if ($model->is_submit_by_api) {
                        if ($model->isFromCrec() && $model->resolution == Submission::RESOLUTION_Y) {
                            Yii::warning($model->crec_leader_name, 'upload-result-before');
                            $result = Crec::updateResult($model);
                            Yii::warning(VarDumper::dumpAsString($result), 'committee-assessed-after');
                            if ($result['error']) {
                                throw new Exception($result['message']);
                            }
                        }
                    } else {
                        if ($model->send_to_crec) {
                            Yii::warning($model->crec_leader_name, 'before-create-continue-to-crec');
                            $result = Crec::createContinueEc($model);
                            Yii::warning(VarDumper::dumpAsString($result), 'after-create-continue-to-crec');

                            if ($result['error']) {
                                // throw new Exception($result['message']);
                                $tran->rollBack();
                                Yii::$app->response->format = Response::FORMAT_JSON;
                                return [
                                    'title' => Yii::t('app', 'Upload เอกสารแจ้งผล'),
                                    'size' => 'large',
                                    'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                                        'error' => $result['error']['message'],
                                    ]) . '</div>',
                                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                ];
                            }
                            $preSub = $model->getPreviousSubmission();
                            if (isset($preSub)) {
                                $model->crec_leader_name = $preSub->crec_leader_name;
                                $model->crec_leader_name_eng = $preSub->crec_leader_name_eng;
                                $model->crec_leader_site_name = $preSub->crec_leader_site_name;
                                $model->crec_leader_site_name_eng = $preSub->crec_leader_site_name_eng;
                                $model->crec_leader_org_name = $preSub->crec_leader_org_name;
                                $model->crec_leader_org_name_eng = $preSub->crec_leader_org_name_eng;
                                $model->save(false);
                            }
                        }
                    }
                    $tran->commit();
                    EmailQueue::addQueue(EmailQueue::TYPE_INFO_RESULT_PROJECTLEADER, $model->id);

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => Yii::t('app', 'ยืนยันการอัพโหลดหนังสือแจ้งผล'),
                        'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'ยืนยันการอัพโหลดหนังสือแจ้งผลเรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
                    ];
                } catch (Throwable $ex) {
                    $tran->rollBack();
                    Yii::error($ex->getMessage(), 'upload-result-throwable');
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => Yii::t('app', 'Upload เอกสารแจ้งผล'),
                        'size' => 'large',
                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                            'error' => $ex->getMessage(),
                        ]) . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', "แจ้งการ Upload เอกสาร"),
                    'content' => $this->renderAjax('upload-result', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        }
    }

    public function actionAcknowledgeCrecResult($id) {
        $currentRole = \Yii::$app->session->get('currentRole');
        $request = Yii::$app->request;
        $tran = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => Yii::t('app', "รับทราบผลประเมินจาก CREC"),
                    'content' => $this->renderAjax('acknowledge-crec-result', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg  pull-right', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                try {
                    $model->acknowledged_crec_result = Submission::CREC_RESULT_ACKNOWLEDGED;

                    if (!$model->save()) {

                        Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถบันทึกข้อมูลได้ {error}", [
                                    'error' => \Yii::$app->util->errorSummary($model),
                        ]));
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => Yii::t('app', 'เกิดข้อผิดพลาด'),
                            'forceReload' => '#submission-status-pjax',
                            'content' => $this->renderAjax('@app/views/widgets/_alert'),
                            'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                        ];
                    }

                    $tran->commit();
                    if ($model->notify_crec_result_leader) {
                        EmailQueue::addQueue(EmailQueue::TYPE_NOTIFY_CREC_RESULT_LEADER, $model->id);
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => Yii::t('app', 'รับทราบผลประเมินจาก CREC'),
                        'content' => "<div class='alert alert-success dark'>" . Yii::t('app', 'ยืนยันการรับทราบผลประเมินจาก CREC เรียบร้อยแล้ว') . "<script>window.location = window.location</script></div>",
                    ];
                } catch (Throwable $ex) {
                    $tran->rollBack();
                    Yii::error($ex->getMessage(), 'upload-result-throwable');
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => Yii::t('app', 'รับทราบผลประเมินจาก CREC'),
                        'size' => 'large',
                        'content' => '<div class="alert alert-danger dark">' . Yii::t('app', 'เกิดข้อผิดพลาด {error}', [
                            'error' => $ex->getMessage(),
                        ]) . '</div>',
                        'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => Yii::t('app', "รับทราบผลประเมินจาก CREC"),
                    'content' => $this->renderAjax('acknowledge-crec-result', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            }
        }
    }

    /**
     * Delete multiple existing Submission model.
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
     * Finds the Submission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Submission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Submission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubmissionContinue() {
        $id = 1;
//$this->layout = 'register';
        $request = Yii::$app->request;
        $regForm = new Project();
//$profile = new Person();

        $sdocSearch = new DocumentSubmissionTypeSearch();
        $sdocSearch->deleted = 0;
        $sdocSearch->submission_type_id = 1;
        $sdocProvider = $sdocSearch->search(Yii::$app->request->queryParams);

        $presearcherSearch = new ProjectResearcherSearch();
        $presearcherSearch->deleted = 0;
        $presearcherSearch->project_id = $id;
        $presearcherProvider = $presearcherSearch->search(Yii::$app->request->queryParams);

        $steps = self::contSteps();
        $step = null !== $request->post('step') ? $request->post('step') : self::CONT_STEP1;
        $regForm->load($request->post());
// $profile->load($request->post());
//        if (is_int($company->id)) {
//            $company = Company::findOne($company->id);
//            $company->load($request->post());
//        }

        if ($step == self::CONT_STEP1) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [];
            }
            if (null !== $request->post('next-step')) {
                $step++;
            }
        } else if ($step == self::CONT_STEP2) {

//            \yii\helpers\VarDumper::dump($profile->attributes);
            if (null !== $request->post('previous-step')) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step--;
            } else {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }

                if (null !== $request->post('next-step')) {
                    $step++;
                }
            }
        } else if ($step == self::CONT_STEP3) {
//            \yii\helpers\VarDumper::dump($company->attributes);
//            \yii\helpers\VarDumper::dump($profile->attributes);
            if (null !== $request->post('previous-step')) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step--;
            } else {

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }

                if (null !== $request->post('next-step')) {
//                    \yii\helpers\VarDumper::dump($company->attributes);
//                    \yii\helpers\VarDumper::dump(is_int($company->id));

                    $step++;
                }
            }
        } else if ($step == self::CONT_STEP4) {
            if (null !== $request->post('previous-step')) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step--;
            } else {
                if (null !== $request->post('next-step')) {
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [];
                    }
// $step = self::REGISTER_STEP4 + 1;


                    return $this->redirect(['submission-complete']);
                }
            }
        }
        return $this->render('submission-continue', [
                    'regForm' => $regForm,
                    'sdocSearch' => $sdocSearch,
                    'sdocProvider' => $sdocProvider,
                    'presearcherSearch' => $presearcherSearch,
                    'presearcherProvider' => $presearcherProvider,
                    'steps' => $steps,
                    'step' => $step,
                    'id' => $id,
        ]);
    }

    public function actionSubmissionComplete() {
// $this->layout = 'register';
        return $this->render('submission-complete');
    }

    public function actionNew($refSubmissionId = NULL, $submissionId = NULL, $step = self::NEW_STEP1) {
        $request = Yii::$app->request;
        $currentRole = \Yii::$app->session->get('currentRole');
        $submissionTypes = ArrayHelper::map(SubmissionType::find()->internal(FALSE)->group(SubmissionTypeGroup::GROUP_NEW)->all(), 'id', 'i18nName');
//        $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->internal(FALSE)->group(SubmissionTypeGroup::GROUP_NEW)->all(), 'id', 'i18nName');
//        $projectId = $request->post('projectId');
//        \yii\helpers\VarDumper::dump($projectId);
//        \yii\helpers\VarDumper::dump($request->post());


        if (isset($submissionId)) {
            $submission = Submission::findOne($submissionId);
            if (!isset($submission)) {
                $submission = new Submission();
                $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                $submission->ref_submission_id = $refSubmissionId;
                $project = new Project();
//                $project->funding_source_id = $submission->fundingSourceId;
            } else {
                $project = $submission->project;
                $submission->correspondence_at = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y-m-d') : null;
//                $project->funding_source_id = $submission->fundingSourceId;
            }
        } else {
            $submission = new Submission();
            $submission->status = Submission::STATUS_PENDING_SUBMISSION;
            $project = new Project();
            $submission->ref_submission_id = $refSubmissionId;
//            $project->funding_source_id = $submission->fundingSourceId;
        }
        if (isset($submission->ref_submission_id)) {
            $refSubmission = $submission->refSubmission;
            $submission = Submission::find()->isDeleted(FALSE)->refSubmission($submission->ref_submission_id)->one();
            $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->resolution($refSubmission->resolution)->group(SubmissionTypeGroup::GROUP_NEW)->all(), 'id', 'name');

            if (!isset($submission)) {
                $submission = new Submission();
                $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                $submission->ref_submission_id = $refSubmissionId;
                $submission->project_id = $refSubmission->project_id;
                $submission->responsible_person = $refSubmission->responsible_person;
                if (isset($refSubmission->project_coordinator_id)) {
                    $submission->project_coordinator_id = $refSubmission->project_coordinator_id;
                } else if (isset($refSubmission->project_coordinator_2nd_id)) {
                    $submission->project_coordinator_id = $refSubmission->project_coordinator_2nd_id;
                } else if (isset($refSubmission->project_coordinator_3rd_id)) {
                    $submission->project_coordinator_id = $refSubmission->project_coordinator_3rd_id;
                } else if (isset($refSubmission->project_viewer_id)) {
                    $submission->project_coordinator_id = $refSubmission->project_viewer_id;
                }
                if ($refSubmission->resolution == Submission::RESOLUTION_C) {
                    $submission->submission_type_id = 5;
                } elseif ($refSubmission->resolution == Submission::RESOLUTION_R) {
                    $submission->submission_type_id = 6;
                }
                $submission->submission_type_id = array_keys($submissionTypes)[0];
                $submission->save(FALSE);
                $oldDocs = $refSubmission->getSubmissionDocuments()->isDeleted(FALSE)->all();
                foreach ($oldDocs as $oldDoc) {
                    $newDoc = new SubmissionDocument();
                    $newDoc->name = $oldDoc->name;
                    $newDoc->name_eng = $oldDoc->name_eng;
                    $newDoc->file_name = $oldDoc->file_name;
                    $newDoc->project_id = $oldDoc->project_id;
                    $newDoc->document_id = $oldDoc->document_id;
                    $newDoc->submission_id = $submission->id;
                    $newDoc->version = $oldDoc->version;
                    $newDoc->version_at = $oldDoc->version_at;
                    $newDoc->save(FALSE);
                    \yii\helpers\FileHelper::createDirectory($newDoc->path);
                    copy($oldDoc->filePath, $newDoc->filePath);
                }
                $oldResearchers = $refSubmission->getProjectResearchers()->isDeleted(FALSE)->all();
                foreach ($oldResearchers as $or) {
                    $newR = new ProjectResearcher();
                    $newR->project_id = $or->project_id;
                    $newR->submission_id = $submission->id;
                    $newR->person_id = $or->person_id;
                    $newR->is_leader = $or->is_leader;
                    $newR->cv_file = $or->person->cv_file;
                    $newR->acknowledge_status = $or->acknowledge_status;
                    $newR->save(FALSE);
                }
                $oldConsultants = $refSubmission->getProjectConsultants()->isDeleted(FALSE)->all();
                foreach ($oldConsultants as $oc) {
                    $newC = new \app\models\ProjectConsultant();
                    $newC->project_id = $oc->project_id;
                    $newC->submission_id = $submission->id;
                    $newC->person_id = $oc->person_id;
//                    $newC->is_leader = $oc->is_leader;
                    $newC->cv_file = $oc->person->cv_file;
                    $newC->acknowledge_status = $oc->acknowledge_status;
                    $newC->save(FALSE);
                }
                $oldCoi = $refSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                foreach ($oldCoi as $ocoi) {
                    $newCoi = new \app\models\SubmissionCoiPerson();
                    $newCoi->submission_id = $submission->id;
                    $newCoi->person_id = $ocoi->person_id;
                    $newCoi->save(FALSE);
                }
            }
            $project = $refSubmission->project;
//            $project->funding_source_id = $submission->fundingSourceId;
        }
        if ($submission->status > Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER) {
            throw new \yii\web\HttpException(400, Yii::t('app', 'ไม่สามารถดำเนินการได้'));
        }

        if (isset($submissionId)) {
            $researcheCheck = ProjectResearcher::find()->submission($submissionId)->isDeleted(FALSE)->count();
            if ($submission->deleted == 0) {
                if ($researcheCheck > 0) {
                    if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
                        throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
                    }
                }
            } else {
                if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
                    throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
                }
            }
        }
//        \yii\helpers\VarDumper::dump($isNewProject);
//        \yii\helpers\VarDumper::dump($project->attributes);
        $steps = self::newSteps();
//        $step = null !== $request->post('step') ? $request->post('step') : $step;
        $submission->scenario = Submission::SCENARIO_NEWSUBMISSION;
        $project->load($request->post());
        $submission->load($request->post());
        $submissionDocs = [];

        $researcherSearch = new ProjectResearcherSearch();
        $researcherSearch->deleted = 0;
        $researcherSearch->project_id = $project->id;
        $researcherSearch->submission_id = $submission->id;

        $consultantSearch = new \app\models\ProjectConsultantSearch();
        $consultantSearch->deleted = 0;
        $consultantSearch->project_id = $project->id;
        $consultantSearch->submission_id = $submission->id;

        $submissionDoc = new SubmissionDocument();
        $previousStep = $request->post('previousStep');
        $nextStep = $request->post('nextStep');

        if ($step == self::NEW_STEP1) {
            if (isset($refSubmission->id)) {
                if ($refSubmission->resolution == Submission::RESOLUTION_C) {
                    $submission->submission_type_id = 5;
                } elseif ($refSubmission->resolution == Submission::RESOLUTION_R) {
                    $submission->submission_type_id = 6;
                }
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($project, $submission);
            }
            if (isset($nextStep)) {
                $step = $nextStep;
//                $isNewProject = empty($project->id);
                if ($currentRole['role_id'] == Role::COORDINATOR) {
                    $project->project_coordinator_id = \Yii::$app->user->identity->id;
                }
//                $project->funding_source_id = $submission->fundingSourceId;
                $project->save(FALSE);
                $submission->project_id = $project->id;
                if ($currentRole['role_id'] == Role::COORDINATOR) {
                    $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                }
                $submission->status = isset($submission->status) ? $submission->status : Submission::STATUS_PENDING_SUBMISSION;
                $submission->save(FALSE);
                if (!isset($submission->projectLeader) and $currentRole['role_id'] != Role::COORDINATOR) {
                    $pr = new ProjectResearcher();
                    $pr->project_id = $project->id;
                    $pr->submission_id = $submission->id;
                    $pr->person_id = \Yii::$app->user->identity->person->id;
                    $pr->is_leader = 1;
                    $pr->mail_sent = 1;
                    $pr->cv_file = \Yii::$app->user->identity->person->cv_file;
                    $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                    $pr->acknowledge_at = date('Y-m-d H:i:s');
                    $pr->save();
                    $pr->addCoi();
                }
//                $researcherSearch->project_id = $project->id;
//                $researcherSearch->submission_id = $submission->id;
//                $researcherProvider = $researcherSearch->search([]);
//                if (isset($submission->previousSubmission) && $researcherProvider->totalCount == 0) {
//                    $prs = ProjectResearcher::find()->isDeleted(FALSE)->project($project->id)->submission($submission->id)->acknowledgeStatus(ProjectResearcher::STATUS_ACCEPTED)->all();
//                    foreach ($prs as $pr) {
//                        $newPr = new ProjectResearcher();
//                        $newPr->project_id = $pr->project_id;
//                        $newPr->submission_id = $pr->submission_id;
//                        $newPr->person_id = $pr->person_id;
//                        $newPr->is_leader = $pr->person_id;
//                        $newPr->acknowledge_status = $pr->acknowledge_status;
//                        $newPr->acknowledge_by = $pr->acknowledge_by;
//                        $newPr->acknowledge_at = $pr->acknowledge_at;
//                        $newPr->save(FALSE);
//                    }
//                }

                return $this->redirect(Url::to(['submission/new', 'submissionId' => $submission->id, 'step' => $step], 'http'));
//                $researcherSearch->project_id = $project->id;
//                $submissionDocs = $this->getSubmissionDocs($submission);
            }
        } else if ($step == self::NEW_STEP2) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $allReqDocs = $submission->submissionType->getRequireDocumentSubmissTypes();
                    $currentReqDocs = $submission->getRequireSubmissionDocuments();
                    if (count($allReqDocs) != count($currentReqDocs)) {
                        return ['submissiondocument-project_id' => ['กรุณาอับโหลดไฟล์เอกสารให้ครบถ้วน']];
                    } else {
                        return [];
                    }
                }
                $researcherProvider = $researcherSearch->search([]);
                $lastSub = $submission->previousSubmission;
                if (isset($lastSub)) {

                    $submission->project_coordinator_id = $lastSub->project_coordinator_id;
                    $submission->save();

                    $oldCoi = $submission->previousSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                    foreach ($oldCoi as $ocoi) {
                        $newCoi = new \app\models\SubmissionCoiPerson();
                        $newCoi->submission_id = $submission->id;
                        $newCoi->person_id = $ocoi->person_id;
                        $newCoi->save(FALSE);
                    }
                }
                if (isset($lastSub) && $researcherProvider->totalCount == 0) {
                    $oldResearchers = $lastSub->getProjectResearchers()->isDeleted(FALSE)->all();
                    foreach ($oldResearchers as $or) {

                        $newR = new ProjectResearcher();
                        $newR->project_id = $or->project_id;
                        $newR->submission_id = $submission->id;
                        $newR->person_id = $or->person_id;
                        $newR->is_leader = $or->is_leader;
                        $newR->cv_file = $or->person->cv_file;
                        $newR->acknowledge_status = $or->acknowledge_status;
                        $newR->save(FALSE);
                    }
                }
                $consultantProvider = $consultantSearch->search([]);
//                $lastSubcon = $submission->previousSubmission;
                if (isset($lastSub) && $consultantProvider->totalCount == 0) {
                    $oldConsultants = $lastSub->getProjectConsultants()->isDeleted(FALSE)->all();
                    foreach ($oldConsultants as $oc) {
                        $newC = new \app\models\ProjectConsultant();
                        $newC->project_id = $oc->project_id;
                        $newC->submission_id = $submission->id;
                        $newC->person_id = $oc->person_id;
//                        $newC->is_leader = $oc->is_leader;
                        $newC->cv_file = $oc->person->cv_file;
                        $newC->acknowledge_status = $oc->acknowledge_status;
                        $newC->save(FALSE);
                    }
                }




                $step = $nextStep;
//                $step++;
            }
//            \yii\helpers\VarDumper::dump($profile->attributes);
//            if ($request->isAjax && null !== $request->post('ajax')) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return [];
//            }
//            \yii\helpers\VarDumper::dump($step);
//            exit;
        } else if ($step == self::NEW_STEP3) {
//            \yii\helpers\VarDumper::dump($company->attributes);
//            \yii\helpers\VarDumper::dump($profile->attributes);
//            if ($request->isAjax && null !== $request->post('ajax')) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return ['Submission_document-id' => ['ไม่ผ่าน']];
//            }
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $researcherProvider = $researcherSearch->search($request->queryParams);
                    if (!isset($submission->projectLeader)) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุหัวหน้าโครงการวิจัย']];
                    }
                    if ($researcherProvider->count == 0) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุผู้วิจัย']];
                    } else {
                        return [];
                    }
                }
                $consultantProvider = $consultantSearch->search([]);
                $lastSub = $submission->previousSubmission;
                if (isset($lastSub) && $consultantProvider->totalCount == 0) {
                    $oldResearchers = $lastSub->getProjectConsultants()->isDeleted(FALSE)->all();
                    foreach ($oldResearchers as $or) {

                        $newR = new \app\models\ProjectConsultant();
                        $newR->project_id = $or->project_id;
                        $newR->submission_id = $submission->id;
                        $newR->person_id = $or->person_id;
                        $newR->cv_file = $or->person->cv_file;
                        $newR->acknowledge_status = $or->acknowledge_status;
                        $newR->save(FALSE);
                    }
                }

                $step = $nextStep;
            }
        } else if ($step == self::NEW_STEP4) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $consultantProvider = $consultantSearch->search($request->queryParams);
                    return [];
                }
                $step = $nextStep;
            }
        } else if ($step == self::NEW_STEP5) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
//                $step--;
            } else {
                if (null !== $nextStep) {
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [];
                    }
//                    $step = self::REGISTER_STEP4 + 1;
                    $pr = $submission->projectLeader;
                    $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($pr->id)->one();
                    if (!isset($spr)) {
                        $ppr = $pr->previousProjectResearcher;
                        $spr = new SubmissionProjectResearcher();
                        $spr->submission_id = $pr->submission_id;
                        $spr->project_researcher_id = $pr->id;
                        if (isset($ppr) && $pr->cv_file == $ppr->cv_file) {
                            $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                        }
                        $spr->save(FALSE);
                    }
                    $crs = $submission->projectCoResearchers;
                    foreach ($crs as $cr) {
                        $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($cr->id)->one();
                        if (!isset($spr)) {
                            $ppr = $cr->previousProjectResearcher;
                            $spr = new SubmissionProjectResearcher();
                            $spr->submission_id = $submission->id;
                            $spr->project_researcher_id = $cr->id;
                            if (isset($ppr) && $cr->cv_file == $ppr->cv_file) {
                                $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                            }
                            $spr->save(FALSE);
                        }
                    }

                    $css = $submission->projectConsultants;
                    foreach ($css as $cs) {
                        $spc = \app\models\SubmissionProjectConsultant::find()->isDeleted(FALSE)->submission($submission->id)->projectConsultant($cs->id)->one();
                        if (!isset($spc)) {
                            $ppc = $cs->previousProjectConsultant;
                            $spc = new \app\models\SubmissionProjectConsultant();
                            $spc->submission_id = $submission->id;
                            $spc->project_consultant_id = $cs->id;
                            if (isset($ppc) && $cs->cv_file == $ppc->cv_file) {
                                $spc->status = \app\models\SubmissionProjectConsultant::STATUS_PASS;
                            }
                            $spc->save(FALSE);
                        }
                    }



                    if ($currentRole['role_id'] == Role::COORDINATOR) {
                        $submission->status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
//                        $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECTLEADER_NEW_SUBMISSION, $submission->id);
                    } else {
                        $submission->status = Submission::STATUS_SUBMITTED;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                    }
                    $submission->save(FALSE);
                    \app\models\Alert::addNewSubmission($submission);

//                    $submission->notifyCoResearcher();
                    return $this->redirect(['submission-complete']);
                }
            }
        }
        $researcherProvider = $researcherSearch->search($request->queryParams);
        $consultantProvider = $consultantSearch->search($request->queryParams);
        $submissionDocs = $submission->getSubmissionDocs();
        $subDocProvider = new ArrayDataProvider([
            'allModels' => $submissionDocs,
        ]);
        return $this->render('new', [
                    'submissionTypes' => $submissionTypes,
                    'project' => $project,
                    'submission' => $submission,
                    'consultantSearch' => $consultantSearch,
                    'consultantProvider' => $consultantProvider,
                    'researcherSearch' => $researcherSearch,
                    'researcherProvider' => $researcherProvider,
                    'submissionDocs' => $submissionDocs,
                    'subDocProvider' => $subDocProvider,
                    'submissionDoc' => $submissionDoc,
                    'steps' => $steps,
                    'step' => $step,
        ]);
    }

    public function actionContinue($refSubmissionId = NULL, $submissionId = NULL, $projectId = null, $step = self::CONT_STEP1) {
        $request = Yii::$app->request;
        $currentRole = \Yii::$app->session->get('currentRole');

        $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->internal(FALSE)->group(SubmissionTypeGroup::GROUP_CONT)->all(), 'id', 'name');
//        $projectId = $request->post('projectId');
//        \yii\helpers\VarDumper::dump($projectId);
//        \yii\helpers\VarDumper::dump($request->post());


        $submission = Submission::findOne($submissionId);

        if (isset($submissionId)) {
            $researcheCheck = ProjectResearcher::find()->submission($submissionId)->isDeleted(FALSE)->count();
            if ($submission->deleted == 0) {
                if ($researcheCheck > 0) {
                    if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
                        throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
                    }
                }
            } else {
                if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submissionId)) {
                    throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
                }
            }
        }
        if (!isset($submission)) {
            $submission = new Submission();
            $submission->project_id = $projectId;
            if ($currentRole['role_id'] == Role::COORDINATOR) {
                $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                $submission->submission_by = \Yii::$app->user->identity->id;
            }
            $submission->status = Submission::STATUS_PENDING_SUBMISSION;
            $submission->ref_submission_id = $refSubmissionId;
        }

        $submission->correspondence_at = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y-m-d') : null;

        $researcherSearch = new ProjectResearcherSearch();
        $researcherSearch->deleted = 0;
        $researcherSearch->project_id = $submission->project_id;
        $researcherSearch->submission_id = $submission->id;

        $consultantSearch = new \app\models\ProjectConsultantSearch();
        $consultantSearch->deleted = 0;
        $consultantSearch->project_id = $submission->project_id;
        $consultantSearch->submission_id = $submission->id;

        $subVolSearch = new SubmissionVolunteerSearch();
        $subVolSearch->deleted = 0;
        $subVolSearch->submissionId = $submission->id;
        $subVolSearch->projectId = isset($projectId) ? $projectId : (isset($submission) ? $submission->project_id : null);
        if (!isset($subVolSearch->projectId)) {
            $subVolSearch->id = -1;
        }

        if (isset($submission->ref_submission_id)) {
            $refSubmission = $submission->refSubmission;
            $submission = Submission::find()->isDeleted(FALSE)->refSubmission($submission->ref_submission_id)->one();
            $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->andWhere(['id' => $refSubmission->submission_type_id])->all(), 'id', 'name');
//            $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->resolution($refSubmission->resolution)->group(SubmissionTypeGroup::GROUP_CONT)->all(), 'id', 'name');
//            \yii\helpers\VarDumper::dump(array_keys($submissionTypes)[0]);
//            exit;
            if (!isset($submission)) {
                $submission = new Submission();
                $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                $submission->ref_submission_id = $refSubmissionId;
                $submission->project_id = $refSubmission->project_id;
                $submission->responsible_person = $refSubmission->responsible_person;
                if (isset($refSubmission->project_coordinator_id)) {
                    $submission->project_coordinator_id = $refSubmission->project_coordinator_id;
                }
                if (isset($refSubmission->project_coordinator_2nd_id)) {
                    $submission->project_coordinator_2nd_id = $refSubmission->project_coordinator_2nd_id;
                }
                if (isset($refSubmission->project_coordinator_3rd_id)) {
                    $submission->project_coordinator_3rd_id = $refSubmission->project_coordinator_3rd_id;
                }
                if (isset($refSubmission->project_viewer_id)) {
                    $submission->project_viewer_id = $refSubmission->project_viewer_id;
                }
                $submission->submission_type_id = array_keys($submissionTypes)[0];
                $submission->save(FALSE);

                $newSvSearch = new SubmissionVolunteerSearch();
                $newSvSearch->deleted = 0;
                $newSvSearch->submission_id = $submission->id;
                $newSvProvider = $newSvSearch->search([]);
                if ($newSvProvider->totalCount == 0) {
                    $oldSvs = $refSubmission->getSubmissionVolunteers()->isDeleted(false)->all();
                    foreach ($oldSvs as $oldSv) {
                        $newSv = new SubmissionVolunteer();
                        $newSv->submission_id = $submission->id;
                        $newSv->volunteer_id = $oldSv->volunteer_id;
                        $newSv->type = $oldSv->type;
                        $newSv->follow_up_no = $oldSv->follow_up_no;
                        $newSv->save(false);
                    }
                }
                $newSeSearch = new SubmissionEventSearch();
                $newSeSearch->deleted = 0;
                $newSeSearch->submission_id = $submission->id;
                $newSeProvider = $newSeSearch->search([]);
                if ($newSeProvider->totalCount == 0) {
                    $oldSes = $refSubmission->getSubmissionEvents()->isDeleted(false)->all();
                    foreach ($oldSes as $oldSe) {
                        $newSe = new SubmissionEventSearch();
                        $newSe->submission_id = $submission->id;
                        $newSe->event_no = $oldSe->event_no;
                        $newSe->code = $oldSe->code;
                        $newSe->meeting_violation_type = $oldSe->meeting_violation_type;
                        $newSe->save(false);
                    }
                }

                $oldDocs = $refSubmission->getSubmissionDocuments()->isDeleted(FALSE)->all();
                foreach ($oldDocs as $oldDoc) {
                    $newDoc = new SubmissionDocument();
                    $newDoc->name = $oldDoc->name;
                    $newDoc->file_name = $oldDoc->file_name;
                    $newDoc->project_id = $oldDoc->project_id;
                    $newDoc->document_id = $oldDoc->document_id;
                    $newDoc->submission_id = $submission->id;
                    $newDoc->version = $oldDoc->version;
                    $newDoc->version_at = $oldDoc->version_at;
                    $newDoc->volunteer_id = $oldDoc->volunteer_id;
                    if (isset($oldDoc->submission_event_id)) {
                        $se = SubmissionEvent::find()->isDeleted(false)->submission($submission->id)
                                        ->eventNo($oldDoc->event_no)->one();
                        $newDoc->submission_event_id = isset($se) ? $se->id : null;
                    }
                    $newDoc->save(FALSE);
                    \yii\helpers\FileHelper::createDirectory($newDoc->path);
                    copy($oldDoc->filePath, $newDoc->filePath);
                }

                $researcherSearch->project_id = $submission->project_id;
                $researcherSearch->submission_id = $submission->id;
                $researcherProvider = $researcherSearch->search([]);

                $consultantSearch->project_id = $submission->project_id;
                $consultantSearch->submission_id = $submission->id;
                $consultantProvider = $consultantSearch->search([]);

                if ($researcherProvider->totalCount == 0) {

                    $oldResearchers = $refSubmission->getProjectResearchers()->isDeleted(FALSE)->all();
                    foreach ($oldResearchers as $or) {
                        $newR = new ProjectResearcher();
                        $newR->project_id = $or->project_id;
                        $newR->submission_id = $submission->id;
                        $newR->person_id = $or->person_id;
                        $newR->is_leader = $or->is_leader;
                        $newR->cv_file = $or->person->cv_file;
                        $newR->acknowledge_status = $or->acknowledge_status;
                        $newR->save(FALSE);
                    }
                }

                if ($consultantProvider->totalCount == 0) {

                    $oldConsultants = $refSubmission->getProjectConsultants()->isDeleted(FALSE)->all();
                    foreach ($oldConsultants as $oc) {
                        $newC = new \app\models\ProjectConsultant();
                        $newC->project_id = $oc->project_id;
                        $newC->submission_id = $submission->id;
                        $newC->person_id = $oc->person_id;
                        $newC->cv_file = $oc->person->cv_file;
                        $newC->acknowledge_status = $oc->acknowledge_status;
                        $newC->save(FALSE);
                    }
                }

//                $oldCoi = $refSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
//                foreach ($oldCoi as $ocoi) {
//                    $newCoi = new \app\models\SubmissionCoiPerson();
//                    $newCoi->submission_id = $submission->id;
//                    $newCoi->person_id = $ocoi->person_id;
//                    $newCoi->save(FALSE);
//                }
            }
            $project = $refSubmission->project;
        }
//        $lastSub = Submission::find()->isDeleted(FALSE)->project($submission->project_id)->andWhere(['<', 'id', $submission->id])->orderBy('id DESC')->one();
//        if (isset($lastSub)) {
//            $submission->responsible_person = $lastSub->responsible_person;
//            $submission->responsible_date = $lastSub->responsible_date;
//        }
        $submission->scenario = Submission::SCENARIO_CONTSUBMISSION;
//        \yii\helpers\VarDumper::dump($isNewProject);
//        \yii\helpers\VarDumper::dump($project->attributes);
        $steps = self::contSteps();
//        $step = null !== $request->post('step') ? $request->post('step') : $step;

        $submission->load($request->post());

        $numbers = SubmissionTypeVolunteerNumber::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->indexBy('volunteer_number_id')->all();
        $currentSubNumbers = ArrayHelper::index($submission->getSubmissionVolunteerNumbers()->isDeleted(FALSE)->all(), 'volunteer_number_id');
        $submissionNumbers = [];
//        \yii\helpers\VarDumper::dump($currentSubNumbers, 4, TRUE);
        foreach ($numbers as $number) {
            if (isset($currentSubNumbers[$number->volunteer_number_id])) {
                $submissionNumbers[] = $currentSubNumbers[$number->volunteer_number_id];
            } else {
                $submissionNumber = new SubmissionVolunteerNumber();
                $submissionNumber->project_id = $submission->project_id;
                $submissionNumber->submission_id = $submission->id;
                $submissionNumber->volunteer_number_id = $number->volunteer_number_id;
//                \yii\helpers\VarDumper::dump($submissionNumber->attributes);
//                \yii\helpers\VarDumper::dump($submission->project->attributes);
//                exit;
                $submissionNumber->value = $submissionNumber->getLastValue();
                $submissionNumbers[] = $submissionNumber;
            }
        }

//        \yii\helpers\VarDumper::dump($submissionNumbers, 4, TRUE);
//        exit;
        Model::loadMultiple($submissionNumbers, $request->post());

        $submissionDocs = [];

        $submissionDoc = new SubmissionDocument();
        $previousStep = $request->post('previousStep');
        $nextStep = $request->post('nextStep');

        if ($step == self::CONT_STEP1) {

            if ($request->isAjax && isset($nextStep)) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $res = ActiveForm::validate($submission);
                if ($submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
                    $count = SubmissionVolunteer::find()->joinWith(['volunteer'])->isDeleted(false)->submissionOrNull($submission->id)
                                    ->projectId($submission->project_id)->count();
                    if ($count == 0) {
                        $res['submissiondocument-project_id'] = [Yii::t('app', 'กรุณาเพิ่มอาสาสมัคร')];
                    }
                } else if ($submission->submission_type_id == SubmissionType::TYPE_DEVIATION) {
                    if (empty($submission->events)) {
                        $res['submissiondocument-project_id'] = [Yii::t('app', 'กรุณาระบุจำนวนเหตุการณ์')];
                    }
                }
                return $res;
            }
            if (isset($nextStep)) {
                $step = $nextStep;
//                $isNewProject = empty($project->id);
//                $submission->project_id = $project->id;
                $status = isset($submission->status) ? $submission->status : Submission::STATUS_PENDING_SUBMISSION;

                if ($currentRole['role_id'] == Role::COORDINATOR) {
                    $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                    $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                    $submission->submission_by = \Yii::$app->user->identity->id;
                } else {
                    $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                    $submission->submission_by = \Yii::$app->user->identity->id;
                }
                $submission->save(FALSE);

                $subVols = SubmissionVolunteer::find()->joinWith(['volunteer'])->isDeleted(false)->submissionOrNull($submission->id)
                                ->projectId($submission->project_id)->all();
                foreach ($subVols as $subVol) {
                    $subVol->submission_id = $submission->id;
                    $subVol->save();
                }

                $subEvents = $submission->getSubmissionEvents()->isDeleted(false)->orderBy('id')->all();
                if ($submission->events > count($subEvents)) {
                    $diff = $submission->events - count($subEvents);
                    $startEvNo = count($subEvents) + 1;
                    for ($i = 0; $i < $diff; $i++) {
                        $subEv = new SubmissionEvent();
                        $subEv->submission_id = $submission->id;
                        $subEv->event_no = $startEvNo;
                        $subEv->save();
                        $startEvNo++;
                    }
                } else if ($submission->events < count($subEvents)) {
                    $diff = count($subEvents) - $submission->events;
                    $i = count($subEvents) - 1;
                    $n = $i - $diff;
                    for ($i; $i > $n; $i--) {
                        $subEv = $subEvents[$i];
                        $subEv->deleted = 1;
                        $subEv->save();
                    }
                }
                foreach ($subVols as $subVol) {
                    $subVol->submission_id = $submission->id;
                    $subVol->save();
                }

                $researcherSearch->project_id = $submission->project->id;
                $researcherSearch->submission_id = $submission->id;
                $researcherProvider = $researcherSearch->search([]);

//                $firstEndorseSubs = $submission->project->getFirstEndorsedSubmission();
//                if (isset($firstEndorseSubs)) {
//                    if (isset($firstEndorseSubs->project_coordinator_id)) {
//                        $submission->project_coordinator_id = $firstEndorseSubs->project_coordinator_id;
//                    }  if (isset($firstEndorseSubs->project_coordinator_2nd_id)) {
//                        $submission->project_coordinator_2nd_id = $firstEndorseSubs->project_coordinator_2nd_id;
//                    }  if (isset($submission->previousSubmission->project_coordinator_3rd_id)) {
//                        $submission->project_coordinator_3rd_id = $firstEndorseSubs->project_coordinator_3rd_id;
//                    }  if (isset($firstEndorseSubs->project_viewer_id)) {
//                        $submission->project_viewer_id = $firstEndorseSubs->project_viewer_id;
//                    }
//
//                    $submission->save();
//                }

                if (isset($submission->previousSubmission)) {
//                    $submission->project_coordinator_id = $submission->previousSubmission->project_coordinator_id;
                    if (isset($submission->previousSubmission->project_coordinator_id)) {
                        $submission->project_coordinator_id = $submission->previousSubmission->project_coordinator_id;
                    }
                    if (isset($submission->previousSubmission->project_coordinator_2nd_id)) {
                        $submission->project_coordinator_2nd_id = $submission->previousSubmission->project_coordinator_2nd_id;
                    }
                    if (isset($submission->previousSubmission->project_coordinator_3rd_id)) {
                        $submission->project_coordinator_3rd_id = $submission->previousSubmission->project_coordinator_3rd_id;
                    }
                    if (isset($submission->previousSubmission->project_viewer_id)) {
                        $submission->project_viewer_id = $submission->previousSubmission->project_viewer_id;
                    }

                    $submission->save();

                    $coiCount = \app\models\SubmissionCoiPerson::find()->isDeleted(false)->submission($submission->id)->count();
                    if ($coiCount == 0) {
                        $oldCoi = $submission->previousSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                        foreach ($oldCoi as $ocoi) {
                            $coi = \app\models\SubmissionCoiPerson::find()->isDeleted(false)->submission($submission->id)->person($ocoi->person_id)->one();
                            if (!isset($coi)) {
                                $newCoi = new \app\models\SubmissionCoiPerson();
                                $newCoi->submission_id = $submission->id;
                                $newCoi->person_id = $ocoi->person_id;
                                $newCoi->save(FALSE);
                            }
                        }
                    }
                }
// VarDumper::dump(isset($submission->previousSubmission));
// VarDumper::dump($researcherProvider->totalCount);
// exit;
                if (isset($submission->previousSubmission) && $researcherProvider->totalCount == 0) {
// ดึงจาก amendment ที่ได้ Y ล่าสุด
                    $aSub = $submission->getLastAmendmentSubmission();
// VarDumper::dump(isset($aSub));
// exit;
                    if (isset($aSub)) {
                        $prs = ProjectResearcher::find()->isDeleted(FALSE)->project($submission->project->id)->submission($aSub->id)->acknowledgeStatus(ProjectResearcher::STATUS_ACCEPTED)->all();
                        foreach ($prs as $pr) {
                            $newPr = new ProjectResearcher();
                            $newPr->project_id = $pr->project_id;
                            $newPr->submission_id = $submission->id;
                            $newPr->person_id = $pr->person_id;
                            $newPr->is_leader = $pr->is_leader;
                            $newPr->acknowledge_status = $pr->acknowledge_status;
                            $newPr->acknowledge_by = $pr->acknowledge_by;
                            $newPr->acknowledge_at = $pr->acknowledge_at;
                            $newPr->cv_file = $pr->person->cv_file;
                            $newPr->save(FALSE);
                        }
                    } else {
// ถ้าไม่มี amendment ที่ได้ Y ล่าสุด ดึงจาก new ที่ Y
                        $firstEndorseSub = $submission->project->getFirstEndorsedSubmission();



                        $prs = ProjectResearcher::find()->isDeleted(FALSE)->project($submission->project->id)->submission($firstEndorseSub->id)->acknowledgeStatus(ProjectResearcher::STATUS_ACCEPTED)->all();
                        foreach ($prs as $pr) {
                            $newPr = new ProjectResearcher();
                            $newPr->project_id = $pr->project_id;
                            $newPr->submission_id = $submission->id;
                            $newPr->person_id = $pr->person_id;
                            $newPr->is_leader = $pr->is_leader;
                            $newPr->acknowledge_status = $pr->acknowledge_status;
                            $newPr->acknowledge_by = $pr->acknowledge_by;
                            $newPr->acknowledge_at = $pr->acknowledge_at;
                            $newPr->cv_file = $pr->person->cv_file;
                            $newPr->save(FALSE);
                        }
                    }
                }

                $consultantSearch->project_id = $submission->project->id;
                $consultantSearch->submission_id = $submission->id;
                $consultantProvider = $consultantSearch->search([]);
                if (isset($submission->previousSubmission) && $consultantProvider->totalCount == 0) {
                    $aSub = $submission->getLastAmendmentSubmission();
                    if (isset($aSub)) {
                        $pco = \app\models\ProjectConsultant::find()->isDeleted(FALSE)->project($submission->project->id)->submission($aSub->id)->acknowledgeStatus(\app\models\ProjectConsultant::STATUS_ACCEPTED)->all();
                        foreach ($pco as $co) {
                            $newPc = new \app\models\ProjectConsultant();
                            $newPc->project_id = $co->project_id;
                            $newPc->submission_id = $submission->id;
                            $newPc->person_id = $co->person_id;
                            $newPc->acknowledge_status = $co->acknowledge_status;
                            $newPc->acknowledge_by = $co->acknowledge_by;
                            $newPc->acknowledge_at = $co->acknowledge_at;
                            $newPc->cv_file = $co->person->cv_file;
                            $newPc->save(FALSE);
                        }
                    } else {
                        $firstEndorseSub = $submission->project->getFirstEndorsedSubmission();
                        $pco = \app\models\ProjectConsultant::find()->isDeleted(FALSE)->project($submission->project->id)->submission($firstEndorseSub->id)->acknowledgeStatus(\app\models\ProjectConsultant::STATUS_ACCEPTED)->all();
                        foreach ($pco as $co) {
                            $newPc = new \app\models\ProjectConsultant();
                            $newPc->project_id = $co->project_id;
                            $newPc->submission_id = $submission->id;
                            $newPc->person_id = $co->person_id;
                            $newPc->acknowledge_status = $co->acknowledge_status;
                            $newPc->acknowledge_by = $co->acknowledge_by;
                            $newPc->acknowledge_at = $co->acknowledge_at;
                            $newPc->cv_file = $co->person->cv_file;
                            $newPc->save(FALSE);
                        }
                    }
                }
                return $this->redirect(['submission/continue', 'submissionId' => $submission->id, 'step' => $step]);

//                return $this->redirect(['submission/continue', 'submissionId' => $submission->id, 'step' => $step]);
//                $researcherSearch->project_id = $project->id;
//                $submissionDocs = $this->getSubmissionDocs($submission);
            }

//        } else if ($step == self::CONT_STEP2) {
//            if (null !== $previousStep) {
//                if ($request->isAjax) {
//                    Yii::$app->response->format = Response::FORMAT_JSON;
//                    return [];
//                }
//                $step = $previousStep;
//            } else if (null !== $nextStep) {
//                if ($request->isAjax) {
//                    Yii::$app->response->format = Response::FORMAT_JSON;
//                    return ActiveForm::validateMultiple($submissionNumbers);
//                }
//                foreach ($submissionNumbers as $sm) {
//                    $sm->save(FALSE);
//                }
//                $step = $nextStep;
////                $step++;
//            }
        } else if ($step == self::CONT_STEP2) {
//            \yii\helpers\VarDumper::dump($company->attributes);
//            \yii\helpers\VarDumper::dump($profile->attributes);
//            if ($request->isAjax && null !== $request->post('ajax')) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return ['Submission_document-id' => ['ไม่ผ่าน']];
//            }
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $allReqDocs = $submission->getRequiredDocumentCount();
                    $currentReqDocs = $submission->getRequireSubmissionDocuments();

                    if ($allReqDocs != count($currentReqDocs)) {
//                        return ['submissiondocument-project_id' => ["{$allReqDocs} = " . count($currentReqDocs)]];
                        return ['submissiondocument-project_id' => [Yii::t('app', 'กรุณาอับโหลดไฟล์เอกสารให้ครบถ้วน')]];
                    } else {
                        return [];
                    }
                }
                $step = $nextStep;
            }
        } else if ($step == self::CONT_STEP3) {
//            \yii\helpers\VarDumper::dump($company->attributes);
//            \yii\helpers\VarDumper::dump($profile->attributes);
//            if ($request->isAjax && null !== $request->post('ajax')) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return ['Submission_document-id' => ['ไม่ผ่าน']];
//            }
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if (!isset($submission->projectLeader)) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุหัวหน้าโครงการวิจัย']];
                    }
                    $researcherProvider = $researcherSearch->search($request->queryParams);
                    if ($researcherProvider->count == 0) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุผู้วิจัย']];
                    } else {
                        return [];
                    }
                }
                $step = $nextStep;
            }
        } else if ($step == self::CONT_STEP4) {
//            \yii\helpers\VarDumper::dump($company->attributes);
//            \yii\helpers\VarDumper::dump($profile->attributes);
//            if ($request->isAjax && null !== $request->post('ajax')) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return ['Submission_document-id' => ['ไม่ผ่าน']];
//            }
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $nextStep;
            }
        } else if ($step == self::CONT_STEP5) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
//                $step--;
            } else {
                if (null !== $nextStep) {
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [];
                    }
//                    $step = self::REGISTER_STEP4 + 1;
                    $pr = $submission->projectLeader;
                    $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($pr->id)->one();
                    if (!isset($spr)) {
                        $ppr = $pr->previousProjectResearcher;
                        $spr = new SubmissionProjectResearcher();
                        $spr->submission_id = $pr->submission_id;
                        $spr->project_researcher_id = $pr->id;
//                        \yii\helpers\VarDumper::dump($ppr->attributes);
//                        \yii\helpers\VarDumper::dump($pr->attributes);
//                        exit;
                        if (isset($ppr) && $pr->cv_file == $ppr->cv_file) {
                            $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                        }
                        $spr->save(FALSE);
                    }
                    $crs = $submission->projectCoResearchers;
                    foreach ($crs as $cr) {
                        $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($cr->id)->one();
                        if (!isset($spr)) {
                            $cpr = $cr->previousProjectResearcher;
                            $spr = new SubmissionProjectResearcher();
                            $spr->submission_id = $submission->id;
                            $spr->project_researcher_id = $cr->id;
                            if (isset($cpr) && $cr->cv_file == $cpr->cv_file) {
                                $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                            }
                            $spr->save(FALSE);
                        }
                    }

                    $cpc = $submission->projectConsultants;
                    foreach ($cpc as $pc) {
                        $spc = \app\models\SubmissionProjectConsultant::find()->isDeleted(FALSE)->submission($submission->id)->projectConsultant($pc->id)->one();
                        if (!isset($spc)) {
                            $ppc = $pc->previousProjectConsultant;
                            $spc = new \app\models\SubmissionProjectConsultant();
                            $spc->submission_id = $pc->submission_id;
                            $spc->project_consultant_id = $pc->id;
                            $spc->save(FALSE);
                        }
                    }



//                    if (isset($submission->ref_submission_id)) {
//                        $submissionDocs = $this->getSubmissionDocs($submission);
//                        foreach ($submissionDocs as $doc) {
//                            if (count($doc->getDocumentHistories()) == 0 && isset($doc->document_id)) {
//                                $doc->status = SubmissionDocument::STATUS_PASS;
//                                $doc->save(FALSE);
//                            }
//                        }
//                    }
//                    $currentRole = \Yii::$app->session->get('currentRole');
                    if ($currentRole['role_id'] == Role::COORDINATOR) {
                        $submission->status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
//                        $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECTLEADER_CONTINUE_SUBMISSION, $submission->id);
                    } else {
                        $submission->status = Submission::STATUS_SUBMITTED;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                    }
                    $submission->save(FALSE);
//                    \app\models\Alert::addNewSubmission($submission);
//                    $submission->notifyCoResearcher();
                    return $this->redirect(['submission-complete']);
                }
            }
        }
        $researcherProvider = $researcherSearch->search($request->queryParams);
        $consultantProvider = $consultantSearch->search($request->queryParams);
        $subVolProvider = $subVolSearch->search($request->queryParams);
        $submissionDocs = $submission->getSubmissionDocs();
        $subDocProvider = new ArrayDataProvider([
            'modelClass' => SubmissionDocument::className(),
            'allModels' => $submissionDocs,
        ]);
        return $this->render('continue', [
                    'submissionTypes' => $submissionTypes,
                    'submission' => $submission,
                    'researcherSearch' => $researcherSearch,
                    'researcherProvider' => $researcherProvider,
                    'consultantSearch' => $consultantSearch,
                    'consultantProvider' => $consultantProvider,
                    'submissonDocs' => $submissionDocs,
                    'subDocProvider' => $subDocProvider,
                    'submissionDoc' => $submissionDoc,
                    'submissionNumbers' => $submissionNumbers,
                    'subVolSearch' => $subVolSearch,
                    'subVolProvider' => $subVolProvider,
                    'steps' => $steps,
                    'step' => $step,
        ]);
    }

    public function actionLog($id) {
        $model = $this->findModel($id);
        return $this->render('log', ['model' => $model]);
    }

    private function getSubmissionDocs($submission) {
        $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->submissionTypeRole(Role::RESEARCHER)->indexBy('id')->orderBy([new \yii\db\Expression('document_submission_type.sort IS NULL ,document_submission_type.sort ASC')])->all();
        $submissionDocs = [];
        foreach ($docTypes as $type) {
            $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($submission->id)->documents($type->document_id)->one();
            if (!isset($doc)) {
                $doc = new SubmissionDocument();
                $doc->document_id = $type->document_id;
                $doc->submission_id = $submission->id;
                $doc->name = $type->document->name;
                $doc->name_eng = $type->document->name_eng;
            }

            $submissionDocs[] = $doc;
        }
        $docs = SubmissionDocument::find()->isDeleted(FALSE)->submission($submission->id)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
        return array_merge($submissionDocs, $docs);
    }

    public function getSubmissionCommitteeDocs($submission = NULL, $sCommitteeId) {
//        $currentRole = \Yii::$app->session->get('currentRole');

        $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->submissionTypeRole(\app\models\Role::COMMITTEE)->indexBy('id')->all();
        $submissionDocs = [];
        foreach ($docTypes as $type) {
            $doc = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($submission->id)->documents($type->document_id)->submissionCommittee($sCommitteeId)->one();

            if (!isset($doc)) {
                $doc = new SubmissionCommitteeDocument();
                $doc->document_id = $type->document_id;
                $doc->submission_id = $submission->id;
                $doc->name = $type->document->name;
//                $doc->roleID = \app\models\Role::COMMITTEE;
            }

            $submissionDocs[] = $doc;
        }
//$docs = SubmissionDocument::find()->joinWith('documentSubmissionTypes')->isDeleted(FALSE)->submission($submission->id)->submissionTypeRole(\app\models\Role::COMMITTEE)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
        return $submissionDocs;
    }

    public function actionNewCertified($refSubmissionId = NULL, $submissionId = NULL, $step = self::NEW_CERTIFIED_STEP1) {
        $request = Yii::$app->request;
        $currentRole = \Yii::$app->session->get('currentRole');
        $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->internal(FALSE)->group(SubmissionTypeGroup::GROUP_NEW)->all(), 'id', 'name');
//        $projectId = $request->post('projectId');
//        \yii\helpers\VarDumper::dump($projectId);
//        \yii\helpers\VarDumper::dump($request->post());


        if (isset($submissionId)) {
            $submission = Submission::findOne($submissionId);
            if (!isset($submission)) {
                $submission = new Submission();
                $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                $submission->is_legacy = 1;
                $submission->ref_submission_id = $refSubmissionId;
                $project = new Project();
            } else {
                $project = $submission->project;
                $submission->correspondence_at = isset($submission->correspondence_at) ? \Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y-m-d') : null;
            }
        } else {
            $submission = new Submission();
            $submission->is_legacy = 1;
            $submission->status = Submission::STATUS_PENDING_SUBMISSION;
            $project = new Project();
            $submission->ref_submission_id = $refSubmissionId;
        }
        if (isset($submission->ref_submission_id)) {
            $refSubmission = $submission->refSubmission;
            $submission = Submission::find()->isDeleted(FALSE)->refSubmission($submission->ref_submission_id)->one();
            $submissionTypes = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->resolution($refSubmission->resolution)->group(SubmissionTypeGroup::GROUP_NEW)->all(), 'id', 'name');
            if (!isset($submission)) {
                $submission = new Submission();
                $submission->is_legacy = 1;
                $submission->status = Submission::STATUS_PENDING_SUBMISSION;
                $submission->ref_submission_id = $refSubmissionId;
                $submission->project_id = $refSubmission->project_id;
                $submission->submission_type_id = array_keys($submissionTypes)[0];
                $submission->save(FALSE);
                $oldDocs = $refSubmission->getSubmissionDocuments()->isDeleted(FALSE)->all();
                foreach ($oldDocs as $oldDoc) {
                    $newDoc = new SubmissionDocument();
                    $newDoc->name = $oldDoc->name;
                    $newDoc->file_name = $oldDoc->file_name;
                    $newDoc->project_id = $oldDoc->project_id;
                    $newDoc->document_id = $oldDoc->document_id;
                    $newDoc->submission_id = $submission->id;
                    $newDoc->version = $oldDoc->version;
                    $newDoc->version_at = $oldDoc->version_at;
                    $newDoc->save(FALSE);
                    \yii\helpers\FileHelper::createDirectory($newDoc->path);
                    copy($oldDoc->filePath, $newDoc->filePath);
                }

                $oldResearchers = $refSubmission->getProjectResearchers()->isDeleted(FALSE)->all();
                foreach ($oldResearchers as $or) {
                    $newR = new ProjectResearcher();
                    $newR->project_id = $or->project_id;
                    $newR->submission_id = $submission->id;
                    $newR->person_id = $or->person_id;
                    $newR->is_leader = $or->is_leader;
                    $newR->cv_file = $or->person->cv_file;
                    $newR->acknowledge_status = $or->acknowledge_status;
                    $newR->save(FALSE);
                }
                $oldConsultants = $refSubmission->getProjectConsultants()->isDeleted(FALSE)->all();
                foreach ($oldConsultants as $oc) {
                    $newC = new \app\models\ProjectConsultant();
                    $newC->project_id = $oc->project_id;
                    $newC->submission_id = $submission->id;
                    $newC->person_id = $oc->person_id;
//                    $newC->is_leader = $oc->is_leader;
                    $newC->cv_file = $oc->person->cv_file;
                    $newC->acknowledge_status = $oc->acknowledge_status;
                    $newC->save(FALSE);
                }
                $oldCoi = $refSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                foreach ($oldCoi as $ocoi) {
                    $newCoi = new \app\models\SubmissionCoiPerson();
                    $newCoi->submission_id = $submission->id;
                    $newCoi->person_id = $ocoi->person_id;
                    $newCoi->save(FALSE);
                }
            }
            $project = $refSubmission->project;
        }
        if ($submission->status > Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER) {
            throw new \yii\web\HttpException(400, Yii::t('app', 'ไม่สามารถดำเนินการได้'));
        }
//        if (isset($submissionId)) {
//            if (Yii::$app->user->isGuest || !Yii::$app->user->identity->person->isSubmissionVisible($submission->id)) {
//                throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลโครงการ'));
//            }
//        }
        $steps = self::newCertifiedSteps();
        $submission->scenario = Submission::SCENARIO_NEWSUBMISSION;
        $project->load($request->post());
        $submission->load($request->post());
        $submissionDocs = [];

        $researcherSearch = new ProjectResearcherSearch();
        $researcherSearch->deleted = 0;
        $researcherSearch->project_id = $project->id;
        $researcherSearch->submission_id = $submission->id;

        $consultantSearch = new \app\models\ProjectConsultantSearch();
        $consultantSearch->deleted = 0;
        $consultantSearch->project_id = $project->id;
        $consultantSearch->submission_id = $submission->id;

        $submissionDoc = new SubmissionDocument();
        $previousStep = $request->post('previousStep');
        $nextStep = $request->post('nextStep');

        if ($step == self::NEW_CERTIFIED_STEP1) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($project, $submission);
            }
            if (isset($nextStep)) {
                $step = $nextStep;
                if ($currentRole['role_id'] == Role::COORDINATOR) {
                    $project->project_coordinator_id = \Yii::$app->user->identity->id;
                }
                $project->save(FALSE);
                $submission->project_id = $project->id;
                if ($currentRole['role_id'] == Role::COORDINATOR) {
                    $submission->project_coordinator_id = \Yii::$app->user->identity->id;
                }
                $submission->status = isset($submission->status) ? $submission->status : Submission::STATUS_PENDING_SUBMISSION;
                $submission->is_legacy = 1;
                $submission->save(FALSE);

                return $this->redirect(['submission/new-certified', 'submissionId' => $submission->id, 'step' => $step]);
            }
        } else if ($step == self::NEW_CERTIFIED_STEP2) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $allReqDocs = $submission->submissionType->getRequireDocumentSubmissTypes();
                    $currentReqDocs = $submission->getRequireSubmissionDocuments();
                    if (count($allReqDocs) != count($currentReqDocs)) {
                        return ['submissiondocument-project_id' => ['กรุณาอับโหลดไฟล์เอกสารให้ครบถ้วน']];
                    } else {
                        return [];
                    }
                }
                $researcherProvider = $researcherSearch->search([]);
                $lastSub = $submission->previousSubmission;
                if (isset($lastSub)) {

                    $submission->project_coordinator_id = $lastSub->project_coordinator_id;
                    $submission->save();

                    $oldCoi = $submission->previousSubmission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                    foreach ($oldCoi as $ocoi) {
                        $newCoi = new \app\models\SubmissionCoiPerson();
                        $newCoi->submission_id = $submission->id;
                        $newCoi->person_id = $ocoi->person_id;
                        $newCoi->save(FALSE);
                    }
                }
                if (isset($lastSub) && $researcherProvider->totalCount == 0) {
                    $oldResearchers = $lastSub->getProjectResearchers()->isDeleted(FALSE)->all();
                    foreach ($oldResearchers as $or) {

                        $newR = new ProjectResearcher();
                        $newR->project_id = $or->project_id;
                        $newR->submission_id = $submission->id;
                        $newR->person_id = $or->person_id;
                        $newR->is_leader = $or->is_leader;
                        $newR->cv_file = $or->person->cv_file;
                        $newR->acknowledge_status = $or->acknowledge_status;
                        $newR->save(FALSE);
                    }
                }

                $consultantProvider = $consultantSearch->search([]);
//                $lastSubcon = $submission->previousSubmission;
                if (isset($lastSub) && $consultantProvider->totalCount == 0) {
                    $oldConsultants = $lastSub->getProjectConsultants()->isDeleted(FALSE)->all();
                    foreach ($oldConsultants as $oc) {
                        $newC = new \app\models\ProjectConsultant();
                        $newC->project_id = $oc->project_id;
                        $newC->submission_id = $submission->id;
                        $newC->person_id = $oc->person_id;
//                        $newC->is_leader = $oc->is_leader;
                        $newC->cv_file = $oc->person->cv_file;
                        $newC->acknowledge_status = $oc->acknowledge_status;
                        $newC->save(FALSE);
                    }
                }

                if (!isset($submission->projectLeader) and $currentRole['role_id'] != Role::COORDINATOR) {
                    $pr = new ProjectResearcher();
                    $pr->project_id = $project->id;
                    $pr->submission_id = $submission->id;
                    $pr->person_id = \Yii::$app->user->identity->person->id;
                    $pr->is_leader = 1;
                    $pr->mail_sent = 1;
                    $pr->cv_file = \Yii::$app->user->identity->person->cv_file;
                    $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                    $pr->acknowledge_at = date('Y-m-d H:i:s');
                    $pr->save();
                }
                $step = $nextStep;
            }
        } else if ($step == self::NEW_CERTIFIED_STEP3) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $researcherProvider = $researcherSearch->search($request->queryParams);
                    if (!isset($submission->projectLeader)) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุหัวหน้าโครงการวิจัย']];
                    }
                    if ($researcherProvider->count == 0) {
                        return ['projectresearchersearch-id' => ['กรุณาระบุผู้วิจัย']];
                    } else {
                        return [];
                    }
                }
                $consultantProvider = $consultantSearch->search([]);
                $lastSub = $submission->previousSubmission;
                if (isset($lastSub)) {
                    $submission->project_coordinator_id = $lastSub->project_coordinator_id;
                    $submission->save();
                }
                if (isset($lastSub) && $consultantProvider->totalCount == 0) {
                    $oldResearchers = $lastSub->getProjectConsultants()->isDeleted(FALSE)->all();
                    foreach ($oldResearchers as $or) {

                        $newR = new \app\models\ProjectConsultant();
                        $newR->project_id = $or->project_id;
                        $newR->submission_id = $submission->id;
                        $newR->person_id = $or->person_id;
                        $newR->cv_file = $or->person->cv_file;
                        $newR->acknowledge_status = $or->acknowledge_status;
                        $newR->save(FALSE);
                    }
                }

                $step = $nextStep;
            }
        } else if ($step == self::NEW_CERTIFIED_STEP4) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
            } else if (null !== $nextStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $consultantProvider = $consultantSearch->search($request->queryParams);
                    return [];
                }
                $step = $nextStep;
            }
        } else if ($step == self::NEW_CERTIFIED_STEP5) {
            if (null !== $previousStep) {
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [];
                }
                $step = $previousStep;
//                $step--;
            } else {
                if (null !== $nextStep) {
                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [];
                    }
//                    $step = self::REGISTER_STEP4 + 1;
                    $pr = $submission->projectLeader;
                    $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($pr->id)->one();
                    if (!isset($spr)) {
                        $ppr = $pr->previousProjectResearcher;
                        $spr = new SubmissionProjectResearcher();
                        $spr->submission_id = $pr->submission_id;
                        $spr->project_researcher_id = $pr->id;
                        if (isset($ppr) && $pr->cv_file == $ppr->cv_file) {
                            $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                        }
                        $spr->save(FALSE);
                    }
                    $crs = $submission->projectCoResearchers;
                    foreach ($crs as $cr) {
                        $spr = SubmissionProjectResearcher::find()->isDeleted(FALSE)->submission($submission->id)->projectResearcher($cr->id)->one();
                        if (!isset($spr)) {
                            $ppr = $cr->previousProjectResearcher;
                            $spr = new SubmissionProjectResearcher();
                            $spr->submission_id = $submission->id;
                            $spr->project_researcher_id = $cr->id;
                            if (isset($ppr) && $cr->cv_file == $ppr->cv_file) {
                                $spr->status = SubmissionProjectResearcher::STATUS_PASS;
                            }
                            $spr->save(FALSE);
                        }
                    }

                    $css = $submission->projectConsultants;
                    foreach ($css as $cs) {
                        $spc = \app\models\SubmissionProjectConsultant::find()->isDeleted(FALSE)->submission($submission->id)->projectConsultant($cs->id)->one();
                        if (!isset($spc)) {
                            $ppc = $cs->previousProjectConsultant;
                            $spc = new \app\models\SubmissionProjectConsultant();
                            $spc->submission_id = $submission->id;
                            $spc->project_consultant_id = $cs->id;
                            if (isset($ppc) && $cs->cv_file == $ppc->cv_file) {
                                $spc->status = \app\models\SubmissionProjectConsultant::STATUS_PASS;
                            }
                            $spc->save(FALSE);
                        }
                    }

//                    if (isset($submission->ref_submission_id)) {
//                        $submission->responsible_person = $submission->refSubmission->responsible_person;
//                        $submissionDocs = $this->getSubmissionDocs($submission);
//                        foreach ($submissionDocs as $doc) {
//                            if (count($doc->getDocumentHistories()) == 0) {
//                                $doc->status = SubmissionDocument::STATUS_PASS;
//                                $doc->save(FALSE);
//                            }
//                        }
//                    }

                    if ($currentRole['role_id'] == Role::COORDINATOR) {
                        $submission->status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                        EmailQueue::addQueue(EmailQueue::TYPE_INFORM_PROJECTLEADER_NEW_CERTIFIED_SUBMISSION, $submission->id);
                    } else {
                        $submission->status = Submission::STATUS_SUBMITTED;
                        $submission->submission_by = \Yii::$app->user->identity->id;
                    }
                    $submission->save(FALSE);
                    \app\models\Alert::addNewCertifiedSubmission($submission);

                    return $this->redirect(['submission-complete']);
                }
            }
        }
        $researcherProvider = $researcherSearch->search($request->queryParams);
        $consultantProvider = $consultantSearch->search($request->queryParams);
        $submissionDocs = $this->getSubmissionDocs($submission);
        $subDocProvider = new ArrayDataProvider([
            'allModels' => $submissionDocs,
        ]);
        return $this->render('new-certified', [
                    'submissionTypes' => $submissionTypes,
                    'project' => $project,
                    'submission' => $submission,
                    'consultantSearch' => $consultantSearch,
                    'consultantProvider' => $consultantProvider,
                    'researcherSearch' => $researcherSearch,
                    'researcherProvider' => $researcherProvider,
                    'submissionDocs' => $submissionDocs,
                    'subDocProvider' => $subDocProvider,
                    'submissionDoc' => $submissionDoc,
                    'steps' => $steps,
                    'step' => $step,
        ]);
    }

    public function actionChangePanel($id) {
        $request = Yii::$app->request;
        $submission = $this->findModel($id);
        $model = new ChangePanelForm();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เปลี่ยน Panel"),
                    'content' => $this->renderAjax('change-panel', [
                        'model' => $model,
                        'submission' => $submission,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $tran = Yii::$app->db->beginTransaction();
                try {
                    $his = new ProjectCodeHistory();
                    $his->project_id = $submission->project_id;
                    $his->submission_id = $submission->id;
                    $his->old_code = $submission->project->project_code;
                    $project = $submission->project;
                    $project->panel_id = $model->panelId;
                    $project->project_code = null;
                    $project->generateHECode();
                    $project->save();
                    if ($submission->status >= Submission::STATUS_MEETING_DONE) {
                        $newSub = new Submission();
                        $newSub->attributes = $submission->attributes;
                        $newSub->created_at = null;
                        $newSub->updated_at = null;
                        $newSub->responsible_person = $model->responsiblePersonId;
                        $newSub->responsible_date = date('Y-m-d H:i:s');
                        $newSub->resolution = null;
                        $newSub->status = Submission::STATUS_COMMITTEE_ASSESSED;
                        $newSub->save();
                        $oldCoi = $submission->getSubmissionCoiPerson()->isDeleted(FALSE)->all();
                        foreach ($oldCoi as $ocoi) {
                            $newCoi = new \app\models\SubmissionCoiPerson();
                            $newCoi->submission_id = $newSub->id;
                            $newCoi->person_id = $ocoi->person_id;
                            $newCoi->save(FALSE);
                        }
                        $oldDocs = $submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
                        foreach ($oldDocs as $oldDoc) {
                            $newDoc = new SubmissionDocument();
                            $newDoc->name = $oldDoc->name;
                            $newDoc->file_name = $oldDoc->file_name;
                            $newDoc->project_id = $oldDoc->project_id;
                            $newDoc->document_id = $oldDoc->document_id;
                            $newDoc->submission_id = $newSub->id;
                            $newDoc->version = $oldDoc->version;
                            $newDoc->version_at = $oldDoc->version_at;
                            $newDoc->save(FALSE);
                            \yii\helpers\FileHelper::createDirectory($newDoc->path);
                            copy($oldDoc->filePath, $newDoc->filePath);
                        }
                        $oldResearchers = $submission->getProjectResearchers()->isDeleted(FALSE)->all();
                        foreach ($oldResearchers as $or) {
                            $newR = new ProjectResearcher();
                            $newR->project_id = $or->project_id;
                            $newR->submission_id = $newSub->id;
                            $newR->person_id = $or->person_id;
                            $newR->is_leader = $or->is_leader;
                            $newR->cv_file = $or->person->cv_file;
                            $newR->acknowledge_status = $or->acknowledge_status;
                            $newR->save(FALSE);
                        }
                        $oldConsultants = $submission->getProjectConsultants()->isDeleted(FALSE)->all();
                        foreach ($oldConsultants as $oc) {
                            $newC = new \app\models\ProjectConsultant();
                            $newC->project_id = $oc->project_id;
                            $newC->submission_id = $newSub->id;
                            $newC->person_id = $oc->person_id;
                            $newC->cv_file = $oc->person->cv_file;
                            $newC->acknowledge_status = $oc->acknowledge_status;
                            $newC->save(FALSE);
                        }
                        $oldCommittees = $submission->getSubmissionCommittees()->isDeleted(false)->status('>=' . \app\models\SubmissionCommittee::STATUS_ACCEPTED)->all();
                        foreach ($oldCommittees as $cm) {
                            $newC = new \app\models\SubmissionCommittee();
                            $newC->attributes = $cm->attributes;
                            $newC->created_at = null;
                            $newC->updated_at = null;
                            $newC->submission_id = $newSub->id;
                            $newC->save();
                            $oldDocs = $cm->getSubmissionCommitteeDocuments()->all();
                            foreach ($oldDocs as $oldDoc) {
                                $newD = new SubmissionCommitteeDocument();
                                $newD->attributes = $oldDoc->attributes;
                                $newD->file_name = $oldDoc->file_name;
                                $newD->created_at = null;
                                $newD->updated_at = null;
                                $newD->submission_id = $newSub->id;
                                $newD->submission_committee_id = $newC->id;
                                $newD->save();
                                \yii\helpers\FileHelper::createDirectory($newD->path);
                                copy($oldDoc->filePath, $newD->filePath);
                            }
                            $oldRevises = $cm->getSubmissionCommitteeRevises()->isDeleted(false)->all();
                            foreach ($oldRevises as $oldR) {
                                $newR = new SubmissionCommitteeRevise();
                                $newR->attributes = $oldR->attributes;
                                $newR->created_at = null;
                                $newR->updated_at = null;
                                $newR->submission_id = $newSub->id;
                                $newR->submission_committee_id = $newC->id;
                                $newR->save();
                            }
                            $oldAnswers = $cm->getQuestionnaireAnswers()->isDeleted(false)->all();
                            foreach ($oldAnswers as $oldA) {
                                $newA = new QuestionnaireAnswer();
                                $newA->attributes = $oldA->attributes;
                                $newA->created_at = null;
                                $newA->updated_at = null;
                                $newA->submission_id = $newSub->id;
                                $newA->submission_committee_id = $newC->id;
                                $newA->save();
                            }
                        }
                    } else {
                        $submission->responsible_person = $model->responsiblePersonId;
                        $submission->save();
                    }
                    $his->new_code = $project->project_code;
                    $his->save();
                    $tran->commit();
                } catch (Exception $ex) {
                    $tran->rollback();
                    throw $ex;
                }
                return [
                    'forceReload' => '#crud-datatable-submission-pjax',
                    'title' => Yii::t('app', "เปลี่ยน Panel"),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'เปลี่ยน Panel เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เปลี่ยน Panel"),
                    'content' => $this->renderAjax('change-panel', [
                        'model' => $model,
                        'submission' => $submission,
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
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }

}
