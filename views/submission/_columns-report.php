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
        'attribute' => 'typeAndRef',
    ],
//        [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'projectLeader.person.fullName',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'ผู้ร่วมวิจัย'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getResearcherNamesList();
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'file_name',
        'header' => Yii::t('app', 'จำนวนอาสาสมัคร'),
        'format' => 'raw',
        'value' => function($model) {
            return $model->getVolunteerNumber();
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            if ($model->status) {
                if ($model->status == Submission::STATUS_COMMITTEE_ACCEPTED) {
                    $count = $model->getSubmissionCommittees()->isDeleted(false)->status(SubmissionCommittee::STATUS_ACCEPTED)->count();
                    $message = " ({$count} " . Yii::t('app', 'คน') . ")";
                    if ($count == 0) {
                        $message = Yii::t('app', ' (กรรมการส่งครบแล้ว)');
                    }
                    return Submission::getStatusLabels()[$model->status] . $message;
                } else {
                    return Submission::getStatusLabels()[$model->status];
                }
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'resolution',
        'value' => function($model) {
            if ($model->resolution != NULL) {
                return Submission::getResolutionLables()[$model->resolution];
            } else {
                return Yii::t('app', 'ยังไม่มีมติที่ประชุม');
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วาระ'),
        'attribute' => 'meetingAgenda.sort_label',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'ครั้งที่ประชุม'),
        'attribute' => 'meetingAgenda.meeting.yearNo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ประชุม'),
        'attribute' => 'meetingAgenda.meeting.start_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'วันที่ออกเอกสารรับรอง'),
        'attribute' => 'certified_date',
        'format' => ['date', 'php:d/m/Y'],
        'filter' => FALSE,
    ],
];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'responsiblePerson.person.fullName',
//    'value' => function($model) {
//        if ($model->responsible_person != NULL) {
//            return $model->responsiblePerson->person->fullName;
//        } else {
//            return 'ยังไม่กำหนดเจ้าหน้าที่';
//        }
//    }
//];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'resolution',
//    'value' => function($model) {
//        if ($model->resolution != NULL) {
//            return Submission::getResolutionLables()[$model->resolution];
//        } else {
//            return 'ยังไม่มีมติที่ประชุม';
//        }
//    }
//];
//if ((isset($resolution)) and ( $currentRole['role_id'] == app\models\Role::ADMIN or $currentRole['role_id'] == app\models\Role::STAFF)) {
//
//    $items[] = [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'letterAgenda',
//        'format' => 'raw',
//    ];
//}

return $items;
