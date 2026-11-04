<?php
use yii\helpers\Url;

return [
    //[
    //    'class' => 'kartik\grid\CheckboxColumn',
    //    'width' => '20px',
    //],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'code',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>Yii::t('app', 'รายละเอียด'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>Yii::t('app', 'แก้ไข'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>Yii::t('app', 'ลบ'), 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
                          'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
                          'data-confirm-ok' => Yii::t('app', 'ใช่'),
                          'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
    ],

];   