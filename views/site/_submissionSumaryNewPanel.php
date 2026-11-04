<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$startDate = $searchModel->startDate;
$endDate = $searchModel->endDate;

$scoreValueFullboardPanel1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 1, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 1, $startDate, $endDate, 2));
$scoreValueFullboardPanel2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 2, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 2, $startDate, $endDate, 2));
$scoreValueFullboardPanel3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 3, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 3, $startDate, $endDate, 2));
$scoreValueFullboardPanel4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 4, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 4, $startDate, $endDate, 2));
$scoreValueExemption1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 1, $startDate, $endDate, 3));
$scoreValueExemption2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 2, $startDate, $endDate, 3));
$scoreValueExemption3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 3, $startDate, $endDate, 3));
$scoreValueExemption4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 4, $startDate, $endDate, 3));
$scoreValueExpedite1 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 1, $startDate, $endDate, 4));
$scoreValueExpedite2 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 2, $startDate, $endDate, 4));
$scoreValueExpedite3 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 3, $startDate, $endDate, 4));
$scoreValueExpedite4 = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, 4, $startDate, $endDate, 4));

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = $date = yii::t('app', 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรกรมการ จำแนกตามกลุ่มสาขาวิชา <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรกรมการ จำแนกตามกลุ่มสาขาวิชา (ตั้งแต่วันที่ ไม่มีการเลือกวันที่)');
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
                'text' => 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา',
            ],
            'xAxis' => [
                'categories' => ['panel 1', 'panel 2', 'panel 3','panel 4'],
            ],
            'yAxis' => [
                'title' => [
                    'text'=>'โครงการ',
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
                    'name' => 'แบบกรรมการเต็มคณะ (Full Board)',
                    'data' => [$scoreValueFullboardPanel1, $scoreValueFullboardPanel2, $scoreValueFullboardPanel3, $scoreValueFullboardPanel4],
                ],
                [
                    'type' => 'column',
                    'name' => 'เข้าข่ายการยกเว้น (Exemption)',
                    'data' => [$scoreValueExemption1, $scoreValueExemption2, $scoreValueExemption3, $scoreValueExemption4],
                ],
                [
                    'type' => 'column',
                    'name' => 'แบบเร่งด่วน (Expedited)',
                    'data' => [$scoreValueExpedite1, $scoreValueExpedite2, $scoreValueExpedite3, $scoreValueExpedite4],
                ],
            ],
        ]
    ]);
    ?>
</div>