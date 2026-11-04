<?php

namespace app\controllers;

use Yii;
use app\models\QuestionnaireAnswer;
use app\models\QuestionnaireAnswerSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\SubmissionCommitteeDocument;
use app\models\DocumentSubmissionType;
use app\models\Submission;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\SubmissionTypeAssessForm;
use app\models\SubmissionTypeGroup;
use app\models\SubmissionType;
use app\models\ContinueAssessForm;
use app\models\SaeAssessForm;
use app\models\Ethics;
use app\models\ContinueAssessFormEthics;
use app\models\ReviewChoice;
use app\models\Resolution;
use app\models\CAssessForm;
use app\models\DeviationAssessForm;

/**
 * QuestionnaireAnswerController implements the CRUD actions for QuestionnaireAnswer model.
 */
class QuestionnaireAnswerController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['assessment-info'];

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
     * Lists all QuestionnaireAnswer models.
     * @return mixed
     */
    public function actionAssessment($submissionId, $sCommitteeId) {
        $currentRole = Yii::$app->session->get('currentRole');

        if ($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR || !Yii::$app->user->identity->person->isAssessmentVisible($submissionId)) {
            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลได้'));
        }
        $submission = Submission::findOne($submissionId);
        $docsearchModel = new \app\models\SubmissionDocumentSearch();
        $docsearchModel->deleted = 0;
        $docsearchModel->submission_id = $submissionId;
//        $docdataProvider = $docsearchModel->search(Yii::$app->request->queryParams);
//        $subDocs = $submission->getSubmissionDocs(true);
//        $docdataProvider = new ArrayDataProvider([
//            'allModels' => $subDocs,
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
        $pResearchersearchModel = new \app\models\ProjectResearcherSearch();
        $pResearchersearchModel->deleted = 0;
        $pResearchersearchModel->submission_id = $submissionId;
        $pResearcherdataProvider = $pResearchersearchModel->search(Yii::$app->request->queryParams);

        $submissionDocs = [];

        $submissionCommittee = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->andWhere(['id' => $sCommitteeId])->one();
        $submissionDoc = new SubmissionCommitteeDocument();

        $submissionDocs = $this->getSubmissionDocs($submission, $sCommitteeId, $submissionCommittee->committee_position_id);
        $submissionDocs = $submission->getCommitteeDocs($sCommitteeId, $submissionCommittee->committee_position_id);
        $subDocProvider = new ArrayDataProvider([
            'allModels' => $submissionDocs,
            'modelClass' => \app\models\SubmissionCommitteeDocument::class,
        ]);

//        $qts = \app\models\QuestionnaireTitle::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->orderBy('order')->andWhere(['deleted' => 0])->all();
//        $answers = [];
//        foreach ($qts as $qt) {
//            $answer = QuestionnaireAnswer::find()->isDeleted(FALSE)->andWhere([
//                        'questionnaire_title_id' => $qt->id,
//                        'submission_id' => $submissionId,
//                        'submission_committee_id' => $sCommitteeId,
//                    ])->one();
//            if (!isset($answer)) {
//                $answer = new QuestionnaireAnswer();
//            } else {
//                $answer->choices = ArrayHelper::getColumn(QuestionnaireAnswer::find()->isDeleted(FALSE)
//                                        ->questionnaireTitle($answer->questionnaire_title_id)->submission($answer->submission_id)->submissionCommittee($answer->submission_committee_id)->all(), 'questionnaire_choice_id');
//            }
//            $answer->questionnaire_title_id = $qt->id;
//            $answer->submission_id = $submissionId;
//            $answer->submission_committee_id = $sCommitteeId;
//            $answers[] = $answer;
//        }
        $answers = [];
        $staf = new SubmissionTypeAssessForm();
        if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT && isset($submission->refSubmission) && $submission->refSubmission->resolution == Submission::RESOLUTION_C) {
            $staf->assess_form = SubmissionType::FORM_C;
        } else {
            $staf = $submission->submissionType->getSubmissionTypeAssessForms()->isDeleted(false)->one();
        }

        if (!isset($staf)) {
            $staf = new SubmissionTypeAssessForm();
        }
        $assessForm = null;
        $assessFormParams = [];
        if ($staf->assess_form == SubmissionType::FORM_CONTINUE) {
            $assessForm = ContinueAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new ContinueAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
            if (!isset($assessForm->review_choice_id)) {
                $assessForm->review_choice_id = $submission->submissionType->review_choice_id;
            }
            $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
            $conEthicses = [];
            foreach ($ethicses as $ethics) {
                $conEthics = ContinueAssessFormEthics::find()->isDeleted(false)->continueAssessForm($assessForm->id)->ethics($ethics->id)->one();
                if (!isset($conEthics)) {
                    $conEthics = new ContinueAssessFormEthics();
                    $conEthics->continue_assess_form_id = $assessForm->id;
                    $conEthics->ethics_id = $ethics->id;
                }
                $conEthicses[$ethics->id] = $conEthics;
            }

            $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
            $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'ethicses' => $ethicses,
                'conEthicses' => $conEthicses,
                'reviewChoices' => $reviewChoices,
                'resolutions' => $resolutions,
            ];
        } else if ($staf->assess_form == SubmissionType::FORM_SAE) {
            $assessForm = SaeAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new SaeAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
            $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'resolutions' => $resolutions,
            ];
        } else if ($staf->assess_form == SubmissionType::FORM_C) {
            $assessForm = CAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new CAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
        } else if ($staf->assess_form == SubmissionType::FORM_DEVIATION) {
            $assessForm = DeviationAssessForm::find()->isDeleted(false)->submission($submissionId)
                            ->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new DeviationAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
            if (!isset($assessForm->review_choice_id)) {
                $assessForm->review_choice_id = $submission->submissionType->review_choice_id;
            }
            $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
            $resolutions = $submission->submissionType->getResolutions(); //Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'resolutions' => $resolutions,
                'reviewChoices' => $reviewChoices,
            ];
        }
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Model::loadMultiple($answers, $request->post()) & ($request->isPost && Model::validateMultiple($answers))) {

                foreach ($answers as $answer) {
//                    \yii\helpers\VarDumper::dump($answer->attributes);
//                    \yii\helpers\VarDumper::dump($answer->choices);
//                    exit;
                    if (is_array($answer->choices)) {
                        QuestionnaireAnswer::deleteAll([
                            'questionnaire_title_id' => $answer->questionnaire_title_id,
                            'submission_id' => $answer->submission_id,
                            'submission_committee_id' => $answer->submission_committee_id,
                        ]);
                        foreach ($answer->choices as $choice) {
                            $ans = new QuestionnaireAnswer();
                            $ans->attributes = $answer->attributes;
                            $ans->questionnaire_choice_id = $choice;
                            $ans->text_answer = $answer->text_answer;
                            $ans->deleted = 0;
                            $ans->save(FALSE);
                        }
                    } else {

                        $answer->questionnaire_choice_id = $answer->choices;
                        $answer->save(FALSE);
                    }
                }
//                exit;
            } else {
                return [
                    'title' => "แบบประเมิน",
                    'size' => 'large',
                    'content' => $this->renderAjax('assessment', [
                        'submission' => $submission,
                        'answers' => $answers,
                        'sCommitteeId' => $sCommitteeId,
                        'submissionDocs' => $submissionDocs,
                        'subDocProvider' => $subDocProvider,
                        'submissionDoc' => $submissionDoc,
                        'submissionCommittee' => $submissionCommittee,
                        'docsearchModel' => $docsearchModel,
                        'docdataProvider' => $docdataProvider,
                        'pResearchersearchModel' => $pResearchersearchModel,
                        'pResearcherdataProvider' => $pResearcherdataProvider,
                        'assessForm' => $assessForm,
                        'assessFormParams' => $assessFormParams,
                        'staf' => $staf,
                    ]),
                    'footer' => Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-lg btn-primary', 'type' => "submit", 'name' => 'btn-submit-assessment'])
                ];
            }
        } else {
            if (Model::loadMultiple($answers, $request->post()) & ($request->isPost && Model::validateMultiple($answers))) {
                foreach ($answers as $answer) {
//                    \yii\helpers\VarDumper::dump($answer->attributes);
//                    \yii\helpers\VarDumper::dump($answer->choices);
//                    exit;
                    if (is_array($answer->choices)) {
                        QuestionnaireAnswer::deleteAll([
                            'questionnaire_title_id' => $answer->questionnaire_title_id,
                            'submission_id' => $answer->submission_id,
                            'submission_committee_id' => $answer->submission_committee_id,
                        ]);
                        foreach ($answer->choices as $choice) {
                            $ans = new QuestionnaireAnswer();
                            $ans->attributes = $answer->attributes;
                            $ans->questionnaire_choice_id = $choice;
                            $ans->text_answer = $answer->text_answer;
                            $ans->deleted = 0;
                            $ans->save(FALSE);
                        }
                    } else {

                        $answer->questionnaire_choice_id = $answer->choices;
                        $answer->save(FALSE);
                    }
                }
//                exit;
            }

            return $this->render('assessment', [
                        'submission' => $submission,
                        'answers' => $answers,
                        'sCommitteeId' => $sCommitteeId,
                        'submissionDocs' => $submissionDocs,
                        'subDocProvider' => $subDocProvider,
                        'submissionDoc' => $submissionDoc,
                        'submissionCommittee' => $submissionCommittee,
                        'docsearchModel' => $docsearchModel,
                        'docdataProvider' => $docdataProvider,
                        'pResearchersearchModel' => $pResearchersearchModel,
                        'pResearcherdataProvider' => $pResearcherdataProvider,
                        'assessForm' => $assessForm,
                        'assessFormParams' => $assessFormParams,
                        'staf' => $staf,
            ]);
        }
    }

    public function actionAssessmentInfo($submissionId, $sCommitteeId) {
        $currentRole = Yii::$app->session->get('currentRole');

        if ($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR || !Yii::$app->user->identity->person->isAssessmentVisible($submissionId)) {
            throw new \yii\web\UnauthorizedHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงข้อมูลได้'));
        }
        $submission = Submission::findOne($submissionId);
        $docsearchModel = new \app\models\SubmissionDocumentSearch();
        $docsearchModel->deleted = 0;
        $docsearchModel->submission_id = $submissionId;
//        $docdataProvider = $docsearchModel->search(Yii::$app->request->queryParams);
        $subDocs = $submission->getSubmissionDocs(true);
        $docdataProvider = new ArrayDataProvider([
            'allModels' => $subDocs,
            'key' => 'id',
        ]);

        $pResearchersearchModel = new \app\models\ProjectResearcherSearch();
        $pResearchersearchModel->deleted = 0;
        $pResearchersearchModel->submission_id = $submissionId;
        $pResearcherdataProvider = $pResearchersearchModel->search(Yii::$app->request->queryParams);

        $submissionDocs = [];

        $submissionCommittee = \app\models\SubmissionCommittee::find()->isDeleted(FALSE)->andWhere(['id' => $sCommitteeId])->one();
        $submissionDoc = new SubmissionCommitteeDocument();
        $submissionDocs = $this->getSubmissionDocs($submission, $sCommitteeId, $submissionCommittee->committee_position_id);
        $subDocProvider = new ArrayDataProvider([
            'allModels' => $submissionDocs,
        ]);

//        $qts = \app\models\QuestionnaireTitle::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->orderBy('order')->andWhere(['deleted' => 0])->all();
//        $answers = [];
//        foreach ($qts as $qt) {
//            $answer = QuestionnaireAnswer::find()->isDeleted(FALSE)->andWhere([
//                        'questionnaire_title_id' => $qt->id,
//                        'submission_id' => $submissionId,
//                        'submission_committee_id' => $sCommitteeId,
//                    ])->one();
//            if (!isset($answer)) {
//                $answer = new QuestionnaireAnswer();
//            } else {
//                $answer->choices = ArrayHelper::getColumn(QuestionnaireAnswer::find()->isDeleted(FALSE)
//                                        ->questionnaireTitle($answer->questionnaire_title_id)->submission($answer->submission_id)->submissionCommittee($answer->submission_committee_id)->all(), 'questionnaire_choice_id');
//            }
//            $answer->questionnaire_title_id = $qt->id;
//            $answer->submission_id = $submissionId;
//            $answer->submission_committee_id = $sCommitteeId;
//            $answers[] = $answer;
//        }
        $answers = [];
        $staf = new SubmissionTypeAssessForm();
        if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT && isset($submission->refSubmission) && $submission->refSubmission->resolution == Submission::RESOLUTION_C) {
            $staf->assess_form = SubmissionType::FORM_C;
        } else {
            $staf = $submission->submissionType->getSubmissionTypeAssessForms()->isDeleted(false)->one();
        }

        if (!isset($staf)) {
            $staf = new SubmissionTypeAssessForm();
        }
        $assessForm = null;
        $assessFormParams = [];
        if ($staf->assess_form == SubmissionType::FORM_CONTINUE) {
            $assessForm = ContinueAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new ContinueAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }

            $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
            $conEthicses = [];
            foreach ($ethicses as $ethics) {
                $conEthics = ContinueAssessFormEthics::find()->isDeleted(false)->continueAssessForm($assessForm->id)->ethics($ethics->id)->one();
                if (!isset($conEthics)) {
                    $conEthics = new ContinueAssessFormEthics();
                    $conEthics->continue_assess_form_id = $assessForm->id;
                    $conEthics->ethics_id = $ethics->id;
                }
                $conEthicses[$ethics->id] = $conEthics;
            }

            $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
            $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'ethicses' => $ethicses,
                'conEthicses' => $conEthicses,
                'reviewChoices' => $reviewChoices,
                'resolutions' => $resolutions,
            ];
        } else if ($staf->assess_form == SubmissionType::FORM_SAE) {
            $assessForm = SaeAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new SaeAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
            $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'resolutions' => $resolutions,
            ];
        } else if ($staf->assess_form == SubmissionType::FORM_C) {
            $assessForm = CAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new CAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
        } else if ($staf->assess_form == SubmissionType::FORM_DEVIATION) {
            $assessForm = DeviationAssessForm::find()->isDeleted(false)->submission($submissionId)
                            ->submissionCommittee($sCommitteeId)->one();
            if (!isset($assessForm)) {
                $assessForm = new DeviationAssessForm();
                $assessForm->submission_id = $submissionId;
                $assessForm->submission_committee_id = $sCommitteeId;
            }
            $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
            $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
            $assessFormParams = [
                'resolutions' => $resolutions,
                'reviewChoices' => $reviewChoices,
            ];
        }
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Model::loadMultiple($answers, $request->post()) & ($request->isPost && Model::validateMultiple($answers))) {

                foreach ($answers as $answer) {
//                    \yii\helpers\VarDumper::dump($answer->attributes);
//                    \yii\helpers\VarDumper::dump($answer->choices);
//                    exit;
                    if (is_array($answer->choices)) {
                        QuestionnaireAnswer::deleteAll([
                            'questionnaire_title_id' => $answer->questionnaire_title_id,
                            'submission_id' => $answer->submission_id,
                            'submission_committee_id' => $answer->submission_committee_id,
                        ]);
                        foreach ($answer->choices as $choice) {
                            $ans = new QuestionnaireAnswer();
                            $ans->attributes = $answer->attributes;
                            $ans->questionnaire_choice_id = $choice;
                            $ans->text_answer = $answer->text_answer;
                            $ans->deleted = 0;
                            $ans->save(FALSE);
                        }
                    } else {

                        $answer->questionnaire_choice_id = $answer->choices;
                        $answer->save(FALSE);
                    }
                }
//                exit;
            } else {
                return [
                    'title' => "แบบประเมิน",
                    'size' => 'large',
                    'content' => $this->renderAjax('assessment-info', [
                        'submission' => $submission,
                        'answers' => $answers,
                        'sCommitteeId' => $sCommitteeId,
                        'submissionDocs' => $submissionDocs,
                        'subDocProvider' => $subDocProvider,
                        'submissionDoc' => $submissionDoc,
                        'submissionCommittee' => $submissionCommittee,
                        'docsearchModel' => $docsearchModel,
                        'docdataProvider' => $docdataProvider,
                        'pResearchersearchModel' => $pResearchersearchModel,
                        'pResearcherdataProvider' => $pResearcherdataProvider,
                        'assessForm' => $assessForm,
                        'assessFormParams' => $assessFormParams,
                        'staf' => $staf,
                    ]),
                    'footer' => Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-lg btn-primary', 'type' => "submit", 'name' => 'btn-submit-assessment'])
                ];
            }
        } else {
            if (Model::loadMultiple($answers, $request->post()) & ($request->isPost && Model::validateMultiple($answers))) {
                foreach ($answers as $answer) {
//                    \yii\helpers\VarDumper::dump($answer->attributes);
//                    \yii\helpers\VarDumper::dump($answer->choices);
//                    exit;
                    if (is_array($answer->choices)) {
                        QuestionnaireAnswer::deleteAll([
                            'questionnaire_title_id' => $answer->questionnaire_title_id,
                            'submission_id' => $answer->submission_id,
                            'submission_committee_id' => $answer->submission_committee_id,
                        ]);
                        foreach ($answer->choices as $choice) {
                            $ans = new QuestionnaireAnswer();
                            $ans->attributes = $answer->attributes;
                            $ans->questionnaire_choice_id = $choice;
                            $ans->text_answer = $answer->text_answer;
                            $ans->deleted = 0;
                            $ans->save(FALSE);
                        }
                    } else {

                        $answer->questionnaire_choice_id = $answer->choices;
                        $answer->save(FALSE);
                    }
                }
//                exit;
            }

            return $this->render('assessment-info', [
                        'submission' => $submission,
                        'answers' => $answers,
                        'sCommitteeId' => $sCommitteeId,
                        'submissionDocs' => $submissionDocs,
                        'subDocProvider' => $subDocProvider,
                        'submissionDoc' => $submissionDoc,
                        'submissionCommittee' => $submissionCommittee,
                        'docsearchModel' => $docsearchModel,
                        'docdataProvider' => $docdataProvider,
                        'pResearchersearchModel' => $pResearchersearchModel,
                        'pResearcherdataProvider' => $pResearcherdataProvider,
                        'assessForm' => $assessForm,
                        'assessFormParams' => $assessFormParams,
                        'staf' => $staf,
            ]);
        }
    }

    public function actionIndex() {
        $searchModel = new QuestionnaireAnswerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QuestionnaireAnswer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "QuestionnaireAnswer #" . $id,
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
     * Creates a new QuestionnaireAnswer model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new QuestionnaireAnswer();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new QuestionnaireAnswer",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new QuestionnaireAnswer",
                    'content' => '<span class="text-success">Create QuestionnaireAnswer success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new QuestionnaireAnswer",
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
     * Updates an existing QuestionnaireAnswer model.
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
                    'title' => "Update QuestionnaireAnswer #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "QuestionnaireAnswer #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update QuestionnaireAnswer #" . $id,
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
     * Delete an existing QuestionnaireAnswer model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing QuestionnaireAnswer model.
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
     * Finds the QuestionnaireAnswer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionnaireAnswer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function getSubmissionDocs($submission = NULL, $sCommitteeId, $cpId = NULL) {
//        $currentRole = \Yii::$app->session->get('currentRole');
        $docTypes = [];
        if (isset($cpId)) {
            if (!isset($submission->ref_submission_id) || (
                    $submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT && ($submission->refSubmission->resolution == Submission::RESOLUTION_R || $submission->refSubmission->resolution == Submission::RESOLUTION_N )
                    ))
                $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                                ->refSubmissionType(null)->submissionType($submission->submission_type_id)
                                ->submissionTypeRole(\app\models\Role::COMMITTEE)
                                ->committeePosition($cpId)->indexBy('id')->all();
        } else {
            $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                            ->submissionType($submission->submission_type_id)
                            ->submissionTypeRole(\app\models\Role::COMMITTEE)
                            ->andWhere('document_submission_type.committee_position_id IS NULL')->indexBy('id')->all();
        }
        $submissionDocs = [];
//        \yii\helpers\VarDumper::dump($docTypes, 4, TRUE);
//        exit;
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

        if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
            $firstRef = $submission->getFirstRefSubmission();
        } else if (isset($submission->refSubmission) && $submission->refSubmission->resolution == Submission::RESOLUTION_C) {
            $firstRef = $submission->refSubmission;
        }
        $docTypes1 = [];
        if (isset($firstRef) && isset($cpId)) {
            $docTypes1 = DocumentSubmissionType::find()->isDeleted(FALSE)->refSubmissionType($firstRef->submission_type_id)
                            ->submissionType($submission->submission_type_id)->submissionTypeRole(\app\models\Role::COMMITTEE)
                            ->committeePosition($cpId)->indexBy('id')->all();
//            \yii\helpers\VarDumper::dump($docTypes, 4, TRUE);
//        exit;
            foreach ($docTypes1 as $type) {
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
        }
//\yii\helpers\VarDumper::dump($submissionDocs, 4, TRUE);
//        exit;
        //$docs = SubmissionDocument::find()->joinWith('documentSubmissionTypes')->isDeleted(FALSE)->submission($submission->id)->submissionTypeRole(\app\models\Role::COMMITTEE)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
//        return $submissionDocs;
        $docTypes = array_merge($docTypes, $docTypes1);
        $docs = SubmissionCommitteeDocument::find()->isDeleted(FALSE)->submission($submission->id)->submissionCommittee($sCommitteeId)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
        return array_merge($submissionDocs, $docs);
    }

    protected function findModel($id) {
        if (($model = QuestionnaireAnswer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
