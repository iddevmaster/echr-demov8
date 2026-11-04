<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'category',
        'value' => function($model) {
            return $model->categoryLabel;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'validity_years',
        'value' => function($model) {
            return isset($model->validity_years) ? $model->validity_years . ' ' . Yii::t('app', 'ปี') : Yii::t('app', 'ไม่หมดอายุ');
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updatedBy.person.fullName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{update} {delete}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'รายละเอียด'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'แก้ไข'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
    ],
];
