<?php

use app\models\CommitteePosition;
use app\models\Panel;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;
use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

$this->title = Yii::t('app', 'รายงานภาพรวมการพิจารณาของกรรมการ');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

$panels = Panel::find()->isDeleted(false)->orderBy('id')->all();
$positions = CommitteePosition::find()->isDeleted(false)->orderBy('id')->all();

$colors = [
    1 => 'crimson',
    2 => 'gold',
    3 => 'dodgerblue',
    4 => 'limegreen',
];
?>


<div class="committee-review-count-index">
    <div class="panel panel-bordered margin-bottom-10">
        <div class="padding-10">
            <?= $this->render('_search-review-count-by-reviewer-graph', ['searchModel' => $searchModel]) ?>
        </div>
    </div>

    <h3 class="panel-title text-center"><?= $this->title ?></h3><br>


    <?php foreach ($panels as $p):
        ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10" style="padding: 10px;">
                <h3 class="card-title">
    <?= $p->name_eng ?>
                </h3>
                <div class="card-block">
                    <div class="row">

    <?php
    foreach ($positions as $ps):
        $psData = array_filter($data, function ($v, $k) use ($ps, $p) {
            return $v['panel_id'] == $p->id && $v['position_id'] == $ps->id;
        }, ARRAY_FILTER_USE_BOTH);
        $series = [[
        'name' => Yii::t('app', 'จำนวน'),
        'color' => $colors[$p->id],
        'data' => []
        ]];
        foreach ($psData as $psD) {
            $series[0]['data'][] = floatval($psD['c']);
        }

        // VarDumper::dump($series, 10, true);
        // VarDumper::dump(array_values(ArrayHelper::getColumn($psData, 'reviewer_name'), 10, true);
        // exit;
        ?>
                            <?php

                            $dataCount = count($psData);
                            $chartHeight = max(400, $dataCount * 35 + 100); // 35px ต่อ 1 แถว + padding
                            ?>

                            <div class="col-md-3">
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
                                            'height' => $chartHeight, // ใช้ความสูงที่คำนวณ
                                            'style' => [
                                                'fontSize' => '13px',
                                                'fontFamily' => 'Prompt',
                                            ],
                                        ],
                                        'title' => [
                                            'text' => $ps->name
                                        ],
                                        'xAxis' => [
                                            'categories' => array_values(ArrayHelper::getColumn($psData, 'reviewer_name')),
                                            'min' => 0,
                                            'max' => $dataCount > 0 ? $dataCount - 1 : 0,
                                            'labels' => [
                                                'crop' => false,
                                                'overflow' => 'allow',
                                                'step' => 1, // บังคับให้แสดงทุก label
                                                'autoRotation' => false,
                                                'style' => [
                                                    'fontSize' => '11px',
                                                    'textOverflow' => 'none',
                                                ]
                                            ]
                                        ],
                                        'yAxis' => [
                                            'title' => [
                                                'text' => Yii::t('app', 'จำนวนโครงการ')
                                            ],
                                        ],
                                        'legend' => false,
                                        'plotOptions' => [
                                            'bar' => [
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                                'pointPadding' => 0.1,
                                                'groupPadding' => 0.1,
                                            ]
                                        ],
                                        'series' => $series,
                                    ]
                                ]);
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>