<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Submission;

$currentRoles = Yii::$app->session->get('currentRole');
$revise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->all();

$this->title = Yii::t('app', 'รายละเอียดโครงการวิจัย');
if ($currentRoles['role_id'] != \app\models\Role::COPRESIDENT && $currentRoles['role_id'] != \app\models\Role::PRESIDENT && $currentRoles['role_id'] != \app\models\Role::COMMITTEE) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'โครงการ'), 'url' => ['submission/index', 'status' => $submission->status, 'typeGroup' => $submission->submissionType->submission_type_group_id]];
}
$this->params['breadcrumbs'][] = $this->title;
if ($currentRoles['role_id'] == \app\models\Role::COPRESIDENT || $currentRoles['role_id'] == \app\models\Role::PRESIDENT || $currentRoles['role_id'] == \app\models\Role::COMMITTEE) {
    $researchers = Submission::find()->isresearch($currentRoles['person_id'], $submission->project_id)->all();
    foreach ($researchers as $researcher) {
        $re = $researcher->id;
    }
}
?>

<div class="site-about">

    <div class=" panel col-md-12">
        <div class="page-header">
            <div class="pull-right">
                <?php \yii\widgets\Pjax::begin(['id' => 'submission-status-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
                <span class="label <?= Submission::statusColors()[$submission->status] ?> label-lg"><?= yii::t('app', 'สถานะ : ') . Submission::getStatusLabels()[$submission->status]; ?></span>
                <?php \yii\widgets\Pjax::end(); ?>

            </div>
            <div class="col-md-12"><p class="page-title pull-left font-size-20"><?php
                    if (isset($submission->project->project_code)) {
                        echo $submission->project->project_code . ' : ';
                    }
                    ?><?= $submission->project->name_thai; ?>
                </p></div>

            <div class="col-md-12"><p class="page-title pull-left font-size-20"><?= Yii::t('app', 'ประเภทโครงการ : ') . $submission->typeAndRef; ?></p></div>
            <?php if (isset($submission->refSubmission) && ($currentRoles['role_id'] == app\models\Role::STAFF) or $currentRoles['role_id'] == app\models\Role::ADMIN): ?>
                <div class="col-md-12 margin-top-10">
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise"
                            aria-expanded="false" aria-controls="exampleCollapseExample">
                                <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะของกรรมการ') ?>
                    </button>
                    <div class="collapse alert alert-danger" id="revise">

                        <?php
                        if (!empty($revise)) {
                            foreach ($revise as $reviseCommittee) {
                                echo $reviseCommittee->remark;
                            }
                        } else {
                            //echo $submission->refSubmission->meetingAgenda->conclusion;
                        }
                        ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ($submission->status == Submission::STATUS_DOC_REJECTED): ?>
                <div class="col-md-12 margin-top-10">
                    <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#revise"
                            aria-expanded="false" aria-controls="exampleCollapseExample">
                                <?= Yii::t('app', 'รายละเอียดข้อเสนอแนะการแก้ไขเพิ่มเติมเอกสารจากเจ้าหน้าที่ คลิกเพื่อดู') ?>
                    </button>
                    <div class="collapse alert alert-danger" id="revise">

                        <?php
                        echo $submission->remark_checkdoc_staff;
                        ?>

                    </div>
                </div>
            <?php endif; ?>
            <?php if ($submission->status == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE): ?>
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
            <?php endif; ?>

        </div>
        <!-- Example Tabs Line Left With JS -->

        <div class="clearfix"></div>
        <?php \yii\widgets\Pjax::begin(['id' => 'submission-tap-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
        <div class=" example-wrap">
            <div class="nav-tabs-vertical">

                <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist">
                    <li class="active" role="presentation">
                        <a data-toggle="tab" href="#tab-1" aria-controls="tab-1" role="tab"><?= yii::t('app', 'ข้อมูลทั่วไป') ?></a>
                    </li>
                    <?php if (($currentRoles['role_id'] == app\models\Role::STAFF) or ( $currentRoles['role_id'] == app\models\Role::ADMIN)): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-coi" aria-controls="tab-coi" role="tab"><?= yii::t('app', 'กำหนดสิทธิไม่ให้ดูงานวิจัย') ?></a></li>
                    <?php endif; ?>
                    <?php if ((($currentRoles['role_id'] == app\models\Role::STAFF) or ( $currentRoles['role_id'] == app\models\Role::ADMIN)) && ($submission->status > Submission::STATUS_DOC_APPROVED)): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-9" aria-controls="tab-9" role="tab"><?= yii::t('app', 'ข้อมูลประมาณวันที่เข้าประชุม') ?></a></li>
                    <?php endif; ?>
                    <?php if ($currentRoles['role_id'] == app\models\Role::STAFF and $submission->status >= Submission::STATUS_MEETING_DONE && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) { ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-certificate" aria-controls="tab-1" role="tab"><?= yii::t('app', 'ข้อมูลการรับรองโครงการ') ?></a>
                        </li>
                    <?php } ?>

                    <?php if ($currentRoles['role_id'] == app\models\Role::RESEARCHER) { ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tab-coordinator" aria-controls="tab-coordinator" role="tab"><?= yii::t('app', 'ผู้ประสานงานโครงการ') ?></a>
                        </li>
                    <?php } ?>                     

                    <li role="presentation"><a data-toggle="tab" href="#tab-2" aria-controls="tab-2" role="tab"><?= yii::t('app', 'รายชื่อผู้ร่วมวิจัย') ?></a></li>
                    <?php if (isset($submission->projectConsultant)): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-consultant" aria-controls="tab-consultant" role="tab"><?= yii::t('app', 'รายชื่ออาจารย์ที่ปรึกษา') ?></a></li>
                    <?php endif; ?>
                    <li role="presentation"><a data-toggle="tab" href="#tab-3" aria-controls="tab-3" role="tab"><?= yii::t('app', 'เอกสารที่เกี่ยวข้อง') ?></a></li>
                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER) && ($submission->status > Submission::STATUS_CODE_GENERATED) && $submission->submissionType->is_fullboard): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-4" aria-controls="tab-4" role="tab"><?= yii::t('app', 'เลือกเลขา') ?></a></li>
                    <?php endif; ?>
                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER) && ( ($submission->status >= Submission::STATUS_SECRETARY_SELECTED))): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-5" aria-controls="tab-5" role="tab"><?= yii::t('app', 'เลือกกรรมการ') ?></a></li>
                    <?php endif; ?>
                    <?php if (!isset($re) && ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED) && $currentRoles['role_id'] != app\models\Role::RESEARCHER): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-6" aria-controls="tab-6" role="tab"><?= yii::t('app', 'ผลการประเมินจากกรรมการ') ?></a></li>
                    <?php endif; ?>
                    <?php if (!isset($re) && ($currentRoles['role_id'] == app\models\Role::COPRESIDENT or $currentRoles['role_id'] == app\models\Role::PRESIDENT) && ($submission->status == Submission::STATUS_MEETING_DONE)): ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-7" aria-controls="tab-7" role="tab"><?= yii::t('app', 'มติที่ประชุม') ?></a></li>
                    <?php endif; ?>
                    <li role="presentation"><a data-toggle="tab" href="#tab-8" aria-controls="tab-8" role="tab"><?= yii::t('app', 'ประวัติการดำเนินการ') ?></a></li>
                    <li role="presentation"><a data-toggle="tab" href="#tab-project-history" aria-controls="tab-project-history" role="tab"><?= yii::t('app', 'ประวัติการขอรับพิจารณาโครงการ') ?></a></li>
                    <?php if (isset($submission->resolution) or ! empty($revise) || ($submission->status > Submission::STATUS_AGENDA_ADDED)) { ?>
                        <li role="presentation"><a data-toggle="tab" href="#tab-letter" aria-controls="tab-letter" role="tab"><?= yii::t('app', 'ออกหนังสือแจ้งผล') ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content padding-vertical-15">
                    <div class="tab-pane active" id="tab-1" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/submission/_general.php', [
                            'submission' => $submission,
                        ]);
                        ?>
                    </div>
                    <?php if (($currentRoles['role_id'] == app\models\Role::STAFF)): ?>
                        <div class="tab-pane" id="tab-coi" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-coi-person/index-show.php', [
                                'submissionId' => $submission->id,
                                'searchModel' => $coisearchModel,
                                'dataProvider' => $coidataProvider
                            ]);
                            ?>
                        </div> 
                    <?php endif; ?>
                    <div class="tab-pane" id="tab-coordinator" role="tabpanel">
                        <?=
                        $this->renderFile('@app/views/submission/coordinator.php', [
                            'id' => $submission->id,
                            'model' => $submission,
                            'action' => Url::to(['submission/coordinator', 'id' => $submission->id,])
                        ]);
                        ?>
                    </div> 
                    <?php if ($currentRoles['role_id'] == app\models\Role::STAFF and $submission->status >= Submission::STATUS_MEETING_DONE && $submission->resolution == Submission::RESOLUTION_Y && $submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) { ?>
                        <div class="tab-pane" id="tab-certificate" role="tabpanel">
                            <?=
                            $this->render('update', [
                                'id' => $submission->id,
                                'mode' => Submission::MODE_CERTIFICATE,
                                'model' => $submission,
                                'project' => $project,
                                'action' => Url::to(['submission/update', 'id' => $submission->id, 'mode' => Submission::MODE_CERTIFICATE])
                            ]);
                            ?>
                        </div> 
                    <?php } ?>
                    <?php if (($currentRoles['role_id'] != app\models\Role::RESEARCHER) && ($submission->status > Submission::STATUS_DOC_APPROVED)): ?>
                        <div class="tab-pane" id="tab-9" role="tabpanel">
                            <?=
                            $this->render('update', [
                                'id' => $submission->id,
                                'mode' => Submission::MODE_MEETINGPLAN,
                                'model' => $submission,
                                'project' => $project
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
                    <?php if (isset($submission->projectConsultant)): ?>
                        <div class="tab-pane" id="tab-consultant" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/project-consultant/consultant.php', [
                                'submission' => $submission,
                                'searchModel' => $pConsultantsearchModel,
                                'dataProvider' => $pConsultantdataProvider
                            ]);
                            ?>
                        </div> 
                    <?php endif; ?>
                    <?php if ($currentRoles['role_id'] == \app\models\Role::STAFF or $currentRoles['role_id'] == \app\models\Role::ADMIN) { ?>
                        <div class="tab-pane" id="tab-3" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-document/staff.php', [
                                'submission' => $submission,
                                'searchModel' => $docsearchModel,
                                'dataProvider' => $docdataProvider
                            ]);
                            ?>
                        </div> 
                    <?php } else { ?>
                        <div class="tab-pane" id="tab-3" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-document/researcher.php', [
                                'submission' => $submission,
                                'searchModel' => $docsearchModel,
                                'dataProvider' => $docdataProvider
                            ]);
                            ?>
                        </div> 
                    <?php } ?>

                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER) && ($submission->status >= Submission::STATUS_CODE_GENERATED) && $submission->submissionType->is_fullboard): ?>
                        <div class="tab-pane" id="tab-4" role="tabpanel">
                            <?=
                            $this->render('update', [
                                'id' => $submission->id,
                                'mode' => Submission::MODE_SETSECRETARY,
                                'model' => $submission,
                                'project' => $project
                            ]);
                            ?>
                        </div> 
                    <?php endif; ?>
                    <?php if (!isset($re) && ($currentRoles['role_id'] != app\models\Role::RESEARCHER) && ($submission->status >= Submission::STATUS_SECRETARY_SELECTED)): ?>

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
                    <?php if (!isset($re) && ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED) && $currentRoles['role_id'] != app\models\Role::RESEARCHER): ?>
                        <div class="tab-pane" id="tab-6" role="tabpanel">
                            <?=
                            $this->renderFile('@app/views/submission-committee/index.php', [
                                'submission' => $submission,
                                'searchModel' => $committeesearchModel,
                                'dataProvider' => $committeedataProvider,
                            ]);
                            ?>                        </div>   
                        <?php endif; ?>
                    <?php if (!isset($re) && ($submission->status == Submission::STATUS_MEETING_DONE)): ?>

                        <div class="tab-pane" id="tab-7" role="tabpanel">
                            มติที่ประชุม
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
                        ?>                    </div>
                    <div class="tab-pane" id="tab-project-history" role="tabpanel">
                        <?php
                        $subSearchModel = new \app\models\SubmissionSearch();
                        $subSearchModel->deleted = 0;
                        $subSearchModel->project_id = $submission->project_id;
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
                    <?php if (isset($submission->resolution) or ! empty($revise)) { ?>
                        <div class="tab-pane" id="tab-letter" role="tabpanel">
                            <?php
//                            $ma = $submission->meetingAgenda;
//                            $ardSearch = new \app\models\AgendaResultDocumentSearch();
//                            if (isset($submission->ref_submission_id) && !empty($submission->resolution)) {
//                                $ardSearch->agenda_id = $submission->refSubmission->meetingAgenda->agenda_id;
//                            } else {
//                                $ardSearch->agenda_id = isset($ma) ? $ma->agenda_id : NULL;
//                            }
//                            $ardSearch->deleted = 0;
//                            $ardSearch->resolution = $submission->resolution;
//                            $lastRevise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
////                              
//                            if (isset($lastRevise)) {
////                                  \yii\helpers\VarDumper::dump($revise);
//                                $ardSearch->committeeResolution = $lastRevise->resolution;
//                            }
//                            $ardProvider = $ardSearch->search([]);
                            $ardProvider = new \yii\data\ArrayDataProvider([
                                'allModels' => $submission->getResultDocuments()
                            ]);
                            echo $this->renderFile('@app/views/submission/letter-result.php', [
                                'submission' => $submission,
                                'pjaxId' => 'index',
                                'dataProvider' => $ardProvider,
                            ]);
                            ?>                    
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>

</div>
<?php
    if ($submission->status >= Submission::STATUS_SUBMITTED && $submission->status < Submission::STATUS_SECRETARY_SELECTED) {
        $js = <<<js
    $(document).on('pjax:complete', '#submission-status-pjax', function(){
        $.pjax.reload({container: '#submission-tap-pjax'});
    });            
js;
        $this->registerJs($js);
    }
    if ($submission->status >= Submission::STATUS_PENDING_SUBMISSION && $submission->status < Submission::STATUS_AGENDA_ADDED) {
        $js = <<<js
                $(document).on('pjax:complete', '#submission-status-pjax', function(){
        $.pjax.reload({container: '#submission-buttom-pjax'});
    });
           
js;
        $this->registerJs($js);
    }
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
js;
$this->registerJs($js);