<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

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
        'attribute' => 'role.name',
    ],
        [
        'class' => 'kartik\grid\ActionColumn',
        'noWrap' => true,
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{select}',
        'buttons' => [
            'select' => function($url, $model) use ($selectDocument) {
                $options = ['role' => 'modal-remote', 'title' => 'เลือก', 'data-toggle' => 'tooltip'];
                return Html::a('<i class="icon md-arrow-right font-size-18"></i>', ['document-submission-type/select-documents', 'id' => $_GET['id'], 'documentId' => $model->id, 'submissionTypeId' => $_GET['id'],'roleId'=>$model->role_id], $options);
            },
        ],
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
//        'viewOptions' => ['label' => '<i class="icon md-view-list font-size-18"></i>', 'role' => 'modal-remote', 'title' => 'รายละเอียด', 'data-toggle' => 'tooltip'],
//        'updateOptions' => ['label' => '<i class="icon md-edit font-size-18"></i>', 'data-pjax' => '0', 'title' => 'แก้ไข', 'data-toggle' => 'tooltip'],
//        'deleteOptions' => ['label' => '<i class="icon md-delete font-size-18"></i>', 'role' => 'modal-remote', 'title' => 'ลบ',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
//            'data-request-method' => 'post',
//            'data-toggle' => 'tooltip',
//            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
//            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่?'),
//            'data-confirm-ok' => Yii::t('app', 'ใช่'),
//            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
//        ],
    ],
];

