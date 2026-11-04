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
        'attribute'=>'questionnaireTitle.title',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'title',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updatedByUserProfile.fullName',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'noWrap' => true,
        'dropdown' => false,
        'vAlign' => 'middle',
        'controller'=>'questionnaire-choice',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'viewOptions' => ['label' => '<i class="icon md-view-list font-size-18"></i>', 'role' => 'modal-remote', 'title' => 'รายละเอียด', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['label' => '<i class="icon md-edit font-size-18"></i>', 'role' => 'modal-remote', 'title' => 'แก้ไข', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['label' => '<i class="icon md-delete font-size-18"></i>','role' => 'modal-remote', 'title' => 'ลบ',
             'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
    ],
];    