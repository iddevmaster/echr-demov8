<?php

namespace app\controllers;

use Yii;
use app\models\CAssessForm;
use app\models\CAssessFormSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use kartik\mpdf\Pdf;

/**
 * CAssessFormController implements the CRUD actions for CAssessForm model.
 */
class CAssessFormController extends RbacController {

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
     * Lists all CAssessForm models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CAssessFormSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrint($id) {
        $model = CAssessForm::findOne($id);

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
     * Displays a single CAssessForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "CAssessForm #" . $id,
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
     * Creates a new CAssessForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionId, $submissionCommitteeId) {
        $request = Yii::$app->request;
        $model = CAssessForm::find()->isDeleted(false)->submission($submissionId)->submissionCommittee($submissionCommitteeId)->one();
        if (!isset($model)) {
            $model = new CAssessForm();
            $model->submission_id = $submissionId;
            $model->submission_committee_id = $submissionCommitteeId;
        }


        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
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
                return $this->render('create', [
                            'model' => $model,
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing CAssessForm model.
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
                    'title' => Yii::t('app', "แก้ไข") . " CAssessForm #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-cassess-form-pjax',
                    'title' => Yii::t('app', "แก้ไข") . " CAssessForm #" . $id,
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูล cassess-form เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไข") . " CAssessForm #" . $id,
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
     * Delete an existing CAssessForm model.
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
                    'forceReload' => '#crud-datatable-cassess-form-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-cassess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing CAssessForm model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-cassess-form-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the CAssessForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CAssessForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = CAssessForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
