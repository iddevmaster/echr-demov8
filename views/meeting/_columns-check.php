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
        'header' => Yii::t('app', 'ชื่อการประชุม'),
//        'attribute' => 'fullNameWithDate',
        'value' => function($model) {
            $m = \app\models\Meeting::findOne($model['id']);
            return $m->fullNameWithDate;
        }
    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'format' => ['date', 'php:d/m/Y'],
//        'attribute' => 'start_date',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'format' => ['date', 'php:d/m/Y'],
//        'attribute' => 'end_date',
//    ],
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
        'template' => '{meeting}',
        'buttons' => [
            'meeting' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i>', ['meeting/update','id'=>$model['id']], ['data-pjax' => 0, 'data-toggle' => 'tooltip', 'target' => '_blank', 'title' => 'ตรวจสอบการประชุม']);
            },
        ]
    ],
];
