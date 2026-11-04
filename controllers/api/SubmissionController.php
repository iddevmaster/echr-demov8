<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use app\components\ExternalApi;
use app\models\Alert;
use app\models\DocumentSubmissionType;
use app\models\EmailQueue;
use app\models\FundingSource;
use app\models\Submission;
use app\models\SubmissionTypeGroup;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use app\models\Panel;
use app\models\Project;
use app\models\ProjectResearcher;
use app\models\Role;
use app\models\Setting;
use app\models\SubmissionDocument;
use app\models\SubmissionEvent;
use app\models\SubmissionResultDocument;
use app\models\SubmissionType;
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\helpers\VarDumper;

class SubmissionController extends ActiveController {

    public $modelClass = 'app\models\Submission';

//    public function actions() {
//        $actions = parent::actions();
//        unset($actions['create']);
//        unset($actions['delete']);
//        return $actions;
//    }

    /**
     * @api {get} /index.php?r=api/submission/document-status ดึงข้อมูลโครงการวิจัยใหม่รอตรวจสอบเอกสาร
     * @apiVersion 1.0.0
     * @apiName SubmissionDocumentStatus
     * @apiGroup Data
     * 
     * @apiHeader (Header) {String} Authorization Bearer + (token)
     *
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} status สถานะของผลการส่งข้อมูล (OK=ส่งข้อมูลสำเร็จ, ERROR=มีข้อผิดพลาดเกิดขึ้น)
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Object} data ข้อมูลจำนวนโครงการ
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Integer} data.document_pending_count จำนวนโครงการรอตรวจสอบเอกสาร
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Integer} data.document_reject_count จำนวนโครงการตรวจสอบเอกสารแล้วส่งกลับให้แก้ไข
     * 
     * @apiSuccessExample ตัวอย่างข้อมูล
     *     HTTP/1.1 200 OK
     *     {
     *       "status": "OK",
     *       "data": {
     *          "doc_pending_count":10,
     *          "doc_reject_count": 67
     *       }
     *     }
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
    public function actionDocumentStatus() {
        \Yii::$app->language = 'th';
        $docRejectCount = \Yii::$app->user->identity->getSubmissionNewPanelCount(SubmissionTypeGroup::GROUP_NEW, Submission::STATUS_DOC_REJECTED);
        $docPendingCount = Submission::find()->joinWith(['project', 'submissionType', 'submissionType.submissionTypeGroup'])
                        ->isDeleted(FALSE)->submissionTypeGroup(SubmissionTypeGroup::GROUP_NEW)->status(Submission::STATUS_SUBMITTED)
                        ->andWhere(['project.project_code' => null, 'submission.is_legacy' => 0])->count();

        $data = [
            'doc_pending_count' => intval($docPendingCount),
            'doc_reject_count' => intval($docRejectCount),
        ];
        $results = [
            'status' => 'OK',
            'data' => $data,
        ];
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);
        return $results;
    }

    /**
     * @api {get} /index.php?r=api/submission/committee-assessment ดึงข้อมูลโครงการวิจัยใหม่ที่อยู่ระหว่างพิจารณาจากกรรมการ
     * @apiVersion 1.0.0
     * @apiName SubmissionCommitteeAssessment
     * @apiGroup Data
     * 
     * @apiHeader (Header) {String} Authorization Bearer + (token)
     *
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} status สถานะของผลการส่งข้อมูล (OK=ส่งข้อมูลสำเร็จ, ERROR=มีข้อผิดพลาดเกิดขึ้น)
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Object[]} data ข้อมูลจำนวนโครงการ
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Integer} data.panel_id รหัส Panel
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.panel_name ชื่อ Panel ภาษาไทย
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {String} data.panel_name_eng ชื่อ Panel ภาษาอังกฤษ
     * @apiSuccess (ส่งข้อมูลสำเร็จ) {Integer} data.count จำนวนโครงการ
     * 
     * @apiSuccessExample ตัวอย่างข้อมูล
     *   HTTP/1.1 200 OK
     *   {
     *       "status": "OK",
     *       "data": [
     *           {
     *               "panel_id": 1,
     *               "panel_name": "กลุ่มของผู้ปฏิบัติงาน 1",
     *               "panel_name_eng": "panel 1",
     *               "count": 58
     *           },
     *           {
     *               "panel_id": 2,
     *               "panel_name": "กลุ่มของผู้ปฏิบัติงาน 2",
     *               "panel_name_eng": "panel 2",
     *               "count": 24
     *           },
     *           {
     *               "panel_id": 3,
      v               "panel_name": "กลุ่มของผู้ปฏิบัติงาน 3",
     *               "panel_name_eng": "panel 3",
      v               "count": 10
     *           }
     *        ]
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
    public function actionCommitteeAssessment() {
        \Yii::$app->language = 'th';
        $panels = Panel::find()->isDeleted(false)->all();
        $data = [];
        foreach ($panels as $p) {
            $count = \Yii::$app->user->identity->getSubmissionCount(SubmissionTypeGroup::GROUP_NEW, Submission::CUSTOM_STATUS_MEETING_PENDING, null, $p->id);
            $data[] = [
                'panel_id' => $p->id,
                'panel_name' => $p->name,
                'panel_name_eng' => $p->name_eng,
                'count' => intval($count),
            ];
        }

        $results = [
            'status' => 'OK',
            'data' => $data,
        ];
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);
        return $results;
    }

    public function actionCreateInitialCrec() {
        $headers = Yii::$app->getRequest()->getHeaders();
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';
        $tran = Yii::$app->db->beginTransaction();
        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
        ];
        $message = '';
        $newSubmission = false;

        try {
            // VarDumper::dump($headers);
            // VarDumper::dump($bodyParams);
            $modelProject = new Project([
                'scenario' => Project::SCENARIO_CREATE_CREC,
            ]);

            $modelSubmission = new Submission([
                'scenario' => Submission::SCENARIO_CREATE_CREC,
            ]);

            $modelProject->load($bodyParams, 'project');
            $modelSubmission->load($bodyParams, 'submission');


            $p = Project::find()->isDeleted(false)->crecNumber($modelProject->project_code)->one();
            Yii::warning(VarDumper::dumpAsString($p, 2, false), 'create-initial-crec-api-called');
            $fs = FundingSource::find()->isDeleted(false)->crecId($modelProject->funding_source_id)->one();
            if (isset($fs)) {
                $fundingSourceId = $fs->id;
            } else {
                $fundingSourceId = Setting::getValue(Setting::CREC_FUNDING_SOURCE);
            }
            if (!isset($p)) {
                
                $p = new Project(['scenario' => Project::SCENARIO_CREATE_CREC,]);
                $p->crec_number = $modelProject->project_code;
                $p->name_eng = $modelProject->name_eng;
                $p->name_thai = $modelProject->name_thai;
                $p->is_child_project = $modelProject->is_child_project;
                $p->is_fda = $modelProject->is_fda;
                $p->fda_no = $modelProject->fda_no;
                $p->remark = $modelProject->remark;
                $p->funding_source_id = $fundingSourceId;
                $p->funding_source_description = $modelProject->funding_source_description;
                $p->crec_certified_date = $modelProject->certified_date;
                $p->crec_expire_at = $modelProject->expire_at;
                $p->crec_next_progress_at = $modelProject->next_progress_at;
                $p->crec_progress_period = $modelProject->progress_period;
                $p->certified_date = $modelProject->certified_date;
                $p->expire_at = $modelProject->expire_at;
                $p->next_progress_at = $modelProject->next_progress_at;
                $p->progress_period = $modelProject->progress_period;
                $p->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $p->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $p->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $p->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $p->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $p->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;

                if (!$p->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $p->firstErrors)
                    ];
                    Yii::warning($response, 'create-initial-crec-api-called');
                    return $response;
                }
                $p->save(false);
            } else {
                $p->scenario = Project::SCENARIO_CREATE_CREC;
                $p->crec_number = $modelProject->project_code;
                $p->name_eng = $modelProject->name_eng;
                $p->name_thai = $modelProject->name_thai;
                $p->is_child_project = $modelProject->is_child_project;
                $p->is_fda = $modelProject->is_fda;
                $p->fda_no = $modelProject->fda_no;
                $p->remark = $modelProject->remark;
                $p->funding_source_id = $fundingSourceId;
                $p->funding_source_description = $modelProject->funding_source_description;
                $p->crec_certified_date = empty($modelProject->certified_date) ? $p->crec_certified_date : $modelProject->certified_date;
                $p->crec_expire_at = empty($modelProject->expire_at) ? $p->crec_expire_at : $modelProject->expire_at;
                $p->crec_next_progress_at = empty($modelProject->next_progress_at) ? $p->crec_next_progress_at : $modelProject->next_progress_at;
                $p->crec_progress_period = empty($modelProject->progress_period) ? $p->crec_progress_period : $modelProject->progress_period;
                $p->certified_date = empty($modelProject->certified_date) ? $p->certified_date : $modelProject->certified_date;
                $p->expire_at = empty($modelProject->expire_at) ? $p->expire_at : $modelProject->expire_at;
                $p->next_progress_at = empty($modelProject->next_progress_at) ? $p->next_progress_at : $modelProject->next_progress_at;
                $p->progress_period = empty($modelProject->progress_period) ? $p->progress_period : $modelProject->progress_period;
                $p->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $p->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $p->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $p->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $p->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $p->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;

                if (!$p->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $p->firstErrors)
                    ];
                    Yii::warning($response, 'create-initial-crec-api-called');
                    return $response;
                }
                $p->save(false);
            }

            $submissionTypeId = Setting::getValue(Setting::INITIAL_CREC_SUBMISSION_TYPE);
            $s = Submission::find()->isDeleted(false)->project($p->id)->submissionType($submissionTypeId)->one();
            if (!isset($s)) {
                $newSubmission = true;
                $s = new Submission(['scenario' => Submission::SCENARIO_CREATE_CREC,]);
                $s->project_id = $p->id;
                $s->submission_type_id = $submissionTypeId;
                $s->status = Submission::STATUS_SUBMITTED;
                $s->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $s->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $s->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $s->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $s->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $s->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;
                $s->correspondence_no = $modelSubmission->correspondence_no;
                $s->correspondence_at = $modelSubmission->correspondence_at;
                $s->crec_send_plan_date = $modelSubmission->send_plan_date;
                $s->crec_staff = $modelSubmission->crec_staff;
                $s->is_submit_by_api = 1;
                $s->crec_certified_date = $modelSubmission->certified_date;
                $s->crec_expire_at = $modelSubmission->expire_at;
                $s->crec_next_progress_at = $modelSubmission->next_progress_at;
                $s->crec_progress_period = $modelSubmission->progress_period;
                $s->certified_date = $modelSubmission->certified_date;
                $s->expire_at = $modelSubmission->expire_at;
                $s->next_progress_at = $modelSubmission->next_progress_at;
                $s->progress_period = $modelSubmission->progress_period;
                $s->need_local_issue = $modelSubmission->need_local_issue;
                $s->is_legacy = $modelSubmission->is_legacy;
                $s->subject = $modelSubmission->subject;
                $s->submission_number = isset($modelSubmission->submission_number) ? $modelSubmission->submission_number : NULL;


                if (!$s->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $s->firstErrors)
                    ];
                    Yii::warning($response, 'create-initial-crec-api-called');
                    return $response;
                }
                $s->save(false);
            } else {
                $s->scenario = Submission::SCENARIO_CREATE_CREC;
                $s->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $s->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $s->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $s->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $s->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $s->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;
                $s->correspondence_no = $modelSubmission->correspondence_no;
                $s->correspondence_at = $modelSubmission->correspondence_at;
                $s->is_submit_by_api = 1;

                $s->crec_send_plan_date = empty($modelSubmission->send_plan_date) ? $s->crec_send_plan_date : $modelSubmission->send_plan_date;
                $s->crec_staff = empty($modelSubmission->crec_staff) ? $s->crec_staff : $modelSubmission->crec_staff;
                $s->crec_certified_date = empty($modelSubmission->certified_date) ? $s->crec_certified_date : $modelSubmission->certified_date;
                $s->crec_expire_at = empty($modelSubmission->expire_at) ? $s->crec_expire_at : $modelSubmission->expire_at;
                $s->crec_next_progress_at = empty($modelSubmission->next_progress_at) ? $s->crec_next_progress_at : $modelSubmission->next_progress_at;
                $s->crec_progress_period = empty($modelSubmission->progress_period) ? $s->crec_progress_period : $modelSubmission->progress_period;
                $s->certified_date = empty($modelSubmission->certified_date) ? $s->certified_date : $modelSubmission->certified_date;
                $s->expire_at = empty($modelSubmission->expire_at) ? $s->expire_at : $modelSubmission->expire_at;
                $s->next_progress_at = empty($modelSubmission->next_progress_at) ? $s->next_progress_at : $modelSubmission->next_progress_at;
                $s->progress_period = empty($modelSubmission->progress_period) ? $s->progress_period : $modelSubmission->progress_period;
                $s->need_local_issue = empty($modelSubmission->need_local_issue) ? $s->need_local_issue : $modelSubmission->need_local_issue;
                $s->submission_number = isset($modelSubmission->submission_number) ? $modelSubmission->submission_number : NULL;
                $s->subject = empty($modelSubmission->subject) ? $s->subject : $modelSubmission->subject;
                if (!$s->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $s->firstErrors)
                    ];
                    Yii::warning($response, 'create-initial-crec-api-called');
                    return $response;
                }
                $s->save(false);
            }

            $leader = null;
            $hasLeader = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                            ->submission($s->id)->isLeader()->exists();
            foreach ($bodyParams['ecProjectResearchers'] as $paramsPr) {

                $pr = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                                ->submission($s->id)->person($paramsPr['person_ec_id'])->one();
                if (!isset($pr)) {
                    $pr = new ProjectResearcher();
                    $pr->project_id = $p->id;
                    $pr->submission_id = $s->id;
                    $pr->person_id = $paramsPr['person_ec_id'];
                    $pr->is_leader = $paramsPr['is_site_leader'];
                    if ($pr->is_leader && (isset($leader) || $hasLeader)) {
                        $pr->is_leader = 0; // Only one leader allowed
                    }
                    if ($pr->is_leader) {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        $leader = $pr;
                    } else {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        // $pr->mail_sent = 0;
                        // $pr->acknowledge_status = ProjectResearcher::STATUS_PENDING_ACK;
                    }
                    $pr->addCoi();
                    $pr->cv_file = $pr->person->cv_file;
                    if (!$pr->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $pr->firstErrors)
                        ];
                        Yii::warning($response, 'create-initial-crec-api-called');
                        return $response;
                    }
                    if (!$pr->is_leader) {
                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_RESEARCHER_ACK, $pr->id);
                    }
                }
            }
            if (isset($leader)) {
                $s->submission_by = $leader->person->user_id;
                $s->save(false);
            }

            foreach ($bodyParams['ecSubmissionDocuments'] as $paramsSd) {

                $sd = SubmissionDocument::find()->isDeleted(false)
                        ->submission($s->id)
                        ->sdCrecId($paramsSd['id'])
                        ->one();
                if (!isset($sd)) {
                    $sd = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC,]);
                    $sd->project_id = $p->id;
                    $sd->submission_id = $s->id;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'create-initial-crec-api-called');
                        return $response;
                    }
                } else {
                    $sd->scenario = SubmissionDocument::SCENARIO_CREATE_CREC;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'create-initial-crec-api-called');
                        return $response;
                    }
                }
            }

            $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                            ->submissionType($s->submission_type_id)
                            ->submissionTypeRole(Role::RESEARCHER)->indexBy('id')
                            ->isApi()
                            ->orderBy([new \yii\db\Expression('document_submission_type.sort IS NULL ,document_submission_type.sort ASC')])->all();
            foreach ($docTypes as $type) {
                $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($s->id)->documents($type->document_id)->one();

                if (!isset($doc)) {
                    $doc = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC]);
                    $doc->document_id = $type->document_id;
                    $doc->project_id = $p->id;
                    $doc->submission_id = $s->id;
                    $doc->name = $type->document->name;
                    $doc->name_eng = $type->document->name_eng;
                    if (!$doc->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $doc->firstErrors)
                        ];
                        Yii::warning($response, 'create-initial-crec-api-called');
                        return $response;
                    }
                }
            }

            $tran->commit();
            if ($newSubmission && !$s->is_legacy) {
                Alert::addNewSubmission($s);
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_NEW_SUBMISSION_CREC, $s->id);
            }
            EmailQueue::execSendMailCmd();

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => [
                    'project' => $p->attributes,
                    'submission' => $s->attributes,
                ]
            ];
            Yii::warning($response, 'create-initial-crec-api-called');
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
            Yii::warning($response, 'create-initial-crec-api-called');
            return $response;
        } finally {
            // $apiTran = new ApiTran();
            // $apiTran->kkf_ref_id = $model->kkfRefId;
            // $apiTran->api_name = $this->action->uniqueId;
            // $apiTran->headers = Json::encode($headers);
            // $apiTran->data = Json::encode($bodyParams);
            // $apiTran->status = $response['status'];
            // $apiTran->message = Json::encode($response);
            // $apiTran->trace = $stackTrace;
            // $apiTran->save();
            // $response['fds_ref_id'] = $apiTran->fds_ref_id;
            // echo "Finally Block";
        }
    }

    public function actionUpdateResultDocument() {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';
        $tran = Yii::$app->db->beginTransaction();
        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
        ];
        $message = '';
        $newSubmission = false;
        $modelProject = new Project([
            'scenario' => Project::SCENARIO_CREATE_CREC,
        ]);

        $modelSubmission = new Submission([
            'scenario' => Submission::SCENARIO_CREATE_CREC,
        ]);

        $modelProject->load($bodyParams, 'project');
        $modelSubmission->load($bodyParams, 'submission');

        try {

            $paramsSubmissionEc = $bodyParams['submissionEc'];
            $submission = Submission::findOne($paramsSubmissionEc['ec_submission_id']);
            $submission->crec_resolution = $paramsSubmissionEc['crec_resolution'];
            if ($submission->send_to_crec) {
                $submission->acknowledged_crec_result = Submission::CREC_WAITING_RESULT_ACKNOWLEDGE;
            }
            $submission->crec_certified_date = $modelSubmission->certified_date;
            $submission->crec_expire_at = $modelSubmission->expire_at;
            $submission->crec_next_progress_at = $modelSubmission->next_progress_at;
            $submission->crec_progress_period = $modelSubmission->progress_period;
            $submission->certified_date = $modelSubmission->certified_date;
            $submission->expire_at = $modelSubmission->expire_at;
            $submission->next_progress_at = $modelSubmission->next_progress_at;
            $submission->progress_period = $modelSubmission->progress_period;


            if (!$submission->save()) {
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => \implode(", ", $submission->firstErrors)
                ];
                Yii::warning($response, 'update-result-document-api-called');
                return $response;
            }

            $data = [];
            foreach ($bodyParams['submissionResultDocuments'] as $paramsPr) {
                $srd = SubmissionResultDocument::find()->isDeleted(false)->srdCrecId($paramsPr['id'])->one();
                if (!isset($srd)) {
                    $srd = new SubmissionResultDocument();
                    $srd->submission_id = $submission->id;
                    $srd->srd_crec_id = $paramsPr['id'];
                    $srd->name = $paramsPr['resultDocument']['name'] ?? $paramsPr['name'];
                    $srd->is_site = 1;
                    if (!$srd->save()) {
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $srd->firstErrors)
                        ];
                        Yii::warning($response, 'update-result-document-api-called');
                        return $response;
                    }
                    $data[] = $srd;
                }
            }

            $sds = SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasSdCrecId()->all();
            // foreach ($sds as $sd) {
            //     $sd->deleted = 1;
            //     $sd->save(false);
            // }
            $updatedSdIds = [];
            foreach ($bodyParams['ecSubmissionDocuments'] as $paramsSd) {

                // $sd = SubmissionDocument::find()->isDeleted(false)
                //     ->submission($submission->id)
                //     ->sdCrecId($paramsSd['id'])
                //     ->one();
                $matchedSds = \array_filter($sds, function ($val) use ($paramsSd) {
                    return $val->sd_crec_id == $paramsSd['id'];
                });
                if (empty($matchedSds)) {
                    $deletingSds = \array_filter($sds, function ($val) use ($paramsSd) {
                        return $val->name == $paramsSd['name'];
                    });

                    foreach ($deletingSds as $ds) {
                        $ds->deleted = 1;
                        $ds->save(false);
                        $updatedSdIds[] = $ds->id;
                    }
                    $sd = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC,]);
                    $sd->project_id = $submission->project_id;
                    $sd->submission_id = $submission->id;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
                    // if (!$sd->is_site) {
                    $sd->status = SubmissionDocument::STATUS_PASS;
                    // }
                    if (!$sd->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $sd->firstErrors)
                        ];
                        Yii::warning($response, 'update-result-document-crec-api-called');
                        return $response;
                    }
                } else {
                    $sd = \array_shift($matchedSds);
                    $sd->scenario = SubmissionDocument::SCENARIO_CREATE_CREC;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
                    // if (!$sd->is_site) {
                    $sd->status = SubmissionDocument::STATUS_PASS;
                    // }
                    if (!$sd->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $sd->firstErrors)
                        ];
                        Yii::warning($response, 'update-result-document-api-called');
                        return $response;
                    }
                    $updatedSdIds[] = $sd->id;
                }
            }

            $deletingSds = \array_filter($sds, function ($val) use ($updatedSdIds) {
                return !in_array($val->id, $updatedSdIds);
            });
            foreach ($deletingSds as $ds) {
                $ds->deleted = 1;
                $ds->save(false);
            }

            $p = $submission->project;
            $p->crec_certified_date = $modelProject->certified_date;
            $p->crec_expire_at = $modelProject->expire_at;
            $p->crec_next_progress_at = $modelProject->next_progress_at;
            $p->crec_progress_period = $modelProject->progress_period;
            $p->certified_date = $modelProject->certified_date;
            $p->expire_at = $modelProject->expire_at;
            $p->next_progress_at = $modelProject->next_progress_at;
            $p->progress_period = $modelProject->progress_period;
            $p->save(false);

            $tran->commit();
            // if ($newSubmission) {
            //     Alert::addNewSubmission($s);
            //     EmailQueue::addQueueNoExec(EmailQueue::TYPE_NEW_SUBMISSION_CREC, $s->id);
            // }
            // EmailQueue::execSendMailCmd();
            Alert::addCrecUpdateResultDocument($submission);
            EmailQueue::addQueue(EmailQueue::TYPE_UPDATE_RESULT_DOCUMENT_CREC, $submission->id);

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => $data,
            ];
            Yii::warning($response, 'update-result-document-api-called');
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
            Yii::warning($response, 'update-result-document-api-called');
            return $response;
        } finally {
            // $apiTran = new ApiTran();
            // $apiTran->kkf_ref_id = $model->kkfRefId;
            // $apiTran->api_name = $this->action->uniqueId;
            // $apiTran->headers = Json::encode($headers);
            // $apiTran->data = Json::encode($bodyParams);
            // $apiTran->status = $response['status'];
            // $apiTran->message = Json::encode($response);
            // $apiTran->trace = $stackTrace;
            // $apiTran->save();
            // $response['fds_ref_id'] = $apiTran->fds_ref_id;
            // echo "Finally Block";
        }
    }

    public function actionUpdateResolution() {
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';
        $tran = Yii::$app->db->beginTransaction();
        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'บันทึกมติการประชุม CREC เรียบร้อยแล้ว',
        ];
        $message = '';
        $newSubmission = false;

        $modelProject = new Project([
            'scenario' => Project::SCENARIO_CREATE_CREC,
        ]);

        $modelSubmission = new Submission([
            'scenario' => Submission::SCENARIO_CREATE_CREC,
        ]);

        $modelProject->load($bodyParams, 'project');
        $modelSubmission->load($bodyParams, 'submission');

        try {

            $paramsSubmissionEc = $bodyParams['submissionEc'];
            $submission = Submission::findOne($paramsSubmissionEc['ec_submission_id']);
            $submission->scenario = Submission::SCENARIO_UPDATE_CREC_RESOLUTION;
            $submission->crec_resolution = $paramsSubmissionEc['crec_resolution'];
            $submission->crec_certified_date = $modelSubmission->certified_date;
            $submission->crec_expire_at = $modelSubmission->expire_at;
            $submission->crec_next_progress_at = $modelSubmission->next_progress_at;
            $submission->crec_progress_period = $modelSubmission->progress_period;
            $submission->certified_date = $modelSubmission->certified_date;
            $submission->expire_at = $modelSubmission->expire_at;
            $submission->next_progress_at = $modelSubmission->next_progress_at;
            $submission->progress_period = $modelSubmission->progress_period;

            $data = [];
            if (!$submission->save()) {
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => \implode(", ", $submission->firstErrors)
                ];
                Yii::warning($response, 'update-resolution-api-called');
                return $response;
            }
            $p = $submission->project;
            $p->crec_certified_date = $modelProject->certified_date;
            $p->crec_expire_at = $modelProject->expire_at;
            $p->crec_next_progress_at = $modelProject->next_progress_at;
            $p->crec_progress_period = $modelProject->progress_period;
            $p->certified_date = $modelProject->certified_date;
            $p->expire_at = $modelProject->expire_at;
            $p->next_progress_at = $modelProject->next_progress_at;
            $p->progress_period = $modelProject->progress_period;
            $p->save(false);

            $tran->commit();
            // if ($newSubmission) {
            //     Alert::addNewSubmission($s);
            //     EmailQueue::addQueueNoExec(EmailQueue::TYPE_NEW_SUBMISSION_CREC, $s->id);
            // }
            // EmailQueue::execSendMailCmd();
            Alert::addCrecUpdateResolution($submission);
            EmailQueue::addQueue(EmailQueue::TYPE_UPDATE_RESOLUTION_CREC, $submission->id);

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'บันทึกมติการประชุม CREC เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => $data,
            ];
            Yii::warning($response, 'update-resolution-api-called');
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
            Yii::warning($response, 'update-resolution-api-called');
            return $response;
        } finally {
            // $apiTran = new ApiTran();
            // $apiTran->kkf_ref_id = $model->kkfRefId;
            // $apiTran->api_name = $this->action->uniqueId;
            // $apiTran->headers = Json::encode($headers);
            // $apiTran->data = Json::encode($bodyParams);
            // $apiTran->status = $response['status'];
            // $apiTran->message = Json::encode($response);
            // $apiTran->trace = $stackTrace;
            // $apiTran->save();
            // $response['fds_ref_id'] = $apiTran->fds_ref_id;
            // echo "Finally Block";
        }
    }

    public function actionResubmitLocalIssue() {
        $headers = Yii::$app->getRequest()->getHeaders();
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';

        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
        ];
        $message = '';
        $newSubmission = false;
        $modelProject = new Project([
            'scenario' => Project::SCENARIO_CREATE_CREC,
        ]);

        $modelSubmission = new Submission([
            'scenario' => Submission::SCENARIO_CREATE_CREC,
        ]);

        $modelProject->load($bodyParams, 'project');
        $modelSubmission->load($bodyParams, 'submission');
        $p = Project::find()->isDeleted(false)->crecNumber($modelProject->project_code)->one();
        if (!isset($p)) {
            $response = $this->actionCreateInitialCrec();
            return $response;
        }

        $tran = Yii::$app->db->beginTransaction();
        $fs = FundingSource::find()->isDeleted(false)->crecId($modelProject->funding_source_id)->one();
        if (isset($fs)) {
            $fundingSourceId = $fs->id;
        } else {
            $fundingSourceId = Setting::getValue(Setting::CREC_FUNDING_SOURCE);
        }
        try {
            // VarDumper::dump($headers);
            // VarDumper::dump($bodyParams);

            $p->scenario = Project::SCENARIO_CREATE_CREC;
            $p->crec_number = $modelProject->project_code;
            $p->name_eng = $modelProject->name_eng;
            $p->name_thai = $modelProject->name_thai;
            $p->is_child_project = $modelProject->is_child_project;
            $p->is_fda = $modelProject->is_fda;
            $p->fda_no = $modelProject->fda_no;
            $p->remark = $modelProject->remark;
            $p->funding_source_id = $fundingSourceId;
            $p->funding_source_description = $modelProject->funding_source_description;
            $p->crec_certified_date = empty($modelProject->certified_date) ? $p->crec_certified_date : $modelProject->certified_date;
            $p->crec_expire_at = empty($modelProject->expire_at) ? $p->crec_expire_at : $modelProject->expire_at;
            $p->crec_next_progress_at = empty($modelProject->next_progress_at) ? $p->crec_next_progress_at : $modelProject->next_progress_at;
            $p->crec_progress_period = empty($modelProject->progress_period) ? $p->crec_progress_period : $modelProject->progress_period;
            $p->certified_date = empty($modelProject->certified_date) ? $p->certified_date : $modelProject->certified_date;
            $p->expire_at = empty($modelProject->expire_at) ? $p->expire_at : $modelProject->expire_at;
            $p->next_progress_at = empty($modelProject->next_progress_at) ? $p->next_progress_at : $modelProject->next_progress_at;
            $p->progress_period = empty($modelProject->progress_period) ? $p->progress_period : $modelProject->progress_period;
            $p->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
            $p->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
            $p->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
            $p->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
            $p->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
            $p->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;

            if (!$p->validate()) {
                $tran->rollBack();
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => \implode(", ", $p->firstErrors)
                ];
                Yii::warning($response, 'resubmit-local-issue-api-called');
                return $response;
            }
            $p->save(false);

            $submissionType = SubmissionType::find()->isDeleted(false)->crecId($modelSubmission->submission_type_id)->one();
            if (!isset($submissionType)) {
                $submissionTypeId = Setting::getValue(Setting::INITIAL_CREC_SUBMISSION_TYPE);
            } else {
                $submissionTypeId = $submissionType->id;
            }
            if ($submissionTypeId == SubmissionType::TYPE_CREC) {
                $s = Submission::find()->isDeleted(false)->project($p->id)->submissionType($submissionTypeId)->one();
            } else {
                $s = null;
                if (isset($modelSubmission->ref_submission_id)) {

                    // $s = Submission::find()->isDeleted(false)->project($p->id)->submissionType($submissionTypeId)->orderBy('id DESC')->one();
                    $s = Submission::findOne($bodyParams['firstSubmissionEc']['ec_submission_id']);
                }
            }
            if (!isset($s)) {
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => 'ไม่พบข้อมูลการยื่นโครงการ',
                ];
                Yii::warning($response, 'resubmit-local-issue-api-called');
            } else {
                $s->scenario = Submission::SCENARIO_CREATE_CREC;
                if (!$modelSubmission->is_legacy) {
                    $s->status = Submission::STATUS_SUBMITTED;
                }

                $s->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $s->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $s->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $s->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $s->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $s->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;
                $s->correspondence_no = $modelSubmission->correspondence_no;
                $s->correspondence_at = $modelSubmission->correspondence_at;
                $s->crec_issue_req_detail = $bodyParams['submissionEc']['issue_req_detail'] ?? null;
                $s->crec_staff = empty($modelSubmission->crec_staff) ? $s->crec_staff : $modelSubmission->crec_staff;

                $s->crec_send_plan_date = empty($modelSubmission->send_plan_date) ? $s->crec_send_plan_date : $modelSubmission->send_plan_date;
                $s->crec_certified_date = empty($modelSubmission->certified_date) ? $s->crec_certified_date : $modelSubmission->certified_date;
                $s->crec_expire_at = empty($modelSubmission->expire_at) ? $s->crec_expire_at : $modelSubmission->expire_at;
                $s->crec_next_progress_at = empty($modelSubmission->next_progress_at) ? $s->crec_next_progress_at : $modelSubmission->next_progress_at;
                $s->crec_progress_period = empty($modelSubmission->progress_period) ? $s->crec_progress_period : $modelSubmission->progress_period;
                $s->certified_date = empty($modelSubmission->certified_date) ? $s->certified_date : $modelSubmission->certified_date;
                $s->expire_at = empty($modelSubmission->expire_at) ? $s->expire_at : $modelSubmission->expire_at;
                $s->next_progress_at = empty($modelSubmission->next_progress_at) ? $s->next_progress_at : $modelSubmission->next_progress_at;
                $s->progress_period = empty($modelSubmission->progress_period) ? $s->progress_period : $modelSubmission->progress_period;
                $s->need_local_issue = empty($modelSubmission->need_local_issue) ? $s->need_local_issue : $modelSubmission->need_local_issue;
                $s->submission_number = isset($modelSubmission->submission_number) ? $modelSubmission->submission_number : NULL;

                $s->subject = empty($modelSubmission->subject) ? $s->subject : $modelSubmission->subject;
                if (!$s->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $s->firstErrors)
                    ];
                    Yii::warning($response, 'resubmit-local-issue-api-called');
                    return $response;
                }
                $s->save(false);
            }

            $leader = null;
            $hasLeader = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                            ->submission($s->id)->isLeader()->exists();
            foreach ($bodyParams['ecProjectResearchers'] as $paramsPr) {

                $pr = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                                ->submission($s->id)->person($paramsPr['person_ec_id'])->one();
                if (!isset($pr)) {
                    $pr = new ProjectResearcher();
                    $pr->project_id = $p->id;
                    $pr->submission_id = $s->id;
                    $pr->person_id = $paramsPr['person_ec_id'];
                    $pr->is_leader = $paramsPr['is_site_leader'];
                    if ($pr->is_leader && (isset($leader) || $hasLeader)) {
                        $pr->is_leader = 0; // Only one leader allowed
                    }
                    if ($pr->is_leader) {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        $leader = $pr;
                    } else {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        // $pr->mail_sent = 0;
                        // $pr->acknowledge_status = ProjectResearcher::STATUS_PENDING_ACK;
                    }
                    $pr->cv_file = $pr->person->cv_file;
                    $pr->addCoi();

                    if (!$pr->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $pr->firstErrors)
                        ];
                        Yii::warning($response, 'resubmit-local-issue-api-called');
                        return $response;
                    }
                    if (!$pr->is_leader) {
                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_RESEARCHER_ACK, $pr->id);
                    }
                }
            }

            $sds = SubmissionDocument::find()->isDeleted(false)->submission($s->id)->hasSdCrecId()->all();
            // foreach ($sds as $sd) {
            //     $sd->deleted = 1;
            //     $sd->save(false);
            // }
            $updatedSdIds = [];
            foreach ($bodyParams['ecSubmissionDocuments'] as $paramsSd) {

                // $sd = SubmissionDocument::find()->isDeleted(false)->sdCrecId($paramsSd['id'])
                //     ->one();

                $matchedSds = \array_filter($sds, function ($val) use ($paramsSd) {
                    return $val->sd_crec_id == $paramsSd['id'];
                });
                if (empty($matchedSds)) {
                    $deletingSds = \array_filter($sds, function ($val) use ($paramsSd) {
                        return $val->name == $paramsSd['name'];
                    });

                    foreach ($deletingSds as $ds) {
                        $ds->deleted = 1;
                        $ds->save(false);
                        $updatedSdIds[] = $ds->id;
                    }
                    $sd = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC]);
                    $sd->project_id = $p->id;
                    $sd->submission_id = $s->id;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'resubmit-local-issue-api-called');
                        return $response;
                    }
                } else {
                    $sd = \array_shift($matchedSds);
                    $sd->scenario = SubmissionDocument::SCENARIO_CREATE_CREC;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'resubmit-local-issue-api-called');
                        return $response;
                    }
                    $updatedSdIds[] = $sd->id;
                }
            }
            $deletingSds = \array_filter($sds, function ($val) use ($updatedSdIds) {
                return !in_array($val->id, $updatedSdIds);
            });
            foreach ($deletingSds as $ds) {
                $ds->deleted = 1;
                $ds->save(false);
            }
            $tran->commit();
            if (!$s->is_legacy) {
                Alert::addCrecResubmitLocalIssue($s);
                EmailQueue::addQueue(EmailQueue::TYPE_RESUBMIT_LOCAL_ISSUE_CREC, $s->id);
            }
            // if ($newSubmission) {
            //     Alert::addNewSubmission($s);
            //     EmailQueue::addQueueNoExec(EmailQueue::TYPE_NEW_SUBMISSION_CREC, $s->id);
            // }
            // EmailQueue::execSendMailCmd();

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'ส่งข้อมูล Submission CREC MOU เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => [
                    'project' => $p->attributes,
                    'submission' => $s->attributes,
                ]
            ];
            Yii::warning($response, 'resubmit-local-issue-api-called');
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
            Yii::warning($response, 'resubmit-local-issue-api-called');
            return $response;
        } finally {
            // $apiTran = new ApiTran();
            // $apiTran->kkf_ref_id = $model->kkfRefId;
            // $apiTran->api_name = $this->action->uniqueId;
            // $apiTran->headers = Json::encode($headers);
            // $apiTran->data = Json::encode($bodyParams);
            // $apiTran->status = $response['status'];
            // $apiTran->message = Json::encode($response);
            // $apiTran->trace = $stackTrace;
            // $apiTran->save();
            // $response['fds_ref_id'] = $apiTran->fds_ref_id;
            // echo "Finally Block";
        }
    }

    public function actionCreateContinueCrec() {
        $headers = Yii::$app->getRequest()->getHeaders();
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        $stackTrace = '';
        $response = [
            'status' => ExternalApi::STATUS_OK,
            'message' => 'ส่งข้อมูล Continue Submission CREC MOU เรียบร้อยแล้ว',
        ];
        $message = '';
        $newSubmission = false;
        $modelProject = new Project([
            'scenario' => Project::SCENARIO_CREATE_CREC,
        ]);

        $modelSubmission = new Submission([
            'scenario' => Submission::SCENARIO_CREATE_CREC,
        ]);

        $modelProject->load($bodyParams, 'project');
        $modelSubmission->load($bodyParams, 'submission');


        $p = Project::find()->isDeleted(false)->crecNumber($modelProject->project_code)->one();
        if (!isset($p)) {
            $response = $this->actionCreateInitialCrec();
            return $response;
        }

        $tran = Yii::$app->db->beginTransaction();
        try {
            $fs = FundingSource::find()->isDeleted(false)->crecId($modelProject->funding_source_id)->one();
            if (isset($fs)) {
                $fundingSourceId = $fs->id;
            } else {
                $fundingSourceId = Setting::getValue(Setting::CREC_FUNDING_SOURCE);
            }

            $p->scenario = Project::SCENARIO_CREATE_CREC;
            $p->crec_number = $modelProject->project_code;
            $p->name_eng = $modelProject->name_eng;
            $p->name_thai = $modelProject->name_thai;
            $p->is_child_project = $modelProject->is_child_project;
            $p->is_fda = $modelProject->is_fda;
            $p->fda_no = $modelProject->fda_no;
            $p->remark = $modelProject->remark;
            $p->funding_source_id = $fundingSourceId;
            $p->funding_source_description = $modelProject->funding_source_description;

            $p->crec_certified_date = empty($modelProject->certified_date) ? $p->crec_certified_date : $modelProject->certified_date;
            $p->crec_expire_at = empty($modelProject->expire_at) ? $p->crec_expire_at : $modelProject->expire_at;
            $p->crec_next_progress_at = empty($modelProject->next_progress_at) ? $p->crec_next_progress_at : $modelProject->next_progress_at;
            $p->crec_progress_period = empty($modelProject->progress_period) ? $p->crec_progress_period : $modelProject->progress_period;
            $p->certified_date = empty($modelProject->certified_date) ? $p->certified_date : $modelProject->certified_date;
            $p->expire_at = empty($modelProject->expire_at) ? $p->expire_at : $modelProject->expire_at;
            $p->next_progress_at = empty($modelProject->next_progress_at) ? $p->next_progress_at : $modelProject->next_progress_at;
            $p->progress_period = empty($modelProject->progress_period) ? $p->progress_period : $modelProject->progress_period;
            $p->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
            $p->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
            $p->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
            $p->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
            $p->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
            $p->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;

            if (!$p->validate()) {
                $tran->rollBack();
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => \implode(", ", $p->firstErrors)
                ];
                Yii::warning($response, 'create-continue-crec-api-called');
                return $response;
            }
            $p->save(false);


            // $submissionTypeId = Setting::getValue(Setting::INITIAL_CREC_SUBMISSION_TYPE);
            $submissionType = SubmissionType::find()->isDeleted(false)->crecId($modelSubmission->submission_type_id)->one();
            if (!isset($submissionType)) {
                $tran->rollBack();
                Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                $response = [
                    'status' => ExternalApi::STATUS_ERROR,
                    'params' => $bodyParams,
                    'message' => 'ไม่พบข้อมูลประเภทการยื่นโครงการ',
                ];
                Yii::warning($response, 'create-continue-crec-api-called');
                return $response;
            }
            $submissionTypeId = $submissionType->id;
            // $submissionTypeId = Setting::getValue(Setting::INITIAL_CREC_SUBMISSION_TYPE);
            $s = null;
            if (isset($modelSubmission->ref_submission_id)) {

                // $s = Submission::find()->isDeleted(false)->project($p->id)->submissionType($submissionTypeId)->orderBy('id DESC')->one();
                $s = Submission::findOne($bodyParams['firstSubmissionEc']['ec_submission_id']);
            }
            if (!isset($s)) {
                $newSubmission = true;
                $s = new Submission(['scenario' => Submission::SCENARIO_CREATE_CREC,]);
                $s->project_id = $p->id;
                $s->submission_type_id = $submissionTypeId;
                $s->status = Submission::STATUS_SUBMITTED;
                $s->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $s->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $s->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $s->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $s->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $s->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;
                $s->correspondence_no = $modelSubmission->correspondence_no;
                $s->correspondence_at = $modelSubmission->correspondence_at;
                $s->crec_send_plan_date = $modelSubmission->send_plan_date;
                $s->crec_staff = $modelSubmission->crec_staff;
                $s->is_submit_by_api = 1;
                $s->crec_certified_date = $modelSubmission->certified_date;
                $s->crec_expire_at = $modelSubmission->expire_at;
                $s->crec_next_progress_at = $modelSubmission->next_progress_at;
                $s->crec_progress_period = $modelSubmission->progress_period;
                $s->certified_date = $modelSubmission->certified_date;
                $s->expire_at = $modelSubmission->expire_at;
                $s->next_progress_at = $modelSubmission->next_progress_at;
                $s->progress_period = $modelSubmission->progress_period;
                $s->need_local_issue = $modelSubmission->need_local_issue;
                $s->submission_number = isset($modelSubmission->submission_number) ? $modelSubmission->submission_number : NULL;
                $s->project_coordinator_id = $p->project_coordinator_id;
                $s->project_coordinator_2nd_id = $p->project_coordinator_2nd_id;
                $s->project_coordinator_3rd_id = $p->project_coordinator_3rd_id;
                $s->project_viewer_id = $p->project_viewer_id;

                $s->subject = $modelSubmission->subject;
                if (!$s->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $s->firstErrors)
                    ];
                    Yii::warning($response, 'create-continue-crec-api-called');
                    return $response;
                }
                $s->save(false);
            } else {
                $s->scenario = Submission::SCENARIO_CREATE_CREC;
                $s->crec_leader_name = isset($bodyParams['projectLeader']['fullName']) ? $bodyParams['projectLeader']['fullName'] : null;
                $s->crec_leader_name_eng = isset($bodyParams['projectLeader']['fullNameEng']) ? $bodyParams['projectLeader']['fullNameEng'] : null;
                $s->crec_leader_site_name = isset($bodyParams['projectLeader']['site']['name']) ? $bodyParams['projectLeader']['site']['name'] : null;
                $s->crec_leader_site_name_eng = isset($bodyParams['projectLeader']['site']['name_eng']) ? $bodyParams['projectLeader']['site']['name_eng'] : null;
                $s->crec_leader_org_name = isset($bodyParams['projectLeader']['site']['organization']['name']) ? $bodyParams['projectLeader']['site']['organization']['name'] : null;
                $s->crec_leader_org_name_eng = isset($bodyParams['projectLeader']['site']['organization']['name_eng']) ? $bodyParams['projectLeader']['site']['organization']['name_eng'] : null;
                $s->correspondence_no = $modelSubmission->correspondence_no;
                $s->correspondence_at = $modelSubmission->correspondence_at;
                $s->is_submit_by_api = 1;
                $s->is_legacy = $modelSubmission->is_legacy;

                $s->crec_send_plan_date = empty($modelSubmission->send_plan_date) ? $s->crec_send_plan_date : $modelSubmission->send_plan_date;
                $s->crec_staff = empty($modelSubmission->crec_staff) ? $s->crec_staff : $modelSubmission->crec_staff;
                $s->crec_certified_date = empty($modelSubmission->certified_date) ? $s->crec_certified_date : $modelSubmission->certified_date;
                $s->crec_expire_at = empty($modelSubmission->expire_at) ? $s->crec_expire_at : $modelSubmission->expire_at;
                $s->crec_next_progress_at = empty($modelSubmission->next_progress_at) ? $s->crec_next_progress_at : $modelSubmission->next_progress_at;
                $s->crec_progress_period = empty($modelSubmission->progress_period) ? $s->crec_progress_period : $modelSubmission->progress_period;
                $s->certified_date = empty($modelSubmission->certified_date) ? $s->certified_date : $modelSubmission->certified_date;
                $s->expire_at = empty($modelSubmission->expire_at) ? $s->expire_at : $modelSubmission->expire_at;
                $s->next_progress_at = empty($modelSubmission->next_progress_at) ? $s->next_progress_at : $modelSubmission->next_progress_at;
                $s->progress_period = empty($modelSubmission->progress_period) ? $s->progress_period : $modelSubmission->progress_period;
                $s->need_local_issue = empty($modelSubmission->need_local_issue) ? $s->need_local_issue : $modelSubmission->need_local_issue;
                $s->submission_number = isset($modelSubmission->submission_number) ? $modelSubmission->submission_number : NULL;
                $s->subject = empty($modelSubmission->subject) ? $s->subject : $modelSubmission->subject;
                if (!$s->validate()) {
                    $tran->rollBack();
                    Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                    $response = [
                        'status' => ExternalApi::STATUS_ERROR,
                        'params' => $bodyParams,
                        'message' => \implode(", ", $s->firstErrors)
                    ];
                    Yii::warning($response, 'create-continue-crec-api-called');
                    return $response;
                }
                $s->save(false);
            }

            $leader = null;
            $hasLeader = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                            ->submission($s->id)->isLeader()->exists();
            foreach ($bodyParams['ecProjectResearchers'] as $paramsPr) {

                $pr = ProjectResearcher::find()->isDeleted(false)->project($p->id)
                                ->submission($s->id)->person($paramsPr['person_ec_id'])->one();
                if (!isset($pr)) {
                    $pr = new ProjectResearcher();
                    $pr->project_id = $p->id;
                    $pr->submission_id = $s->id;
                    $pr->person_id = $paramsPr['person_ec_id'];
                    $pr->is_leader = $paramsPr['is_site_leader'];
                    if ($pr->is_leader && (isset($leader) || $hasLeader)) {
                        $pr->is_leader = 0; // Only one leader allowed
                    }
                    if ($pr->is_leader) {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        $leader = $pr;
                    } else {
                        $pr->mail_sent = 1;
                        $pr->acknowledge_status = ProjectResearcher::STATUS_ACCEPTED;
                        $pr->acknowledge_at = date('Y-m-d H:i:s');
                        // $pr->mail_sent = 0;
                        // $pr->acknowledge_status = ProjectResearcher::STATUS_PENDING_ACK;
                    }
                    $pr->cv_file = $pr->person->cv_file;
                    $pr->addCoi();

                    if (!$pr->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $pr->firstErrors)
                        ];
                        Yii::warning($response, 'create-continue-crec-api-called');
                        return $response;
                    }
                    if (!$pr->is_leader) {
                        EmailQueue::addQueueNoExec(EmailQueue::TYPE_RESEARCHER_ACK, $pr->id);
                    }
                }
            }
            if (isset($leader)) {
                $s->submission_by = $leader->person->user_id;
                $s->save(false);
            }

            foreach ($bodyParams['ecSubmissionDocuments'] as $paramsSd) {
                // Yii::warning(VarDumper::dumpAsString($paramsSd), 'create-continue-crec-submission-documents');
                $sd = SubmissionDocument::find()->isDeleted(false)
                        ->submission($s->id)
                        ->sdCrecId($paramsSd['id'])
                        ->one();
                // Yii::warning(VarDumper::dumpAsString($sd, 2, false), 'create-continue-crec-submission-documents');

                if (!isset($sd)) {
                    $sd = new SubmissionDocument(['scenario' => SubmissionDocument::SCENARIO_CREATE_CREC,]);
                    $sd->project_id = $p->id;
                    $sd->submission_id = $s->id;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'create-continue-crec-api-called');
                        return $response;
                    }
                } else {
                    $sd->scenario = SubmissionDocument::SCENARIO_CREATE_CREC;
                    $sd->name = $paramsSd['name'];
                    $sd->name_eng = $paramsSd['name_eng'];
                    $sd->remark = $paramsSd['remark'];
                    $sd->version = $paramsSd['version'];
                    $sd->version_at = $paramsSd['version_at'];
                    $sd->sd_crec_id = $paramsSd['id'];
                    $sd->is_site = isset($paramsSd['site_id']) ? 1 : 0;
                    $sd->is_certificate = $paramsSd['is_certificate'];
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
                        Yii::warning($response, 'create-continue-crec-api-called');
                        return $response;
                    }
                }
            }

            $docTypes = DocumentSubmissionType::find()->isDeleted(FALSE)
                            ->submissionType($s->submission_type_id)
                            ->submissionTypeRole(Role::RESEARCHER)->indexBy('id')
                            ->isApi()
                            ->orderBy([new \yii\db\Expression('document_submission_type.sort IS NULL ,document_submission_type.sort ASC')])->all();
            foreach ($docTypes as $type) {
                $doc = SubmissionDocument::find()->isDeleted(FALSE)->submission($s->id)->documents($type->document_id)->one();

                if (!isset($doc)) {
                    $doc = new SubmissionDocument();
                    $doc->document_id = $type->document_id;
                    $doc->project_id = $p->id;
                    $doc->submission_id = $s->id;
                    $doc->name = $type->document->name;
                    $doc->name_eng = $type->document->name_eng;
                    if (!$doc->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $doc->firstErrors)
                        ];
                        Yii::warning($response, 'create-continue-crec-api-called');
                        return $response;
                    }
                }
            }

            foreach ($bodyParams['ecSubmissionEvents'] as $paramsEv) {
                // Yii::warning(VarDumper::dumpAsString($paramsSd), 'create-continue-crec-submission-documents');
                $sd = SubmissionEvent::find()->isDeleted(false)
                        ->submission($s->id)
                        ->eventNo($paramsEv['event_no'])
                        ->one();
                // Yii::warning(VarDumper::dumpAsString($sd, 2, false), 'create-continue-crec-submission-documents');

                if (!isset($sd)) {
                    $sd = new SubmissionEvent();
                    $sd->submission_id = $s->id;
                    $sd->event_no = $paramsEv['event_no'];
                    if (!$sd->save()) {
                        $tran->rollBack();
                        Yii::$app->response->setStatusCode(HttpCode::UNPROCESSABLE_ENTITY);
                        $response = [
                            'status' => ExternalApi::STATUS_ERROR,
                            'params' => $bodyParams,
                            'message' => \implode(", ", $sd->firstErrors)
                        ];
                        Yii::warning($response, 'create-continue-crec-api-called');
                        return $response;
                    }
                }
            }
            $tran->commit();
            if ($newSubmission) {
                Alert::addNewSubmission($s);
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_NEW_SUBMISSION_CREC, $s->id);
            }
            EmailQueue::execSendMailCmd();

            Yii::$app->response->setStatusCode(HttpCode::OK);
            $response = [
                'status' => ExternalApi::STATUS_OK,
                'message' => 'ส่งข้อมูล Continue Submission CREC MOU เรียบร้อยแล้ว',
                'params' => $bodyParams,
                'data' => [
                    'project' => $p->attributes,
                    'submission' => $s->attributes,
                ]
            ];
            Yii::warning($response, 'create-continue-crec-api-called');
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
            Yii::warning($response, 'create-continue-crec-api-called');
            return $response;
        } finally {
            // $apiTran = new ApiTran();
            // $apiTran->kkf_ref_id = $model->kkfRefId;
            // $apiTran->api_name = $this->action->uniqueId;
            // $apiTran->headers = Json::encode($headers);
            // $apiTran->data = Json::encode($bodyParams);
            // $apiTran->status = $response['status'];
            // $apiTran->message = Json::encode($response);
            // $apiTran->trace = $stackTrace;
            // $apiTran->save();
            // $response['fds_ref_id'] = $apiTran->fds_ref_id;
            // echo "Finally Block";
        }
    }

}
