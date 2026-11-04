<?php

use app\models\SubmissionType;
use yii\helpers\Url;

$cols = [
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
];

if ($submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE) {
    $cols[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submissionVolunteer.typeLabel',
    ];
}

$cols = array_merge($cols, [
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
            return $model->getFileIconHtml(FALSE);
//            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", $model->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'ประวัติเอกสาร'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getDocumentHistoriesHtml();
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{download} {download-eng} {upload} {delete}',
        'buttons' => [
            'download' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), ['document/download-template', 'id' => $model->document_id, 'submissionId' => $model->submission_id], ['data-pjax' => 0, 'target' => '_blank']);
            },
            'download-eng' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม(Eng)'), ['document/download-template', 'id' => $model->document_id, 'submissionId' => $model->submission_id, 'lang' => app\models\Document::LANG_ENG], ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model) {
                if (isset($model->id)) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'id' => $model->id], ['role' => 'modal-remote']);
                } else {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id, 'volunteerId' => $model->volunteer_id, 'eventId' => $model->submission_event_id], ['role' => 'modal-remote']);
                }
            },
            'delete' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', ['submission-document/delete', 'id' => $model->id], ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                ]);
            },
        ],
        'visibleButtons' => [
            'download' => function($model) {
                return isset($model->document->template_file);
            },
            'download-eng' => function($model) {
                return isset($model->document->template_file_eng);
            },
            'delete' => function($model) {
                return isset($model->id) && !isset($model->document_id);
            }
        ]
    ],
]);

return $cols;
