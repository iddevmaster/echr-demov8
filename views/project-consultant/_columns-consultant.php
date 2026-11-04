<?php

use yii\helpers\Url;
use yii\helpers\Html;

$currentRole = \Yii::$app->session->get('currentRole');

$items = [
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
        'attribute' => 'acknowledgeStatusLabel',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.cv_file',
        'format' => 'raw',
        'value' => function($model) {
            if (isset($model->person->cvFileUrl)) {
                return Html::a('<i class="glyphicon glyphicon-download" data-toggle="tooltip" ></i> ' . Yii::t('app', " ไฟล์ประวัติ "), 
                    // $model->person->cvFileUrl, 
                    Url::to(['person/download', 'id' => $model->person_id]), 
                    ['target' => '_blank', 'data-pjax' => 0]) . Html::a('<i class="glyphicon glyphicon-user" data-toggle="tooltip" ></i> ' . Yii::t('app', " การอบรม "), ['person-training/show', 'personId' => $model->person_id], ['role' => 'modal-remote',]);
            }else{
                return yii::t('app', "ไม่มีไฟล์ประวัติ");
            }
        },
    ],
];

$items1 = array_merge($items, [
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'noWrap' => true,
            'controller' => 'project-researcher',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
            'template' => '{accept}',
            'buttons' => [
                'accept' => function($url, $model) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับร่วมโครงการ'), ['project-consultant/accept', 'id' => $model->submission_id, 'personId' => $model->person_id], ['role' => 'modal-remote', 'title' => 'ตอบรับร่วมโครงการ',
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                                'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
                },
            ],
            'visibleButtons' => [
                'accept' => function($model)use ($currentRole, $submission) {
                    if ($currentRole['role_id'] == \app\models\Role::RESEARCHER) {
                        return $model->person_id == \Yii::$app->user->identity->person->id && $model->acknowledge_status == 100 ;
                    } elseif ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) {
                        return $model->acknowledge_status == 100 ;
                    }

                }
            ],
        ],
    ]);
    return $items1;

