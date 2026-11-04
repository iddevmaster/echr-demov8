<?php

use yii\helpers\Url;
use yii\helpers\VarDumper;

$currentRole = \Yii::$app->session->get('currentRole');
$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'document_name',
        'header' => Yii::t('app', 'ชื่อเอกสาร'),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'ไฟล์ที่อับโหลด'),
        'format' => 'raw',
        'value' => function($model) use ($submission) {
            if (isset($model['submission_result_document_id'])) {
                $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
                return $srd->getFileLink();
            } else {
                return '';
            }
//            $revise = \app\models\SubmissionCommitteeRevise::findOne($model['submission_revise_id']);
//            $srds = $submission->getSubmissionResultDocuments()->isDeleted(FALSE)->revise($model['submission_revise_id'])->resultDocument($model['result_document_id'])->all();
//            foreach ($srds as $srd) {
//                if (isset($srd)) {
//                    return $srd->fileIconHtml;
//                } else {
//                    return '';
//                }
//            }
//            return isset($srd) ? $srd->fileIconHtml : "";
//            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", $model->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i'],
        'header' => Yii::t('app', 'วันที่เอกสาร'),
//        'filter' => FALSE,
    ],
];
if ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN) {
    $items[] = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{download} {upload} {delete}  {view-president}',
        'buttons' => [
            'download' => function($url, $model) use ($submission) {
                $url = ['result-document/download-template', 'id' => $model['result_document_id'], 'submissionId' => $model['submission_id'], 'submissionReviseId' => $model['submission_revise_id']];
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), $url, ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model) use ($submission, $pjaxId) {
//                \yii\helpers\VarDumper::dump($model);
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-result-document/create', 'id' => $model['submission_result_document_id'], 'submissionId' => $submission->id, 'documentId' => $model['result_document_id'], 'reviseId' => $model['submission_revise_id'], 'pjaxId' => $pjaxId], ['role' => 'modal-remote']);
            },
            'view-president' => function ($url, $model) use ($submission) {

                if (!isset($model['submission_result_document_id'])) {
                    $url = ['result-document/view-template-president', 'submissionResultDocId' => $model['submission_result_document_id'], 'id' => $model['result_document_id'], 'submissionId' => $model['submission_id'], 'submissionReviseId' => $model['submission_revise_id']];
                    return \yii\helpers\Html::a('<i class="icon wb-zoom-in" data-toggle="tooltip" ></i> ' . Yii::t('app', 'Views'), $url, ['data-pjax' => 0, 'target' => '_blank']);
                } else {
                    return false;
                }
            },
            'delete' => function($url, $model) use ($pjaxId) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', ['submission-result-document/delete', 'id' => $model['submission_result_document_id'], 'pjaxId' => $pjaxId], ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
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
            'download' => function($model)use ($submission) {
                return isset($model['result_document_id']) && $submission->status < app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
//                return true; // Yii::$app->util->checkPermission("result-document.download-template");
            },
            'upload' => function($model)use($submission) {
                $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
                return !isset($srd->srd_crec_id) && Yii::$app->util->checkPermission("submission-result-document.create") && $submission->status < app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'view-president' => function($model)use($submission, $currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF) && $submission->status >= app\models\Submission::STATUS_AGENDA_ADDED;
            },
            'delete' => function($model)use ($currentRole) {
                $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
//                return isset($model['submission_result_document_id']) && !isset($srd->srd_crec_id);
                return !isset($srd->srd_crec_id) && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN);
            }
        ]
    ];
}
$items = array_merge($items);
return $items;
