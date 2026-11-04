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
        'attribute' => 'project.name_thai',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submissionType.name',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่รับเอกสาร'),
        'format' => ['date', 'php:d/m/Y'],
        'value' => function($model) {
            return $model->getStatusDate(Submission::STATUS_SUBMITTED);
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่แจ้งเลขโครงการ'),
        'format' => ['date', 'php:d/m/Y'],
        'value' => function($model) {
            return $model->getStatusDate(Submission::STATUS_CODE_GENERATED);
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ส่งเลขา'),
        'format' => ['date', 'php:d/m/Y'],
        'value' => function($model) {
            return $model->getStatusDate(Submission::STATUS_SECRETARY_SELECTED);
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'วันที่ส่งกรรมการ'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteePersonSubmit();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'วันที่รับผลจากกรรมการ'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteePersonReturn();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่เข้าประชุม'),
        'attribute' => 'meetingAgenda.meeting.start_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่แจ้งผลการพิจารณา'),
        'attribute' => 'correspondence_at',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ส่งผลให้ผู้วิจัยแก้ไข'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteeReviseCreate();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่รับการแก้ไขจากผู้วิจัย'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteeReviseCreate();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ส่งกรรมการ'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteeReviseCreate();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่รับผลประเมิน'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getCommitteeReviseCreate();
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ออกเอกสารรับรอง'),
        'attribute' => 'certified_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ส่งใบรับรอง'),
        'attribute' => 'certified_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
];


return $items;
