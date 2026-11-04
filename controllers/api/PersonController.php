<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use app\models\Person;
use app\models\PersonSearch;
use app\models\PersonTraining;
use app\models\PersonTrainingSearch;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Response;

class PersonController extends Controller {

    public function actionIndex()
    {
        $filter = new ActiveDataFilter([
            'searchModel' => PersonSearch::class
        ]);

        $filterCondition = null;
        $inPersonRoleId = null;
        $crecDepartmentId = null;

        // You may load filters from any source. For example,
        // if you prefer JSON in request body,
        // use Yii::$app->request->getBodyParams() below:
        if ($filter->load(\Yii::$app->request->get())) {
            $filterParams = $filter->filter;
            // VarDumper::dump($filter);
            if (isset($filterParams['inPersonRoleId'])) {
                $inPersonRoleId = $filterParams['inPersonRoleId'];
                unset($filterParams['inPersonRoleId']);
            }
            if (isset($filterParams['crecDepartmentId'])) {
                $crecDepartmentId = $filterParams['crecDepartmentId'];
                unset($filterParams['crecDepartmentId']);
            }
            // VarDumper::dump($filter);
            // exit;
            
            $filter->filter = $filterParams;
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                // Serializer would get errors out of it
                return $filter;
            }
        }


        $query = PersonSearch::find()->isDeleted(false)->isResearcherCrec();
        if (!empty($inPersonRoleId)) {
            $roleIds = explode(',', $inPersonRoleId);
            $query->isInPersonRole($roleIds);
        }
        if (!empty($crecDepartmentId)) {
            $depIds = explode(',', $crecDepartmentId);
            $query->joinWith(['department'])->crecDepartmentId($depIds);
        }
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

    }

    public function actionDownloadCvFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = Person::findOne($id);
        $info = pathinfo($model->cvFile);
        $t = \time();
        $fileName = "cv-{$t}.{$info['extension']}";

        //        echo $model->filePath;
        if (file_exists($model->cvPath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->cvFile));
            readfile($model->cvFile);
        }
        exit;
    }

    public function actionViewCvFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = Person::findOne($id);
        $info = pathinfo($model->cvFile);
        $t = \time();
        $fileName = "cv-{$t}.{$info['extension']}";

        //        echo $model->filePath;
        if (file_exists($model->cvPath)) {
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->cvFile));
            readfile($model->cvFile);
        }
        exit;
    }

    public function actionListTrainings()
    {
        $filter = new ActiveDataFilter([
            'searchModel' => PersonTrainingSearch::class
        ]);

        $filterCondition = null;
        $inPersonRoleId = null;
        $crecDepartmentId = null;

        // You may load filters from any source. For example,
        // if you prefer JSON in request body,
        // use Yii::$app->request->getBodyParams() below:
        if ($filter->load(\Yii::$app->request->get())) {
            $filterParams = $filter->filter;
            // VarDumper::dump($filter);
            if (isset($filterParams['inPersonRoleId'])) {
                $inPersonRoleId = $filterParams['inPersonRoleId'];
                unset($filterParams['inPersonRoleId']);
            }
            

            $filter->filter = $filterParams;
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                // Serializer would get errors out of it
                return $filter;
            }
        }


        $query = PersonTrainingSearch::find()->isDeleted(false);
        
        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }

    public function actionViewTrainingFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = PersonTraining::findOne($id);
        $info = pathinfo($model->filePath);
        $t = \time();
        $fileName = "training-{$t}.{$info['extension']}";

        //        echo $model->filePath;
        if (file_exists($model->path)) {
            header('Content-Description: Preview');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($model->filePath));
            readfile($model->filePath);
        }
        exit;
    }
}
