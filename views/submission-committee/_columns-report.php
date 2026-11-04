<?php

use yii\helpers\Url;
use yii\helpers\Html;

$currentRole = Yii::$app->session->get('currentRole');

return [
        [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.fullName',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.meetingPersons.role_name',
        'header' => Yii::t('app', 'ตำแหน่ง'),
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'หมายเหตุ'),
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'format' => ['decimal', 2],
        'attribute' => 'submission.submissionType.payment',
        'header' => Yii::t('app', 'ค่าตอบแทน'),
        'pageSummary' => TRUE,
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'header' => Yii::t('app', 'ลายมือชื่อผู้อ่าน'),
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.bank_account',
        'header' => Yii::t('app', 'ธนาคาร'),
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.bank_account_number',
        'header' => Yii::t('app', 'เลขบัญชี'),
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'person.bank_branch',
        'header' => Yii::t('app', 'สาขา'),
    ],
];
