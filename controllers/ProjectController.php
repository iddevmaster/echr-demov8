<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\DocumentSubmissionTypeSearch;
use app\models\ProjectResearcherSearch;
use yii\widgets\ActiveForm;
use app\models\Submission;
use app\models\DocumentSubmisstionType;
use app\models\SubmissionDocument;
use kartik\alert\Alert;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends RbacController {

    /**
     * @inheritdoc
     */
    const REGISTER_STEP1 = 1;
    const REGISTER_STEP2 = 2;
    const REGISTER_STEP3 = 3;
    const REGISTER_STEP4 = 4;

    public static function registerSteps() {
        return [
            self::REGISTER_STEP1 => [
                'step' => self::REGISTER_STEP1,
                'label' => 'ข้อมูลทั่วไป',
                'icon' => 'icon md-settings'
            ],
            self::REGISTER_STEP2 => [
                'step' => self::REGISTER_STEP2,
                'label' => 'ผู้ร่วมวิจัย',
                'icon' => 'icon md-account'
            ],
            self::REGISTER_STEP3 => [
                'step' => self::REGISTER_STEP3,
                'label' => 'เอกสารงานวิจัย',
                'icon' => 'icon md-card'
            ],
            self::REGISTER_STEP4 => [
                'step' => self::REGISTER_STEP4,
                'label' => 'ยืนยันการส่งโครงการวิจัย',
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

    protected $allowedActions = ['index-committee'];

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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport($date = NULL) {
        $searchModel = new ProjectSearch();
        $searchModel->deleted = 0;
        if ($date == 'expire_at') {
            $searchModel->expire_at1 = date('Y-m-d');
            $searchModel->expire_at2 = date('Y-m-d');
        } else {
            $searchModel->next_progress_at1 = date('Y-m-d');
            $searchModel->next_progress_at2 = date('Y-m-d');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'date' => $date
        ]);
    }

    public function actionIndexContinue() {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-continue', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexCommittee() {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-committee', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Project #" . $id,
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

    public function actionProjectSubmission() {
        $searchModel = new \app\models\SubmissionDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'size' => 'large',
                'title' => "Project",
                'content' => $this->renderAjax('submission-document/index', (['searchModel' => $searchModel, 'dataProvider' => $dataProvider])),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('submission-document/index', [
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Creates a new Project model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new Project();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Project",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Project",
                    'content' => '<span class="text-success">Create Project success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new Project",
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

    public function actionHeNumber() {
        $request = Yii::$app->request;
        $model = new Project();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'size' => 'large',
                    'title' => "กำหนดหมายเลยโครงการ",
                    'content' => $this->renderAjax('he-number', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('ส่ง Email แจ้งหัวหน้าโครงการวิจัย', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Project",
                    'content' => '<span class="text-success">Create Project success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "กำหนดหมายเลยโครงการ",
                    'content' => $this->renderAjax('he-number', [
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
                return $this->render('he-number', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Project model.
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
                    'title' => "Update Project #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Project #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Project #" . $id,
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
     * Delete an existing Project model.
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
     * Delete multiple existing Project model.
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function getSubmissionDocs($submission) {
        $docTypes = DocumentSubmisstionType::find()->isDeleted(FALSE)->submissionType($submission->submission_type_id)->indexBy('id')->all();
        $submissionDocs = [];
        foreach ($docTypes as $type) {
            $doc = SubmissionDocument::find()->isDeleted(FALSE)->documents($type->id)->one();
            if (!isset($doc)) {
                $doc = new SubmissionDocument();
                $doc->document_id = $type->document_id;
                $doc->submission_id = $submission->id;
                $doc->name = $type->document->name;
            }

            $submissionDocs[] = $doc;
        }
        $docs = SubmissionDocument::find()->isDeleted(FALSE)->notInDocuments(ArrayHelper::getColumn($docTypes, 'document_id'))->all();
        return array_merge($submissionDocs, $docs);
    }

    public function actionSubmission() {
        $id = 1;
        $request = Yii::$app->request;
        $project = new Project();
        $submission = new Submission();
        //$profile = new Person();

        $sdocSearch = new DocumentSubmissionTypeSearch();
        $sdocSearch->deleted = 0;
        $sdocSearch->submission_type_id = 1;
        $sdocProvider = $sdocSearch->search(Yii::$app->request->queryParams);

        $presearcherSearch = new ProjectResearcherSearch();
        $presearcherSearch->deleted = 0;
        $presearcherSearch->project_id = $id;
        $presearcherProvider = $presearcherSearch->search(Yii::$app->request->queryParams);

        $steps = self::registerSteps();
        $step = null !== $request->post('step') ? $request->post('step') : self::REGISTER_STEP1;
        $project->load($request->post());
        $submission->load($request->post());

        $submissionDocs = $this->getSubmissionDocs($submission);
        // $profile->load($request->post());
//        if (is_int($company->id)) {
//            $company = Company::findOne($company->id);
//            $company->load($request->post());
//        }

        if ($step == self::REGISTER_STEP1) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($project, $submission);
            }
            if (null !== $request->post('next-step')) {
                $step++;
                $project->save(FALSE);
                $submission->project_id = $project->id;
                $submission->save(FALSE);
                $submissionDocs = $this->getSubmissionDocs($submission);
            }
        } else if ($step == self::REGISTER_STEP2) {

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
                    return Model::loadMultiple($submissionDocs, $request->post()) && Model::validateMultiple($submissionDocs);
                }

                if (null !== $request->post('next-step')) {
                    $step++;
                }
            }
        } else if ($step == self::REGISTER_STEP3) {
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
        } else if ($step == self::REGISTER_STEP4) {
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

        $sdocProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $submissionDocs,
        ]);

        return $this->render('submission', [
                    'project' => $project,
//                    'docTypes' => $docTypes,
                    'submission' => $submission,
//                    'sdocSearch' => $sdocSearch,
                    'sdocProvider' => $sdocProvider,
                    'presearcherSearch' => $presearcherSearch,
                    'presearcherProvider' => $presearcherProvider,
                    'steps' => $steps,
                    'step' => $step,
                    'id' => $id,
        ]);
    }

    public function actionToggleActive($id, $reload = NULL) {
        $reload = isset($reload) ? $reload : '#crud-datatable-submission-pjax';
        $request = Yii::$app->request;
        $model = Project::findOne($id);
        $model->is_active = ($model->is_active ? 0 : 1);
        if ($model->is_active == 0) {
            $model->is_closed = 1;
        } else {
            $model->is_closed = 0;
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
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

    public function actionSubmissionComplete() {
        // $this->layout = 'register';
        return $this->render('submission-complete');
    }

    public function actionGetInfo($id) {
        // $this->layout = 'register';
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Project::findOne($id);
    }

}
