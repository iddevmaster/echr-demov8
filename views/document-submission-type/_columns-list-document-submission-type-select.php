<?php

use yii\helpers\Url;
use app\models\Document;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'sort',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'document.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'role.name',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'committeePosition.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'refSubmissionType.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'isRequireLabel',
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'isApiLabel',
        'format' => 'raw',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{up}{delete}',
        'controller' => 'document-submission-type',
//        'urlCreator' => function($action, $model, $key, $index) { 
//                return Url::to([$action,'id'=>$key]);
//        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => yii::t('app', 'ลบ'),
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
        ],
        'buttons' => [
            'up' => function($url, $model) {
                $options = ['role' => 'modal-remote', 'title' => 'แก้ไข', 'data-toggle' => 'tooltip'];
                return Html::a('<i class="icon md-edit font-size-18"></i>', ['document-submission-type/update', 'id' => $model->id, 'roleId' => $model->role_id,'submissionTypeId'=>$model->submission_type_id], $options);
            },
        ],
    ],
];
