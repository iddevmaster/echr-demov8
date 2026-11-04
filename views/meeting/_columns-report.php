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
        'attribute' => 'panel.name',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'yearNo',
    ],
        [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tranMeeting',
        'format' => 'raw',
        'value' => function($model) {
            $transMeeting = $model->getTranMeeting(Yii::$app->user->identity->person->id);
            return $transMeeting;
        }
    ],
        [
        'class' => '\kartik\grid\DataColumn',
//        'format' => ['date', 'php:d/m/Y'],
        'label' => 'จำนวนชั่วโมง (ชั่วโมง : นาที)',
        'attribute' => 'hourMeeting',
        'value' => function($model) {
//            \yii\helpers\VarDumper::dump($model->getTotalInterval(Yii::$app->user->identity->person->id));
            $interval = $model->getTotalInterval(Yii::$app->user->identity->person->id);
//            \yii\helpers\VarDumper::dump($interval, 10, TRUE);
//            exit;
            return Yii::$app->util->getMeetingIntervalFormat($interval);
        },
        'pageSummary' => function($summary, $data, $widget) {
            $th = 0;
            $tm = 0;
            foreach ($data as $d) {
                $int = explode(':', $d);
                $th += intval($int[0]);
                $tm += intval($int[1]);
            }
            $tm += $th * 60;
            return Yii::$app->util->getHourMinuteFormat($tm);
        },
    ],
];
