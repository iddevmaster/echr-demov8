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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'resolution',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'committee_resolution',
    ],
       [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'template_file',
        'format' => 'raw',
        'value' => function($model) {
            return !empty($model->template_file) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", 
            // $model->templateFileUrl, 
            Url::to(['result-document/download', 'id' => $model->id]), 
            ['target' => '_blank', 'data-pjax' => 0]) : "";
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_at',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
                'template'=>'{update} {delete}',
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
    ],
];
 