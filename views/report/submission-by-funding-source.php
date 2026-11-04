<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'จำนวนโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาแยกตามแหล่งทุน');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'จำนวนโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาแยกตามแหล่งทุน <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'จำนวนโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาแยกตามแหล่งทุน');
}
$series = [];
?>

<div class="submission-index ">
    <?php
    echo $this->renderFile('@app/views/report/_search-date.php', ['searchModel' => $searchModel]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $date ?></h3>
                </div>
                <div class="panel-body">
                    <?php foreach ($data as $d): 
                        $series[] = [
                            'name' => $d['name'],
                            'y' => floatval($d['c']),
                        ];
                        ?>
                    <div class="row">
                        <div class="col-md-6"><?= $d['name'] ?></div>
                        <div class="col-md-3 text-right">
                            <div class="col-md-8 text-right"><?= yii::t('app', 'จำนวน ') ?> </div>
                            <div class="col-md-2">
                                <font class="blue-900"><?= number_format($d['c']); ?></font>
                            </div>
                        </div>
                        <div class="col-md-3 text-left"><?= yii::t('app', 'โครงการ') ?></div>
                    </div>
                    <?php endforeach; ?>
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
                            'data' => $series,
                        ]],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>