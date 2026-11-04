<?php

use app\models\Project;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$currentRole = \Yii::$app->session->get('currentRole');
?>
<?php \yii\widgets\Pjax::begin(['id' => 'submission-general-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
<?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

<div class="panel panel-bordered panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Yii::t('app', 'ข้อมูลทั่วไปของงานวิจัย') ?></h3>
    </div><br>
    <div class="col-md-12">
        <?php
        if (isset($submission->submissionType)) {
            if (((($submission->submission_type_id == 9) || ($submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW)) || ($submission->isFromCrec())) && ((empty($submission->responsible_person) && ($currentRole['role_id'] == app\models\Role::STAFF && $submission->status <= app\models\Submission::STATUS_SUBMITTED)) || ($currentRole['role_id'] == app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id && $submission->status <= app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT) || $currentRole['role_id'] == app\models\Role::ADMIN)) {
                echo Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'แก้ไขข้อมูลทั่วไป'), ['submission/general', 'id' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised pull-right']);
            }
        }
        ?>
    </div>
    <br>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">            

                <?=
                DetailView::widget([
                    'model' => $submission,
                    'attributes' => [
                        'submissionType.name',
                        [
                            'attribute' => 'project.name_thai',
                            'format' => 'raw',
                            'value' => function($model) {
                                $name = "";
                                $color = "";
                                $title = "";
                                if (isset($model->project->name_thai)) {
                                    if ($model->is_legacy == 1) {
                                        $name .= $model->project->name_thai . Yii::t('app', '(โครงการเดิมที่ผ่านการรับรองแล้ว)');
                                    } else {
                                        $name .= $model->project->name_thai;
                                    }

                                    $color = in_array($model->project->name_changed, [Project::NAME_THAI_CHANGED, Project::NAME_BOTH_CHANGED]) ? "blue-600" : "";
                                    $title = $model->project->name_changed == Project::NAME_THAI_CHANGED ? "โครงการวิจัยนี้ได้รับการเปลี่ยนชื่อตามการแก้ไขของผู้วิจัย" : "";
                                }
                                return "<span class='{$color}' title='{$title}' data-toggle='tooltip' data-placement='bottom'>{$name}</span>";
                            }
                        ],
                        [
                            'attribute' => 'project.name_eng',
                            'format' => 'raw',
                            'value' => function($model) {
                                $name = "";
                                $color = "";
                                $title = "";
                                if (isset($model->project->name_eng)) {
                                    if ($model->is_legacy == 1) {
                                        $name .= $model->project->name_eng . Yii::t('app', '(โครงการเดิมที่ผ่านการรับรองแล้ว)');
                                    } else {
                                        $name .= $model->project->name_eng;
                                    }

                                    $color = in_array($model->project->name_changed, [Project::NAME_ENG_CHANGED, Project::NAME_BOTH_CHANGED]) ? "blue-600" : "";
                                    $title = $model->project->name_changed ? "โครงการวิจัยนี้ได้รับการเปลี่ยนชื่อตามการแก้ไขของผู้วิจัย" : "";
                                }
                                return "<span class='{$color}' title='{$title}' data-toggle='tooltip' data-placement='bottom'>{$name}</span>";
                            }
                        ],
                        [
                            'attribute' => 'project.is_child_project',
                            'value' => isset($submission->project) ? $submission->project->isChildProjectLabel : ''
                        ],
                        'correspondence_no',
                        'project.remark',
                    ],
                ])
                ?>
            </div>
            <div class="col-md-6">
                <?=
                DetailView::widget([
                    'model' => $submission,
                    'attributes' => [
                        'project.organization.name',
                        'project.isFda',
                        'project.fundingSource.name',
                        'project.funding_source_description',
                        [
                            'attribute' => 'projectLeader.person.i18nFullName',
                            'label' => Yii::t('app', 'หัวหน้าโครงการ'),
                            'format' => 'raw',
                            'value' => isset($submission->projectLeader->person->i18nFullName) ? $submission->projectLeader->person->i18nFullName . $submission->projectLeader->person->fullOrgEn . "<br>Mobile : " . $submission->projectLeader->person->mobile_no : ""
                        ],
                        [
                            'attribute' => 'projectCoordinator.person.i18nFullName',
                            'label' => Yii::t('app', 'ผู้ประสานงาน'),
                            'format' => 'raw',
                            'value' => isset($submission->project->project_coordinator_id) ? $submission->project->projectCoordinator->person->i18nFullName : "",
                            'visible' => isset($submission->project->project_coordinator_id)
                        ],
                        [
                            'attribute' => 'projectCoordinator.person.i18nFullName',
                            'label' => Yii::t('app', 'ผู้ประสานงานคนที่ 2'),
                            'format' => 'raw',
                            'value' => isset($submission->project->project_coordinator_2nd_id) ? $submission->project->projectCoordinator2nd->person->i18nFullName : "",
                            'visible' => isset($submission->project->project_coordinator_2nd_id)
                        ],
                        [
                            'attribute' => 'projectCoordinator.person.i18nFullName',
                            'label' => Yii::t('app', 'ผู้ประสานงานคนที่ 3'),
                            'format' => 'raw',
                            'value' => isset($submission->project->project_coordinator_3rd_id) ? $submission->project->projectCoordinator3rd->person->i18nFullName : "",
                            'visible' => isset($submission->project->project_coordinator_3rd_id)
                        ],
                        [
                            'attribute' => 'viewer',
                            'label' => 'Viewer',
                            'format' => 'raw',
                            'value' => isset($submission->project->project_viewer_id) ? $submission->project->projectViewer->person->i18nFullName : "",
                            'visible' => isset($submission->project->project_viewer_id)
                        ],
//                        'projectCoordinator.person.i18nFullName',
                        [
                            'attribute' => 'projectLeader.person.i18nFullName',
                            'label' => 'CREC Number',
                            'format' => 'raw',
                            'value' => isset($submission->project->crec_number) ? $submission->project->crec_number : "",
                            'visible' => isset($submission->project->crec_number),
                        ],
                        [
                            'attribute' => 'projectLeader.person.i18nFullName',
                            'label' => 'CREC case no.',
                            'format' => 'raw',
                            'value' => isset($submission->submission_number) ? $submission->submission_number : "",
                            'visible' => isset($submission->submission_number),
                        ],
                        [
                            'attribute' => 'projectLeader.person.i18nFullName',
                            'label' => 'เจ้าหน้าที่ CREC',
                            'format' => 'raw',
                            'value' => isset($submission->crec_staff) ? $submission->crec_staff : "",
                            'visible' => isset($submission->crec_staff),
                        ],
//                        'project.crec_number',
                    ],
                ])
                ?>
            </div>

            <div class="col-md-6 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', 'วันที่ประมาณการประชุม : ');
                    if (isset($submission->meeting_plan_date)) {
                        echo Yii::$app->formatter->format($submission->meeting_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                    if (isset($submission->send_plan_date)) {
                        echo Yii::$app->formatter->format($submission->send_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end(); ?>