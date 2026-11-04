<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

$cols = [
    [
        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'i18nName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'version',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'version_at',
        'format' => ['date'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fileIconHtml',
        'format' => 'raw',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{upload}',
        'buttons' => [
            'upload' => function($url, $model) use ($submission) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'submissionId' => $submission->id, 'submissionDocumentId' => $model->id], ['role' => 'modal-remote']);
            },
        ],
    ],
];

return $cols;
