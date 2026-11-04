<?php

use yii\helpers\Url;

return [
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'created_at',
        'format' => ['date', 'php:d/m/Y'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'message',
        'value' => function($model) {
            return yii\helpers\Html::a($model->message, Url::to(['submission/project-submission', 'submissionId' => $model->submission_id]), ['data-pjax' => 0]);
        },
        'format' => 'raw',
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'alert_type',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'is_acknowledged',
        'value' => function($model) {
            return $model->is_acknowledged ? Yii::t('app', 'ใช่') : "";
        }
    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'acknowledged_by',
//    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'acknowledged_at',
        // ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
        // ],
//    [
//        'class' => 'kartik\grid\ActionColumn',
//        'dropdown' => false,
//        'vAlign' => 'middle',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
//        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
//        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
//        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
//            'data-request-method' => 'post',
//            'data-toggle' => 'tooltip',
//            'data-confirm-title' => 'Are you sure?',
//            'data-confirm-message' => 'Are you sure want to delete this item'],
//    ],
];
