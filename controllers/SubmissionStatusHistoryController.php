<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionStatusHistory;
use app\models\SubmissionStatusHistorySearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;

/**
 * SubmissionStatusHistoryController implements the CRUD actions for SubmissionStatusHistory model.
 */
class SubmissionStatusHistoryController extends RbacController {

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
     * Lists all SubmissionStatusHistory models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubmissionStatusHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexResearcher($submissionId, $pdf = null) {
        $searchModel = new SubmissionStatusHistorySearch();
        $searchModel->submission_id = $submissionId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $submission = $this->findModel($submissionId);
        $submission= \app\models\Submission::findOne($submissionId);

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
                'marginTop' => 38,
                'content' => $this->renderPartial('index-researcher', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'submission' => $submission,
                    'submissionId'=>$submission->id
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
                'cssFile' => '@app/web/css/pdf.css',
                'cssInline' => 'body { font-family: thsarabun !important }',
//                'cssInline' => 'body { font-family: thsarabun !important; background: url("../images/MLR1-01.jpg") no-repeat 0 0;background-image-resize: 6}',
                'methods' => [
                    'SetTitle' => 'พิมพ์ใบขอติดตามประกันคุณภาพ',
                    'SetSubject' => 'พิมพ์ใบขอติดตามประกันคุณภาพ',
//                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->formatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetHeader' => '<font style="text-align: right;font-size: 18 px;"> ประวัติการดำเนินงาน </font><font style="font-size : 25px;">' . Yii::t('app', 'เลขที่ HE : ') . $submission->project->project_code . '</font>',
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->curlAllowUnsafeSslRequests = true;
//            $img_file =  \yii\helpers\Url::to("@web/images/CCP-01.jpg");
//            $mPdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
//            $mPdf->SetBackground('../images/CCP-01.jpg');
//            $mPdf->SetWatermarkImage('../images/CCP-01.jpg', 1, '');
//            $mPdf->showWatermarkImage = true;
//            $mPdf->watermarkImageAlpha = 0.7;

            $mPdf->SetDefaultBodyCSS('background', '../images/MFL-01.jpg');
//            $mPdf->SetDefaultBodyCSS('background-image-resize', 6);
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }
        return $this->render('index-researcher', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubmissionStatusHistory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SubmissionStatusHistory #" . $id,
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
     * Creates a new SubmissionStatusHistory model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new SubmissionStatusHistory();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new SubmissionStatusHistory",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new SubmissionStatusHistory",
                    'content' => '<span class="text-success">Create SubmissionStatusHistory success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new SubmissionStatusHistory",
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
     * Updates an existing SubmissionStatusHistory model.
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
                    'title' => "Update SubmissionStatusHistory #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "SubmissionStatusHistory #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update SubmissionStatusHistory #" . $id,
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
     * Delete an existing SubmissionStatusHistory model.
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
     * Delete multiple existing SubmissionStatusHistory model.
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
     * Finds the SubmissionStatusHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubmissionStatusHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubmissionStatusHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
