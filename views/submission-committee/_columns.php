<?php

use yii\helpers\Url;
use yii\helpers\Html;

$currentRole = Yii::$app->session->get('currentRole');
$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.fullName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            if (isset($model->status)) {
                return app\models\SubmissionCommittee::getStatusLabels()[$model->status];
            } else {
                return 'ยังไม่มีการตอบรับ';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'วันที่ส่งแบบประเมิน',
        'attribute' => 'return_date',
        'format' => ['date', 'php:d/m/Y'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'is_meeting',
        'value' => function($model) {
            if (isset($model->return_date)) {
//                if($model->is_meeting != 0 ){
                return app\models\SubmissionCommittee::getStatusLabelsMeeting()[$model->is_meeting];
                ////                }else{
//                    return 'เข้าประชุม';
//                }
            } else {
                return 'ยังไม่มีการส่งแบบประเมิน';
            }
        }
    ]
];
if ($submission->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_NEW && $currentRole['role_id'] == \app\models\Role::STAFF) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'comment จากกรรมการ',
        'attribute' => 'remark',
        'value' => function($model) {
            if (isset($model->submissionCommitteeRevise->remark)) {
                return $model->submissionCommitteeRevise->remark;
            } else {
                return '';
            }
        }
    ];
}
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'resolution',
//        'value' => function($model) {
//            if (isset($model->return_date) && isset($model->resolution)) {
//                return \app\models\Submission::getResolutionLablesNew()[$model->resolution];
//            } else {
//                return 'ยังไม่มีการส่งแบบประเมิน';
//            }
//        }
//    ],
$items = array_merge($items, [
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view}',
        'controller' => 'submission-committee',
//        'urlCreator' => function($action, $model, $key, $index) { 
//                return Url::to([$action,'id'=>$key]);
//        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'ลบ',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
        ],
        // 'template' => '{select} {delete}',
        'buttons' => [
            'view' => function($url, $model) use ($currentRole) {
                $options = ['data-pjax' => 0, 'title' => 'เลือก', 'data-toggle' => 'tooltip', 'target' => '_blank'];
                if ($currentRole['role_id'] == \app\models\Role::ADMIN || ($currentRole['role_id'] == \app\models\Role::STAFF && $model->submission->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::COMMITTEE) {
                    return Html::a('<i class="glyphicon glyphicon-open-file font-size-18"></i>', ['questionnaire-answer/assessment', 'submissionId' => $model->submission_id, 'projectId' => $model->project_id, 'sCommitteeId' => $model->id, 'model' => $model], $options);
                } else {
                    return Html::a('<i class="glyphicon glyphicon-open-file font-size-18"></i>', ['questionnaire-answer/assessment-info', 'submissionId' => $model->submission_id, 'projectId' => $model->project_id, 'sCommitteeId' => $model->id, 'model' => $model], $options);
                }
            },
        ],
        'visibleButtons' => [
            'delete' => function($model) use ($currentRole) {
                return $currentRole['role_id'] != \app\models\Role::COPRESIDENT && $currentRole['role_id'] != \app\models\Role::PRESIDENT && $currentRole['role_id'] != \app\models\Role::COMMITTEE;
            },
        ],
    ],
        ]);
return $items;

