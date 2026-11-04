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
        'attribute' => 'personRole.person.fullName',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'panel.name',
    ],
            [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'personRole.person.expertise',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'personRole.role.name',
    ],
        [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{delete}',
        'controller' => 'person-role',
//        'urlCreator' => function($action, $model, $key, $index) { 
//                return Url::to([$action,'id'=>$key]);
//        },
        'template' => '{select}',
        'buttons' => [
            'select' => function($url, $model ) use ($submissionId, $projectId) {
                $options = ['role' => 'modal-remote', 'title' => 'เลือก', 'data-toggle' => 'tooltip'];
                return Html::a('<i class="glyphicon glyphicon-arrow-down font-size-12"></i>', ['submission-committee/select-committees', 'id' => $model->id, 'personId' => $model->personRole->person->id, 'submissionId' => $submissionId, 'projectId' => $projectId], $options);
            },
        ],
        'visibleButtons' => [
            'select' => function($model) use ($currentRole) {
            return $currentRole['role_id'] != \app\models\Role::COPRESIDENT && $currentRole['role_id'] != \app\models\Role::PRESIDENT && $currentRole['role_id'] != \app\models\Role::COMMITTEE;
            },

        ],
    ],
];
