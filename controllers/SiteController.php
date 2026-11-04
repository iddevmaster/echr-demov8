<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\rbac\RbacController;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Submission;
use app\models\SubmissionSearch;
use yii\data\ActiveDataProvider;
use app\models\Role;
use Phpdocx\Create\CreateDocx;
use kartik\mpdf\Pdf;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;
use yii\helpers\ArrayHelper;

class SiteController extends RbacController {

    protected $allowedActions = ['login', 'logout', 'error', 'captcha', 'test-template', 'select-role', 'change-role', 'test', 'coa-check'];

    /**
     * @inheritdoc
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                        [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['get'],
//                ],
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    
        public function actionCoaCheck($sig =NULL) {
        $this->layout = 'register';

        $searchModel = new \app\models\SubmissionResultDocumentSearch();
        $searchModel->deleted = 0;
        $searchModel->coa_token = $sig;
        $searchModel->load(Yii::$app->request->queryParams);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('coa-check', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionIndex($panelId = NULL) {
        $currentRole = Yii::$app->session->get('currentRole');

        $submission = Submission::find()->joinWith('submissionType')->submissionTypeCon('1')->one();

        $user = NULL;
        $searchModel = new SubmissionSearch();
        $searchModel->deleted = 0;

        $searchModelMonitor = new SubmissionSearch();
        $userMonitor = \Yii::$app->user->identity->person->id;
        $searchModelMonitor->coiPersonId = $userMonitor;

        if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
            $searchModelMonitor->project_viewer_id = \Yii::$app->user->id;
        }
        $searchModelMonitor->deleted = 0;
        $dataProviderMonitor = $searchModelMonitor->search(Yii::$app->request->queryParams);
        $queryMonitor = $dataProviderMonitor->query;

        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $user = \Yii::$app->user->identity->person->id;
            $searchModel->is_leader = $user;
//            $searchModel->researcherPersonId = $user;
//            $searchModel->is_leader = 0;
        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $user = \Yii::$app->user->identity->person->id;
            $searchModel->committeeId = $user;
            $searchModel->coiPersonId = $user;
//            $searchModel->getCustomStatusCommitteeValues();
        }
        if ($currentRole['role_id'] == Role::STAFF) {
//            $user = \Yii::$app->user->identity->id;
//            $searchModel->responsible_person = $user;
            $searchModel->coiPersonId = $user;
        }
        //  $searchModel->created_by = Yii::$app->user->identity->id;
        if ($currentRole['role_id'] == Role::COPRESIDENT || $currentRole['role_id'] == Role::PRESIDENT || $currentRole['role_id'] == Role::SECRETARY) {
            //$searchModel->status = Submission::STATUS_MEETING_DONE;
            $searchModel->getCustomStatusValues();
            $user = \Yii::$app->user->identity->person->id;
            $searchModel->coiPersonId = $user;
            // $searchModel->panel_id = $panelId;
        }
        //$searchModel->deleted = 0;
        $panels = $currentRole['panels'];
        $dataProviders = [];
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        foreach ($panels as $panelId => $panelName) {
            $searchModel->panel_id = $panelId;
            $dataProviders[$panelId] = $searchModel->search(Yii::$app->request->queryParams);
            $dataProviders[$panelId]->pagination = ['pageSize' => 10];
        }

        if ($currentRole['role_id'] == Role::COORDINATOR) {
            $searchModel->panelId = NULL;
            $user = \Yii::$app->user->identity->id;
            $searchModel->project_coordinator_id = $user;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination = ['pageSize' => 10];
            return $this->render('@app/views/site/index-coordinator.php', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'searchModelMonitor' => $searchModelMonitor,
                        'dataProviderMonitor' => $dataProviderMonitor,
                        'queryMonitor' => $queryMonitor,
                        'panelId' => $panelId]);
        }
        if ($currentRole['role_id'] == Role::RESEARCHER) {
            $searchModel->panelId = NULL;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination = ['pageSize' => 10];
            return $this->render('@app/views/site/index-researcher.php', ['searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'panelId' => $panelId]);
        }
        if ($currentRole['role_id'] == Role::STAFF) {
            return $this->render('@app/views/site/index-staff.php', [
                        'searchModel' => $searchModel,
                        'dataProviders' => $dataProviders,
                        'panels' => $panels]);
        }
        if ($currentRole['role_id'] == Role::ADMIN) {
            return $this->render('@app/views/site/index-staff.php', [
                        'searchModel' => $searchModel,
                        'dataProviders' => $dataProviders,
                        'panels' => $panels]);
        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            if (\Yii::$app->devicedetect->isMobile()) {
                return $this->render('@app/views/site/index-committee-mobile.php', [
                            'searchModel' => $searchModel,
                            'dataProviders' => $dataProviders,
                            'panels' => $panels
                ]);
            } else {
                //  $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('@app/views/site/index-committee.php', [
                            'searchModel' => $searchModel,
                            'dataProviders' => $dataProviders,
                            'panels' => $panels]);
            }
        }
        if ($currentRole['role_id'] == Role::SECRETARY) {
            return $this->render('@app/views/site/index-secretary.php', [
                        'searchModel' => $searchModel,
                        'dataProviders' => $dataProviders,
                        'panels' => $panels,
                        'submission' => $submission]);
        }
        if ($currentRole['role_id'] == Role::COPRESIDENT) {
            return $this->render('@app/views/site/index-co-president.php', [
                        'searchModel' => $searchModel,
                        'dataProviders' => $dataProviders,
                        'panels' => $panels,
            ]);
        }
        if ($currentRole['role_id'] == Role::PRESIDENT) {
            return $this->render('@app/views/site/index-president.php', [
                        'searchModel' => $searchModel,
                        'dataProviders' => $dataProviders,
                        'panels' => $panels,
            ]);
        }
    }

    public function actionReport() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_CODE_GENERATED;
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAgendaPanelReport($pdf = NULL, $startDate = NULL, $endDate = NULL, $chart = false, $agendaIds = 0, $title = null) {

        ini_set("memory_limit", "256M");
        $d = new \DateTime;
        $searchModel = new SubmissionSearch([
            'scenario' => SubmissionSearch::SCENARIO_SUBMISSION_STATISTICS
        ]);
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->deleted = 0;
        $searchModel->load(Yii::$app->request->queryParams);

        if (isset($pdf)) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'content' => $this->renderPartial('agenda-panel-report-pdf', [
                    'searchModel' => $searchModel,
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        if ($chart) {
            if (empty($agendaIds)) {
                $agendas = \app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
            } else {
                $agendas =  \app\models\Agenda::find()->andWhere(['id' => \explode(',', $agendaIds)])->all();
            }
            return $this->render('agenda-panel-report-chart', [
                'searchModel' => $searchModel,
                'agendas' => $agendas,
                'title' => $title,
            ]);
        }
        return $this->render('agenda-panel-report', [
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionSubmissionFdaReport($pdf = NULL, $startDate = NULL, $endDate = NULL, $panelId = NULL) {
//        if (empty($startDate) && empty($startDate)) {
//            $startDate = date('Y-m-d');
//            $endDate = date('Y-m-d');
//        }
        $status = Submission::STATUS_CODE_GENERATED;
        $d = new \DateTime;
        $searchModel = new SubmissionSearch([
            'scenario' => SubmissionSearch::SCENARIO_SUBMISSION_STATISTICS
        ]);
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = $status;
        $searchModel->deleted = 0;
        $searchModel->fda = 1;
        $searchModel->load(Yii::$app->request->queryParams);

        if (isset($pdf)) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'content' => $this->renderPartial('submission-fda-report-pdf', [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'status' => $searchModel->status,
                    'panelId' => $panelId,
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                    'shrink_tables_to_fit' => 0,
                ],
                'cssInline' => 'body { font-family: thsarabun;font-size : 16px;  !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);

            $mPdf = $pdf->getApi();
            $mPdf->keep_table_proportions = true;
            $mPdf->SetDefaultFontSize(9);
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        return $this->render('submission-fda-report', [
                    'searchModel' => $searchModel,
                    'status' => $searchModel->status,
        ]);
    }

    public function actionSubmissionYearReport($pdf = NULL, $startDate = NULL, $endDate = NULL, $panelId = NULL) {
//        if (empty($startDate) && empty($startDate)) {
//            $startDate = date('Y-m-d');
//            $endDate = date('Y-m-d');
//        }
        $status = Submission::STATUS_AGENDA_ADDED;
        $d = new \DateTime;
        $searchModel = new SubmissionSearch([
            'scenario' => SubmissionSearch::SCENARIO_SUBMISSION_STATISTICS
        ]);
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = $status;
        $searchModel->deleted = 0;
        $searchModel->load(Yii::$app->request->queryParams);

        if (isset($pdf)) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');

            return $this->renderPartial('submission-year-report-pdf', [
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'status' => $searchModel->status,
                        'panelId' => $panelId,
                        'pdf' => 1,
            ]);
//            $pdf = new Pdf([
//                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
//                'destination' => Pdf::DEST_BROWSER,
//                'orientation' => Pdf::ORIENT_LANDSCAPE,
//                'content' => $this->renderPartial('submission-year-report-pdf', [
//                    'startDate' => $startDate,
//                    'endDate' => $endDate,
//                    'status' => $searchModel->status,
//                    'panelId' => $panelId,
//                ]),
//                'options' => [
//                    'fontDir' => array_merge($fontDirs, [
//                        Yii::getAlias('@app/web/fonts'),
//                    ]),
//                    'fontdata' => $fontData + [
//                'thsarabun' => [
//                    'R' => "THSarabunNew.ttf",
//                    'B' => "THSarabunNew-Bold.ttf",
//                    'I' => "THSarabunNew-Italic.ttf",
//                    'BI' => "THSarabunNew-BoldItalic.ttf"
//                ]
//                    ],
//                ],
//                'cssInline' => 'body { font-family: thsarabun !important }',
//                'methods' => [
//                    'SetTitle' => 'พิมพ์รายงาน',
//                    'SetSubject' => 'พิมพ์รายงาน',
//                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
//                    'SetFooter' => ['|หน้า {PAGENO}|'],
//                ]
//            ]);
//            $mPdf = $pdf->getApi();
//            $mPdf->SetDefaultFont('thsarabun');
//            return $pdf->render();
        }

        return $this->render('submission-year-report', [
                    'searchModel' => $searchModel,
                    'status' => $searchModel->status,
        ]);
    }

    public function actionAgenda5yearReport($pdf = NULL, $statusYear = NULL) {
        $d = new \DateTime;
        if (!isset($statusYear)) {
            $statusYear = $d->format('Y');
        }
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->statusYear = $statusYear;
        $searchModel->deleted = 0;
        $searchModel->load(Yii::$app->request->queryParams);

        if (isset($pdf)) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'content' => $this->renderPartial('agenda-5year-report-pdf', [
                    'statusYear' => $searchModel->statusYear,
                    'searchModel' => $searchModel
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        return $this->render('agenda-5year-report', [
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionReportNew() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_CODE_GENERATED;
        $searchModel->deleted = 0;

//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->load(Yii::$app->request->queryParams);

        return $this->render('report-new', [
                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportSumNew($pdf = NULL, $startDate = NULL, $endDate = NULL, $researcherOrg = NULL, $researcherDep = NULL, $researcherDiv = NULL) {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
        $searchModel->deleted = 0;

//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->load(Yii::$app->request->queryParams);


        if (isset($pdf)) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'content' => $this->renderPartial('report-sum-new-pdf', [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'researcherOrg' => $researcherOrg,
                    'researcherDep' => $researcherDep,
                    'researcherDiv' => $researcherDiv,
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }


        return $this->render('report-sum-new', [
                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportNewMonthly() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
//        $searchModel->startDate = $d->format('Y-m-d');
//        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->statusYear = $d->format('Y');
        $searchModel->deleted = 0;

//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->load(Yii::$app->request->queryParams);

        return $this->render('report-new-monthly', [
                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportConMonthly() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
//        $searchModel->startDate = $d->format('Y-m-d');
//        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->statusYear = $d->format('Y');
        $searchModel->deleted = 0;

//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->load(Yii::$app->request->queryParams);

        return $this->render('report-con-monthly', [
                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportNewPanel() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_CODE_GENERATED;
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report-new-panel', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportCon() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_CODE_GENERATED;
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report-con', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportConPanel() {
        $d = new \DateTime;

        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->status = Submission::STATUS_CODE_GENERATED;
        $searchModel->deleted = 0;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report-con-panel', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportSummaryDuration($pdf = NULL, $submissionTypeGroupId = NULL, $panelId = NULL, $startDate = NULL, $endDate = NULL, $chart = false) {
        $d = new \DateTime;
        $results = [];

        $searchModel = new SubmissionSearch([
            'scenario' => SubmissionSearch::SCENARIO_SUMMARY_DURATION
        ]);
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        if ($searchModel->load(Yii::$app->request->queryParams) && !empty($searchModel->submission_type_group_id)) {
            //\yii\helpers\VarDumper::dump($searchModel->startDate);
            //\yii\helpers\VarDumper::dump($searchModel->endDate);
            //exit;
            $submissionTypes = SubmissionType::find()->isDeleted(false)->group($searchModel->submission_type_group_id)->internal(false)->all();

            foreach ($submissionTypes as $type) {
                $results[$type->name] = [];
                $submissions = Submission::find()->joinWith(['project'])->isDeleted(false)->noRef()->submissionType($type->id)->panel($searchModel->panel_id)
                        ->dateStatusBetween($searchModel->startDate, $searchModel->endDate, Submission::STATUS_CODE_GENERATED)
                        ->status(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)
                        ->all();
//                \yii\helpers\VarDumper::dump(count($submissions), 10, true);
                $submissions = array_filter($submissions, function($model) {
                    return $model->latestResolution != null && !in_array($model->latestResolution, [Submission::RESOLUTION_R,Submission::RESOLUTION_N, Submission::RESOLUTION_C]);
                });
                $durations = $type->getSubmissionTypeDurations()->isDeleted(false)->all();
//            \yii\helpers\VarDumper::dump($durations, 10, true);
                foreach ($durations as $dur) {
                    $days = ArrayHelper::getColumn($submissions, SubmissionType::durationTypeFunction()[$dur->duration_type]);
//                    if ($type->id == 9) {
////                        \yii\helpers\VarDumper::dump($submissions);
//                        \yii\helpers\VarDumper::dump($days, 10, true);
//                        exit;
//                    }
                    $results[$type->name][SubmissionType::durationTypeLabels()[$dur->duration_type]] = [
                        Yii::t('app', 'ค่าเฉลี่ย') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->mmmr($days, 'mean'), 2) : null,
                        Yii::t('app', 'ค่ากลาง') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->mmmr($days, 'median'), 0) : null,
                        Yii::t('app', 'SD') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->standardDeviation($days), 2) : null,
                        Yii::t('app', 'มากสุด') => count($days) ? max($days) : null,
                        Yii::t('app', 'ต่ำสุด') => count($days) ? min($days) : null,
                    ];
                }
            }

//            \yii\helpers\VarDumper::dump($approveToEndorses, 10, true);
        }
//        \yii\helpers\VarDumper::dump($results, 10, true);
//        exit;
//        $searchModel = new \app\models\SubmissionSearch();
//        $searchModel->startDate = '2019-01-01';//$d->format('Y-m-d');
//        $searchModel->endDate = $d->format('Y-m-d');
//        $searchModel->deleted = 0;
//        $searchModel->noRef = 1;
//
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $searchModel->load(Yii::$app->request->queryParams);

        if (isset($pdf)) {
            $results = [];

            $submissionTypes = SubmissionType::find()->isDeleted(false)->group($submissionTypeGroupId)->internal(false)->all();

            foreach ($submissionTypes as $type) {
                $results[$type->name] = [];
                $submissions = Submission::find()->joinWith(['project'])->isDeleted(false)->noRef()->submissionType($type->id)->panel($panelId)
                        ->dateStatusBetween($startDate, $endDate, Submission::STATUS_CODE_GENERATED)
                        ->status(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)
                        ->all();
                //\yii\helpers\VarDumper::dump($submissions, 10, true);
                $submissions = array_filter($submissions, function($model) {
                    return $model->latestResolution != null && !in_array($model->latestResolution, [Submission::RESOLUTION_R,Submission::RESOLUTION_N, Submission::RESOLUTION_C]);
                });
                $durations = $type->getSubmissionTypeDurations()->isDeleted(false)->all();
//            \yii\helpers\VarDumper::dump($durations, 10, true);
                foreach ($durations as $dur) {
                    $days = ArrayHelper::getColumn($submissions, SubmissionType::durationTypeFunction()[$dur->duration_type]);
//                    if ($type->id == 9) {
////                        \yii\helpers\VarDumper::dump($submissions);
//                        \yii\helpers\VarDumper::dump($days, 10, true);
//                        exit;
//                    }
                    $results[$type->name][SubmissionType::durationTypeLabels()[$dur->duration_type]] = [
                        Yii::t('app', 'ค่าเฉลี่ย') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->mmmr($days, 'mean'), 2) : null,
                        Yii::t('app', 'ค่ากลาง') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->mmmr($days, 'median'), 2) : null,
                        Yii::t('app', 'SD') => count($days) ? Yii::$app->formatter->asDecimal(Yii::$app->util->standardDeviation($days), 2) : null,
                        Yii::t('app', 'มากสุด') => count($days) ? max($days) : null,
                        Yii::t('app', 'ต่ำสุด') => count($days) ? min($days) : null,
                    ];
                }
            }

            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'content' => $this->renderPartial('report-summary-duration-pdf', [
                    'results' => $results,
                    'submissionTypeGroupId' => $submissionTypeGroupId,
                    'panelId' => $panelId,
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        if ($chart) {
            return $this->render('report-summary-duration-chart', [
                'searchModel' => $searchModel,
                'results' => $results,
            ]);
        }
        return $this->render('report-summary-duration', [
                    'searchModel' => $searchModel,
                    'results' => $results,
        ]);
    }

    public function actionReportSubmissionStatistics() {
        ini_set("memory_limit", "256M");
        $d = new \DateTime;
        $searchModel = new SubmissionSearch([
            'scenario' => SubmissionSearch::SCENARIO_SUBMISSION_STATISTICS
        ]);
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        if ($searchModel->agendaId != 15) {
            $searchModel->noRef = 1;
        }
        $searchModel->status = Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->get('pdf')) {

            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'content' => $this->renderPartial('report-submission-statistics-pdf', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => "THSarabunNew.ttf",
                    'B' => "THSarabunNew-Bold.ttf",
                    'I' => "THSarabunNew-Italic.ttf",
                    'BI' => "THSarabunNew-BoldItalic.ttf"
                ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        return $this->render('report-submission-statistics', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionMasterList() {
        $menus = [
            'master' => [
                'label' => yii::t('app', 'ค่าเริ่มต้น'),
                'items' => [
                    ['label' => yii::t('app', 'ตั้งค่าโปรแกรม'), 'url' => ['setting/index']],
//                        ['label' => yii::t('app', 'กำหนดภาษา'), 'url' => ['']],
                    ['label' => yii::t('app', 'คำนำหน้าชื่อ'), 'url' => ['title/index']],
                    ['label' => yii::t('app', 'องค์กร'), 'url' => ['organization/index']],
                    ['label' => yii::t('app', 'หน่วยงาน/คณะ'), 'url' => ['department/index']],
                    ['label' => yii::t('app', 'แผนก/ภาควิชา'), 'url' => ['division/index']],
                    ['label' => yii::t('app', 'ตำแหน่งงาน'), 'url' => ['position/index']],
                    ['label' => yii::t('app', 'ความชำนาญในที่ประชุม'), 'url' => ['job-category/index']],
                    ['label' => yii::t('app', 'ทะเบียนเอกสาร'), 'url' => ['document/index']],
                    ['label' => yii::t('app', 'ทะเบียนหนังสือแจ้งผล'), 'url' => ['result-document/index']],
                    ['label' => yii::t('app', 'แหล่งเงินทุน'), 'url' => ['funding-source/index']],
                    ['label' => yii::t('app', 'ห้องประชุม'), 'url' => ['meeting-room/index']],
                    ['label' => yii::t('app', 'คำถามเพิ่มเติมตอนประชุม'), 'url' => ['project-question/index']],
                    ['label' => yii::t('app', 'ตำแหน่งกรรมการ'), 'url' => ['committee-position/index']],
                    ['label' => yii::t('app', 'ประเภทการอบรม'), 'url' => ['training-type/index']],
                    ['label' => yii::t('app', 'เกณฑ์การอบรมตามประเภทโครงการ'), 'url' => ['submission-type-training-requirement/index']],
                //  ['label' => 'กลุ่มประเภทโครงการวิจัย', 'url' => ['product/index']],
                ],
            ],
            'person' => [
                'label' => yii::t('app', 'จัดการข้อมูลสำหรับผู้ใช้งาน'),
                'items' => [
                    ['label' => yii::t('app', 'จัดการผู้ใช้งาน'), 'url' => ['person/index']],
                    ['label' => yii::t('app', 'กำหนด Administrator'), 'url' => ['person-role/select-person', 'id' => Role::ADMIN]],
                    ['label' => yii::t('app', 'กำหนดประธาน'), 'url' => ['person-role/select-person', 'id' => Role::PRESIDENT]],
                    ['label' => yii::t('app', 'กำหนดรองประธาน'), 'url' => ['person-role/select-person', 'id' => Role::COPRESIDENT]],
                    ['label' => yii::t('app', 'กำหนดคณะกรรมการ'), 'url' => ['person-role/select-person', 'id' => Role::COMMITTEE]],
                    ['label' => yii::t('app', 'กำหนดเลขา'), 'url' => ['person-role/select-person', 'id' => Role::SECRETARY]],
                    ['label' => yii::t('app', 'กำหนดเจ้าหน้าที่'), 'url' => ['person-role/select-person', 'id' => Role::STAFF]],
                    ['label' => yii::t('app', 'กำหนดผู้ประสานงานโครงการ'), 'url' => ['person-role/select-person', 'id' => Role::COORDINATOR, 'co' => 1]],
                    ['label' => yii::t('app', 'กำหนดนักวิจัย'), 'url' => ['person-role/select-person', 'id' => Role::RESEARCHER, 'co' => 1]],
                ],
            ], 'submission' => [
                'label' => yii::t('app', 'ค่าตั้งต้นเกี่ยวกับประเภทโครงการวิจัย'),
                'items' => [
                    ['label' => yii::t('app', 'กำหนดเอกสารตามประเภทโครงการสำหรับนักวิจัย'), 'url' => ['document-submission-type/index', 'roleId' => Role::RESEARCHER]],
                    ['label' => yii::t('app', 'กำหนดเอกสารตามประเภทโครงการสำหรับกรรมการ'), 'url' => ['document-submission-type/index', 'roleId' => Role::COMMITTEE]],
                    ['label' => yii::t('app', 'กำหนดอาสาสมัครของประเภทโครงการ'), 'url' => ['submission-type-volunteer-number/index']],
//                    ['label' => yii::t('app', 'แบบฟอร์มประเมินสำหรับกรรมการ'), 'url' => ['questionnaire-title/index-list-submission-type']],
                    ['label' => yii::t('app', 'รายละเอียดประเภทโครงการวิจัย'), 'url' => ['project-type/index']],
                ],
            ],
            'letter' => [
                'label' => yii::t('app', 'ค่าตั้งต้นเอกสารหนังสือแจ้งผล'),
                'items' => [
                    ['label' => yii::t('app', 'กำหนดเอกสารหนังสือแจ้งผลตามวาระการประชุม'), 'url' => ['agenda-result-document/index']],
                ],
            ],
        ];

//        return $this->redirect(['/content']);
        return $this->render('master-list', [
                    'menus' => $menus,
        ]);
    }

    public function actionReportList() {
        $menus = [
            'report' => [
                'label' => yii::t('app', 'รายงานในรูปแบบตารางข้อมูล'),
                'items' => [
                    ['label' => yii::t('app', 'รายงานโครงการวิจัย'), 'url' => ['site/report-submission-statistics']],
//                    ['label' => yii::t('app', 'รายงานสถิติระยะเวลาการพิจารณาของโครงการใหม่'), 'url' => ['submission/submission-report-duration', 'submissionTypeGroupId' => \app\models\SubmissionTypeGroup::GROUP_NEW]],
//                    ['label' => yii::t('app', 'รายงานสถิติระยะเวลาการพิจารณาของโครงการต่อเนื่อง'), 'url' => ['submission/submission-report-duration', 'submissionTypeGroupId' => \app\models\SubmissionTypeGroup::GROUP_CONT]],
                    ['label' => yii::t('app', 'รายงานสถิติการอ่านและเข้าประชุมของกรรมการ'), 'url' => ['submission-committee/report']],
                    ['label' => yii::t('app', 'รายงานโครงการที่กำลังจะหมดอายุรับรอง'), 'url' => ['project/report', 'date' => 'expire_at']],
                    ['label' => yii::t('app', 'รายงานโครงการที่จะต้องรายงานความก้าวหน้า'), 'url' => ['project/report', 'date' => 'next_progress_at']],
                    ['label' => yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)'), 'url' => ['site/agenda-panel-report', 'date' => 'next_progress_at']],
                    ['label' => yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ ย้อนหลัง 5 ปี'), 'url' => ['site/agenda-5year-report', 'date' => 'next_progress_at']],
                    ['label' => yii::t('app', 'สรุประยะเวลาที่ใช้ในการพิจารณาโครงการวิจัยแต่ละประเภท'), 'url' => ['site/report-summary-duration']],
                    ['label' => yii::t('app', 'รายงานผลการดำเนินโครงการวิจัยทางคลินิกเกี่ยวกับยา'), 'url' => ['site/submission-fda-report']],
                    ['label' => yii::t('app', 'รายการโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาฯ'), 'url' => ['site/submission-year-report']],
                    ['label' => yii::t('app', 'รายงานสถิติโครงการใหม่'), 'url' => ['site/report-sum-new']],
                    ['label' => yii::t('app', 'รายงาน Deviation'), 'url' => ['report/deviation']],
                    ['label' => yii::t('app', 'รายงาน Sae'), 'url' => ['report/sae']],
                    ['label' => yii::t('app', '1.1.รายงานสถิติการดำเนินการอ่านโครงการวิจัยและการเข้าร่วมประชุมของกรรมการแต่ละท่าน'), 'url' => ['report/committee-review-count']],
                    ['label' => yii::t('app', '1.2. สถิติการเข้าประชุมของกรรมการ'), 'url' => ['report/committee-review-meeting']],
                    ['label' => yii::t('app', '1.4.1 i รายงานระยะเวลาดำเนินการ แต่ละ PANEL'), 'url' => ['site/report-summary-duration', 'chart' => 1]],
                    ['label' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยใหม่ Full Board)'), 'url' => ['site/agenda-panel-report', 'chart' => 1, 'title' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยใหม่ Full Board)'), 'agendaIds' => '12,13,14,15']],
                    ['label' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยใหม่ exemption/expedited)'), 'url' => ['site/agenda-panel-report', 'chart' => 1, 'title' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยใหม่ exemption/expedited)'), 'agendaIds' => '8,9,21']],
                    ['label' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยต่อเนื่อง)'), 'url' => ['site/agenda-panel-report', 'chart' => 1, 'title' => Yii::t('app', 'รายงานจำนวนโครงการวิจัยแต่ละ Panel (โครงการวิจัยต่อเนื่อง)'), 'agendaIds' => '6,7,10,11,16,17,18,22']],
                    ['label' => Yii::t('app', 'จำนวนโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาแยกตามแหล่งทุน'), 'url' => ['report/submission-by-funding-source']],
                    ['label' => Yii::t('app', 'รายงานประเภทโครงการวิจัยจากหน้ารายงานประชุม'), 'url' => ['report/submission-by-project-agenda-answer']],
                ],
            ],
        ];
        $gds = [
            'report' => [
                'label' => yii::t('app', 'รายงานในรูปแบบแผนภูมิ'),
                'items' => [
                    ['label' => yii::t('app', 'จำนวนโครงการโดยแยกตามประเภทโครงการใหม่ และโครงการต่อเนื่อง'), 'url' => ['site/report']],
                    ['label' => yii::t('app', 'โครงการวิจัยใหม่ที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา'), 'url' => ['site/report-new']],
                    ['label' => yii::t('app', 'โครงการวิจัยต่อเนื่องที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา'), 'url' => ['site/report-con']],
                    ['label' => yii::t('app', 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรกรมการ จำแนกตามกลุ่มสาขาวิชา'), 'url' => ['site/report-new-panel']],
                    ['label' => yii::t('app', 'โครงการวิจัยต่อเนื่องที่เสนอขอรับการพิจารณาจากคณะกรกรมการ จำแนกตามกลุ่มสาขาวิชา'), 'url' => ['site/report-con-panel']],
                    ['label' => yii::t('app', 'สรุปจำนวนโครงการวิจัยใหม่ที่เสนอขอรับการรับรองจากที่ประชุมคณะกรรมการฯ จำแนกตามเดือนและประจำสาขาวิชา'), 'url' => ['site/report-new-monthly']],
                    ['label' => yii::t('app', 'สรุปจำนวนโครงการวิจัยต่อเนื่องที่เสนอขอรับการรับรองจากที่ประชุมคณะกรรมการฯ จำแนกตามเดือนและประจำสาขาวิชา'), 'url' => ['site/report-con-monthly']],
                    ['label' => yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)'), 'url' => ['report/submission-by-agenda-graph']],
                    ['label' => yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) ตามปี'), 'url' => ['report/submission-by-agenda-year-graph']],
                    ['label' => yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระและประเภทโครงการ ตามปี'), 'url' => ['report/submission-by-type-year-graph']],
                    ['label' => yii::t('app', 'กราฟรายงานภาพรวมการพิจารณาของกรรมการ'), 'url' => ['report/review-count-by-reviewer-graph']],
                ],
            ],
        ];
//        return $this->redirect(['/content']);
        return $this->render('report-list', [
                    'menus' => $menus,
                    'gds' => $gds
        ]);
    }

    public function actionSelectRole($personRoleId) {
        $personRole = \app\models\PersonRole::findOne($personRoleId);
//        Yii::$app->user->identity->currentRole = $personRole;
        Yii::$app->session->set('currentRole', $personRole->toArrayData());

//        exit;
        return $this->redirect(['site/index']);
    }

    public function actionChangeRole() {
        $this->layout = 'full';
        $roles = Yii::$app->user->identity->person->getAvailableRoles();
        return $this->render('select-role', [
                    'roles' => $roles
        ]);
    }

    public function actionLogin() {
        $this->layout = 'full';
        if (!Yii::$app->user->isGuest) {
            if (!Yii::$app->session->has('currentRole')) {
                $roles = Yii::$app->user->identity->person->getPersonRoles()->isDeleted(FALSE)->all();
                return $this->render('select-role', [
                            'roles' => $roles
                ]);
            }
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $roles = Yii::$app->user->identity->person->getPersonRoles()->isDeleted(FALSE)->all();
            if (count($roles) > 1) {
//                \yii\helpers\VarDumper::dump($roles);
//                exit;
                return $this->render('select-role', [
                            'roles' => $roles
                ]);
            } else {
                Yii::$app->session->set('currentRole', $roles[0]->toArrayData());
                return $this->redirect(['site/index']);
//                \yii\helpers\VarDumper::dump($roles);
//                \yii\helpers\VarDumper::dump(Yii::$app->user->identity, 10, TRUE);
//                exit;
            }

            return $this->goBack();
        }

        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['location' => Yii::$app->getHomeUrl()];
        } else {
            return $this->goHome();
        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionTestTemplate() {
        $file = Yii::getAlias('@app/web/uploads/template/Sample_07_TemplateCloneRow.docx');
        $fileResult = Yii::getAlias('@app/web/uploads/template/Sample_07_TemplateCloneRow-result.docx');
        $html = '';
        $html .= '<p>List with complex content:</p>';
        $html .= '<ul>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 12px;">list item1</span>
                    </span>
                </li>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;color: red;">
                        <span style="font-size: 12px;">list item2</span>
                    </span>
                </li>
            </ul>';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
//        \yii\helpers\VarDumper::dump($word->getSections(), 10, TRUE);
//        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        $phpWord->save($fileResult);
//        \yii\helpers\VarDumper::dump(XmlWriter, 10, TRUE);
//        exit;
//        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);
//        $templateProcessor->setValue('weekday', 'ทดสอบ');
//        $templateProcessor->saveAs($fileResult);

        return $this->render('test-template');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionSelectProject() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', "เลือกโครงการวิจัย"),
                'content' => $this->renderAjax('select-project', [
                ]),
                'footer' => Html::button(Yii::t('app', "ดาวน์โหลด"), ['class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('select-project');
        }
    }

    public function actionTest() {
        $docx = new CreateDocx();
        $file = \Yii::getAlias('@app/web/tmp/HE621503_2.docx');
        $pdf = \Yii::getAlias('@app/web/tmp/document.pdf');
        $docx->transformDocument($file, $pdf, 'libreoffice', ['homeFolder' => \Yii::getAlias('@app')]);
    }

}
