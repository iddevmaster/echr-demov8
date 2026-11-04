<?php

use yii\helpers\Url;
use app\models\SubmissionDocument;
use app\models\Project;
use kartik\grid\GridView;
use yii\helpers\Html;

$currentRole = \Yii::$app->session->get('currentRole');

$items = [
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'rowSelectedClass' => GridView::TYPE_ACTIVE,
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
            $name = $model->name;
            $nameEng = isset($model->name_eng) ? '<br>' . $model->name_eng : "";
            if ($model->is_site) {
                $name .= ' <span class="text-danger">(' . Yii::t('app', 'เอกสาร Site') . ')</span>';
            }
            if (isset($model->sd_crec_id) && $model->is_certificate == false && $model->submission->crec_resolution == app\models\Submission::RESOLUTION_Y) {
                $name .= '<br> <span class="text-info">[' . $model->isCertificate . ']</span>';
            }
            return $name . $nameEng;
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
            // $date = " (" . Yii::$app->formatter->asDate($model->created_at) . ")";
            // $file = isset($model->file_name) ? \yii\helpers\Html::a("<i class='font-size-20 {$model->fileIconClass}'></i> {$date}", ['submission-document/download', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'download file')]) : "";
            // $view = $model->viewFilePdf ;
            // return $file . $view;
            return $model->fileLink;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'groupDoc.name',
        'format' => 'raw',
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'remark',
        'format' => 'raw',
    ],
];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'status',
    'value' => function($model) {
        if (isset($model->status)) {
            return SubmissionDocument::getStatusLabels()[$model->status];
        } else {
            return "ยังไม่ตรวจสอบ";
        }
    }
];


if ($submission->status != NULL) {
    $items[] = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'controller' => 'submission-document',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{check} {upload} {edit} {select-cer} {delete}',
        'buttons' => [
            'check' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-check" data-toggle="tooltip" ></i> ' . Yii::t('app', 'ตรวจสอบ'), ['submission-document/check-document', 'id' => $model->id], ['role' => 'modal-remote',]) . '<br>';
            },
            'select-cer' => function ($url, $model) use ($currentRole) {
                $color = $model->is_certificate ? 'red-600' : 'blue-600';
                $newLabel = \app\models\SubmissionDocument::getStatusLabelCer()[!$model->is_certificate];
                $icon = '';
                if ($model->is_certificate == 0) {
                    $icon = 'glyphicon glyphicon-ok';
                } else {
                    $icon = 'glyphicon glyphicon-remove';
                }
                if ((($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF) && ($model->submission->status <= app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)) || (($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR) && ($model->submission->status < \app\models\Submission::STATUS_SUBMITTED))) {
                    return Html::a("<i class='{$icon}' data-toggle='tooltip' ></i> " . $model->isCertificate, ['submission-document/select-certificate', 'submissionId' => $model->submission_id, 'id' => $model->id], [
                                'class' => $color,
                                'role' => 'modal-remote',
//                            'data-pjax' => 0,
                                'data-toggle' => 'tooltip'
                            ]) . '<br>';
                } else {
//                    return Html::a("<i class='{$icon}' data-toggle='tooltip' ></i> " . $model->isCertificate, ['submission-document/select-certificate', 'submissionId' => $model->submission_id, 'id' => $model->id, 'reloadUrl' => "#{$id}-pjax"], [
//                                'class' => $color,
//                                'role' => 'modal-remote',
////                            'data-pjax' => 0,
//                                'data-toggle' => 'tooltip'
//                            ]) . '<br>';
                    return "<i class='{$icon} $color' data-toggle='tooltip'></i> " . $model->isCertificate . '<br>';
                }
            },
            'upload' => function($url, $model) {
                if (isset($model->id)) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'id' => $model->id], ['role' => 'modal-remote']) . '<br>';
                } else {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload" data-toggle="tooltip" ></i> ' . Yii::t('app', 'อับโหลดไฟล์'), ['submission-document/create', 'submissionId' => $model->submission_id, 'documentId' => $model->document_id], ['role' => 'modal-remote']) . '<br>';
                }
            },
            'edit' => function ($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil" data-toggle="tooltip" ></i> ' . Yii::t('app', 'แก้ไขชื่อและกลุ่ม'), ['submission-document/edit', 'id' => $model->id, 'reloadUrl' => "#{$model->id}-pjax"], ['role' => 'modal-remote',]) . '<br>';
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
            'check' => function($model)use ($currentRole) {
                return $model->isAbleToCheck();
            },
            'upload' => function($model)use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::STAFF && !isset($model->sd_crec_id));
            },
            'edit' => function ($model) use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF || (($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR) && ($model->submission->status < app\models\Submission::STATUS_SUBMITTED)));
            },
            'delete' => function($model)use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF) && !isset($model->document_id);
            },
        ],
    ];
}
$items = array_merge($items);
return $items;

