<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Submission;
use app\models\SubmissionType;
use kartik\datecontrol\DateControl;

DateControl::widget([
    'name' => 'kartik-date',
    'type' => DateControl::FORMAT_DATE,
]);

$currentRoles = Yii::$app->session->get('currentRole');
$revise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->all();
$hisCom = \app\models\SubmissionStatusHistory::find()->submission($submission->id)->committee()->one();

if ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED || $submission->status < Submission::STATUS_AGENDA_ADDED) {
    $status = Submission::CUSTOM_STATUS_MEETING_PENDING;
} else {
    $status = $submission->status;
}



$this->title = Yii::t('app', 'รายละเอียดโครงการวิจัย');
if ($currentRoles['role_id'] != \app\models\Role::COPRESIDENT && $currentRoles['role_id'] != \app\models\Role::PRESIDENT && $currentRoles['role_id'] != \app\models\Role::COMMITTEE) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'โครงการ'), 'url' => ['submission/index', 'status' => $status, 'typeGroup' => $submission->submissionType->submission_type_group_id, 'staff' => \Yii::$app->user->identity->id]];
}
$this->params['breadcrumbs'][] = $this->title;
if ($currentRoles['role_id'] == \app\models\Role::COPRESIDENT || $currentRoles['role_id'] == \app\models\Role::PRESIDENT || $currentRoles['role_id'] == \app\models\Role::COMMITTEE || $currentRoles['role_id'] == \app\models\Role::SECRETARY) {
    $researchers = Submission::find()->isresearch($currentRoles['person_id'], $submission->project_id)->all();
    foreach ($researchers as $researcher) {
        $re = $researcher->id;
    }
}
?>

<div class="site-about">

    <div class=" panel col-md-12">
        <div class="page-body">
            <div class="pull-right margin-top-20">
                <?php \yii\widgets\Pjax::begin(['id' => 'submission-status-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
                <span class="label <?= Submission::statusColors()[$submission->status] ?> label-lg"><?= yii::t('app', 'สถานะ : ') . Submission::getStatusLabels()[$submission->status]; ?></span>
                <?php \yii\widgets\Pjax::end(); ?>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="page-title pull-left font-size-20">
                        <?php
                        if (isset($submission->project->project_code)) {
                            echo $submission->project->project_code . ' : ';
                        }
                        ?>
                        <?= $submission->project->name_thai; ?>
                    </p>
                </div>
            </div>



            <div class="row">
                <div class="col-md-12">
                    <p class="page-title pull-left font-size-20">
                        <?= Yii::t('app', 'ประเภทโครงการ : ') . $submission->typeAndRef; ?>
                        <?php
                        if (!empty($submission->resolution)) {
                            if ($submission->resolution == Submission::RESOLUTION_Y) {
                                $rc = '<font class="blue-700">' . Submission::getResolutionLables()[$submission->resolution] . '</font>';
                            } else {
                                $rc = '<font class="red-700">' . Submission::getResolutionLables()[$submission->resolution] . '</font>';
                            }
                            echo Yii::t('app', ' <span class="font-weight-900" style="font-weight: bold;">  ผลการพิจารณา ECKKU : </span>') . $rc;
                        }
                        ?>
                        <?php
                        if (isset($submission->assess_type)) {
                            echo Yii::t('app', '<span class="font-weight-900" style="font-weight: bold;"> ประเภทการพิจารณา : </span>') . app\models\Submission::getAssessTypeLabel()[$submission->assess_type];
                        }
                        ?>
                    </p>

                </div>
            </div>

            <?= isset($submission->projectLeader) ? '<div class="row"><div class="col-md-12">' . $submission->projectLeader->person->getAlertDeviationProtocolHtml() . '</div></div>' : "" ?>
            <?php if (!empty($submission->crec_issue_req_detail)): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#crec-issue-req-detail"
                                aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดนำส่งเรื่องประเมิน คลิกเพื่ออ่าน') ?>
                        </button>
                        <div class="collapse alert alert-danger " id="crec-issue-req-detail">

                            <?php
                            //                        if (!empty($revise)) {
                            //                            foreach ($revise as $reviseCommittee) {
                            //                                echo $reviseCommittee->remark;
                            //                            }
                            //                        } else {
                            //                            //echo $submission->refSubmission->meetingAgenda->conclusion;
                            //                        }
                            echo $submission->crec_issue_req_detail;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($submission->refSubmission) && (($currentRoles['role_id'] == app\models\Role::STAFF) or $currentRoles['role_id'] == app\models\Role::ADMIN) && $submission->resolution <> Submission::RESOLUTION_Y): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise-committee"
                                aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะของกรรมการ คลิกเพื่ออ่าน') ?>
                        </button>
                        <div class="collapse alert alert-danger " id="revise-committee">

                            <?php
                            //                        if (!empty($revise)) {
                            //                            foreach ($revise as $reviseCommittee) {
                            //                                echo $reviseCommittee->remark;
                            //                            }
                            //                        } else {
                            //                            //echo $submission->refSubmission->meetingAgenda->conclusion;
                            //                        }
                            echo $submission->remark_assessed_staff;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($submission->refSubmission) && $submission->resolution <> Submission::RESOLUTION_Y): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-warning btn-block" data-toggle="collapse" data-target="#revise-resubmission"
                                aria-expanded="true" aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะในการแก้ไข คลิกเพื่ออ่าน') ?>
                        </button>
                        <div class="collapse alert alert-warning in" aria-expanded="true" id="revise-resubmission">

                            <?php
                            //                        if (!empty($revise)) {
                            //                            foreach ($revise as $reviseCommittee) {
                            //                                echo $reviseCommittee->remark;
                            //                            }
                            //                        } else {
                            //                            //echo $submission->refSubmission->meetingAgenda->conclusion;
                            //                        }
                            echo $submission->refSubmission->issue1;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (($submission->status == Submission::STATUS_SECRETARY_APPROVE_AGENDA) && !empty($submission->president_comment) && ($currentRoles['role_id'] == app\models\Role::STAFF || $currentRoles['role_id'] == app\models\Role::ADMIN)): ?>
                <div class="row"><div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise-staff"
                                aria-expanded="false" aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะการแก้ไขหนังสือแจ้งผลจากประธาน คลิกเพื่อดู') ?>
                        </button>
                        <div class="collapse alert alert-danger" id="revise-staff">

                            <?php
                            echo $submission->president_comment;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>  
            <?php if (($submission->status == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE || $submission->status == Submission::STATUS_DOC_REJECTED) && $submission->resolution <> Submission::RESOLUTION_Y): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise-staff"
                                aria-expanded="true" aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะการแก้ไขเพิ่มเติมเอกสารจากเจ้าหน้าที่ คลิกเพื่ออ่าน') ?>
                        </button>
                        <div class="collapse alert alert-danger in" aria-expanded="true" id="revise-staff">

                            <?php
                            echo $submission->remark_checkdoc_staff;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($submission->leader_comment) && ($submission->status == Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER) && ($currentRoles['role_id'] == app\models\Role::STAFF || ($currentRoles['role_id'] == \app\models\Role::COORDINATOR && ($submission->project_coordinator_id == \Yii::$app->user->id || $submission->project_coordinator_2nd_id == \Yii::$app->user->id || $submission->project_coordinator_3rd_id == \Yii::$app->user->id)))): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise-pm"
                                aria-expanded="false" aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะการแก้ไขเพิ่มเติมเอกสารจากหัวหน้าโครงการ คลิกเพื่ออ่าน') ?>
                        </button>
                        <div class="collapse alert alert-danger" id="revise-pm">

                            <?php
                            echo $submission->leader_comment;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (((($submission->status == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE) || ($submission->status == Submission::STATUS_SUBMITTED)) && (!empty($submission->remark_assessed_staff) or isset($submission->remark_assessed_staff))) && $submission->resolution <> Submission::RESOLUTION_Y && ($currentRoles['role_id'] != app\models\Role::STAFF || ($currentRoles['role_id'] != app\models\Role::ADMIN))): ?>
                <div class="row">
                    <div class="col-md-12 margin-top-10">
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise"
                                aria-expanded="false" aria-controls="exampleCollapseExample">
                                    <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะการแก้ไขเพิ่มเติมเอกสารหลังจากกรรมการประเมิน คลิกเพื่อดู') ?>
                        </button>
                        <div class="collapse alert alert-danger" id="revise">

                            <?php
                            echo $submission->remark_assessed_staff;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <!-- Example Tabs Line Left With JS -->

        <br>
        <?=
        $this->renderFile('@app/views/submission/_step.php', [
            'submission' => $submission,
        ]);
        ?>

        <div class="clearfix"></div>
        <?php \yii\widgets\Pjax::begin(['id' => 'submission-tap-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
        <div class=" example-wrap">
            <div class="nav-tabs-vertical">
                <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist" style="width:260px;">
                    <li class="active" role="presentation">
                        <a data-toggle="tab" href="#tab-3" aria-controls="tab-3" role="tab"><?= yii::t('app', 'เอกสารที่เกี่ยวข้อง') ?><span class="site-menu-arrow font-size-20 padding-left-50"></span></a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#tab-1" aria-controls="tab-1" role="tab"><?= yii::t('app', 'ข้อมูลทั่วไป') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                    </li>
                    <?php if (($currentRoles['role_id'] == app\models\Role::STAFF) or ($currentRoles['role_id'] == app\models\Role::ADMIN)): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-coi" aria-controls="tab-coi" role="tab"><?= yii::t('app', 'กำหนดสิทธิไม่ให้ดูงานวิจัย') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>
                    <?php if ((($currentRoles['role_id'] == app\models\Role::STAFF) or ($currentRoles['role_id'] == app\models\Role::ADMIN)) && ($submission->status == Submission::STATUS_CODE_GENERATED)): ?>
                        <?php if ($submission->isFromCrec()) { ?>
                            <li role="presentation">
                                <a data-toggle="tab" href="#tab-9" aria-controls="tab-9" role="tab"><?= yii::t('app', 'ข้อมูลประมาณวันที่ส่งผลประเมิน') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                            </li>
                        <?php } else { ?>
                            <li role="presentation">
                                <a data-toggle="tab" href="#tab-9" aria-controls="tab-9" role="tab"><?= yii::t('app', 'ข้อมูลประมาณวันที่เข้าประชุม') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                            </li>
                        <?php } ?>
                    <?php endif; ?>

                    <?php if ($currentRoles['role_id'] == app\models\Role::RESEARCHER || $currentRoles['role_id'] == app\models\Role::STAFF) { ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-coordinator" aria-controls="tab-coordinator" role="tab"><?= yii::t('app', 'ผู้ประสานงานโครงการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php } ?>
                    <li role="presentation">
                        <a data-toggle="tab" href="#tab-2" aria-controls="tab-2" role="tab"><?= yii::t('app', 'รายชื่อผู้ร่วมวิจัย') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                    </li>
                    <?php if (isset($submission->projectConsultant) || $currentRoles['role_id'] == app\models\Role::STAFF): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-consultant" aria-controls="tab-consultant" role="tab"><?= yii::t('app', 'รายชื่ออาจารย์ที่ปรึกษา') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR && $currentRoles['role_id'] != app\models\Role::COMMITTEE) && ($submission->status > Submission::STATUS_CODE_GENERATED) && $submission->submissionType->is_fullboard): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-4" aria-controls="tab-4" role="tab"><?= yii::t('app', 'เลือกเลขา') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (!isset($re) && (((($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || ($currentRoles['role_id'] == \app\models\Role::SECRETARY && $submission->secretary_person == \Yii::$app->user->identity->id) || $currentRoles['role_id'] == \app\models\Role::ADMIN))) && (($submission->status >= Submission::STATUS_SECRETARY_SELECTED))): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-5" aria-controls="tab-5" role="tab"><?= yii::t('app', 'เลือกกรรมการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (!isset($re) && ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED || isset($hisCom)) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR)): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-6" aria-controls="tab-6" role="tab"><?= yii::t('app', 'ผลการประเมินจากกรรมการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($ma) && !isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR) && ($submission->status >= Submission::STATUS_SECRETARY_APPROVE_AGENDA)): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-7" aria-controls="tab-7" role="tab"><?= yii::t('app', 'มติที่ประชุม') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>

                    <li role="presentation">
                        <a data-toggle="tab" href="#tab-8" aria-controls="tab-8" role="tab"><?= yii::t('app', 'ประวัติการดำเนินการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                    </li>

                    <li role="presentation">
                        <a data-toggle="tab" href="#tab-project-history" aria-controls="tab-project-history" role="tab"><?= yii::t('app', 'ประวัติการขอรับพิจารณาโครงการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#tab-project-documents" aria-controls="tab-project-documents" role="tab"><?= yii::t('app', 'ประวัติเอกสารโครงการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                    </li>
                    <?php if (count($submission->project->saeVolunteers) > 0): ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-project-volunteers" aria-controls="tab-project-volunteers" role="tab"><?= yii::t('app', 'ประวัติอาสาสมัคร') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php endif; ?>
                    <?php if (($submission->status >= Submission::STATUS_COMMITTEE_ASSESSED && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || (isset($submission->crec_resolution) && $submission->crec_resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE)) { ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-certificate" aria-controls="tab-certificate" role="tab"><?= yii::t('app', 'ข้อมูลการรับรองโครงการ') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php } ?>
                    <?php if ((isset($submission->resolution) || !empty($revise) || ( $submission->status >= Submission::STATUS_COMMITTEE_ASSESSED) || (isset($submission->crec_resolution) && $submission->crec_resolution == Submission::RESOLUTION_Y)) && ((((($submission->resolution == Submission::RESOLUTION_C || $submission->resolution == Submission::RESOLUTION_R || $submission->resolution == Submission::RESOLUTION_W || $submission->resolution == Submission::RESOLUTION_P || $submission->resolution == Submission::RESOLUTION_N || $submission->resolution == Submission::RESOLUTION_T || ($submission->resolution == '' && !isset($submission->crec_resolution))) && $submission->resolution != Submission::RESOLUTION_Y) && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ACKNOWLEDGE || ((($currentRoles['role_id'] == \app\models\Role::RESEARCHER && $submission->project->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRoles['role_id'] == \app\models\Role::COORDINATOR && ($submission->project_coordinator_id == \Yii::$app->user->id || $submission->project_coordinator_2nd_id == \Yii::$app->user->id || $submission->project_coordinator_3rd_id == \Yii::$app->user->id))))))) { ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-letter" aria-controls="tab-letter" role="tab"><?= yii::t('app', 'หนังสือแจ้งผล') ?><span class="site-menu-arrow font-size-20 center"></span></a>
                        </li>
                    <?php } ?>

                </ul>
                <div class="tab-content padding-vertical-15">
                    <?php if ($currentRoles['role_id'] == \app\models\Role::STAFF || $currentRoles['role_id'] == \app\models\Role::ADMIN || $currentRoles['role_id'] == \app\models\Role::COMMITTEE) { ?>
                        <div class="tab-pane active" id="tab-3" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-document/staff.php', [
                                'submission' => $submission,
                                //                                'searchModel' => $docsearchModel,
                                'dataProvider' => $docdataProvider
                            ]);
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="tab-pane active" id="tab-3" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-document/researcher.php', [
                                'submission' => $submission,
                                //                                'searchModel' => $docsearchModel,
                                'dataProvider' => $docdataProvider
                            ]);
                            ?>
                        </div>
                    <?php } ?>
                    <div class="tab-pane " id="tab-1" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/submission/_general.php', [
                            'submission' => $submission,
                        ]);
                        ?>
                    </div>
                    <?php if (($currentRoles['role_id'] == app\models\Role::STAFF) or ($currentRoles['role_id'] == app\models\Role::ADMIN)): ?>
                        <div class="tab-pane" id="tab-coi" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-coi-person/index.php', [
                                'submissionId' => $submission->id,
                                'searchModel' => $coisearchModel,
                                'dataProvider' => $coidataProvider
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($currentRoles['role_id'] == app\models\Role::RESEARCHER || $currentRoles['role_id'] == app\models\Role::STAFF): ?>
                        <div class="tab-pane" id="tab-coordinator" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission/coordinator.php', [
                                'action' => Url::to(['submission/coordinator', 'id' => $submission->id]),
                                'id' => $submission->id,
                                'model' => $submission,
                                'project' => $submission->project,
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (($submission->status >= Submission::STATUS_COMMITTEE_ASSESSED && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || (isset($submission->crec_resolution) && $submission->crec_resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE)) { ?>

                        <div class="tab-pane" id="tab-certificate" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission/certificate.php', [
                                'id' => $submission->id,
                                'model' => $submission,
                                'submission' => $submission,
                                'pjaxId' => 'cer',
                                'action' => Url::to(['submission/certificate', 'id' => $submission->id,])
                            ]);
                            ?>

                        </div>
                    <?php } ?>
                    <?php if ((($currentRoles['role_id'] == app\models\Role::STAFF) or ($currentRoles['role_id'] == app\models\Role::ADMIN)) && ($submission->status == Submission::STATUS_CODE_GENERATED)): ?>
                        <div class="tab-pane" id="tab-9" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission/meeting-plan.php', [
                                'id' => $submission->id,
                                'model' => $submission,
                                'submission' => $submission,
                                'action' => Url::to(['submission/meeting-plan', 'id' => $submission->id,])
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="tab-pane" id="tab-2" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/project-researcher/researcher.php', [
                            'submission' => $submission,
                            'searchModel' => $pResearchersearchModel,
                            'dataProvider' => $pResearcherdataProvider
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane" id="tab-consultant" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/project-consultant/consultant.php', [
                            'submission' => $submission,
                            'searchModel' => $pConsultantsearchModel,
                            'dataProvider' => $pConsultantdataProvider
                        ]);
                        ?>
                    </div>
                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR && $currentRoles['role_id'] != app\models\Role::COMMITTEE) && ($submission->status > Submission::STATUS_CODE_GENERATED) && $submission->submissionType->is_fullboard): ?>
                        <div class="tab-pane" id="tab-4" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission/set-secretary.php', [
                                'id' => $submission->id,
                                'model' => $submission,
                                'submission' => $submission,
                                'action' => Url::to(['submission/set-secretary', 'id' => $submission->id,])
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($re) && (((($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || ($currentRoles['role_id'] == \app\models\Role::SECRETARY && $submission->secretary_person == \Yii::$app->user->identity->id) || $currentRoles['role_id'] == \app\models\Role::ADMIN)) && $currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR && $currentRoles['role_id'] != app\models\Role::COMMITTEE && $currentRoles['role_id'] != app\models\Role::PRESIDENT) && (($submission->status >= Submission::STATUS_SECRETARY_SELECTED))): ?>
                        <div class="tab-pane" id="tab-5" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-committee/select-committee.php', [
                                'id' => $submission,
                                'submissionId' => $submission->id,
                                'comsearchModel' => $comsearchModel,
                                'comdataProvider' => $comdataProvider,
                                'PsearchModel' => $PsearchModel,
                                'PdataProvider' => $PdataProvider,
                                'submission' => $submission,
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($re) && ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED || isset($hisCom)) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR)): ?>
                        <div class="tab-pane" id="tab-6" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-committee/revise.php', [
                                'submission' => $submission,
                                'searchModel' => $committeesearchModel,
                                'dataProvider' => $committeedataProvider,
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($ma) && !isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER && $currentRoles['role_id'] != app\models\Role::COORDINATOR) && ($submission->status >= Submission::STATUS_SECRETARY_APPROVE_AGENDA)): ?>

                        <div class="tab-pane" id="tab-7" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/meeting-agenda/submission-agenda-info.php', [
                                'ma' => $ma,
                                'answers' => $answers,
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="tab-pane" id="tab-8" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/submission-status-history/index.php', [
                            'id' => $submission,
                            'submissionId' => $submission->id,
                            'searchModel' => $hissearchModel,
                            'dataProvider' => $hisdataProvider,
                        ]);
                        ?> </div>
                    <div class="tab-pane" id="tab-project-history" role="tabpanel">
                        <?php
                        $subSearchModel = new \app\models\SubmissionSearch();
                        $subSearchModel->deleted = 0;
                        $subSearchModel->project_id = $submission->project_id;
                        if ($currentRoles['role_id'] == \app\models\Role::RESEARCHER && $submission->projectLeader->person_id != Yii::$app->user->identity->person->id) {
                            $subSearchModel->researcherPersonId = Yii::$app->user->identity->person->id;
                        }
                        $subDataProvider = $subSearchModel->search([]);
                        $subDataProvider->sort->defaultOrder = [
                            'id' => SORT_DESC,
                        ];
                        echo $this->renderFile('@app/views/project/submission-history.php', [
                            //                            'id' => $submission,
                            //                            'submissionId' => $submission->id,
                            'searchModel' => $subSearchModel,
                            'dataProvider' => $subDataProvider,
                        ]);
                        ?>

                    </div>
                    <div class="tab-pane" id="tab-project-documents" role="tabpanel">
                        <?php
                        echo $this->renderFile('@app/views/project/document-history.php', [
                            'project' => $submission->project,
                        ]);
                        ?>

                    </div>
                    <?php if (count($submission->project->saeVolunteers) > 0): ?>
                        <div class="tab-pane" id="tab-project-volunteers" role="tabpanel">
                            <?php
                            echo $this->renderFile('@app/views/project/volunteer-history.php', [
                                'project' => $submission->project,
                            ]);
                            ?>

                        </div>
                    <?php endif; ?>
                    <div class="tab-pane" id="tab-letter" role="tabpanel">
                        <?php if ((isset($submission->resolution) || !empty($revise) || ($submission->status >= Submission::STATUS_AGENDA_ADDED) || (isset($submission->crec_resolution) && $submission->crec_resolution == Submission::RESOLUTION_Y)) && ((((($submission->resolution == Submission::RESOLUTION_C || $submission->resolution == Submission::RESOLUTION_R || $submission->resolution == Submission::RESOLUTION_W || $submission->resolution == Submission::RESOLUTION_P || $submission->resolution == Submission::RESOLUTION_N || $submission->resolution == Submission::RESOLUTION_T || ($submission->resolution == '' && !isset($submission->crec_resolution))) && $submission->resolution != Submission::RESOLUTION_Y) && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || ($submission->submissionType->resolution_label == app\models\SubmissionType::RES_ACKNOWLEDGE) || ((($currentRoles['role_id'] == \app\models\Role::RESEARCHER && $submission->project->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRoles['role_id'] == \app\models\Role::COORDINATOR && ($submission->project_coordinator_id == \Yii::$app->user->id || $submission->project_coordinator_2nd_id == \Yii::$app->user->id || $submission->project_coordinator_3rd_id == \Yii::$app->user->id))))))): ?>
                            <div class="tab-pane" id="tab-letter" role="tabpanel">
                                <?=
                                $this->renderFile('@app/views/submission/certificate.php', [
                                    'id' => $submission->id,
                                    'model' => $submission,
                                    'submission' => $submission,
                                    'pjaxId' => 'cer',
                                    'action' => Url::to(['submission/certificate', 'id' => $submission->id,])
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>
                <div class="pull-right">
                    <?php \yii\widgets\Pjax::begin(['id' => 'submission-buttom-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
                    <?php if (($submission->status == Submission::STATUS_COMMITTEE_SELECTED) && (($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || ($currentRoles['role_id'] == \app\models\Role::SECRETARY && $submission->secretary_person == \Yii::$app->user->identity->id)) && $submission->getIsAllComitteeAcknowledged()) { ?>
                        <?=
                        Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'กรรมการตอบรับพิจารณาโครงการเรียบร้อย'), [
                            'class' => 'btn btn-icon btn-success',
                            'role' => 'modal-remote',
                            'title' => Yii::t('app', 'กรรมการตอบรับพิจารณาโครงการเรียบร้อย'),
                            'data-url' => \yii\helpers\Url::to(['submission/staff-accept', 'id' => $submission->id]),
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันกรรมการตอบรับพิจารณาโครงการ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการส่งกรรมการตอบรับพิจารณาโครงการเรียบร้อยใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                        ?>
                    <?php } ?>

                    <?php if (($submission->status == Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) && ($currentRoles['role_id'] == \app\models\Role::RESEARCHER) && $submission->projectLeader->person_id == $currentRoles['person_id']) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งผลการตรวจสอบ/ยืนยัน'), ['submission/pm-accept', 'id' => $submission->id], [
                            'role' => 'modal-remote',
                            'title' => 'ส่งผลการตรวจสอบ/ยืนยัน',
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'
                        ]);
                        ?>
                    <?php } ?>

                    <?php if (($submission->status == Submission::STATUS_SUBMITTED) && ($currentRoles['role_id'] == \app\models\Role::STAFF)) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งผลการตรวจเอกสาร'), ['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_CHECKDOC], [
                            'role' => 'modal-remote',
                            'title' => 'ส่งผลการตรวจเอกสาร',
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'
                        ])
                        ?>
                    <?php } ?>

                    <?php if (($submission->status == Submission::STATUS_COMMITTEE_ACCEPTED) && ($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) && $submission->getIsAllComitteeReturn()) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งผลการประเมินของกรรมการ'), ['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_ASSESSEDCOMMITTEE], [
                            'role' => 'modal-remote',
                            'title' => 'ส่งผลการประเมินของกรรมการ',
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'
                        ])
                        ?>
                    <?php } ?>

                    <?php if (($submission->status >= Submission::STATUS_DOC_REJECTED and $submission->status < Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) && (($currentRoles['role_id'] == \app\models\Role::RESEARCHER && $submission->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRoles['role_id'] == \app\models\Role::COORDINATOR && ($submission->project_coordinator_id == \Yii::$app->user->id || $submission->project_coordinator_2nd_id == \Yii::$app->user->id || $submission->project_coordinator_3rd_id == \Yii::$app->user->id)))) {
                        ?>
                        <?=
                        Html::button('<i class="icon md-floppy"></i> ยืนยันการส่งแก้ไขเอกสาร', [
                            'class' => 'btn btn-icon btn-success',
                            'role' => 'modal-remote',
                            'title' => Yii::t('app', 'ส่งเอกสารแก้ไข'),
                            'data-url' => \yii\helpers\Url::to(['submission/send-edit', 'id' => $submission->id]),
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งเอกสารแก้ไข'),
                            'data-confirm-message' => Yii::t('app', 'โปรดตรวจสอบเอกสารที่แก้ไขและแนบไฟล์ที่แก้ไขให้ถูกต้อง หากถูกต้องครบถ้วนตามที่ต้องการแล้ว กดปุ่ม "ใช่" เพื่อเป็นการยืนยันการส่งแก้ไขเอกสาร'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                        ?>
                    <?php } ?>
                    <?php if (($submission->status == Submission::STATUS_SECRETARY_SELECT_TYPE) && (($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || ($currentRoles['role_id'] == \app\models\Role::SECRETARY && $submission->secretary_person == \Yii::$app->user->identity->id) )) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'เลือกประเภทโครงการ'), ['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_ASSESSTYPE], ['role' => 'modal-remote', 'title' => 'เลือกประเภทโครงการ',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'])
                        ?>
                    <?php } ?>


                    <?php if (($submission->status == Submission::STATUS_DOC_APPROVED) && ($currentRoles['role_id'] == \app\models\Role::STAFF)) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'ออกเลขโครงการ'), ['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_GENERATECODE], [
                            'role' => 'modal-remote',
                            'title' => 'ออกเลขโครงการ',
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'
                        ])
                        ?>
                    <?php } ?>

                    <?php if ((($submission->status == Submission::STATUS_COMMITTEE_ACCEPTED && (!$submission->isFromCrec())) || ($submission->status == Submission::STATUS_COMMITTEE_ASSESSED)) && ($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id)) { ?>
                        <?=
                        Html::a('<i class="icon md-floppy"></i> ' . yii::t('app', 'บรรจุวาระการประชุม'), ['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_SETAGENDA, 'panelId' => $submission->project->panel_id, 'isNow' => TRUE], [
                            'role' => 'modal-remote',
                            'title' => 'บรรจุวาระการประชุม',
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'class' => 'btn btn-icon btn-success',
                            'data-pjax' => FALSE,
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip'
                        ])
                        ?>
                    <?php } ?>
                    <?php if (($submission->status == Submission::STATUS_SECRETARY_APPROVE_AGENDA) && (($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || ($currentRoles['role_id'] == \app\models\Role::ADMIN))) { ?>
                        <?php
                        if ($submission->submissionType->resolution_label == SubmissionType::RES_ACKNOWLEDGE || ($submission->submissionType->resolution_label == SubmissionType::RES_ENDORSE && $submission->resolution != Submission::RESOLUTION_Y) || ($submission->submissionType->resolution_label === SubmissionType::RES_ENDORSE && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT)) {
                            ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'แจ้งการ Upload หนังสือแจ้งผล'), [
                                'class' => 'btn btn-icon btn-success',
                                'role' => 'modal-remote',
                                // 'title' => Yii::t('app', 'แจ้งการ Upload เอกสาร'),
                                'data-url' => \yii\helpers\Url::to(['submission/upload-result', 'id' => $submission->id]),
                                'data-confirm' => false,
                                'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการแจ้งการ Upload เอกสาร'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการยืนยันแจ้งการ Upload เอกสารใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                            ]);
                            ?>
                            <?php
                        } elseif ($submission->submissionType->resolution_label == SubmissionType::RES_ENDORSE && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) {
                            ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'), ['class' => 'btn btn-icon btn-success', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-url' => \yii\helpers\Url::to(['submission/president-result', 'id' => $submission->id]),
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการส่งการแจ้งผลให้ประธานตรวจสอบใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
                            ?>
                        <?php } ?>
                    <?php } ?> 

                    <?php if ((($submission->status > Submission::STATUS_AGENDA_ADDED && $submission->status < Submission::STATUS_SECRETARY_APPROVE_AGENDA ) && isset($submission->resolution)) && (($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || $currentRoles['role_id'] == \app\models\Role::ADMIN )) { ?>

                        <?php
                        if ($submission->resolution != Submission::RESOLUTION_Y) {
                            ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'แจ้งการ Upload หนังสือแจ้งผล'), [
                                'class' => 'btn btn-icon btn-success',
                                'role' => 'modal-remote',
                                // 'title' => Yii::t('app', 'แจ้งการ Upload เอกสาร'),
                                'data-url' => \yii\helpers\Url::to(['submission/upload-result', 'id' => $submission->id]),
                                'data-confirm' => false,
                                'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการแจ้งการ Upload เอกสาร'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการยืนยันแจ้งการ Upload เอกสารใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                            ]);
                            ?>
                            <?php
                        } else {
                            ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'), ['class' => 'btn btn-icon btn-success', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-url' => \yii\helpers\Url::to(['submission/president-result', 'id' => $submission->id]),
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการส่งการแจ้งผลให้ประธานตรวจสอบใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
                            ?>
                        <?php } ?>

                    <?php } ?>

                    <?php if (($submission->status == Submission::STATUS_AGENDA_ADDED && isset($submission->resolution) && $submission->assess_type != Submission::TYPE_FULLBOARD) && (($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id) || $currentRoles['role_id'] == \app\models\Role::ADMIN )) { ?>
                        <?php
                        if ($submission->resolution != Submission::RESOLUTION_Y) {
                            ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'แจ้งการ Upload หนังสือแจ้งผลก่อนเข้าที่ประชุม'), [
                                'class' => 'btn btn-icon btn-success',
                                'role' => 'modal-remote',
                                // 'title' => Yii::t('app', 'แจ้งการ Upload เอกสาร'),
                                'data-url' => \yii\helpers\Url::to(['submission/send-result', 'id' => $submission->id]),
                                'data-confirm' => false,
                                'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการแจ้งการ Upload เอกสาร'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการยืนยันแจ้งการ Upload เอกสารใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                            ]);
                            ?>
                        <?php } else { ?>
                            <?=
                            Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'), ['class' => 'btn btn-icon btn-success', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-url' => \yii\helpers\Url::to(['submission/president-result', 'id' => $submission->id]),
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการแจ้งผลให้ประธานตรวจสอบ'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการส่งการแจ้งผลให้ประธานตรวจสอบใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
                            ?>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($currentRoles['role_id'] == \app\models\Role::ADMIN && $submission->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT && !$submission->send_to_crec && !$submission->is_submit_by_api && $submission->project->hasCrecNumber() && $submission->resolution == Submission::RESOLUTION_Y): ?>
                        <?=
                        Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'ส่งข้อมูลไปยัง CREC'), [
                            'class' => 'btn btn-icon btn-success',
                            'role' => 'modal-remote',
                            'title' => Yii::t('app', 'ส่งข้อมูลไปยัง CREC'),
                            'data-url' => \yii\helpers\Url::to(['submission/create-continue-crec', 'id' => $submission->id]),
                            'data-confirm' => false,
                            'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งข้อมูลไปยัง CREC'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการส่งข้อมูลไปยัง CREC ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                        ?>
                    <?php endif; ?>

                    <?php if (($submission->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) && $submission->acknowledged_crec_result == Submission::CREC_WAITING_RESULT_ACKNOWLEDGE && ($currentRoles['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id)): ?>
                        <?=
                        Html::button('<i class="icon md-floppy"></i> ' . yii::t('app', 'รับทราบผลประเมินจาก CREC'), [
                            'class' => 'btn btn-icon btn-success',
                            'role' => 'modal-remote',
                            // 'title' => Yii::t('app', 'แจ้งการ Upload เอกสาร'),
                            'data-url' => \yii\helpers\Url::to(['submission/acknowledge-crec-result', 'id' => $submission->id]),
                        ]);
                        ?>
                    <?php endif; ?>
                    <?php \yii\widgets\Pjax::end(); ?>

                </div>
            </div>
            <?php \yii\widgets\Pjax::end(); ?>
        </div>
    </div>
    <?php
    $this->registerJsFile(
            '@web/js/Sortable.min.js',
            ['depends' => [\yii\web\JqueryAsset::class]]
    );

    $urlD = Url::to(['submission-document/update-position']);
    $urlR = Url::to(['project-researcher/update-position']);

    if ($submission->status >= Submission::STATUS_PENDING_SUBMISSION && $submission->status < Submission::STATUS_AGENDA_ADDED) {
        $js = <<<js
    $(document).on('pjax:complete', '#submission-status-pjax', function(){
        $.pjax.reload({container: '#submission-tap-pjax'});
    });           
                        $(document).on('pjax:complete', '#submission-status-pjax', function(){
        $.pjax.reload({container: '#submission-buttom-pjax'});
    });
js;
        $this->registerJs($js);
    }
    $confirmMsg = Yii::t('app', 'ต้องการดาวน์โหลดเอกสารที่เลือกใช่หรือไม่');
    $errorMsg = Yii::t('app', 'กรุณาเลือกเอกสารที่ต้องการดาวน์โหลด');
    $js = <<<js
    $('#ajaxCrudModal').on('click', '.btn-save-researcher', function() {
        $(this).prop('disabled', true);
        var data;

        // Test if browser supports FormData which handles uploads
        if (window.FormData) {
            data = new FormData($('#form-project-researcher')[0]);
        } else {
            // Fallback to serialize
            data = $('#form-project-researcher').serializeArray();
        }
        setTimeout(function() {
            modal.doRemote(
                $('#form-project-researcher').attr('action'),
                $('#form-project-researcher').hasAttr('method') ? $('#form-project-researcher').attr('method') : 'GET',
                data
            );
        }, 200);
        
    });
        $('#ajaxCrudModal').on('click', '.btn-save-consultant', function() {
        $(this).prop('disabled', true);
        var data;

        // Test if browser supports FormData which handles uploads
        if (window.FormData) {
            data = new FormData($('#form-project-consultant')[0]);
        } else {
            // Fallback to serialize
            data = $('#form-project-consultant').serializeArray();
        }
        setTimeout(function() {
            modal.doRemote(
                $('#form-project-consultant').attr('action'),
                $('#form-project-consultant').hasAttr('method') ? $('#form-project-consultant').attr('method') : 'GET',
                data
            );
        }, 200);
        
    });
    $('body').on('click', '.btn-download-merge', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var keys = [];
        if ($('#crud-datatable-submission-document').length > 0) {
            keys = keys.concat($('#crud-datatable-submission-document').yiiGridView('getSelectedRows'));
        } else {
            $('.grid-view').each(function() {
                keys = keys.concat($(this).yiiGridView('getSelectedRows'));
            });
        }
        // var keys = $('#crud-datatable-submission-document').yiiGridView('getSelectedRows');
        if (keys.length == 0) {
            dlgError.alert('{$errorMsg}');
            return false;
        }
        dlgPrimary.confirm('{$confirmMsg}', function (result) {
            if (result) { // ok button was pressed
                console.log(keys);
                window.open(url + '&selections=' + JSON.stringify(keys), '_blank');
            } else { // confirmation was cancelled
                console.log('cancel');
            }
        });
        
    });
            $('body').on('click', '.btn-check-documents', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-href');
        var keys = [];
        if ($('#crud-datatable-submission-document').length > 0) {
            keys = keys.concat($('#crud-datatable-submission-document').yiiGridView('getSelectedRows'));
        } else {
            $('.grid-view').each(function() {
                keys = keys.concat($(this).yiiGridView('getSelectedRows'));
            });
        }
        if (keys.length == 0) {
            dlgError.alert('{$errorMsg}');
            return false;
        }
        $(this).attr('href', url + '&ids=' + keys.join(','));
        console.log(keys);
        
    });
js;
    $this->registerJs($js);


    $this->registerJs(<<<JS

function initSortable() {

    let el = document.querySelector('#sortable-table-document tbody');

    if (!el) {
        return;
    }

    new Sortable(el, {

        animation: 150,

        onEnd: function () {

            let rows = [];

            $('#sortable-table-document tbody tr').each(function () {
                rows.push($(this).data('id'));
            });

            $.ajax({
                url: '{$urlD}',
                type: 'POST',
                data: {
                    rows: rows
                },
                success: function(res) {

                    $.pjax.reload({
                        container: '#crud-datatable-submission-document-pjax',
                        timeout: false
                    });

                }
            });

        }

    });

}

initSortable();

$(document).on('pjax:end', function() {
    initSortable();
});

JS);

    $this->registerJs(<<<JS

function initSortableResearcher() {

    let el = document.querySelector('#sortable-table-researcher tbody');

    if (!el) {
        return;
    }

    new Sortable(el, {

        animation: 150,

        onEnd: function () {

            let rows = [];

            $('#sortable-table-researcher tbody tr').each(function () {
                rows.push($(this).data('id'));
            });

            $.ajax({
                url: '{$urlR}',
                type: 'POST',
                data: {
                    rows: rows
                },
                success: function(res) {

                    $.pjax.reload({
                        container: '#crud-datatable-project-researcher-pjax',
                        timeout: false
                    });

                }
            });

        }

    });

}

initSortableResearcher();

$(document).on('pjax:end', function() {
    initSortableResearcher();
});

JS);

    