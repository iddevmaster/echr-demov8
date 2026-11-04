<?php

namespace app\controllers;

use Yii;
use app\models\SaeAssessForm;
use app\models\SaeAssessFormSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\Resolution;
use kartik\mpdf\Pdf;
use app\models\SubmissionVolunteer;
use app\models\SubmissionCommittee;
use app\models\SubmissionType;

/**
 * SaeAssessFormController implements the CRUD actions for SaeAssessForm model.
 */
class SaeAssessFormController extends RbacController {

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
     * Lists all SaeAssessForm models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SaeAssessFormSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SaeAssessForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SaeAssessForm #" . $id,
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

    /**
     * Creates a new SaeAssessForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPrintPdf($id) {
        $model = $this->findModel($id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $saeVolunteer = \app\models\SaeVolunteer::find()->isDeleted(false)->submission($model->submission_id)->all();
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $dd = date('Y-m-d H:i:s');
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $this->renderPartial('print-pdf', [
                'model' => $model,
                'saeVolunteer'=>$saeVolunteer,
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
                'SetTitle' => 'แบบประเมินรายงานเหตุการณ์ไม่พึงประสงค์ (Serious Adverse Event: SAE)',
                'SetSubject' => 'แบบประเมินรายงานเหตุการณ์ไม่พึงประสงค์ (Serious Adverse Event: SAE)',
                'SetHeader' => ['พิมพ์แบบประเมิน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                'SetFooter' => ['|หน้า {PAGENO}|'],
            ]
        ]);
        $mPdf = $pdf->getApi();
        $mPdf->SetDefaultFont('thsarabun');
        return $pdf->render();
    }

    public function actionCreate($submissionId, $submissionCommitteeId) {
        $request = Yii::$app->request;
        $model = SaeAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($submissionCommitteeId)->one();
        if (!isset($model)) {
            $model = new SaeAssessForm();
            $model->submission_id = $submissionId;
            $model->submission_committee_id = $submissionCommitteeId;
        }

        $resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->validate()) {
                if ($model->submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
                    $subVols = $model->submission->getSubmissionVolunteers()->isDeleted(false)->all();
                    foreach ($subVols as $subVol) {
                        if (!$subVol->getIsAssessed($submissionCommitteeId)) {
                            return ['error' => true, 'message' => Yii::t('app', 'กรุณาประเมินอาสาสมัครให้ครบทุกคน')];
                        }
                    }
                }
                $model->save(false);
                $model->refresh();
                return ['reload' => '#submission-type-assess-form-pjax', 'message' => \Yii::t('app', 'บันทึกข้อมูลเรียบร้อยแล้ว')];
            } else {
                $form = new \yii\widgets\ActiveForm();
                return ['error' => true, 'message' => $form->errorSummary($model)];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                $model->refresh();
                return $this->render('create', [
                            'model' => $model,
                            'resolutions' => $resolutions,
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'resolutions' => $resolutions,
                ]);
            }
        }
    }

    /**
     * Updates an existing SaeAssessForm model.
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
                    'title' => Yii::t('app', "แก้ไข") . " SaeAssessForm #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-sae-assess-form-pjax',
                    'title' => Yii::t('app', "แก้ไข") . " SaeAssessForm #" . $id,
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูล sae-assess-form เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไข") . " SaeAssessForm #" . $id,
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
     * Delete an existing SaeAssessForm model.
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
                    'forceReload' => '#crud-datatable-sae-assess-form-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sae-assess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SaeAssessForm model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sae-assess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the SaeAssessForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaeAssessForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SaeAssessForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
