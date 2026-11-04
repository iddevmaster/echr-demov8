<?php

use yii\helpers\Url;

$currentRole = \Yii::$app->session->get('currentRole');

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
        'header' => Yii::t('app', 'ชื่อ-สกุล')
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'role_name',
        'format' => 'raw',
        'value' => function($model) {
            $res = $model->role_name;
            $checkRoleName = $model->checkRoleName;
            if (!empty($checkRoleName)) {
                $res .= "<span class='text-danger'> ({$checkRoleName})</span>";
            }
            return $res;
        }
//        'header' => Yii::t('app', 'ตำแหน่ง'),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'entryLogs',
        'format' => 'raw',
//        'header' => Yii::t('app', 'ตำแหน่ง'),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'coiAgendas',
        'format' => 'raw',
//        'header' => Yii::t('app', 'ตำแหน่ง'),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.reg_code',
        'format' => 'raw',
//        'header' => Yii::t('app', 'ตำแหน่ง'),
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'controller' => 'meeting-person',
//        'urlCreator' => function($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
        'template' => '{assign-staff} {assign-sec} {assign-pre} {delete}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
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
            'assign-staff' => function($url, $model) {
//                \yii\helpers\VarDumper::dump($model->meeting->allInspectorIds);
//                \yii\helpers\VarDumper::dump(in_array($model->person->user_id, $model->meeting->allInspectorIds));
//                echo '<br>';
                $title = Yii::t('app', 'กำหนดเจ้าหน้าที่ตรวจสอบรายงานการประชุม');
                return \yii\helpers\Html::a('<i class="icon md-assignment-check font-size-18"></i>',
                                ['meeting-person/assign-staff', 'id' => $model->id],
                                ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => $title, 'class' => '',
                                    'data-confirm-title' => $title,
                                    'data-confirm-message' => Yii::t('app', 'ยืนยันการกำหนดเจ้าหน้าที่ตรวจสอบรายงานการประชุมใช่หรือไม่ ?'),
                                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                    'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                ]);
            },
            'assign-pre' => function($url, $model) {
//                \yii\helpers\VarDumper::dump($model->meeting->allInspectorIds);
//                \yii\helpers\VarDumper::dump(in_array($model->person->user_id, $model->meeting->allInspectorIds));
//                echo '<br>';
                $title = Yii::t('app', 'กำหนดประธานตรวจสอบรายงานการประชุม');
                return \yii\helpers\Html::a('<i class="icon md-parking font-size-18"></i>',
                                ['meeting-person/assign-pre', 'id' => $model->id],
                                ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => $title, 'class' => '',
                                    'data-confirm-title' => $title,
                                    'data-confirm-message' => Yii::t('app', 'ยืนยันการกำหนดประธานตรวจสอบรายงานการประชุมใช่หรือไม่ ?'),
                                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                    'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                ]);
            },
            'assign-sec' => function($url, $model) {
                $icon = 'md-n-1-square';
                $title = Yii::t('app', 'กำหนดเลขาตรวจสอบรายงานการประชุม ท่านที่ 1');
                $confirm = Yii::t('app', 'ยืนยันการกำหนดเลขาตรวจสอบรายงานการประชุม ท่านที่ 1 ใช่หรือไม่ ?');
                if (isset($model->meeting->checked_secretary_first)) {
                    $icon = 'md-n-2-square';
                    $title = Yii::t('app', 'กำหนดเลขาตรวจสอบรายงานการประชุม ท่านที่ 2');
                    $confirm = Yii::t('app', 'ยืนยันการกำหนดเลขาตรวจสอบรายงานการประชุม ท่านที่ 2 ใช่หรือไม่ ?');
                }
                return \yii\helpers\Html::a('<i class="icon ' . $icon . ' font-size-18"></i>',
                                ['meeting-person/assign-sec', 'id' => $model->id],
                                ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => $title, 'class' => '',
                                    'data-confirm-title' => $title,
                                    'data-confirm-message' => $confirm,
                                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                    'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                ]);
            },
        ],
        'visibleButtons' => [
            'assign-staff' => function($model) use ($searchModel, $currentRole) {
//                return true;
                return ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) && (!isset($searchModel->meeting->checked_staff) && $model->person->getHasRolePanel(\app\models\Role::STAFF, $model->meeting->panel_id) && !in_array($model->person->user_id, $model->meeting->allInspectorIds));
            },
            'assign-sec' => function($model) use ($searchModel, $currentRole) {
                return  ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) &&  ((!isset($searchModel->meeting->checked_secretary_first) && $model->person->getHasRolePanel(\app\models\Role::SECRETARY, $model->meeting->panel_id) && !in_array($model->person->user_id, $model->meeting->allInspectorIds)
                        ) || (!isset($model->meeting->checked_secretary_second) && !$model->meeting->hasAllCheckingSecretaries && !in_array($model->person->user_id, $model->meeting->allInspectorIds) && $model->person->getHasRolePanel(\app\models\Role::SECRETARY, $model->meeting->panel_id)));
            },
            'assign-pre' => function($model) use ($searchModel, $currentRole) {
//                return true;
                return ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) &&  (!isset($searchModel->meeting->checked_president) && ($model->person->getHasRolePanel(\app\models\Role::PRESIDENT, $model->meeting->panel_id) || $model->person->getHasRolePanel(\app\models\Role::COPRESIDENT, $model->meeting->panel_id)) && !in_array($model->person->user_id, $model->meeting->allInspectorIds));
            },
            'delete' => function($model) use ($searchModel, $currentRole) {
//                return true;
                return ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN);
            }
        ]
    ],
];
