<?php

namespace app\controllers;

use Yii;
use app\models\PersonRole;
use app\models\PersonRoleSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\PersonRolePanel;
use app\models\MeetingRolePanel;
use app\models\EmailQueue;

/**
 * PersonRoleController implements the CRUD actions for PersonRole model.
 */
class PersonRoleController extends RbacController {

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
     * Lists all PersonRole models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PersonRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PersonRole model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "PersonRole #" . $id,
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
     * Creates a new PersonRole model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new PersonRole();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new PersonRole",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new PersonRole",
                    'content' => '<span class="text-success">Create PersonRole success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new PersonRole",
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
     * Updates an existing PersonRole model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $modelPerson = \app\models\Person::findOne($model->person_id);
        $personId = $model->person_id;
        $roleId = $model->role_id;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขสิทธิของผู้ใช้งาน {name}", [
                        'name' => $model->role->name
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'modelPerson' => $modelPerson,
                        'personId' => $personId,
                        'roleId' => $roleId,]),
                    'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('บันทึก', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                if ($roleId == \app\models\Role::COMMITTEE) {
                    if ($modelPerson->load($request->post())) {
                        $modelPerson->save(false);
                    }
                }

                \app\models\PersonRolePanel::updateAll(['deleted' => 1], [
                    'person_role_id' => $model->id,
                ]);
                foreach ($model->panelIds as $panelId) {
                    $pr = new \app\models\PersonRolePanel();
                    $pr->person_role_id = $model->id;
                    $pr->panel_id = $panelId;
                    if (is_array($model->meetingPanelIds) && in_array($panelId, $model->meetingPanelIds, TRUE)) {
                        $pr->is_regular = 1;
                    } else {
                        $pr->is_regular = 0;
                    }
                    $pr->save();
                }
                return [
                    'forceReload' => '#crud-datatable-person-role-pjax',
                    'forceClose' => true,
                    'content' => '',
                    'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Update PersonRole #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'modelPerson' => $modelPerson,
                        'personId' => $personId,
                        'roleId' => $roleId,
                    ]),
                    'footer' => Html::button('ปิด', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('บันทึก', ['class' => 'btn btn-primary', 'type' => "submit"])
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
                            'modelPerson' => $modelPerson,
                            'personId' => $personId,
                            'roleId' => $roleId,
                ]);
            }
        }
    }

    /**
     * Delete an existing PersonRole model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id = NULL, $personId = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $auth = \Yii::$app->getAuthManager();

        $tran = $model->getDb()->beginTransaction();
        try {
            $role = $auth->getRole($model->role->name);
            $auth->revoke($role, $model->person->user_id);
            PersonRolePanel::deleteAll(['person_role_id' => $id]);
            MeetingRolePanel::deleteAll(['person_id' => $model->person_id]);
            $model->delete();
            $tran->commit();
        } catch (Exception $ex) {
            $tran->rollBack();
            throw $ex;
        }


        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-person-role-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing PersonRole model.
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

    public function actionSelectPerson($id) {

        $SsearchModel = new PersonRoleSearch();
        $SsearchModel->deleted = 0;
        $SsearchModel->personDeleted = 0;
        $SsearchModel->role_id = $id;
        $SsearchModel->personDeleted = 0;
        $SdataProvider = $SsearchModel->search(Yii::$app->request->queryParams);

        $DsearchModel = new \app\models\PersonSearch();
        $DsearchModel->deleted = 0;
        $DsearchModel->notInPersonRoleId = $id;
        $DdataProvider = $DsearchModel->search(Yii::$app->request->queryParams);

        $selectPerson = PersonRole::findOne($id);
        $roleId = \app\models\Role::findOne($id);

        return $this->render('select-person', [
                    'DsearchModel' => $DsearchModel,
                    'DdataProvider' => $DdataProvider,
                    'SsearchModel' => $SsearchModel,
                    'SdataProvider' => $SdataProvider,
                    'selectPerson' => $selectPerson,
                    'roleId' => $roleId,
        ]);
    }

    public function actionSelectPersons($id = null, $personId, $roleId) {
        $request = Yii::$app->request;
//        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $modelPerson = \app\models\Person::findOne($personId);
        $model = new PersonRole();
        $model->person_id = $personId;
        $model->role_id = $roleId;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($roleId != \app\models\Role::COORDINATOR && $roleId != \app\models\Role::RESEARCHER) {
                if ($request->isGet) {
                    return [
                        'title' => Yii::t('app', "กำหนดสิทธิของผู้ใช้งาน {name}", [
                            'name' => $model->role->name
                        ]),
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'modelPerson' => $modelPerson,
                            'personId' => $personId,
                            'roleId' => $roleId,
                                //'panelRole' => $panelRole,
                        ]),
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post()) && $model->validate()) {
                    if ($roleId == \app\models\Role::COMMITTEE) {
                        if ($modelPerson->load($request->post())) {
                            $modelPerson->save(false);
                        }
                    }
                    $tran = $model->getDb()->beginTransaction();
                    try {
                        if (!isset($model->person->reg_code)) {
                            $model->person->reg_code = $model->person->generateRegCode();
                            $model->person->save(FALSE);

                            EmailQueue::addQueue(EmailQueue::TYPE_INFORM_REG_CODE, $model->person->id);
                        }
                        $model->save(FALSE);
                        if (isset($model->person->user_id)) {
                            $auth = \Yii::$app->getAuthManager();
                            $role = $auth->getRole($model->role->name);
                            $auth->assign($role, $model->person->user_id);
                        }
                        \app\models\PersonRolePanel::updateAll(['deleted' => 1], [
                            'person_role_id' => $model->id,
                        ]);
                        foreach ($model->panelIds as $panelId) {
                            $pr = new \app\models\PersonRolePanel();
                            $pr->person_role_id = $model->id;
                            $pr->panel_id = $panelId;
                            if (is_array($model->meetingPanelIds) && in_array($panelId, $model->meetingPanelIds)) {
                                $pr->is_regular = 1;
                            }
                            $pr->save();
                        }

                        $tran->commit();
                    } catch (Exception $ex) {
                        $tran->rollBack();
                        throw $ex;
                    }
//                $rgp = PersonRole::find()->isDeleted(FALSE)->person($personId)->role($roleId)->one();

                    return [
                        'title' => Yii::t('app', "เลือกเจ้าหน้าที่", [
                        ]),
                        'forceReload' => '#crud-datatable-person-role-pjax',
                        'forceClose' => true,
                        'content' => '',
                        'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => "กำหนดข้อมูลสิทธิของผู้ใช้งาน",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'modelPerson' => $modelPerson,
                            'personId' => $personId,
                            'roleId' => $roleId,
//                        'panelRole' => $panelRole,
                        ]),
                        'footer' => Html::button(\Yii::t('app', 'ปิด'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button(\Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                $tran = $model->getDb()->beginTransaction();
                try {
                    if (!isset($model->person->reg_code)) {
                        $model->person->reg_code = $model->person->generateRegCode();
                        $model->person->save(FALSE);
                    }
                    $model->save(FALSE);
                    if (isset($model->person->user_id)) {
                        $auth = \Yii::$app->getAuthManager();
                        $role = $auth->getRole($model->role->name);
                        $auth->assign($role, $model->person->user_id);
                    }

                    $tran->commit();
                } catch (Exception $ex) {
                    $tran->rollBack();
                    throw $ex;
                }
                return [
                    'title' => Yii::t('app', "เลือกผู้ประสานงานโครงการ", [
                    ]),
                    'forceReload' => '#crud-datatable-person-role-pjax',
                    'forceClose' => true,
                    'content' => '',
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        }
    }

    /**
     * Finds the PersonRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = PersonRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
