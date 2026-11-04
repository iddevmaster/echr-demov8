<?php

namespace app\controllers;

use Yii;
use app\models\SaeVolunteer;
use app\models\SaeVolunteerSearch;
//use yii\web\Controller;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\SubmissionVolunteer;
use app\models\Ethics;
use app\models\SaeVolunteerEthics;
use app\models\SaeAssessForm;
use yii\base\Model;
use app\models\SubmissionCommittee;
use app\models\Submission;

/**
 * SaeVolunteerController implements the CRUD actions for SaeVolunteer model.
 */
class SaeVolunteerController extends RbacController {

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
     * Lists all SaeVolunteer models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SaeVolunteerSearch();
        $searchModel->deleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SaeVolunteer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "SaeVolunteer #" . $id,
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
     * Creates a new SaeVolunteer model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($submissionVolunteerId, $sCommitteeId) {
        $request = Yii::$app->request;
        $subVol = SubmissionVolunteer::findOne($submissionVolunteerId);
        $sc = SubmissionCommittee::findOne($sCommitteeId);
        $model = SaeVolunteer::find()->isDeleted(false)
                ->submission($subVol->submission_id)
                ->submissionCommittee($sc->id)
                ->volunteer($subVol->volunteer_id)
                ->one();
        if (!isset($model)) {
            $model = new SaeVolunteer();
            $model->volunteer_id = $subVol->volunteer_id;
            $model->submission_id = $subVol->submission_id;
            $model->submission_committee_id = $sc->id;
        }
        
        $ethicses = Ethics::find()->isDeleted(false)->orderBy('id')->all();
        $saeEthicses = [];
        foreach ($ethicses as $ethics) {
            $saeEthics = SaeVolunteerEthics::find()->isDeleted(false)
                    ->saeVolunteer($model->id)->ethics($ethics->id)->one();
            if (!isset($saeEthics)) {
                $saeEthics = new SaeVolunteerEthics();
                $saeEthics->sae_volunteer_id = $model->id;
                $saeEthics->ethics_id = $ethics->id;
                $saeEthics->is_appropriate = SaeVolunteerEthics::APPROPRIATE;
            }
            $saeEthicses[$ethics->id] = $saeEthics;
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', 'ประเมินรายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) ในสถาบัน'),
                    'size' => 'large',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'saeEthicses' => $saeEthicses,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    ($model->canEdit ? Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"]) : "")
                ];
            } else if ($model->load($request->post()) && $model->validate() 
                    && Model::loadMultiple($saeEthicses, $request->post()) 
                    && Model::validateMultiple($saeEthicses)) {
//                return $this->redirect(['view', 'id' => $model->id]);
                $model->save(false);
                
                foreach ($saeEthicses as $saeEthics) {
                    $saeEthics->sae_volunteer_id = $model->id;
                    $saeEthics->save(false);
                }
                $saeAssessForm = SaeAssessForm::find()->isDeleted(false)
                        ->submission($model->submission_id)
                        ->submissionCommittee($sc->id)->one();
                if (!isset($saeAssessForm)) {
                    $saeAssessForm = new SaeAssessForm();
                    $saeAssessForm->submission_id = $model->submission_id;
                    $saeAssessForm->submission_committee_id = $sc->id;
                }
                $saeAssessForm->updateVounteerCount();
                $saeAssessForm->save(false);
                return [
                    'forceReload' => '#submission-type-assess-form-pjax',
                    'title' => Yii::t('app', 'ประเมินรายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) ในสถาบัน'),
                    'content' => "<div class='alert alert-success dark'>".Yii::t('app', 'บันทึกผลประเมินเรียบร้อยแล้ว')."</div>",
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', 'ประเมินรายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) ในสถาบัน'),
                    'size' => 'large',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'saeEthicses' => $saeEthicses,
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
     * Updates an existing SaeVolunteer model.
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
                    'title' => Yii::t('app', "แก้ไข") . " SaeVolunteer #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-sae-volunteer-pjax',
                    'title' => Yii::t('app', "แก้ไข") . " SaeVolunteer #" . $id,
                    'content' => '<div class="alert alert-success dark">' . Yii::t('app', 'แก้ไขข้อมูล sae-volunteer เรียบร้อยแล้ว') . '</div>',
                    'footer' => Html::button(Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(Yii::t('app', 'แก้ไข'), ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไข") . " SaeVolunteer #" . $id,
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
     * Delete an existing SaeVolunteer model.
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
                    'forceReload' => '#crud-datatable-sae-volunteer-pjax',
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sae-volunteer-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SaeVolunteer model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-sae-volunteer-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the SaeVolunteer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaeVolunteer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SaeVolunteer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
