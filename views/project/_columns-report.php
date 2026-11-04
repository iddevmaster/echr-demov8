<?php

use yii\helpers\Url;

return [

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
        'attribute' => 'project_code',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label'=>'หัวหน้าโครงการ',
         'width'=>'15%',
        'attribute' => 'projectLeader.person.fullName',
        'value' => function($model) {
            if (isset($model->projectLeader)) {
                return $model->projectLeader->person->i18nFullName . "<br>คณะ : " . (isset($model->projectLeader->person->department_id) ? $model->projectLeader->person->department->i18nName : "N/A") . "<br>Mobile : " . $model->projectLeader->person->mobile_no;
            } else {
                return '';
            }
        }
    ],
           
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
        'attribute' => $date,
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

];
