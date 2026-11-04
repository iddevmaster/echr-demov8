<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;

$startDate = $searchModel->startDate;
$endDate = $searchModel->endDate;

//$scoreValueFullboard = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 2));
//$scoreValueExemption = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 3));
//$scoreValueExpedite = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 4));
$months = Yii::$app->util->getMonthNames();
$submissionTypes = SubmissionType::find()->isDeleted(false)->group(SubmissionTypeGroup::GROUP_CONT)->internal(false)->all();
$records = [];
foreach ($submissionTypes as $index => $type) {
    $records[$index] = [
        'name' => $type->i18nName,
        'data' => [],
    ];
    foreach ($months as $month => $monthName) {
        $records[$index]['data'][] = intval(Yii::$app->user->identity->getMonthlySubmissionCountReport($searchModel->statusYear, $month, $searchModel->panel_id, $type->id));
    }
}
//yii\helpers\VarDumper::dump($records, 10, TRUE);
//exit;

$title = yii::t('app', 'สรุปจำนวนโครงการวิจัยต่อเนื่องที่เสนอขอรับการรับรองจากที่ประชุมคณะกรรมการฯ จำแนกตามเดือนและประจำสาขาวิชา <Br>');
if (!empty($searchModel->statusYear)) {
    $title .= 'ประจำปี ' . $searchModel->statusYear;
}if (!empty($searchModel->panel_id)) {
    $title .= ' สาขาวิชา ' . $searchModel->panel_id;
}
?>
<div class="panel">

    <?php
    echo Highcharts::widget([
        'scripts' => [
            'modules/exporting',
            'themes/grid-light',
        ],
        'options' => [
            'credits' => [
                'enabled' => FALSE
            ],
            'chart' => [
                'height' => 500,
                'style' => [
                    'fontSize' => '13px',
                    'fontFamily' => 'Prompt',
                ],
            ],
            'title' => [
                'text' => $title,
            ],
            'xAxis' => [
                'categories' => array_values($months),
            ],
            'yAxis' => [
                'title' => [
                    'text' => Yii::t('app', 'โครงการ')
                ],
            ],
//            'plotOptions' => [
//                'pie' => [
//                    'cursor' => 'pointer',
//                    'allowPointSelect' => TRUE,
//                    'dataLabels' => [
//                        'enabled' => TRUE,
//                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
//                        'style' => [
//                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
//                        ]
//                    ]
//                ],
//            ],
            'series' => $records,
        ]
    ]);
    ?>
</div>