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
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Response;

class SubmissionDocumentController extends Controller {

    public function actionReupload()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';
        $tran = Yii::$app->db->beginTransaction();
        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว',
        ];
        $message = '';

        try {
            // VarDumper::dump($headers);
            // VarDumper::dump($bodyParams);

            $oldDoc = SubmissionDocument::find()->sdCrecId($bodyParams['oldSubmissionDocument']['id'])->one();
            if (isset($oldDoc)) {
                $oldDoc->deleted = 1;
                if (!$oldDoc->save(false)) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $oldDoc->firstErrors)
                    ];
                    Yii::warning($response, 'api-called');
                    return $response;
                }
            }
            $sd = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC,]);
            $sd->project_id = $oldDoc->project_id;
            $sd->submission_id = $oldDoc->submission_id;
            $sd->name = $bodyParams['newSubmissionDocument']['name'];
            $sd->name_eng = $bodyParams['newSubmissionDocument']['name_eng'];
            $sd->remark = $bodyParams['newSubmissionDocument']['remark'] . $bodyParams['newSubmissionDocument']['remark_site'];
            $sd->version = $bodyParams['newSubmissionDocument']['version'];
            $sd->version_at = $bodyParams['newSubmissionDocument']['version_at'];
            $sd->sd_crec_id = $bodyParams['newSubmissionDocument']['id'];
            $sd->is_site = isset($bodyParams['newSubmissionDocument']['site_id']) ? 1 : 0;
            $sd->is_certificate = isset($bodyParams['newSubmissionDocument']['is_certificate']) ? $bodyParams['newSubmissionDocument']['is_certificate'] : 0;
            if (!$sd->is_site) {
                $sd->status = SubmissionDocument::STATUS_PASS;
            }
            if (!$sd->save()) {
                $tran->rollBack();
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => \implode(", ", $sd->firstErrors)
                ];
                Yii::warning($response, 'api-called');
                return $response;
            }
            
            $tran->commit();

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => [
                    'oldDoc' => $oldDoc->attributes,
                    'newDoc' => $sd->attributes,
                ]
            ];
            Yii::warning($response, 'api-called');
            return $response;
        } catch (Throwable $ex) {
            $tran->rollBack();
            Yii::$app->response->setStatusCode(HttpCode::INTERNAL_SERVER_ERROR);

            $stackTrace = $ex->getTraceAsString();
            $response = [
                'status' => ExternalApi::STATUS_ERROR,
                'message' => $ex->getMessage(),
                'stackTrace' => $stackTrace,
            ];
            Yii::warning($response, 'api-called');
            return $response;
        } 
    }

    public function actionDownloadFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        //        $request = Yii::$app->request;
        //        $response = \Yii::$app->response;
        $model = SubmissionDocument::findOne($id);
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
        $model = SubmissionDocument::findOne($id);
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
