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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submission_type_id',
    ],

        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'is_require',
        'value' => function($model) {
            return isset($model->is_requise) ? $model->IsRequireLabel : "";
        }
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'deleted',
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'created_by',
//    ],
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
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{download} {upload}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
        'buttons' => [
            'download' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> Download ', ['dashboard/list-print-ckperson?meetingId'], ['data-pjax' => 0]);
            },
            'upload' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" title ="พิมพ์จดหมาย" data-toggle="tooltip" ></i>Upload ', ['register-transaction/list-print-letter'], ['data-pjax' => 0]);
            },
        ]
    ],
];
