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
//            $gender = isset($model->person->gender) ? Person::getGenderStatusLabels()[$model->person->gender] : "N/A";
            if (in_array($currentRole['role_id'], [\app\models\Role::STAFF, \app\models\Role::SECRETARY, \app\models\Role::ADMIN])) {
                return $model->person->i18nFullName . "<br>Mobile : " . $mobile . "<br>Email : " . $email. "<br>คุณสมบัติกรรมการ : " . $cq;
            } else {
                return $model->person->i18nFullName;
            }
        }
    ],
    ['class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.fullOrg',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.expertise',
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
        'class' => '\kartik\grid\DataColumn',
        'label' => 'จำนวนงานวิจัยที่อ่านอยู่',
        'attribute' => 'person.countSubmisionCommittee',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
//        'template' => '{delete}',
        'controller' => 'person-role',
//        'urlCreator' => function($action, $model, $key, $index) { 
//                return Url::to([$action,'id'=>$key]);
//        },
        'template' => '{select}',
        'buttons' => [
            'select' => function($url, $model) use ($submissionId, $projectId, $submission) {
                $options = ['role' => 'modal-remote', 'title' => 'เลือก', 'data-toggle' => 'tooltip'];
                return Html::a('<i class="glyphicon glyphicon-ok font-size-12"></i>', ['submission-committee/select-committees', 'id' => $model->id, 'personId' => $model->person->id, 'submissionId' => $submissionId, 'projectId' => $projectId], $options);
            },
        ],
        'visibleButtons' => [
            'select' => function($model) use ($currentRole, $submission) {
                return (($currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::SECRETARY && $submission->secretary_person == \Yii::$app->user->id) || ($currentRole['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->id)) && ($submission->status < \app\models\Submission::STATUS_AGENDA_ADDED);
            },
        ],
    ],
];
