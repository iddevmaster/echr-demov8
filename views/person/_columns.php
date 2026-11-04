<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

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
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            $searchModel = new app\models\PersonTrainingSearch();
            $searchModel->deleted = 0;
            $searchModel->person_id = $model->id;
            $dataProvider = $searchModel->search([]);
            return Yii::$app->controller->renderPartial('@app/views/person-training/index-person', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
        'hiddenFromExport' => false,
        'detailRowCssClass' => 'detail-row',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fullName',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'user_id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'email',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'vAlign' => 'middle',
//        'hAlign' => 'right',
        'label' => Yii::t('app', 'CV'),
        'value' => function($model) {
            if (!empty($model->cv_file)) {
                return Html::a('<span class="badge badge-info bg-blue-500">CV File</span>', Url::to(['person/download', 'id' => $model->id]), [
                            'target' => '_blank', 'data' => ['pjax' => 0]]);
//                return '<span class="badge badge-danger bg-green-500">' . app\models\Person::getStatusAcceptLabels()[$model->create_status] . '</span>';
            } else {
                return '<span class="badge badge-danger bg-red-500">ไม่มีไฟล์ในการนำแสดง</span>';


//                return '<span class="badge badge-danger bg-blue-500">' . app\models\Person::getStatusAcceptLabels()[$model->create_status] . '</span>';
            }
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'vAlign' => 'middle',
//        'hAlign' => 'right',
        'label' => Yii::t('app', 'สถานะ'),
        'value' => function($model) {
            if (isset($model->user->status)) {
                if ($model->user->status == \app\models\User::STATUS_ACTIVE) {
                    return '<span class="badge badge-info bg-green-500">'.Yii::t('app', 'ปกติ').'</span>';
                } else {
                    return '<span class="badge badge-info bg-red-500">'.Yii::t('app', 'ยังไม่ active').'</span>';
                }
            }
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'vAlign' => 'middle',
//        'hAlign' => 'right',
        'label' => Yii::t('app', 'นักวิจัยในโครงการของ CREC'),
        'value' => function($model) {
            if ($model->is_researcher_crec == 1) {
                return '<span class="badge badge-info bg-green-500">' . Yii::t('app', 'yes') . '</span>';
            } else {
                return '<span class="badge badge-info bg-red-500">' . Yii::t('app', 'no') . '</span>';
            }
        },
    ],
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
//                    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'created_at',
//        'format' => ['date', 'php:d/m/Y'],
//        'filter' => FALSE,
//        'width' => '10%'
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'updatedByUserProfile.fullName',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
        'width' => '10%'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'noWrap' => true,
        'template' => '{updateinfo} {delete}',
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
            'data-confirm-cancel' => Yii::t('app', 'ไม่')],
        'buttons' => [
            'updateinfo' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i>', ['update-info', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
        ],
    ],
];
