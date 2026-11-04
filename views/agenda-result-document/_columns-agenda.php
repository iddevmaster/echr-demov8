<?php

use yii\helpers\Url;

return [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
//        [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'agenda_title',
//    ],
        [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
            [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'label',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
    ],
        [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{update}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
        'buttons' => [
            'update' => function($url, $model){
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit" data-toggle="tooltip" ></i> ', ['agenda-result-document/select-document', 'id' => $model->id], ['data-pjax' => 0]);
            },
        ],
    ],
];
