<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$startDate = $searchModel->startDate;
$endDate = $searchModel->endDate;

$scoreValueFullboard = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 1)) + intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 2));
$scoreValueExemption = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 3));
$scoreValueExpedite = intval(\Yii::$app->user->identity->getSubmissionCountReport(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->panel_id, $startDate, $endDate, 4));

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = $date = yii::t('app', 'โครงการวิจัยใหม่ที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา จำแนกตามประเภทการพิจารณา <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'โครงการวิจัยใหม่ที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา จำแนกตามประเภทการพิจารณา (ตั้งแต่วันที่ ไม่มีการเลือกวันที่)');
}
?>
<div class="panel">
    <div class="panel-heading" >
        <h3 class="panel-title"><?= $date ?><Br> <?= isset($searchModel->panel_id) ? yii::t('app', 'PANEL:') . $searchModel->panel_id : ""; ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'แบบกรรมการเต็มคณะ (Full Board)') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueFullboard; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'แบบเร่งด่วน (Expedited)') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueExemption; ?></font></div></div>
            <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
        </div>     
        <div class="row">
            <div class="col-md-6"><?= yii::t('app', 'เข้าข่ายการยกเว้น (Exemption)') ?></div>
            <div class="col-md-3 text-right"><div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div><div class="col-md-2"><font class="blue-900"><?= $scoreValueExpedite; ?></font></div></div>
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
                'text' => yii::t('app', 'โครงการวิจัยใหม่ที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา จำแนกตามประเภทการพิจารณา'),
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
                    'name' => yii::t('app', 'Full Board'),
                    'y' => $scoreValueFullboard,
                ],
                [
                    'name' => yii::t('app', 'Expedited'),
                    'y' => $scoreValueExpedite,
                ],
                [
                    'name' => yii::t('app', 'Exemption'),
                    'y' => $scoreValueExemption,
                ],
            ],
                ]],
        ]
    ]);
    ?>
</div>