<?php

use yii\helpers\Url;
use yii\helpers\VarDumper;
use app\models\SubmissionType;
use app\models\Submission;

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
        'format' => 'raw',
        'attribute' => 'submission_ec_id',
        'header' => Yii::t('app', 'Site'),
        'value' => function ($model) {
            if (!isset($model['submission_ec_id'])) {
                return 'N/A';
            }
            $sec = \app\models\SubmissionEc::findOne($model['submission_ec_id']);
            if (isset($sec->local_ec_id)) {
                $color = \app\models\SubmissionEc::statusColors()[$sec->status];
                $ecStatus = "<br> <span class='badge badge-info {$color}'>สถานะ LEC : " . \app\models\SubmissionEc::getStatusLabels()[$sec->status] . '</span>';
                return $sec->localEc->name . '<br>' . $ecStatus;
            } else {
                return 'N/A';
            }
        }
    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
////        'attribute' => 'file_name',
//        'header' => Yii::t('app', 'ไฟล์ที่อับโหลด'),
//        'format' => 'raw',
//        'value' => function($model) use ($submission) {
//            if (isset($model['submission_result_document_id'])) {
//                $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
//                return $srd->getFileLink();
//            } else {
//                return '';
//            }
////            $revise = \app\models\SubmissionCommitteeRevise::findOne($model['submission_revise_id']);
////            $srds = $submission->getSubmissionResultDocuments()->isDeleted(FALSE)->revise($model['submission_revise_id'])->resultDocument($model['result_document_id'])->all();
////            foreach ($srds as $srd) {
////                if (isset($srd)) {
////                    return $srd->fileIconHtml;
////                } else {
////                    return '';
////                }
////            }
////            return isset($srd) ? $srd->fileIconHtml : "";
////            return isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", $model->fileUrl, ['target' => '_blank', 'data-pjax' => 0]) : "";
//        }
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'updated_at',
//        'format' => ['date', 'php:d/m/Y H:i'],
//        'header' => Yii::t('app', 'วันที่เอกสาร'),
////        'filter' => FALSE,
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'code',
//        'header' => Yii::t('app', 'เลขที่เอกสาร'),
//    ],
];
if ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN or $currentRole['role_id'] == \app\models\Role::PRESIDENT or $currentRole['role_id'] == \app\models\Role::COORDINATOR) {
    $items[] = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view-president}',
        'buttons' => [
            'download' => function($url, $model) use ($submission) {

                $url = ['result-document/download-template', 'id' => $model['result_document_id'], 'submissionId' => $model['submission_id'], 'submissionReviseId' => $model['submission_revise_id']];
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), $url, ['data-pjax' => 0, 'target' => '_blank']);
            },
            'view' => function ($url, $model) use ($submission) {
                $url = ['result-document/view-template', 'submissionResultDocId' => $model['submission_result_document_id'], 'id' => $model['result_document_id'], 'submissionId' => $model['submission_id'], 'submissionReviseId' => $model['submission_revise_id']];
                return \yii\helpers\Html::a('<i class="icon wb-zoom-in" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดูแบบฟอร์ม'), $url, ['data-pjax' => 0, 'target' => '_blank']);
            },
            'view-president' => function ($url, $model) use ($submission) {
                if (isset($model['submission_result_document_id'])) {
                    $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
                    return $srd->getFileLink();
                } else {
                    $url = ['result-document/view-template-president', 'submissionResultDocId' => $model['submission_result_document_id'], 'id' => $model['result_document_id'], 'submissionId' => $model['submission_id'], 'submissionReviseId' => $model['submission_revise_id']];
                    return \yii\helpers\Html::a('<i class="icon wb-zoom-in" data-toggle="tooltip" ></i> ' . Yii::t('app', 'Views'), $url, ['data-pjax' => 0, 'target' => '_blank']);
                }
//                return \yii\helpers\Html::a('<i class="icon wb-zoom-in" data-toggle="tooltip" ></i> ' . Yii::t('app', 'Views'), $url, ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model) use ($submission, $pjaxId) {
//                \yii\helpers\VarDumper::dump($model);
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-result-document/create', 'id' => $model['submission_result_document_id'], 'submissionId' => $submission->id, 'documentId' => $model['result_document_id'], 'reviseId' => $model['submission_revise_id'], 'pjaxId' => $pjaxId], ['role' => 'modal-remote']);
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
                return Yii::$app->util->checkPermission("submission-result-document.create") && $submission->status < app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
//                return isset($model['result_document_id']) && $submission->status <= app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
//                return true; // Yii::$app->util->checkPermission("result-document.download-template");
            },
            'upload' => function($model)use($submission) {
                $srd = \app\models\SubmissionResultDocument::findOne($model['submission_result_document_id']);
                if (isset($srd->srd_ec_id)) {
                    return false;
                }

                return Yii::$app->util->checkPermission("submission-result-document.create") && $submission->status < app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'delete' => function($model) {
                return isset($model['submission_result_document_id']) && Yii::$app->util->checkPermission("submission-result-document.delete");
            },
            'view' => function($model)use($submission) {
                return Yii::$app->util->checkPermission("result-document.view-template") && $submission->status >= app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
            },
            'view-president' => function($model)use($submission, $currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::PRESIDENT || $currentRole['role_id'] == \app\models\Role::COORDINATOR) && $submission->status >= app\models\Submission::STATUS_SECRETARY_APPROVE_AGENDA;
            }
        ]
    ];
}
$items = array_merge($items);
return $items;
