<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
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
        'attribute' => 'person.fullNameWithEng',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mailSentLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'acknowledgeStatusLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'acknowledgeAtLabel'
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'created_at',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_by',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'updated_at',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'controller' => 'project-consultant',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{send-ack-mail} {delete}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'รายละเอียด'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'แก้ไข'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
        'buttons' => [
            'send-ack-mail' => function($url, $model) use ($submission) {
                $options = [
                    'role' => 'modal-remote', 'title' => Yii::t('app', 'ส่งอีเมลตอบรับการร่วมวิจัย'),
//                    'data-confirm' => true, 'data-method' => false, 'proceed'=>true,// for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'ยืนยันส่งอีเมลตอบรับการร่วมวิจัย',
                    'data-confirm-message' => 'ต้องการส่งอีเมลตอบรับการร่วมวิจัยใช่หรือไม่ ?',
                    'data-confirm-ok' => 'ใช่',
                    'data-confirm-cancel' => 'ไม่',
                ];
                $url = Url::to(['project-consultant/send-ack-mail', 'id' => $model->id, 'submissionId' => $submission->id]);
                return Html::a('<i class="icon md-email font-size-18"></i>', $url, $options);
            }
        ],
        'visibleButtons' => [
//            'delete' => function($model) {
//                return Yii::$app->util->checkPermission('project-consultant.delete') && !isset($model->submission->ref_submission_id);
//            },
            'send-ack-mail' => function($model) {
                return false;//Yii::$app->util->checkPermission('project-researcher.send-ack-mail') && !$model->mail_sent;
            }
        ]
    ],
];
