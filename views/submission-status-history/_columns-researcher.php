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
        'class' => '\kartik\grid\DataColumn',
        'label' => Yii::t('app', 'ประวัติการดำเนินงาน'),
        'attribute' => 'status',
        'value' => function($model) {
            if (isset($model->status)) {
                if ($model->submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT || ($model->submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW && $model->submission->submission_type_id == 5)) {
                    if ($model->submission->is_submit_by_api == 1) {
                        return app\models\Submission::getStatusLabelsResearcherContinueCrecC()[$model->status];
                    } else {
                        return app\models\Submission::getStatusLabelsResearcherContinueC()[$model->status];
                    }
                } else {
                    if ($model->submission->is_submit_by_api == 1) {
                        return app\models\Submission::getStatusLabelsResearcherCrec()[$model->status];
                    } else {
                        return app\models\Submission::getStatusLabelsResearcher()[$model->status];
                    }
                }
            } else {
                return 'ยังไม่มีการตอบรับ';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            if (isset($model->status)) {
                if ($model->submission->is_submit_by_api == 1) {
                    return app\models\Submission::getStatusLabelsCrec()[$model->status];
                } else {
                    return app\models\Submission::getStatusLabels()[$model->status];
                }
            } else {
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
        'label' => Yii::t('app', 'วันที่ดำเนินการ'),
        'attribute' => 'created_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label' => Yii::t('app', 'หมายเหตุ'),
        'attribute' => 'submission.remark_checkdoc_staff',
        'value' => function($model) {
            if ($model->status == \app\models\Submission::STATUS_DOC_REJECTED) {
                return $model->remark_checkdoc_staff;
            } else {
                return '';
            }
        }
    ],
];
