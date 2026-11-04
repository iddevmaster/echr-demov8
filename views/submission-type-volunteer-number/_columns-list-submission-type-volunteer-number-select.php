<?php

use yii\helpers\Url;
use app\models\Document;

return [
        [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'volunteerNumber.name',
    ],
        [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{delete}',
        'controller' => 'submission-type-volunteer-number',
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
    ],
];
