<?php

use app\models\SubmissionCommittee;
use yii\helpers\Url;
use app\models\Submission;
use app\models\SubmissionTypeGroup;

$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submissionType.i18nName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submittedDate',
        'format' => 'date',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'meetingDate',
        'format' => 'date',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'resolution',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            return Submission::getStatusLabels()[$model->status];
        }
    ]
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
                if ($model->status > 100) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> '.Yii::t('app', 'แสดงรายละเอียด'), ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
                } else {
                    if ($model->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
                        $url = 'submission/new';
                    } else {
                        $url = 'submission/continue';
                    }
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> '.Yii::t('app', 'แสดงรายละเอียด'), ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
                }
            },
        ],
    ],
        ]);
return $items;
