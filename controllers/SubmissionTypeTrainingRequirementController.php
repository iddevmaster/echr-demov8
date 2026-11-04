<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionTypeTrainingRequirement;
use app\models\SubmissionTypeTrainingRequirementSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;

/**
 * SubmissionTypeTrainingRequirementController implements the CRUD actions for
 * SubmissionTypeTrainingRequirement model (admin matrix of training requirements
 * per submission type).
 */
class SubmissionTypeTrainingRequirementController extends RbacController {

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

    public function actionIndex() {
        $searchModel = new SubmissionTypeTrainingRequirementSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', "เกณฑ์การอบรม") . " #" . $id,
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

    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new SubmissionTypeTrainingRequirement();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                $model = new SubmissionTypeTrainingRequirement();
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', 'เพิ่มข้อมูลเรียบร้อยแล้ว'));
                return [
                    'forceReload' => '#crud-datatable-sttr-pjax',
                    'title' => Yii::t('app', "เพิ่มเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', ['model' => $model]);
            }
        }
    }

    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => $this->renderAjax('update', ['model' => $model]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-sttr-pjax',
                    'title' => Yii::t('app', "แก้ไขเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูลเรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขเกณฑ์การอบรมตามประเภทโครงการ"),
                    'content' => $this->renderAjax('update', ['model' => $model]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }
    }

    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->save(false);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sttr-pjax'];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks'));
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->deleted = 1;
            $model->save(false);
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sttr-pjax'];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * @param integer $id
     * @return SubmissionTypeTrainingRequirement
     * @throws NotFoundHttpException
     */
    protected function findModel($id) {
        if (($model = SubmissionTypeTrainingRequirement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
