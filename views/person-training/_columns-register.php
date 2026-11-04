<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name_thai_course',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name_eng_course',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'training_type_id',
        'value' => function($model) {
            return isset($model->trainingType) ? $model->trainingType->name : '';
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'expire_date',
        'format' => 'raw',
        'value' => function($model) {
            if (empty($model->expire_date)) {
                return '';
            }
            $text = Yii::$app->formatter->asDate($model->expire_date);
            $cls = $model->getIsExpired() ? 'label label-danger' : 'label label-success';
            return \yii\helpers\Html::tag('span', $text, ['class' => $cls]);
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'file',
        'format' => 'raw',
        'value' => function($model) {
            return $model->fileIconHtml;
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
        'controller' => 'person-training',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{delete}',
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
