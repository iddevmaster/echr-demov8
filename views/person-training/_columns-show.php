<?php

use yii\helpers\Url;
use yii\helpers\Html;

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
        'attribute' => 'file',
        'format' => 'raw',
        'value' => function($model) {
            if (isset($model->fileUrl)) {
                $view = $model->viewFilePdf;
                return $model->fileIconHtml . $view;
            } else {
                return yii::t('app', "ไม่มีไฟล์ประวัติ");
            }
        },
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
        'attribute' => 'start_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
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
            return Html::tag('span', $text, ['class' => $cls]);
        },
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
];
