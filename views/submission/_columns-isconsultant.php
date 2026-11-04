<?php

use yii\helpers\Url;
use app\models\Submission;

$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'project.project_code',
        'value' => function($model) {
            if (isset($model->project->project_code)) {
                $codes = $model->project->projectCodeHistoriesHtml;
                $crecNumber = !empty($model->project->crec_number) ? '<br><font class="green-700">' . Yii::t('app', 'CREC No.') . $model->project->crec_number . '</font>' : "";
                $submissionNumber = !empty($model->submission_number) ? '<br><font class="teal-700">(' . $model->submission_number . ')</font>' : "";

                return $model->project->project_code . (empty($codes) ? '' : "({$codes})") . $crecNumber . $submissionNumber;
            } else {
                $crecNumber = !empty($model->project->crec_number) ? '<br><font class="green-700">' . Yii::t('app', 'CREC No.') . $model->project->crec_number . '</font>' : "";
                $submissionNumber = !empty($model->submission_number) ? '<br><font class="teal-700">(' . $model->submission_number . ')</font>' : "";

                return Yii::t('app', 'N/A') . $crecNumber . $submissionNumber;
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'project.name_thai',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'typeAndRef',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'projectLeader.person.fullName',
        'value' => function($model) {
            if (isset($model->projectLeader)) {
                return $model->projectLeader->person->i18nFullName . $model->projectLeader->person->fullOrgEn . "<br>Mobile : " . $model->projectLeader->person->mobile_no;
            } else {
                return '';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'status',
        'value' => function($model) {
            if ($model->status) {
                if ($model->status == Submission::STATUS_COMMITTEE_ACCEPTED) {
                    $count = $model->getSubmissionCommittees()->isDeleted(false)->status(app\models\SubmissionCommittee::STATUS_ACCEPTED)->count();
                    $message = " ({$count} " . Yii::t('app', 'คน') . ")";
                    if ($count == 0) {
                        $message = Yii::t('app', ' (กรรมการส่งครบแล้ว)');
                    }
                    return Submission::getStatusLabels()[$model->status] . $message;
                } else {
                    return Submission::getStatusLabels()[$model->status];
                }
            } else {
                return '';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsiblePerson.person.fullName',
        'value' => function($model) {
            if ($model->responsible_person != NULL) {
                return $model->responsiblePerson->person->fullName;
            } else {
                return 'ยังไม่กำหนดเจ้าหน้าที่';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'resolution',
        'format' => 'raw',
        'value' => function($model) {
            $res = '';
            if (isset($model->meetingAgenda)) {
                $res .= "[{$model->meetingAgenda->meeting->yearNo}] ";
            }
            if ($model->resolution != NULL) {
                $res .= Submission::getResolutionLables()[$model->resolution];
            } else {
                $res .= 'ยังไม่มีมติที่ประชุม';
            }
            return $res;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label' => Yii::t('app', 'สถานะการจัดการไฟล์หนังสือแจ้งผล'),
        'value' => function($model) {
            if (isset($model->resultDocument)) {
                return '<span class="badge badge-info bg-blue-500">' . Yii::t('app', 'อัฟโหลดแล้ว') . '</span>';
            } else {
                return '<span class="badge badge-info bg-red-500">' . Yii::t('app', 'ยังไม่อัฟโหลด') . '</span>';
            }
        }
    ]
];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'responsiblePerson.person.fullName',
//    'value' => function($model) {
//        if ($model->responsible_person != NULL) {
//            return $model->responsiblePerson->person->fullName;
//        } else {
//            return 'ยังไม่กำหนดเจ้าหน้าที่';
//        }
//    }
//];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'resolution',
//    'value' => function($model) {
//        if ($model->resolution != NULL) {
//            return Submission::getResolutionLables()[$model->resolution];
//        } else {
//            return 'ยังไม่มีมติที่ประชุม';
//        }
//    }
//];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'project_coordinator_id',
    'format' => 'raw',
    'value' => function($model) {
        if (isset($model->project_coordinator_id)) {
            $mobile = isset($model->projectCoordinator->person->mobile_no) ? $model->projectCoordinator->person->mobile_no : "N/A";
            $email = isset($model->projectCoordinator->person->email) ? $model->projectCoordinator->person->email : "N/A";
            return $model->projectCoordinator->person->i18nFullName . "<br>Mobile : " . $mobile . "<br>Email : " . $email;

//            return $model->projectCoordinator->person->i18nFullName;
        } else {
            return Yii::t('app', 'N/A');
        }
    }
];
$items = array_merge($items, [
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'template' => '{view}',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
        'buttons' => [
            'view' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> แสดงรายละเอียด', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
            }
        ],
        'visibleButtons' => [
            'gencode' => function($model) {
                return $model->status == 300;
            },
            'meetingplan' => function($model) {
                return $model->status == 400;
            },
            'setagenda' => function($model) {
                return $model->status >= 900 && $model->status < 1000;
            },
        ],
    ],
        ]);
return $items;
