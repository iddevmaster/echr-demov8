<?php

use yii\helpers\Url;
use app\models\Role;

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

            $downloadLink = isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i>", ['submission-committee-document/download', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => 0]) : "";
            $s = str_replace("-", "", $model->file_name, $var);
            $info = pathinfo($s);
            $view = '';
            if (isset($model->file_name)) {
                if (in_array($info['extension'], ['doc', 'docx'])) {
                    $view = '';
                } else if (in_array($info['extension'], ['pdf'])) {
                    $view = \yii\helpers\Html::a(' <i class="icon wb-zoom-in"></i>  ', ['submission-committee-document/view-file', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'view pdf file')]);
                }
            }

            return "{$downloadLink} {$view}";
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
        'template' => '{download} {upload} {delete}',
        'buttons' => [
            'download' => function($url, $model)use ($sCommitteeId) {
                return $model->getDownloadLink($sCommitteeId);
                // return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ดาวน์โหลดแบบฟอร์ม'), ['document/download-template', 'id' => $model->document_id, 'submissionId' => $model->submission_id, 'submissionComitteeId' => $sCommitteeId], ['data-pjax' => 0, 'target' => '_blank']);
            },
            'upload' => function($url, $model)use ($sCommitteeId) {
                if (isset($model->id)) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-committee-document/create', 'id' => $model->id, 'sCommitteeId' => $sCommitteeId], ['role' => 'modal-remote']);
                } else {
                    if ($model->submission->isFromCrec()) {
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-committee-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id, 'sCommitteeId' => $sCommitteeId, 'crecDocumentId' => $model->crec_document_id, 'name' => $model->name], ['role' => 'modal-remote']);
                    } else {
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-committee-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id, 'sCommitteeId' => $sCommitteeId, 'name' => $model->name], ['role' => 'modal-remote']);
                    }
                }
            },
            'delete' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', ['submission-committee-document/delete', 'id' => $model->id], ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
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
            'download' => function($model) use($currentRole) {
                // return isset($model->document->template_file);
                return ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::COMMITTEE);
            },
            'upload' => function($model) use($currentRole) {
                // return isset($model->document->template_file);
                return ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::COMMITTEE);
            },
            'delete' => function($model)use($currentRole) {
                return isset($model->id) && !isset($model->document_id) && ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::COMMITTEE);
            }
        ]
    ],
];
