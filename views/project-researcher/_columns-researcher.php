<?php

use yii\helpers\Url;
use yii\helpers\Html;

$currentRole = \Yii::$app->session->get('currentRole');

$items = [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.fullNameWithEng',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'isLeaderLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'acknowledgeStatusLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', "ไฟล์ประวัตินักวิจัย"),
        'attribute' => 'person.cv_file',
        'format' => 'raw',
        'value' => function($model) {
            if (isset($model->person->cv_file)) {
                $view = $model->person->viewFilePdf;
                return Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " ไฟล์ประวัติ "),
                                // $model->person->cvFileUrl, 
                                Url::to(['person/download', 'id' => $model->person_id]),
                                ['target' => '_blank', 'data-pjax' => 0]) . $view . Html::a('<i class="glyphicon glyphicon-user" data-toggle="tooltip" ></i> ' . Yii::t('app', " การอบรม "), ['person-training/show', 'personId' => $model->person_id], ['role' => 'modal-remote',]);
            } else {
                return yii::t('app', "ไม่มีไฟล์ประวัติ");
            }
        },
    ],
];
$asOfDate = isset($submission) && !empty($submission->submittedAt)
        ? date('Y-m-d', strtotime($submission->submittedAt))
        : null;
$submissionType = isset($submission) ? $submission->submissionType : null;
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'header' => Yii::t('app', 'วันหมดอายุ CV'),
    'format' => 'raw',
    'value' => function($model) use ($asOfDate) {
        return isset($model->person) ? $model->person->getCvFreshnessStatusHtml($asOfDate) : '';
    },
];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'header' => Yii::t('app', 'วันหมดอายุการอบรม (GCP)'),
    'format' => 'raw',
    'value' => function($model) use ($asOfDate, $submissionType) {
        return isset($model->person) ? $model->person->getTrainingExpiryStatusHtml($asOfDate, $submissionType) : '';
    },
];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'format' => 'raw',
    'attribute' => 'documentResearcherRemark',
];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'format' => 'raw',
    'attribute' => 'documentResearcherStatusLabel',
];


$items1 = array_merge($items, [
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'noWrap' => true,
        'controller' => 'project-researcher',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => ' {accept}  {change}  {check}  {edit-name} {delete} ',
        'buttons' => [
            'check' => function($url, $model)use ($submission) {
                return Html::a('<i class="glyphicon glyphicon-check" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ตรวจสอบ'), ['submission-project-researcher/check-document', 'id' => $model->id, 'submissionId' => $submission->id], ['role' => 'modal-remote',]) . '<br>';
            },
            'edit-name' => function ($url, $model) use ($submission) {
                    return Html::a('<i class="glyphicon glyphicon-check" data-toggle="tooltip" ></i> ' . Yii::t('app', 'แก้ไขชื่อนักวิจัย'), ['person/edit-name', 'id' => $model->person_id], ['role' => 'modal-remote',]) . '<br>';
            },
            'change' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เปลี่ยนหัวหนัาโครงการ'), ['project-researcher/change', 'submissionId' => $model->submission_id, 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'เปลี่ยนหัวหนัาโครงการ',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการเปลี่ยนหัวหนัาโครงการ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการเปลี่ยนหัวหนัาโครงการใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
            },
            'delete' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i> ', ['project-researcher/delete', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'ลบข้อมูล',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบข้อมูล'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการลบข้อมูลใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]);
            },
            'accept' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-check"></i> ' . Yii::t('app', 'ตอบรับร่วมโครงการ'), ['project-researcher/accept', 'id' => $model->submission_id, 'personId' => $model->person_id], ['role' => 'modal-remote', 'title' => 'ตอบรับร่วมโครงการ',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
            },
        ],
        'visibleButtons' => [
            'check' => function($model)use ($currentRole, $submission) {
                $researcher = app\models\SubmissionProjectResearcher::find()->isDeleted(FALSE)->andWhere(['project_researcher_id' => $model->id])->one();
//                    $revise = \app\models\SubmissionCommitteeRevise::find()->submission($model->submission_id)->isDeleted(FALSE)->one();
                if (isset($researcher)) {
                    return ($submission->status == 200) && ($currentRole['role_id'] == \app\models\Role::STAFF);
                }
            },
            'delete' => function($model) use ($currentRole) {
                return ($model->is_leader != 1) && (($model->submission->status <= app\models\Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF)) or ($model->submission->status <= app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && ($currentRole['role_id'] == \app\models\Role::RESEARCHER)) or ($currentRole['role_id'] == \app\models\Role::ADMIN));
            },
            'change' => function($model) use ($currentRole) {
                return $currentRole['role_id'] == \app\models\Role::ADMIN && $model->is_leader != 1;
            },
            'edit-name' => function ($model) use ($currentRole) {
                return $currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF;
            },
            'accept' => function($model)use ($currentRole, $submission) {
                if ($currentRole['role_id'] == \app\models\Role::RESEARCHER) {
                    return $model->person_id == \Yii::$app->user->identity->person->id && $model->acknowledge_status == 100;
                } elseif ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
                    return $model->acknowledge_status == 100;
                }
            }
        ],
    ],
        ]);
return $items1;

