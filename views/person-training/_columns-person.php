<?php

use yii\helpers\Url;

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
        'attribute' => 'training_type_id',
        'value' => function($model) {
            return isset($model->trainingType) ? $model->trainingType->name : '';
        },
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
            return \yii\helpers\Html::tag('span', $text, ['class' => $cls]);
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
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
    // [
// 'class'=>'\kartik\grid\DataColumn',
// 'attribute'=>'updated_at',
// ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'controller' => 'person-training',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{esign-person} {update-person} {delete-person}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
        'buttons' => [
            'esign-person' => function($url, $model) {
                if (empty($model->file)) {
                    return '';
                }
                $label = '<i class="glyphicon glyphicon-pencil"></i>';
                if (!empty($model->id)) {
                    return \yii\helpers\Html::a($label,
                                    ['person/esign', 'personId' => $model->person_id, 'docType' => \app\models\PersonDocumentAudit::DOC_TYPE_TRAINING, 'refId' => $model->id],
                                    ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', 'ลงนามอิเล็กทรอนิกส์')]);
                }
                return '';
            },
            'update-person' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ', ['person-training/update', 'id' => $model->id,'reload'=>true], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'แก้ไข']);
            },    
                                'delete-person' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i> ', ['person-training/delete', 'id' => $model->id,'reload'=>true], ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
            },
        ],
    ],
];
