<?php

use app\models\SubmissionCommittee;
use yii\helpers\Url;
use app\models\Submission;

$sCommitteeId = SubmissionCommittee::find()->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->submission($searchModel->id)->andWhere(['deleted' => 0])->all();

$currentRole = \Yii::$app->session->get('currentRole');
$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.project_code',
        'value' => function($model) {
            if (isset($model->project->project_code)) {
                $codes = $model->project->projectCodeHistoriesHtml;
                return $model->project->project_code . (empty($codes) ? '' : "({$codes})");
            } else {
                return Yii::t('app', 'N/A');
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'projectLeader.person.fullName',
        'value' => function($model) {
            if (isset($model->projectLeader)) {
                return $model->projectLeader->person->i18nFullName;
            } else {
                return '';
            }
        }
    ],
            [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'header' => Yii::t('app', 'สังกัด'),
        'value' => function($model) {
            if (isset($model->projectLeader)) {
                return (isset($model->projectLeader->person->division_id) ? $model->projectLeader->person->division->i18nName : (
                        isset($model->projectLeader->person->department_id) ? $model->projectLeader->person->department->i18nName : "N/A"));
            } else {
                return '';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.fundingSource.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'meetingAgenda.sort_label',
        'header' => 'วาระ',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submitAt',
        'format' => ['dateTime', 'php:d/m/Y']
    ],
];
return $items;
