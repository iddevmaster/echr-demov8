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

$this->title = Yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) ตามปี');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) ตามปี<Br> (ตั้งแต่ ') . $searchModel->startYear . yii::t('app', 'ถึง') . $searchModel->endYear . yii::t('app', ')');
} else {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) ตามปี');
}
$series = [];
$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = Panel::find()->isDeleted(false)->orderBy('id')->all();
$countAgendas = [];
$countMonths = [];
$eAgendas = Agenda::getExpeditedAgendas();
$fAgendas = Agenda::getFullboardAgendas();
$iAgendas = Agenda::getInitialAgendas();
$cAgendas = Agenda::getContinueAgendas();

$agendas = array_merge($eAgendas, $fAgendas, $iAgendas, $cAgendas);
$years = array_values(array_unique(ArrayHelper::getColumn($data, 'year')));
$countYears = [];
$colSeries = [];
foreach ($years as $y) {
    $countYears[$y] = 0;
    $colSeries[$y] = [
        'name' => $y,
        'data' => [],
    ];
    $yData = array_filter($data, function ($v, $k) use ($y) {
        return $v['year'] == $y;
    }, ARRAY_FILTER_USE_BOTH);
    foreach ($agendas as $a) {
        $aData = array_filter($yData, function ($v, $k) use ($a) {
            return $v['agenda_label'] == $a;
        }, ARRAY_FILTER_USE_BOTH);
        foreach (Yii::$app->util->getMonthNames() as $monthNo => $monthName) {
            $mData = array_filter($aData, function ($v, $k) use ($monthNo) {
                return $v['month'] == $monthNo;
            }, ARRAY_FILTER_USE_BOTH);
            $countMonths[$y][$a][$monthNo] = array_sum(ArrayHelper::getColumn($mData, 'c'));
        }
    }
}

// VarDumper::dump($years, 10, true);

foreach ($agendas as $a) {
    $aData = array_filter($data, function ($v, $k) use ($a) {
        return $v['agenda_label'] == $a;
    }, ARRAY_FILTER_USE_BOTH);
    foreach ($years as $y) {
        $yData = array_filter($aData, function ($v, $k) use ($y) {
            return $v['year'] == $y;
        }, ARRAY_FILTER_USE_BOTH);
        $countAgendas[$a][$y] = array_sum(ArrayHelper::getColumn($yData, 'c'));
        $countYears[$y] += array_sum(ArrayHelper::getColumn($yData, 'c'));
        $colSeries[$y]['data'][] = array_sum(ArrayHelper::getColumn($yData, 'c'));
    }
}
$yearSeries = [[
    // 'type' => 'column',
    'name' => Yii::t('app', 'โครงการ'),
    'data' => array_values($countYears),
    // 'colors' => $agendaColors,
    // 'colorByPoint' => true,
]];

?>

<div class="submission-index ">
    <?php
    echo $this->renderFile('@app/views/report/_search-year.php', ['searchModel' => $searchModel]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $date ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <table class="table table-bordered table-condensed table-striped">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <?php foreach ($years as $y): ?>
                                            <td class="text-center"><?= $y ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="text-center">All</td>
                                        <?php foreach ($countYears as $c) : ?>
                                            <td class="text-center"><?= number_format($c) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-condensed table-striped">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <?php foreach ($years as $y): ?>
                                            <td class="text-center"><?= $y ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php foreach ($countAgendas as $aLabel => $valAgendas) : ?>
                                        <tr>
                                            <td class="text-center"><?= $aLabel ?></td>
                                            <?php foreach ($years as $y): ?>
                                                <td class="text-center"><?= number_format($valAgendas[$y]) ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-8">
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
                                        'type' => 'spline',
                                        'height' => 350,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => [
                                        'text' => 'Agenda All Year'
                                    ],
                                    'xAxis' => [
                                        'categories' => $years,
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'โครงการ')
                                        ],
                                        // 'labels' => [
                                        //     'overflow' => 'justify'
                                        // ],
                                    ],
                                    'plotOptions' => [
                                        'spline' => [
                                            'dataLabels' => [
                                                'enabled' => true
                                            ],
                                        ]
                                    ],
                                    'series' => $yearSeries
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
                                        'type' => 'column',
                                        'height' => 350,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => false,
                                    'xAxis' => [
                                        'categories' => array_values($agendas),
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'โครงการ')
                                        ],
                                        // 'labels' => [
                                        //     'overflow' => 'justify'
                                        // ],
                                    ],
                                    'plotOptions' => [
                                        'column' => [
                                            'dataLabels' => [
                                                'enabled' => true,
                                                // 'rotation' => -45,
                                                // 'allowOverlap' => true,
                                                // 'inside' => false,
                                                // 'crop' => false,
                                                // 'overflow' => 'none',
                                            ],
                                        ]
                                    ],
                                    'series' => array_values($colSeries)
                                ]
                            ]);
                            ?>
                        </div>
                    </div>

                    <?php foreach ($years as $y): ?>
                        <div class="row">
                            <h3><?= $y ?></h3>
                            <div class="col-lg-6">
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
                                            'type' => 'line',
                                            'height' => 350,
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Prompt',
                                            ],
                                        ],
                                        'title' => false,
                                        'xAxis' => [
                                            'categories' => array_values(Yii::$app->util->getMonthNames()),
                                        ],
                                        'yAxis' => [
                                            'title' => [
                                                'text' => Yii::t('app', 'โครงการ')
                                            ],
                                            // 'labels' => [
                                            //     'overflow' => 'justify'
                                            // ],
                                        ],
                                        'plotOptions' => [
                                            'line' => [
                                                'dataLabels' => [
                                                    'enabled' => true
                                                ],
                                            ]
                                        ],
                                        'series' => [
                                            [
                                                'name' => '3.3',
                                                'data' => array_values($countMonths[$y]['3.3']),
                                            ],
                                            [
                                                'name' => '3.4',
                                                'data' => array_values($countMonths[$y]['3.4']),
                                            ],
                                            [
                                                'name' => '3.9',
                                                'data' => array_values($countMonths[$y]['3.9']),
                                            ],
                                        ]
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-lg-6">
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
                                            'type' => 'line',
                                            'height' => 350,
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Prompt',
                                            ],
                                        ],
                                        'title' => false,
                                        'xAxis' => [
                                            'categories' => array_values(Yii::$app->util->getMonthNames()),
                                        ],
                                        'yAxis' => [
                                            'title' => [
                                                'text' => Yii::t('app', 'โครงการ')
                                            ],
                                            // 'labels' => [
                                            //     'overflow' => 'justify'
                                            // ],
                                        ],
                                        'plotOptions' => [
                                            'line' => [
                                                'dataLabels' => [
                                                    'enabled' => true
                                                ],
                                            ]
                                        ],
                                        'series' => [
                                            [
                                                'name' => '4.1',
                                                'data' => array_values($countMonths[$y]['4.1']),
                                            ],
                                            [
                                                'name' => '4.2',
                                                'data' => array_values($countMonths[$y]['4.2']),
                                            ],
                                            [
                                                'name' => '4.3',
                                                'data' => array_values($countMonths[$y]['4.3']),
                                            ],
                                            [
                                                'name' => '4.4',
                                                'data' => array_values($countMonths[$y]['4.4']),
                                            ],
                                        ]
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($years as $y): ?>
                        <div class="row">
                            <h3><?= $y ?></h3>
                            <div class="col-lg-12">
                                <table class="table table-bordered table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Agenda</th>
                                            <?php foreach (Yii::$app->util->getMonthNames() as $monthNo => $monthName): ?>
                                                <th class="text-center"><?= $monthName ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($agendas as $a): ?>
                                            <tr>
                                                <td class="text-center"><?= $a; ?></td>
                                                <?php foreach (Yii::$app->util->getMonthNames() as $monthNo => $monthName): ?>
                                                    <td class="text-center"><?= number_format($countMonths[$y][$a][$monthNo]) ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
                <div class="panel-footer">
                </div>
            </div>

        </div>
    </div>
</div>