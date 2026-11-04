<?php

use app\models\Agenda;
use app\models\Panel;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระและประเภทโครงการ ตามปี');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระและประเภทโครงการ ตามปี<Br> (ตั้งแต่ ') . $searchModel->startYear . yii::t('app', 'ถึง') . $searchModel->endYear . yii::t('app', ')');
} else {
    $date = yii::t('app', 'กราฟสรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระและประเภทโครงการ ตามปี');
}
$series = [];
$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = Panel::find()->isDeleted(false)->orderBy('id')->all();
$contTypes = SubmissionType::find()->isDeleted(false)->isNew(false)->all();
$countAgendas = [];
$eAgendas = Agenda::getExpeditedAgendas();
$fAgendas = Agenda::getFullboardAgendas();
$iAgendas = Agenda::getInitialAgendas();
$cAgendas = Agenda::getContinueAgendas();

$agendas = array_merge($eAgendas, $fAgendas, $iAgendas, $cAgendas);
$years = array_values(array_unique(ArrayHelper::getColumn($data, 'year')));
$countYears = [];
$colReviewTypeSeries = [
    [
        'name' => 'Fullboard',
        'data' => [],
    ],
    [
        'name' => 'Expedited',
        'data' => [],
    ],
    [
        'name' => 'Exemption',
        'data' => [],
    ],
];
$colContTypeSeries = [];
foreach ($years as $y) {
    $countYears[$y] = 0;
    $yData = array_filter($data, function ($v, $k) use ($y) {
        return $v['year'] == $y;
    }, ARRAY_FILTER_USE_BOTH);

    $newFullData = array_filter($yData, function ($v, $k) use ($y) {
        return $v['is_new'] == 1 && $v['is_fullboard'] == 1;
    }, ARRAY_FILTER_USE_BOTH);
    $colReviewTypeSeries[0]['data'][] = array_sum(ArrayHelper::getColumn($newFullData, 'c'));
    $newExpData = array_filter($yData, function ($v, $k) use ($y) {
        return $v['is_new'] == 1 && $v['is_fullboard'] == 0 && $v['is_exemption'] == 0;
    }, ARRAY_FILTER_USE_BOTH);
    $colReviewTypeSeries[1]['data'][] = array_sum(ArrayHelper::getColumn($newExpData, 'c'));
    $newExmData = array_filter($yData, function ($v, $k) use ($y) {
        return $v['is_new'] == 1 && $v['is_fullboard'] == 0 && $v['is_exemption'] == 1;
    }, ARRAY_FILTER_USE_BOTH);
    $colReviewTypeSeries[2]['data'][] = array_sum(ArrayHelper::getColumn($newExmData, 'c'));

    foreach ($contTypes as $ct) {
        $tData = array_filter($yData, function ($v, $k) use ($ct) {
            return $v['submission_type_id'] == $ct->id;
        }, ARRAY_FILTER_USE_BOTH);
        if (!isset($colContTypeSeries[$ct->id])) {
            $colContTypeSeries[$ct->id] = [
                'name' => $ct->name_eng,
                'data' => [],
            ];
        }
        $colContTypeSeries[$ct->id]['data'][] = array_sum(ArrayHelper::getColumn($tData, 'c'));
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
                            <div>
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
                                            'categories' => array_values($years),
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
                                                    'enabled' => true,
                                                    // 'rotation' => -45,
                                                    // 'allowOverlap' => true,
                                                    // 'inside' => false,
                                                    // 'crop' => false,
                                                    // 'overflow' => 'none',
                                                ],
                                            ]
                                        ],
                                        'series' => [
                                            [
                                                'name' => Yii::t('app', 'โครงการ'),
                                                'data' => array_values($countYears),
                                            ]
                                        ]
                                    ]
                                ]);
                                ?>
                            </div>
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
                                        'type' => 'column',
                                        'height' => 600,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => false,
                                    'xAxis' => [
                                        'categories' => array_values($years),
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'จำนวนโครงการใหม่')
                                        ],
                                        'stackLabels' => [
                                            'enabled' => true,
                                        ],
                                    ],
                                    'plotOptions' => [
                                        'column' => [
                                            'stacking' => 'normal',
                                            'dataLabels' => [
                                                'enabled' => true,
                                            ],
                                        ]
                                    ],
                                    'series' => $colReviewTypeSeries,
                                ]
                            ]);
                            ?>

                            <table class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <?php foreach ($years as $y): ?>
                                            <th class="text-center"><?= $y ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="">Exemption</td>
                                        <?php foreach ($years as $i => $y): ?>
                                            <td class="text-center"><?= number_format($colReviewTypeSeries[2]['data'][$i]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="">Expedited</td>
                                        <?php foreach ($years as $i => $y): ?>
                                            <td class="text-center"><?= number_format($colReviewTypeSeries[1]['data'][$i]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="">Fullboard</td>
                                        <?php foreach ($years as $i => $y): ?>
                                            <td class="text-center"><?= number_format($colReviewTypeSeries[0]['data'][$i]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <?php foreach ($years as $i => $y):
                                            $sum = $colReviewTypeSeries[0]['data'][$i] + $colReviewTypeSeries[1]['data'][$i]
                                                + $colReviewTypeSeries[2]['data'][$i];
                                        ?>
                                            <td class="text-center"><?= number_format($sum) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
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
                                        'type' => 'column',
                                        'height' => 500,
                                        'style' => [
                                            'fontSize' => '13px',
                                            'fontFamily' => 'Prompt',
                                        ],
                                    ],
                                    'title' => false,
                                    'xAxis' => [
                                        'categories' => array_values($years),
                                    ],
                                    'yAxis' => [
                                        'title' => [
                                            'text' => Yii::t('app', 'จำนวนโครงการต่อเนื่อง')
                                        ],
                                        'stackLabels' => [
                                            'enabled' => true,
                                        ],
                                    ],
                                    'plotOptions' => [
                                        'column' => [
                                            'stacking' => 'normal',
                                            'dataLabels' => [
                                                'enabled' => true,
                                            ],
                                        ]
                                    ],
                                    'series' => array_values($colContTypeSeries),
                                ]
                            ]);
                            ?>

                            <table class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <?php foreach ($years as $y): ?>
                                            <th class="text-center"><?= $y ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sumYears = [];
                                    foreach ($contTypes as $ct): ?>
                                    <tr>
                                        <td class=""><?= $ct->name_eng ?></td>
                                        <?php foreach ($years as $i => $y): 
                                            if (!isset($sumYears[$y])) {
                                                $sumYears[$y] = 0;
                                            }
                                            $sumYears[$y] += $colContTypeSeries[$ct->id]['data'][$i];
                                            ?>
                                            <td class="text-center"><?= number_format($colContTypeSeries[$ct->id]['data'][$i]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <tr>
                                        <td></td>
                                        <?php foreach ($years as $i => $y): ?>
                                            <td class="text-center"><?= number_format($sumYears[$y]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </div>

        </div>
    </div>
</div>