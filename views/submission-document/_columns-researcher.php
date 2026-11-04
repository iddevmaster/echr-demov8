<?php

use yii\helpers\Url;
use app\models\SubmissionDocument;
use app\models\Project;

$revise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->one();
$currentRole = \Yii::$app->session->get('currentRole');

$items = [
    [
        'class' => '\kartik\grid\CheckboxColumn'
    ],
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
        'format' => 'raw',
        'value' => function($model) {
            $name = $model->i18nName;
            if ($model->is_site) {
                $name .= ' <span class="text-danger">(' . Yii::t('app', 'เอกสาร Site') . ')</span>';
            }
            if (isset($model->sd_crec_id) && $model->is_certificate == false && $model->submission->crec_resolution == app\models\Submission::RESOLUTION_Y) {
                $name .= '<br> <span class="text-info">[' . $model->isCertificate . ']</span>';
            }
            return $name;
        }
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
//            $date = " (" . Yii::$app->formatter->asDate($model->created_at) . ")";
//            $file = isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i> {$date}", ['submission-document/download', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'download file')]) : "";
//            $view = $model->viewFilePdf;
//            return $file . $view;
            return $model->fileLink;
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
if ($submission->status == \app\models\Submission::STATUS_DOC_REJECTED || $submission->status == \app\models\Submission::STATUS_DOC_REJECTED_BY_COMMITTEE) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            return isset($model->status) ? SubmissionDocument::getStatusLabels()[$model->status] : "";
        }
    ];
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'remark',
        'value' => function($model) {
            return isset($model->remark) ? $model->remark : "";
        }
    ];
}


if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR) {
    $items[] = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'controller' => 'submission-document',
//    'urlCreator' => function($action, $model, $key, $index) {
//        return Url::to([$action, 'id' => $key]);
//    },
        'template' => '{download} {upload} {delete}',
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
            'download' => function($model) use ($currentRole) {

                return (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->submission->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->submission->project_coordinator_id == \Yii::$app->user->id || $model->submission->project_coordinator_2nd_id == \Yii::$app->user->id || $model->submission->project_coordinator_3rd_id == \Yii::$app->user->id))) && isset($model->document->template_file) && $model->submission->status <= app\models\Submission::STATUS_SUBMITTED;
            },
            'upload' => function($model)use ($revise, $currentRole) {
                return (isset($model->submission->status) && ($model->submission->status < app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && $model->submission->status > app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER)) and ( isset($revise) || $model->status != SubmissionDocument::STATUS_PASS) and (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->submission->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->submission->project_coordinator_id == \Yii::$app->user->id || $model->submission->project_coordinator_2nd_id == \Yii::$app->user->id || $model->submission->project_coordinator_3rd_id == \Yii::$app->user->id)));
            },
            'delete' => function($model)use ($currentRole) {
                return !isset($model->document_id) && isset($model->submission->status) && ($model->submission->status < app\models\Submission::STATUS_SUBMITTED && $model->status == SubmissionDocument::STATUS_FAIL ) && isset($model->submission->projectLeader) && (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->submission->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->submission->project_coordinator_id == \Yii::$app->user->id || $model->submission->project_coordinator_2nd_id == \Yii::$app->user->id || $model->submission->project_coordinator_3rd_id == \Yii::$app->user->id)));
            }
        ]
    ];
}
$items = array_merge($items);
return $items;

