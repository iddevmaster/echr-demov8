<?php

namespace app\controllers;

use Yii;
use app\models\PersonTraining;
use app\models\PersonTrainingSearch;
use app\models\PersonDocumentAudit;
use app\models\ProjectResearcher;
use app\models\Role;
use app\components\EsignService;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * PersonTrainingController implements the CRUD actions for PersonTraining model.
 */
class PersonTrainingController extends RbacController {

    /**
     * @inheritdoc
     */
    protected $allowedActions = ['view', 'create', 'delete'];

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
     * Lists all PersonTraining models.
     * @return mixed
     */
    public function actionIndex($personId) {
        $searchModel = new PersonTrainingSearch();
        $searchModel->deleted = 0;
        $searchModel->person_id = $personId;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionShow($personId) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $searchModel = new PersonTrainingSearch();
            $searchModel->deleted = 0;
            $searchModel->person_id = $personId;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', "ข้อมูลการอบรม {name} ", [
                    'name' => $searchModel->person->fullName
                ]),
                'size' => 'large',
                'content' => $this->renderAjax('show', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('show', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single PersonTraining model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($id);
            return [
                'title' => Yii::t('app', "ข้อมูลการอบรม {name}", [
                    'name' => $model->name_thai_course
                ]),
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                Html::a(Yii::t('app', "แก้ไข"), ['update', 'id' => $id], ['class' => 'btn btn-primary btn-lg', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new PersonTraining model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($personId, $reload = NULL) {
        $request = Yii::$app->request;
        $model = new PersonTraining();
        $model->person_id = $personId;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "เพิ่มข้อมูลการอบรม {name}", [
                        'name' => $model->person->fullName
                    ]),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $file = UploadedFile::getInstance($model, 'file');
                if (!isset($file)) {
                    $model->addError('file', 'ไฟล์แนบต้องไม่ว่างเปล่า');
                } else {
                    $model->file = $file;
                    $model->file->name = 'training-' . uniqid() . '.' . $model->file->extension;
                    \yii\helpers\FileHelper::createDirectory($model->path);
                    $model->file->saveAs($model->path . '/' . $model->file->name);
                    //                $path = 'uploads/training-file/';
                    //                $model->file->saveAs($path . $model->file->name);
                    $model->file = $model->file->name;
                    // Auto-stamp on create when the person uses stamp signing (same
                    // preference as CV). $oldFile = null since this is a new record.
                    $this->saveTrainingWithAutoStamp($model, null);
                    $model = new PersonTraining();
//                    Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('app', "เพิ่มข้อมูลการอบรมเรียบร้อยแล้ว"));
                }

                $p = '#crud-datatable-person-training-pjax';
                if (isset($reload)) {
                    $p = '#crud-datatable-person-training-' . $personId . '-pjax';
                }
                return [
                    'forceReload' => $p,
                    'forceClose' => TRUE,
//                    'title' => Yii::t('app', "เพิ่มข้อมูลการอบรม"),
//                    'content' => $this->renderAjax('create', [
//                        'model' => $model,
//                    ]),
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
//                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "เพิ่มข้อมูลการอบรม"),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
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
     * Updates an existing PersonTraining model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $reload = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $templateFile = $model->file;

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('app', "แก้ไขข้อมูลการอบรม {name}", [
                        'name' => $model->name_thai_course
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $file = UploadedFile::getInstance($model, 'file');
                if (isset($file)) {
                    $model->file = $file;
                    $model->file->name = 'training-' . uniqid() . '.' . $model->file->extension;

                    \yii\helpers\FileHelper::createDirectory($model->path);
                    $model->file->saveAs($model->path . '/' . $model->file->name);
                    //                $path = 'uploads/training-file/';
                    //                $model->file->saveAs($path . $model->file->name);
                    $model->file = $model->file->name;
                } else {
                    $model->file = $templateFile;
                }
                $this->saveTrainingWithAutoStamp($model, $templateFile);
                $p = '#crud-datatable-person-training-pjax';
                if (isset($reload)) {
                    $p = '#crud-datatable-person-training-' . $model->person_id . '-pjax';
                }
                return [
                    'forceReload' => $p,
                    'title' => Yii::t('app', "แก้ไขข้อมูลการอบรม {name}", [
                        'name' => $model->name_thai_course
                    ]),
                    'forceClose' => TRUE,
//
//                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
//                    Html::a(Yii::t('app', "บันทึก"), ['update', 'id' => $id], ['class' => 'btn btn-primary btn-lg', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => Yii::t('app', "แก้ไขข้อมูลการอบรม {name}", [
                        'name' => $model->name_thai_course
                    ]),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button(Yii::t('app', "ปิด"), ['class' => 'btn btn-default pull-left btn-lg', 'data-dismiss' => "modal"]) .
                    Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary btn-lg', 'type' => "submit"])
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
     * Delete an existing PersonTraining model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $reload = NULL) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted = 1;
        $p = '#crud-datatable-person-training-pjax';
        if (isset($reload)) {
            $p = '#crud-datatable-person-training-' . $model->person_id . '-pjax';
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('app', "ไม่สามารถลบข้อมูลได้ {error}", [
                        'error' => \Yii::$app->util->errorSummary($model),
            ]));
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'forceReload' => $p,
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
            return ['forceClose' => true, 'forceReload' => $p];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing PersonTraining model.
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

    public function actionDownload($id) {
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        // $currentRole = Yii::$app->session->get('currentRole');
        // // VarDumper::dump($currentRole, 10, true);
        // if (\in_array($currentRole['role_id'], [Role::RESEARCHER])) {
        //     // VarDumper::dump(Yii::$app->user->identity->person->id);
        //     // VarDumper::dump($id != Yii::$app->user->identity->person->id);
        //     if ($model->person_id != Yii::$app->user->identity->person->id) {
        //         throw new ForbiddenHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงไฟล์'));
        //     }
        // } else if (\in_array($currentRole['role_id'], [Role::COORDINATOR])) {
        //     // VarDumper::dump(Yii::$app->user->identity->person->id);
        //     // VarDumper::dump($id != Yii::$app->user->identity->person->id);
        //     $exists = ProjectResearcher::find()->joinWith(['project'])->isDeleted(false)->person($model->person_id)
        //         ->coordinator(Yii::$app->user->identity->person->id)
        //         ->andWhere(['project.deleted' => 0])
        //         ->exists();
        //     if (!$exists) {
        //         throw new ForbiddenHttpException(Yii::t('app', 'ไม่มีสิทธิ์เข้าถึงไฟล์'));
        //     }
        // }
        // exit;

        $info = pathinfo($model->filePath);
        $t = \time();
        $fileName = "pt-{$t}.{$info['extension']}";
        // $fileName = "{$model->name}.{$info['extension']}";
        //        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }
    public function actionViewFile($id) {
//        $request = Yii::$app->request;
//        $response = \Yii::$app->response;
        $model = $this->findModel($id);
        $info = pathinfo($model->filePath);
        $t = \time();
        $fileName = "pt-{$t}.{$info['extension']}";
//        echo $model->filePath;
        if (file_exists($model->filePath)) {
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'ไม่พบไฟล์'));
        }
        exit;
    }
    /**
     * Finds the PersonTraining model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonTraining the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = PersonTraining::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Persist a training record (create or edit), auto-stamping the uploaded
     * file when the person uses electronic-stamp signing — the same single
     * preference as the CV (Person::usesEsignStamp). No re-auth. When the file
     * changed, stale original state is reset and superseded files cleaned up.
     *
     * @param PersonTraining $model populated with the (possibly new) file
     * @param string|null $oldFile the file value before this save (null on create)
     */
    protected function saveTrainingWithAutoStamp($model, $oldFile) {
        $changed = ($model->file !== $oldFile);
        if (!$changed) {
            $model->save(false);
            return;
        }
        $person = isset($model->person) ? $model->person : \app\models\Person::findOne($model->person_id);
        $usesStamp = isset($person) && $person->usesEsignStamp();
        $priorOriginal = $model->original_file;
        $dir = Yii::getAlias('@app/web/' . $model->path);
        $model->original_file = null;
        if ($usesStamp) {
            $signer = isset(Yii::$app->user->identity->person) ? Yii::$app->user->identity->person : $person;
            EsignService::signTraining($model, $signer, PersonDocumentAudit::AUTH_PASSWORD, true);
        } else {
            $model->save(false);
        }
        EsignService::cleanupSupersededFile($dir, $oldFile, $model->file, $model->original_file);
        if (!empty($priorOriginal) && $priorOriginal !== $oldFile) {
            EsignService::cleanupSupersededFile($dir, $priorOriginal, $model->file, $model->original_file);
        }
    }

}
