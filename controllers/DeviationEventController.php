<?php

namespace app\controllers;

use Yii;
use app\models\DeviationEvent;
use app\models\DeviationEventSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\SubmissionEvent;
use app\models\DeviationEventEthics;
use app\models\DeviationAssessForm;
use app\models\SubmissionCommittee;
use app\models\Ethics;
use app\models\SaeVolunteerEthics;
use yii\base\Model;

/**
 * DeviationEventController implements the CRUD actions for DeviationEvent model.
 */
class DeviationEventController extends RbacController {

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
     * Lists all DeviationEvent models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DeviationEventSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeviationEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "DeviationEvent #" . $id,
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
     * Creates a new DeviationEvent model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionEventId, $sCommitteeId) {
        $request = Yii::$app->request;
        $subEv = SubmissionEvent::findOne($submissionEventId);
        $sc = SubmissionCommittee::findOne($sCommitteeId);
        $model = DeviationEvent::find()->joinWith(['submission'])->isDeleted(false)
                ->submission($subEv->submission_id)
                ->submissionCommittee($sc->id)
                ->submissionEvent($subEv->id)
                ->one();
        if (!isset($model)) {
            $model = new DeviationEvent();
            $model->submission_event_id = $subEv->id;
            $model->submission_id = $subEv->submission_id;
            $model->submission_committee_id = $sc->id;
        }
        
        $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
        $devEthicses = [];
        foreach ($ethicses as $ethics) {
            $devEthics = DeviationEventEthics::find()->isDeleted(false)
                    ->deviationEvent($model->id)->ethics($ethics->id)->one();
            if (!isset($devEthics)) {
                $devEthics = new DeviationEventEthics();
                $devEthics->deviation_event_id = $model->id;
                $devEthics->ethics_id = $ethics->id;
                $devEthics->is_appropriate = SaeVolunteerEthics::APPROPRIATE;
            }
            $devEthicses[$ethics->id] = $devEthics;
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'ประเมินแจ้งการดำเนินการเบี่ยงเบน (Deviation)'),
                    'size' => 'large',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'devEthicses' => $devEthicses,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    ($model->canEdit ? Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"]) : "")
                ];
            } else if ($model->load($request->post()) && $model->validate() 
                    && Model::loadMultiple($devEthicses, $request->post()) 
                    && Model::validateMultiple($devEthicses)) {
//                return $this->redirect(['view', 'id' => $model->id]);
                $model->submissionEvent->meeting_violation_type = $model->is_major_minor_com;
                $model->submissionEvent->save(false);
                $model->save(false);
                
                foreach ($devEthicses as $devEthics) {
                    $devEthics->deviation_event_id = $model->id;
                    $devEthics->save(false);
                }
                $devAssessForm = DeviationAssessForm::find()->isDeleted(false)
                        ->submission($model->submission_id)
                        ->submissionCommittee($sc->id)->one();
                if (!isset($devAssessForm)) {
                    $devAssessForm = new DeviationAssessForm();
                    $devAssessForm->submission_id = $model->submission_id;
                    $devAssessForm->submission_committee_id = $sc->id;
                }
                $devAssessForm->save(false);
                return [
                    'forceReload' => '#submission-type-assess-form-pjax',
                    'title' => Yii::t('app', 'ประเมินแจ้งการดำเนินการเบี่ยงเบน (Deviation)'),
                    'content' => "<div class='alert alert-success dark'>".Yii::t('app', 'บันทึกผลประเมินเรียบร้อยแล้ว')."</div>",
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'ประเมินแจ้งการดำเนินการเบี่ยงเบน (Deviation)'),
                    'size' => 'large',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'devEthicses' => $devEthicses,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    ($model->canEdit ? Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"]) : "")
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
     * Updates an existing DeviationEvent model.
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
                    'title' => Yii::t('app', "แก้ไข") . " DeviationEvent #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-deviation-event-pjax',
                    'title' => Yii::t('app', "แก้ไข") . " DeviationEvent #" . $id,
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูล deviation-event เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไข") . " DeviationEvent #" . $id,
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
     * Delete an existing DeviationEvent model.
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
                    'forceReload' => '#crud-datatable-deviation-event-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-deviation-event-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing DeviationEvent model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-deviation-event-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the DeviationEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeviationEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DeviationEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
