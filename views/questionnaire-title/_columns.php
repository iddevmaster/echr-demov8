<?php

use yii\helpers\Url;

return [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'order',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submissionType.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'questionnaire_type',
        'value' => function($model) {
            if (isset($model->questionnaire_type)) {
                return app\models\QuestionnaireTitle::getTypeLabels()[$model->questionnaire_type];
            } else {
                return 'ไม่กำหนด';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updatedByUserProfile.fullName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    // [
// 'class'=>'\kartik\grid\DataColumn',
// 'attribute'=>'updated_at',
// ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{update} {delete}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['label' => '<i class="icon md-edit font-size-18"></i>', 'data-pjax' => 0, 'title' => 'แก้ไข', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
//                        'buttons' => [
//            'select' => function($url, $model ) use ($submissionId, $projectId) {
//                $options = ['role' => 'modal-remote', 'title' => 'เลือก', 'data-toggle' => 'tooltip'];
//                return Html::a('<i class="icon md-arrow-right font-size-18"></i>', ['submission-committee/select-committees', 'id' => $model->id, 'personId' => $model->personRole->person->id, 'submissionId' => $submissionId, 'projectId' => $projectId], $options);
//            },
//        ],
    ],
];
