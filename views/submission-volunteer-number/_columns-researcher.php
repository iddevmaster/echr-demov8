<?php

use yii\helpers\Url;
use app\models\SubmissionDocument;
use app\models\Project;

$revise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->one();

$items = [
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
        'attribute' => 'name',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documentSubmissionType.isRequireLabel',
        'format' => 'raw',
//        'value' => function($model) {
//            return $model->documentSubmissionType->id;
//        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'file_name',
        'format' => 'raw',
        'value' => function($model) {
            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", $model->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => 'ประวัติเอกสาร',
        'format' => 'raw',
        'value' => function($model) {
            return $model->getDocumentHistoriesHtml();
        }
    ],
];
if ($submission->status == \app\models\Submission::STATUS_DOC_REJECTED) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            return isset($mdoel->status) ? SubmissionDocument::getStatusLabels()[$model->status] : "";
        }
    ];
}

if ($submission->status == app\models\Submission::STATUS_DOC_REJECTED or ! isset($revise)) {
    $items[] = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{download} {upload}',
        'buttons' => [
            'download' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), ['document/download-template', 'id' => $model->document_id, 'submissionId' => $model->submission_id], ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model) {
                if (isset($model->id)) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'id' => $model->id], ['role' => 'modal-remote']);
                } else {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id], ['role' => 'modal-remote']);
                }
            },
        ],
        'visibleButtons' => [
            'download' => function($model) {
                return isset($model->document->template_file);
            },
            'upload' => function($model) {
                return $model->status != SubmissionDocument::STATUS_PASS and $model->submission->status >= \app\models\Submission::STATUS_SUBMITTED;
            },
        ]
    ];
}
$items = array_merge($items);
return $items;

