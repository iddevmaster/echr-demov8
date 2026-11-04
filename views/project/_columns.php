<?php

use yii\helpers\Url;

return [
        [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
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
        'attribute' => 'name_thai',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name_eng',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'start_date',
        'format' => ['date', 'php:d/m/Y']
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'end_date',
//    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'funding_source_id',
//    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'funding_source_description',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'is_child_project',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'progress_period',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'remark',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'certified_date',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'status',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'project_code',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'panel_id',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'organization_id',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'deleted',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'created_by',
    // ],
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
        'noWrap' => true,
        'dropdown' => false,
        //'vAlign' => 'middle',
        'hAlign' => 'center',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view}{ckdoc}{ckperson}{number} ',

        'buttons' => [
            'ckdoc' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-check"></i>', ['submission-document/view?id=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'target' => '_blank', 'title' => 'ตรวจสอบเอกสาร', 'class' => 'btn btn-info btn-raised']);
            },
            'ckperson' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-user"></i>', ['submission-committee/view?id=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'กำหนดกรรมการ', 'class' => 'btn btn-success btn-raised']);
            },
            'view' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-list"></i>', ['site/project-submission'], ['data-toggle' => 'tooltip', 'title' => 'แสดงข้อมูลงานวิจัย', 'class' => 'btn btn-danger btn-raised']);
            },
            'number' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i>', ['project/he-number?id=' . $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => 'Genarate HE', 'class' => 'btn btn-primary btn-raised']);
            },
        ]
    ],
];
