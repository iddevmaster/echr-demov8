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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
                'value' => function($model) {
            if (isset($model->status)) {
                return app\models\Submission::getStatusLabels()[$model->status];
            }else{
                return 'ยังไม่มีการตอบรับ';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => Yii::t('app', 'ดำเนินการโดย'),
        'attribute' => 'createdByUserProfile.fullName',
        'value' => function($model) {
            if ($model->status == \app\models\Submission::STATUS_COMMITTEE_SELECTED) {
                $secretary = isset($model->submission->secretary_person)?$model->submission->secretaryPerson->person->fullName : $model->createdByUserProfile->fullName; 
                return $secretary;
            } else {
                return $model->createdByUserProfile->fullName;
            }
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],

];   