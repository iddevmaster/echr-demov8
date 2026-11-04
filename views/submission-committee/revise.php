<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

$currentRole = Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCommittee */
?>
<div class="submission-committee-view">

    <?php foreach ($dataProvider->models as $m) : ?>
        <div class="panel panel-bordered margin-bottom-10 panel-info" style="border: dotted 3px #a83b24;">
            <div class="padding-10" style="padding: 10px;">
                <span class="font-size-16"><font style="font-weight: bold; font-size: 16px;" color="#073453;"><?= Yii::t('app', 'ชื่อกรรมการ : ') . $m->person->fullName ?> </font></span>
                <div class="card-block font-size-16">

                    <ul>
                        <li><?= Yii::t('app', 'สถานะ : ') . app\models\SubmissionCommittee::getStatusLabels()[$m->status] ?> </li>
                        <li><?= Yii::t('app', 'วันที่ส่งแบบประเมิน : ') . Yii::$app->formatter->asDate($m->return_date, 'php:d/m/Y') ?> </li>
                        <?php if (isset($m->resolution)) { ?>
                            <li><?= Yii::t('app', 'ผลการพิจารณาของกรรมการ : ') . app\models\Submission::getResolutionLables()[$m->resolution] ?> </li>
                        <?php } ?>


                    </ul>
                    <?php if ($submission->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_NEW && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)) { ?>
                        <span><font style="font-weight: bold; font-size: 16px;" color="#073453;"><?= Yii::t('app', 'comment จากกรรมการ : ') ?> </font></span>

                        <?php if (isset($m->submissionCommitteeRevise->remark)) { ?>
                            <p><?= $m->submissionCommitteeRevise->remark; ?>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($submission->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_CONT && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)) { ?>
                            <?php
                            $caf = app\models\ContinueAssessForm::find()->submissionCommittee($m->id)->one();
                            ?>
                            <?php if (isset($caf->review_choice_id)) { ?>
                                <span><font style="font-weight: bold; font-size: 16px;" color="#073453;"><?= Yii::t('app', 'ข้อคิดเห็นของกรรมการ : ') ?> </font> <?= $caf->reviewChoice->name; ?> <?= !empty($caf->review_choice_text) ? $caf->review_choice_text : ""; ?></span>
                                <?php } ?>
                            <?php } ?>
                </div>


                <?php
                if ($m->status != \app\models\SubmissionCommittee::STATUS_REJECTED) {
                    $options = ['data-pjax' => 0, 'title' => 'เลือก', 'data-toggle' => 'tooltip', 'target' => '_blank'];
                    if ($currentRole['role_id'] == \app\models\Role::ADMIN || ($currentRole['role_id'] == \app\models\Role::STAFF && $m->submission->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::COMMITTEE) {
                        echo Html::a('<i class="glyphicon glyphicon-open-file "></i> <span class="font-size-16"> รายละเอียดการประเมินของกรรมการ</span>', ['questionnaire-answer/assessment', 'submissionId' => $m->submission_id, 'projectId' => $m->project_id, 'sCommitteeId' => $m->id, 'model' => $m], $options);
                    } else {
                        echo Html::a('<i class="glyphicon glyphicon-open-file "></i> <span class="font-size-16"> รายละเอียดการประเมินของกรรมการ</span>', ['questionnaire-answer/assessment-info', 'submissionId' => $m->submission_id, 'projectId' => $m->project_id, 'sCommitteeId' => $m->id, 'model' => $m], $options);
                    }
                }
                ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>
