<?php

namespace app\controllers;

use Yii;
use app\models\ContinueAssessForm;
use app\models\ContinueAssessFormSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\ContinueAssessFormEthics;
use app\models\Ethics;
use app\models\ReviewChoice;
use app\models\ContinueAssessFormReview;
use app\models\Resolution;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * ContinueAssessFormController implements the CRUD actions for ContinueAssessForm model.
 */
class ContinueAssessFormController extends RbacController {

    /**
     * @inheritdoc
     */
//        protected $allowedActions = ['create'];

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
     * Lists all ContinueAssessForm models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ContinueAssessFormSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContinueAssessForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ContinueAssessForm #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionPrint($id) {
        $model = ContinueAssessForm::findOne($id);

        $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
        $conEthicses = [];
        foreach ($ethicses as $ethics) {
            $conEthics = ContinueAssessFormEthics::find()->isDeleted(false)->continueAssessForm($model->id)->ethics($ethics->id)->one();
            if (!isset($conEthics)) {
                $conEthics = new ContinueAssessFormEthics();
                $conEthics->continue_assess_form_id = $model->id;
                $conEthics->ethics_id = $ethics->id;
            }
            $conEthicses[$ethics->id] = $conEthics;
        }

        $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
        $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
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
            'content' => $this->renderPartial('print', [
                'model' => $model,
                'ethicses' => $ethicses,
                'conEthicses' => $conEthicses,
                'reviewChoices' => $reviewChoices,
                'resolutions' => $resolutions,
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
            ], 
                    'fontawesome' => [
                'R' => 'fa-regular-400.ttf'
            ],
            'fa-solid' => [
                'R' => 'fa-solid-900.ttf'
            ],
                ],
            ],
            'cssFile' => '@app/web/css/pdf.css',
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

    /**
     * Creates a new ContinueAssessForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionId, $submissionCommitteeId) {
        $request = Yii::$app->request;
        $model = ContinueAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($submissionCommitteeId)->one();
        if (!isset($model)) {
            $model = new ContinueAssessForm();
            $model->submission_id = $submissionId;
            $model->submission_committee_id = $submissionCommitteeId;
        }

        $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
        $conEthicses = [];
        foreach ($ethicses as $ethics) {
            $conEthics = ContinueAssessFormEthics::find()->isDeleted(false)->continueAssessForm($model->id)->ethics($ethics->id)->one();
            if (!isset($conEthics)) {
                $conEthics = new ContinueAssessFormEthics();
                $conEthics->continue_assess_form_id = $model->id;
                $conEthics->ethics_id = $ethics->id;
            }
            $conEthicses[$ethics->id] = $conEthics;
        }

        $reviewChoices = ReviewChoice::find()->isDeleted(false)->parent(null)->orderBy('id')->all();
        $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->validate() 
                    && Model::loadMultiple($conEthicses, $request->post()) 
                    && Model::validateMultiple($conEthicses)) {
//                return $this->redirect(['view', 'id' => $model->id]);
                $model->save(false);
                if (isset($model->reviewIds)) {
                    ContinueAssessFormReview::deleteAll(['and', ['continue_assess_form_id' => $model->id], ['not', ['review_choice_id' => $model->reviewIds]]]);
                    foreach ($model->reviewIds as $reviewId) {
                        $cr = ContinueAssessFormReview::find()->isDeleted(FALSE)->continueAssessForm($model->id)->reviewChoice($reviewId)->one();
                        if (!isset($cr)) {
                            $cr = new ContinueAssessFormReview();
                            $cr->continue_assess_form_id = $model->id;
                            $cr->review_choice_id = $reviewId;
                            $cr->save(false);
                        }
                    }
                }
                foreach ($conEthicses as $conEthics) {
                    $conEthics->continue_assess_form_id = $model->id;
                    $conEthics->save(false);
                }
                return ['reload' => '#submission-type-assess-form-pjax', 'message' => \Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว')];
            } else {
                $form = new \yii\widgets\ActiveForm();
                return ['error' => true, 'message' => $form->errorSummary(array_merge([$model], $conEthicses))];
            }
//            if ($request->isGet) {
//                return [
//                    'title' => Yii::t('app', "เพิ่ม ContinueAssessForm"),
//                    'content' => $this->renderAjax('create', [
//                        'model' => $model,
//                        'ethicses' => $ethicses,
//                        'conEthicses' => $conEthicses,
//                        'reviewChoices' => $reviewChoices,
//                        'resolutions' => $resolutions,
//                    ]),
//                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
//                ];
//            } else if ($model->load($request->post()) && $model->validate() && Model::loadMultiple($conEthicses, $request->post()) && Model::validateMultiple($conEthicses)) {
////                return $this->redirect(['view', 'id' => $model->id]);
//                $model->save(false);
//                ContinueAssessFormReview::deleteAll(['and', ['continue_assess_form_id' => $model->id], ['not', ['review_choice_id' => $model->reviewIds]]]);
//                foreach ($model->reviewIds as $reviewId) {
//                    $cr = ContinueAssessFormReview::find()->isDeleted(FALSE)->continueAssessForm($model->id)->reviewChoice($reviewId)->one();
//                    if (!isset($cr)) {
//                        $cr = new ContinueAssessFormReview();
//                        $cr->continue_assess_form_id = $model->id;
//                        $cr->review_choice_id = $reviewId;
//                        $cr->save(false);
//                    }
//                }
//                foreach ($conEthicses as $conEthics) {
//                    $conEthics->continue_assess_form_id = $model->id;
//                    $conEthics->save(false);
//                }
//                return [
//                    'forceReload' => '#crud-datatable-continue-assess-form-pjax',
//                    'title' => Yii::t('app', "เพิ่ม ContinueAssessForm"),
//                    'content' => $this->renderAjax('create', [
//                        'model' => $model,
//                        'ethicses' => $ethicses,
//                        'conEthicses' => $conEthicses,
//                        'reviewChoices' => $reviewChoices,
//                        'resolutions' => $resolutions,
//                    ]),
//                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
//                ];
//            } else {
//                return [
//                    'title' => Yii::t('app', "เพิ่ม ContinueAssessForm"),
//                    'content' => $this->renderAjax('create', [
//                        'model' => $model,
//                        'ethicses' => $ethicses,
//                        'conEthicses' => $conEthicses,
//                        'reviewChoices' => $reviewChoices,
//                        'resolutions' => $resolutions,
//                    ]),
//                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
//                ];
//            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->validate() && Model::loadMultiple($conEthicses, $request->post()) && Model::validateMultiple($conEthicses)) {
//                return $this->redirect(['view', 'id' => $model->id]);
                $model->save(false);
                ContinueAssessFormReview::deleteAll(['and', ['continue_assess_form_id' => $model->id], ['not', ['review_choice_id' => $model->reviewIds]]]);
                foreach ($model->reviewIds as $reviewId) {
                    $cr = ContinueAssessFormReview::find()->isDeleted(FALSE)->continueAssessForm($model->id)->reviewChoice($reviewId)->one();
                    if (!isset($cr)) {
                        $cr = new ContinueAssessFormReview();
                        $cr->continue_assess_form_id = $model->id;
                        $cr->review_choice_id = $reviewId;
                        $cr->save(false);
                    }
                }
                foreach ($conEthicses as $conEthics) {
                    $conEthics->continue_assess_form_id = $model->id;
                    $conEthics->save(false);
                }
                return $this->render('create', [
                            'model' => $model,
                            'ethicses' => $ethicses,
                            'conEthicses' => $conEthicses,
                            'reviewChoices' => $reviewChoices,
                            'resolutions' => $resolutions,
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'ethicses' => $ethicses,
                            'conEthicses' => $conEthicses,
                            'reviewChoices' => $reviewChoices,
                            'resolutions' => $resolutions,
                ]);
            }
        }
    }

    /**
     * Updates an existing ContinueAssessForm model.
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
                    'title' => Yii::t('app', "แก้ไข") . " ContinueAssessForm #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-continue-assess-form-pjax',
                    'title' => Yii::t('app', "แก้ไข") . " ContinueAssessForm #" . $id,
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูล continue-assess-form เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไข") . " ContinueAssessForm #" . $id,
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
     * Delete an existing ContinueAssessForm model.
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
                    'forceReload' => '#crud-datatable-continue-assess-form-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-continue-assess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ContinueAssessForm model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-continue-assess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ContinueAssessForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContinueAssessForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ContinueAssessForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
