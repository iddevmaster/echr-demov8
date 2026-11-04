<?php

use yii\helpers\Url;

return [
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
        'attribute' => 'title',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'format' => ['date', 'php:d/m/Y'],
        'attribute' => 'start_date',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'format' => ['date', 'php:d/m/Y'],
        'attribute' => 'end_date',
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'start_time',
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'end_time',
//    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'status',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'is_public',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'department_id',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'submission_id',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'organization_id',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'meeting_no',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'year',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'deleted',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'created_by',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'created_at',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_by',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_at',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'noWrap' => true,
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
        'template' => '{view}{addagenda}{addperson}{addconclusion}{delete}',
        'buttons' => [
            'addagenda' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i>', ['meeting-agenda/index'], ['data-pjax' => 0, 'data-toggle' => 'tooltip', 'target' => '_blank', 'title' => 'กำหนดวาระการประชุม', 'class' => 'btn btn-info btn-raised']);
            },
            'addperson' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-user"></i>', ['register-transaction/view?id=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'จัดการผู้เข้าร่วมประชุม', 'class' => 'btn btn-success btn-raised']);
            },
            'view' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['meeting/view?id=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'รายงานการประชุม', 'class' => 'btn btn-danger btn-raised']);
            },
            'addconclusion' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i>', ['meeting-agenda/view'], ['data-pjax' => 0,'data-toggle' => 'tooltip', 'title' => 'กำหนดมติที่ประชุม', 'class' => 'btn btn-warning btn-raised']);
            },
            'delete' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', ['agenda-transaction/create?meetingId=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'ลบการประชุม', 'class' => 'btn btn-primary btn-raised']);
            },        ]
    ],
];
