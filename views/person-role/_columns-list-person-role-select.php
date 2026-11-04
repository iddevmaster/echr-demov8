<?php

use yii\helpers\Url;
use app\models\Person;
use yii\helpers\Html;
$currentRole = \Yii::$app->session->get('currentRole');

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.fullName',
        'format' => 'raw',
        'value' => function($model) use ($currentRole) {
            $mobile = isset($model->person->mobile_no) ? $model->person->mobile_no : "N/A";
            $email = isset($model->person->email) ? $model->person->email : "N/A";
            $cq = isset($model->person->committee_qualification_id) ? $model->person->committeeQualification->name : "N/A";
            $gender = isset($model->person->gender) ? Person::getGenderStatusLabels()[$model->person->gender] : "N/A";
            if (in_array($currentRole['role_id'], [\app\models\Role::STAFF, \app\models\Role::SECRETARY, \app\models\Role::ADMIN])) {
                return $model->person->i18nFullName . "<br>คุณสมบัติกรรมการ : " . $cq. "<br>เพศ : " . $gender;
            } else {
                return $model->person->i18nFullName;
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'panelNames',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.reg_code',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{delete} {update} {signature}',
        'controller' => 'person-role',
//        'urlCreator' => function($action, $model, $key, $index) { 
//                return Url::to([$action,'id'=>$key]);
//        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'ลบ',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
        ],
        'buttons' => [
            'change' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit" data-toggle="tooltip" ></i> ', ['submission/change-responsible-all', 'id' => $model->person->user_id], ['role' => 'modal-remote']);
            },
            'signature' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit" data-toggle="tooltip" ></i> ', ['person/signature', 'personId' => $model->person_id], ['role' => 'modal-remote']);
            },
        ],
        'visibleButtons' => [
            'signature' => function($model)use ($currentRole) {
                return $model->role_id == \app\models\Role::PRESIDENT || $model->role_id == \app\models\Role::SECRETARY || $model->role_id == \app\models\Role::STAFF || $model->role_id == \app\models\Role::COPRESIDENT || $model->role_id == \app\models\Role::ADMIN;
            },
        ],
    ],
];
