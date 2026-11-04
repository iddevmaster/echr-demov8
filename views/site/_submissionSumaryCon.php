<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$startDate = $searchModel->startDate;
$endDate = $searchModel->endDate;

$scoreValueProgress = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 7));
$scoreValueRenew = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 8));
$scoreValueAmendment = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 9));
$scoreValueSaeIn = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 10));
$scoreValueSaeOut = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 11));
$scoreValueDeviation = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 12));
$scoreValueClose = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_CONT, $searchModel->panel_id, $startDate, $endDate, 13));

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = $date = yii::t('app', 'โครงการวิจัยต่อเนื่องที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'โครงการวิจัยต่อเนื่องที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา (ตั้งแต่วันที่ ไม่มีการเลือกวันที่)');
}
?>
<div class="panel">
    <div class="panel-heading" >
        <h3 class="panel-title"><?= $date ?><Br> <?= isset($searchModel->panel_id) ? yii::t('app', 'PANEL:') . $searchModel->panel_id : ""; ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'แจ้งปิด') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueClose; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'รายงานความก้าวหน้า') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueProgress; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div>     
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'ต่ออายุ') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueRenew; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div> 
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'Amendment') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueAmendment; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div> 
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'Deviation') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueDeviation; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div> 
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'SAE') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueSaeIn+$scoreValueSaeOut; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div> 
    </div>
    <div class="panel-footer">
    </div>
</div>
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
                'text' => yii::t('app', 'โครงการวิจัยต่อเนื่องที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา'),
            ],
            'plotOptions' => [
                'pie' => [
                    'cursor' => 'pointer',
                    'allowPointSelect' => TRUE,
                    'dataLabels' => [
                        'enabled' => TRUE,
                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'style' => [
                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                        ]
                    ]
                ],
            ],
            'series' => [[
            'type' => 'pie',
            'name' => yii::t('app', 'จำนวน'),
            'data' => [
                [
                    'name' => yii::t('app', 'แจ้งปิด'),
                    'y' => $scoreValueClose,
                ],
                [
                    'name' => yii::t('app', 'รายงานความก้าวหน้า'),
                    'y' => $scoreValueProgress,
                ],
                [
                    'name' => yii::t('app', 'ต่ออายุ'),
                    'y' => $scoreValueRenew,
                ],
                                [
                    'name' => yii::t('app', 'Amendment'),
                    'y' => $scoreValueAmendment,
                ],
                                [
                    'name' => yii::t('app', 'Deviation'),
                    'y' => $scoreValueDeviation,
                ],
                                [
                    'name' => yii::t('app', 'SAE'),
                    'y' => $scoreValueSaeIn+$scoreValueSaeOut,
                ],
            ],
                ]],
        ]
    ]);
    ?>
</div>