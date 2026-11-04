<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use app\models\MeetingSearch;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class MeetingController extends ActiveController {

    public $modelClass = 'app\models\Meeting';

//    public function actions() {
//        $actions = parent::actions();
//        unset($actions['create']);
//        unset($actions['delete']);
//        return $actions;
//    }

    /**
     * @api {get} /index.php?r=api/meeting/calendar ดึงข้อมูลการประชุมพิจารณาโครงการตามช่วงวันที่
     * @apiVersion 1.0.0
     * @apiName MeetingCalendar
     * @apiGroup Data
     * 
     * @apiHeader (Header) {String} Authorization Bearer + (token)
     * 
     * @apiParam {String} start วันที่เริ่มต้นข้อมูล ในรูปแบบ "php:Y-m-d H:i:s"
     * @apiParam {String} end วันที่สินสุดข้อมูล ในรูปแบบ "php:Y-m-d H:i:s"
     *
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} status สถานะของผลการส่งข้อมูล (OK=ส่งข้อมูลสำเร็จ, ERROR=มีข้อผิดพลาดเกิดขึ้น)
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Object[]} data ข้อมูลการประชุม
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Integer} data.id รหัสการประชุม
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.name ชื่อการประชุม
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.date วันที่ประชุมในรูปแบบ php:Y-m-d H:i:s
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.panel_id รหัส Panel
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.panel_name ชื่อ Panel ภาษาไทย
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.panel_name_eng ชื่อ Panel ภาษาอังกฤษ
     * 
     * @apiSuccessExample ตัวอย่างข้อมูล
     *   HTTP/1.1 200 OK
     *   {
     *       "status": "OK",
     *       "data": [
     *           {
     *               "id": 88,
     *               "name": "การประชุมคณะกรรมการจริยธรรมการวิจัยในมนุษย์มหาวิทยาลัยขอนแก่น ประจำสาขาวิชาคณะที่ 3 ครั้งที่ 21/2562",
     *               "date": "2019-12-12 12:00:00",
     *               "panel_id": 3,
     *               "panel_name": "กลุ่มของผู้ปฏิบัติงาน 3",
     *               "panel_name_eng": "panel 3"
     *           },
     *           {
     *               "id": 89,
     *               "name": "การประชุมคณะกรรมการจริยธรรมการวิจัยในมนุษย์มหาวิทยาลัยขอนแก่น ประจำสาขาวิชาคณะที่ 3 ครั้งที่ 22/2562",
     *               "date": "2019-12-26 12:00:00",
     *               "panel_id": 3,
     *               "panel_name": "กลุ่มของผู้ปฏิบัติงาน 3",
     *               "panel_name_eng": "panel 3"
     *           }
     *        ]
     *   }
     * 
     * @apiError (Error) {String} status สถานะของผลการส่งข้อมูล (OK=ส่งข้อมูลสำเร็จ, ERROR=มีข้อผิดพลาดเกิดขึ้น)
     * @apiError (Error) {Object} errors.params ข้อมูลที่ส่ง
     * @apiError (Error) {Object[]} errors.data ข้อมูล Errors
     * @apiError (Error) {String} errors.data.field field ที่เกิด Error
     * @apiError (Error) {String[]} errors.data.messages ข้อความ Error
     * 
     * 
     * @apiErrorExample ตัวอย่างข้อมูล Error
     *     HTTP/1.1 200 OK
     * {
     *       "status": "ERROR",
     *       "errors": [
     *           {
     *               "data": [
     *                   {
     *                       "field": "start",
     *                       "messages": [
     *                           "วันที่เริ่มต้นข้อมูลต้องไม่ว่างเปล่า"
     *                       ]
     *                   },
     *                   {
     *                       "field": "end",
     *                       "messages": [
     *                           "วันที่สินสุดข้อมูลต้องไม่ว่างเปล่า"
     *                       ]
     *                   }
     *                ],
     *                "params": []
     *           }
     *       ]
     *   }
     *
     * @apiError (Unauthorized) {String} name ชื่อข้อผิดพลาด
     * @apiError (Unauthorized) {String} message ข้อผิดพลาด
     * @apiError (Unauthorized) {Integer} code รหัสข้อผิดพลาด
     * @apiError (Unauthorized) {Integer} status HTTP Status Code
     * @apiError (Unauthorized) {String} type ชนิดของข้อผิดพลาด
     * 
     * @apiErrorExample ตัวอย่างข้อมูล Unauthorized
     *     HTTP/1.1 401 Unauthorized
     *     {
     *          "name":"Unauthorized",
     *          "message":"Your request was made with invalid credentials.",
     *          "code":0,
     *          "status":401,
     *          "type":"yii\\web\\UnauthorizedHttpException"
     *     }
     */
    public function actionCalendar() {
        \Yii::$app->language = 'th';
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $model = new MeetingSearch(['scenario' => MeetingSearch::SCENARIO_API_SEARCH]);
        $model->load($bodyParams, '');
        $results = [];
        $data = [];
        $errors = [];
        if ($model->validate()) {
            $dataProvider = $model->search($bodyParams);
            $dataProvider->pagination = false;
            $meetings = $dataProvider->getModels();
            foreach ($meetings as $meeting) {
                $data[] = [
                    'id' => $meeting->id,
                    'name' => $meeting->fullName,
                    'date' => $meeting->start_date,
                    'panel_id' => $meeting->panel_id,
                    'panel_name' => $meeting->panel->name,
                    'panel_name_eng' => $meeting->panel->name_eng,
                ];
            }
        } elseif (!$model->hasErrors()) {
            $errors[] = ['data' => [
                    [
                        'field' => '',
                        'messages' => ['เกิดข้อผิดพลาด']
                    ]
                ], 'params' => []];
            //throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        } else {
            $ers = $model->getErrors();
            $error['data'] = \Yii::$app->util->buildErrorForApi($ers);
            $error['params'] = $bodyParams;
            $errors[] = $error;
        }
        if (count($errors) > 0) {
            $results = [
                'status' => 'ERROR',
                'errors' => $errors,
            ];
        } else {
            $results = [
                'status' => 'OK',
                'data' => $data,
            ];
        }
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);
        return $results;
    }

}
