<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$startDate = $searchModel->startDate;
$endDate = $searchModel->endDate;

$scoreValueProgress1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 7));
$scoreValueProgress2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 7));
$scoreValueProgress3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 7));
$scoreValueProgress4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 7));
$scoreValueRenew1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 8));
$scoreValueRenew2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 8));
$scoreValueRenew3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 8));
$scoreValueRenew4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 8));
$scoreValueAmendment1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 9));
$scoreValueAmendment2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 9));
$scoreValueAmendment3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 9));
$scoreValueAmendment4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 9));
$scoreValueSaeIn1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 10));
$scoreValueSaeIn2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 10));
$scoreValueSaeIn3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 10));
$scoreValueSaeIn4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 10));
$scoreValueSaeOut1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 11));
$scoreValueSaeOut2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 11));
$scoreValueSaeOut3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 11));
$scoreValueSaeOut4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 11));
$scoreValueDeviation1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 12));
$scoreValueDeviation2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 12));
$scoreValueDeviation3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 12));
$scoreValueDeviation4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 12));
$scoreValueClose1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 1, $startDate, $endDate, 13));
$scoreValueClose2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 2, $startDate, $endDate, 13));
$scoreValueClose3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 3, $startDate, $endDate, 13));
$scoreValueClose4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, 4, $startDate, $endDate, 13));

$scoreValueSae1 = $scoreValueSaeIn1 + $scoreValueSaeOut1;
$scoreValueSae2 = $scoreValueSaeIn2 + $scoreValueSaeOut2;
$scoreValueSae3 = $scoreValueSaeIn3 + $scoreValueSaeOut3;
$scoreValueSae4 = $scoreValueSaeIn4 + $scoreValueSaeOut4;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'โครงการวิจัยต่อเนื่องที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'โครงการวิจัยต่อเนื่องที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา (ตั้งแต่วันที่ ไม่มีการเลือกวันที่)');
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
            'chart' => [
                'height' => 500,
                'style' => [
                    'fontSize' => '13px',
                    'fontFamily' => 'Prompt',
                ],
            ],
            'title' => [
                'text' => 'โครงการวิจัยต่อเนื่องที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา',
            ],
            'xAxis' => [
                'categories' => ['panel 1', 'panel 2', 'panel 3','panel 4'],
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'โครงการ',
                ]
            ],
            'labels' => [
                'items' => [
                    [
//                        'html' => 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา',
                        'style' => [
                            'left' => '50px',
                            'top' => '18px',
                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                        ],
                    ],
                ],
            ],
            'series' => [
                [
                    'type' => 'column',
                    'name' => 'รายงานความก้าวหน้า',
                    'data' => [$scoreValueProgress1, $scoreValueProgress2, $scoreValueProgress3, $scoreValueProgress4],
                ],
                [
                    'type' => 'column',
                    'name' => 'ต่ออายุ',
                    'data' => [$scoreValueRenew1, $scoreValueRenew2, $scoreValueRenew3, $scoreValueRenew4],
                ],
                [
                    'type' => 'column',
                    'name' => 'Amendment',
                    'data' => [$scoreValueAmendment1, $scoreValueAmendment2, $scoreValueAmendment3, $scoreValueAmendment4],
                ],
                [
                    'type' => 'column',
                    'name' => 'Deviation',
                    'data' => [$scoreValueDeviation1, $scoreValueDeviation2, $scoreValueDeviation3, $scoreValueDeviation4],
                ],
                [
                    'type' => 'column',
                    'name' => 'SAE',
                    'data' => [$scoreValueSae1, $scoreValueSae2, $scoreValueSae3, $scoreValueSae4],
                ],
                [
                    'type' => 'column',
                    'name' => 'แจ้งปิด',
                    'data' => [$scoreValueClose1, $scoreValueClose2, $scoreValueClose3, $scoreValueClose4],
                ],
            ],
        ]
    ]);
    ?>
</div>