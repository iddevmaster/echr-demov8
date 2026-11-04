<?php

use app\models\Agenda;
use app\models\Panel;
use app\models\SubmissionTypeGroup;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)');
}
$series = [];
$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = Panel::find()->isDeleted(false)->orderBy('id')->all();
$countAgendas = [];

$iData = array_filter($data, function ($v, $k) {
    return $v['submission_type_group_id'] == SubmissionTypeGroup::GROUP_NEW;
}, ARRAY_FILTER_USE_BOTH);
$cData = array_filter($data, function ($v, $k) {
    return $v['submission_type_group_id'] == SubmissionTypeGroup::GROUP_CONT;
}, ARRAY_FILTER_USE_BOTH);
$eData = array_filter($data, function ($v, $k) {
    return $v['is_fullboard'] == 0;
}, ARRAY_FILTER_USE_BOTH);
$fData = array_filter($data, function ($v, $k) {
    return $v['is_fullboard'] == 1;
}, ARRAY_FILTER_USE_BOTH);
$initial = array_sum(ArrayHelper::getColumn($iData, 'c'));
$continuing = array_sum(ArrayHelper::getColumn($cData, 'c'));
$expedited = array_sum(ArrayHelper::getColumn($eData, 'c'));
$fullboard = array_sum(ArrayHelper::getColumn($fData, 'c'));

$eAgendas = Agenda::getExpeditedAgendas();
$fAgendas = Agenda::getFullboardAgendas();
$iAgendas = Agenda::getInitialAgendas();
$cAgendas = Agenda::getContinueAgendas();

$agendas = array_merge($eAgendas, $fAgendas, $iAgendas, $cAgendas);
foreach ($agendas as $a) {
    $aData = array_filter($data, function ($v, $k) use ($a) {
        return $v['agenda_label'] == $a;
    }, ARRAY_FILTER_USE_BOTH);
    $countAgendas[$a] = array_sum(ArrayHelper::getColumn($aData, 'c'));
}

$agendaColors = [];
$colors = Agenda::getAgendaColor();
foreach ($eAgendas as $a) {
    $agendaColors[] = $colors['expedited'];
}
foreach ($fAgendas as $a) {
    $agendaColors[] = $colors['fullboard'];
}
foreach ($iAgendas as $a) {
    $agendaColors[] = $colors['initial'];
}
foreach ($cAgendas as $a) {
    $agendaColors[] = $colors['continue'];
}

$series1 = [[
    // 'type' => 'column',
    'name' => Yii::t('app', 'โครงการ'),
    'data' => array_values($countAgendas),
    'colors' => $agendaColors,
    'colorByPoint' => true,
]];

$countPanels = [];
$donutPanelSeries = [];
$donutPanelSeriesData = [];
$barPanelSeries = [];
foreach ($panels as $p) {
    $pData = array_filter($data, function ($v, $k) use ($p) {
        return $v['panel_id'] == $p->id;
    }, ARRAY_FILTER_USE_BOTH);
    $countPanels[$p->id] = array_sum(ArrayHelper::getColumn($pData, 'c'));
    $donutPanelSeriesData[] = [
        'name' => $p->name_eng,
        'y' => floatval($countPanels[$p->id]),
    ];
}
$donutPanelSeries = [
    [
        'type' => 'pie',
        'innerSize' => '50%',
        'name' => yii::t('app', 'จำนวน'),
        // 'colorByPoint' => true,
        // 'colors' => [
        //     $colors['expedited'],
        //     $colors['fullboard'],
        // ],
        'data' => $donutPanelSeriesData,
    ]
];
$barPanelSeries = [[
    // 'type' => 'column',
    'name' => Yii::t('app', 'โครงการ'),
    'data' => array_values($countPanels),
    // 'colors' => $agendaColors,
    // 'colorByPoint' => true,
]];
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
                    <div class="row">
                        <div class="col-lg-2">
                            <table class="table table-bordered table-condensed table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center">SUM</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center"><?= number_format(array_sum(ArrayHelper::getColumn($data, 'c'))); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Initial</td>
                                        <td class="text-center">Continuing</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><?= number_format($initial) ?></td>
                                        <td class="text-center"><?= number_format($continuing) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Expedited</td>
                                        <td class="text-center">Fullboard</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><?= number_format($expedited) ?></td>
                                        <td class="text-center"><?= number_format($fullboard) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Agenda</td>
                                        <td class="text-center">Count</td>
                                    </tr>
                                    <?php foreach ($countAgendas as $a => $c): ?>
                                        <tr>
                                            <td class="text-center"><?= $a ?></td>
                                            <td class="text-center"><?= number_format($c) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-4">
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
                                        'type' => 'bar',
                                        'height' => 800,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => [
                                        'text' => Yii::t('app', 'Agenda'),
                                    ],
                                    'xAxis' => [
                                        'categories' => array_keys($countAgendas),
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'โครงการ')
                                        ],
                                        'labels' => [
                                            'overflow' => 'justify'
                                        ],
                                    ],
                                    'plotOptions' => [
                                        'bar' => [
                                            'dataLabels' => [
                                                'enabled' => true
                                            ],
                                        ]
                                    ],
                                    'series' => $series1
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3">
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
                                        'height' => 400,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => [
                                        'text' => yii::t('app', 'Expedited vs. Fullboard'),
                                    ],
                                    'plotOptions' => [
                                        'pie' => [
                                            'cursor' => 'pointer',
                                            'allowPointSelect' => TRUE,
                                            'distance' => 0,
                                            'size' => '80%',
                                            'center' => ['50%', '50%'],
                                            'dataLabels' => [
                                                'enabled' => TRUE,
                                                'distance' => -30,
                                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                'style' => [
                                                    'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                                ]
                                            ]
                                        ],
                                    ],
                                    'series' => [[
                                        'type' => 'pie',
                                        'innerSize' => '50%',
                                        'name' => yii::t('app', 'จำนวน'),
                                        'colorByPoint' => true,
                                        'colors' => [
                                            $colors['expedited'],
                                            $colors['fullboard'],
                                        ],
                                        'data' => [
                                            [
                                                'name' => 'Expedited',
                                                'y' => floatval($expedited),
                                            ],
                                            [
                                                'name' => 'Fullboard',
                                                'y' => floatval($fullboard),
                                            ]
                                        ],
                                    ]],
                                ]
                            ]);
                            ?>

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
                                        'height' => 400,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => [
                                        'text' => yii::t('app', 'Initial vs. Continuing'),
                                    ],
                                    'plotOptions' => [
                                        'pie' => [
                                            'cursor' => 'pointer',
                                            'allowPointSelect' => TRUE,
                                            'size' => '80%',
                                            'center' => ['50%', '50%'],
                                            'dataLabels' => [
                                                'enabled' => TRUE,
                                                'distance' => -30,
                                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                'style' => [
                                                    'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                                ]
                                            ]
                                        ],
                                    ],
                                    'series' => [[
                                        'type' => 'pie',
                                        'innerSize' => '50%',
                                        'name' => yii::t('app', 'จำนวน'),
                                        'colorByPoint' => true,
                                        'colors' => [
                                            $colors['expedited'],
                                            $colors['continue'],
                                        ],
                                        'data' => [
                                            [
                                                'name' => 'Initial',
                                                'y' => floatval($initial),
                                            ],
                                            [
                                                'name' => 'Continuing',
                                                'y' => floatval($continuing),
                                            ]
                                        ],
                                    ]],
                                ]
                            ]);

                            ?>
                        </div>
                        <div class="col-lg-3">
                            <table class="table table-bordered table-condensed table-striped">
                                <tbody>
                                    <tr>
                                        <?php foreach ($panels as $p): ?>
                                            <td class="text-center"><?= $p->name_eng ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <?php foreach ($countPanels as $c): ?>
                                            <td class="text-center"><?= number_format($c) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
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
                                        'height' => 300,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => false,
                                    'plotOptions' => [
                                        'pie' => [
                                            'cursor' => 'pointer',
                                            'allowPointSelect' => TRUE,
                                            'size' => '80%',
                                            'center' => ['50%', '50%'],
                                            'dataLabels' => [
                                                'enabled' => TRUE,
                                                'distance' => -30,
                                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                'style' => [
                                                    'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                                ]
                                            ]
                                        ],
                                    ],
                                    'series' => $donutPanelSeries,
                                ]
                            ]);

                            ?>
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
                                        'type' => 'bar',
                                        'height' => 300,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => false,
                                    'xAxis' => [
                                        'categories' => ArrayHelper::getColumn($panels, 'name_eng'),
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'โครงการ')
                                        ],
                                        'labels' => [
                                            'overflow' => 'justify'
                                        ],
                                    ],
                                    'plotOptions' => [
                                        'bar' => [
                                            'dataLabels' => [
                                                'enabled' => true
                                            ],
                                        ]
                                    ],
                                    'series' => $barPanelSeries
                                ]
                            ]);
                            ?>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                </div>
            </div>
            <div class="panel">
                <?php
                // echo Highcharts::widget([
                //     'scripts' => [
                //         'modules/exporting',
                //         'themes/grid-light',
                //     ],
                //     'options' => [
                //         'credits' => [
                //             'enabled' => FALSE
                //         ],
                //         'chart' => [
                //             'height' => 500,
                //             'style' => [
                //                 'fontSize' => '13px',
                //                 'fontFamily' => 'Prompt',
                //             ],
                //         ],
                //         'title' => [
                //             'text' => yii::t('app', 'โครงการวิจัยใหม่ที่เข้าสู่กระบวนการพิจารณาของคณะกรรมการแยกตามประเภทการพิจารณา จำแนกตามประเภทการพิจารณา'),
                //         ],
                //         'plotOptions' => [
                //             'pie' => [
                //                 'cursor' => 'pointer',
                //                 'allowPointSelect' => TRUE,
                //                 'dataLabels' => [
                //                     'enabled' => TRUE,
                //                     'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                //                     'style' => [
                //                         'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                //                     ]
                //                 ]
                //             ],
                //         ],
                //         'series' => [[
                //             'type' => 'pie',
                //             'name' => yii::t('app', 'จำนวน'),
                //             'data' => $series,
                //         ]],
                //     ]
                // ]);
                ?>
            </div>
        </div>
    </div>
</div>