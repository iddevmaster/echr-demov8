<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    //[
    //    'class' => 'kartik\grid\CheckboxColumn',
    //    'width' => '20px',
    //],
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
        'attribute' => 'volunteer.code',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'typeLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'isAssessed',
        'format' => 'html',
        'value' => function ($model) use ($sCommitteeId) {
            return Yii::$app->util->booleanIconFormat($model->getIsAssessed($sCommitteeId));
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'controller' => 'submission-volunteer',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{assess} {history}',
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
        'buttons' => [
            'history' => function($url, $model) {
                return Html::a(Yii::t('app', 'ประวัติ'), ['submission-volunteer/history', 'id' => $model->id], ['class' => 'btn btn-info btn-xs btn-round', 'role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
            'assess' => function($url, $model) use ($sCommitteeId) {
                return Html::a(Yii::t('app', 'ประเมิน'), ['sae-volunteer/create', 'submissionVolunteerId' => $model->id, 'sCommitteeId' => $sCommitteeId], ['class' => 'btn btn-primary btn-xs btn-round', 'role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
        ],
        'visibleButtons' => [
            'history' => function($model) {
                return count($model->histories) > 1;
            },
            'assess' => function($model) {
                return Yii::$app->util->checkPermission('sae-volunteer.create');
            },
        ],
    ],
];
