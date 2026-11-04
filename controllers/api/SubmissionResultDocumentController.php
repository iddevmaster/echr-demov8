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
use app\models\SubmissionDocument;
use app\models\SubmissionResultDocument;
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Response;

class SubmissionResultDocumentController extends Controller {

    
    public function actionDownloadFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = SubmissionResultDocument::findOne($id);
        $filePath = $model->filePath;
        $info = pathinfo($model->filePath);
        $output = $filePath;

        // VarDumper::dump($info);
        // exit;
        $t = \time();
        $fileName = "{$t}.{$info['extension']}";

        // if ($model->submission->status >= \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT && $model->submission->resolution == 'Y') {
        //     if (!file_exists($filePath)) {
        //         $model->convertToPdf();
        //     }
        //     $filePath = $model->pdfFilePath;
        //     $temp = uniqid() . ".pdf";
        //     $fileName = "{$t}.pdf";
        //     $output = \Yii::getAlias('@app/web/tmp/' . $temp);
        //     exec("qpdf --decrypt \"{$filePath}\" --replace-input");
        //     exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile={$output} {$filePath}");

        //     //            SubmissionDocument::addApproveWatermark($model->submission->certified_date, $output, $output);
        //     SubmissionDocument::addApproveWatermark($model->submission->certified_date, $output, $output);
        // }
        $info = pathinfo($output);

        //        echo $model->filePath;
        if (file_exists($output)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            //            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($output));
            readfile($output);
        }
        //        if (file_exists($model->filePath)) {
        //            header('Content-Description: File Transfer');
        //            header('Content-Type: application/octet-stream; charset=utf-8');
        //            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        //            header('Expires: 0');
        //            //            header('Cache-Control: must-revalidate');
        //            header('Pragma: public');
        //            header('Content-Length: ' . filesize($model->filePath));
        //            readfile($model->filePath);
        //        }
        exit;
    }

    public function actionViewFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = SubmissionResultDocument::findOne($id);
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
