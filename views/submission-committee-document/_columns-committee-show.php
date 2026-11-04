<?php

use yii\helpers\Url;

$currentRole = \Yii::$app->session->get('currentRole');

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
        'attribute' => 'file_name',
        'format' => 'raw',
        'value' => function($model) {
            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", ['submission-committee-document/download', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => 0]) : "";
//            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", $model->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
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
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{download} {upload}',
        'buttons' => [
            'download' => function($url, $model)use ($sCommitteeId) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), ['document/download-template', 'id' => $model->document_id, 'submissionId' => $model->submission_id, 'submissionComitteeId' => $sCommitteeId], ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model)use ($sCommitteeId) {
                if (isset($model->id)) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-committee-document/create', 'id' => $model->id, 'sCommitteeId' => $sCommitteeId], ['role' => 'modal-remote']);
                } else {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-committee-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id, 'sCommitteeId' => $sCommitteeId], ['role' => 'modal-remote']);
                }
            },
        ],
        'visibleButtons' => [
            'download' => function($model)use ($currentRole) {
                return isset($model->document->template_file) && ($currentRole['role_id'] == \app\models\Role::STAFF && $model->submission->responsible_person == \Yii::$app->user->identity->id);
            },
            'upload' => function($model)use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::STAFF && $model->submission->responsible_person == \Yii::$app->user->identity->id);
            }
        ]
    ],
];
