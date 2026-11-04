<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use app\components\ExternalApi;
use app\models\Person;
use app\models\PersonSearch;
use app\models\PersonTraining;
use app\models\PersonTrainingSearch;
use app\models\SubmissionCommitteeDocument;
use app\models\SubmissionDocument;
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Response;

class SubmissionCommitteeDocumentController extends Controller {

    public function actionDownloadFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = SubmissionCommitteeDocument::findOne($id);
        $info = pathinfo($model->filePath);
        // VarDumper::dump($info);
        // exit;
        $t = \time();
        $fileName = "{$t}.{$info['extension']}";

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
        }
        exit;
    }

    public function actionViewFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = SubmissionCommitteeDocument::findOne($id);
        $info = pathinfo($model->filePath);
        $t = \time();
        $fileName = "{$t}.{$info['extension']}";

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
        }
        exit;
    }
}
