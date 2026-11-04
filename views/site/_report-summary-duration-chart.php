<?php

use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\JsExpression;
?>
<div class="panel">
    <?php
    $categories = array_keys($results);
    $series = [];

    foreach ($results as $submissionType => $durTypes) {
        foreach ($durTypes as $durType => $data) {
            if (!key_exists($durType, $series)) {
                $series[$durType] = [
                    'type' => 'column',
                    'name' => Yii::t('app', $durType),
                    'data' => [floatval(array_shift($data))]
                ];
            } else {
                $series[$durType]['data'][] = floatval(array_shift($data));
            }
        }
    }

    $startDate1 = isset($searchModel->startDate) ? \Yii::$app->formatter->asDate($searchModel->startDate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($searchModel->startDate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($searchModel->startDate, 'php:Y') + 543) : "";
    $endDate1 = isset($searchModel->endDate) ? \Yii::$app->formatter->asDate($searchModel->endDate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($searchModel->endDate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($searchModel->endDate, 'php:Y') + 543) : "";
    $date = "วันที่ {$startDate1} ถึงวันที่ {$endDate1}";
    $panelName = \app\models\Panel::findOne($searchModel->panel_id);

    // VarDumper::dump($results, 10, true);
    // VarDumper::dump(array_values($series), 10, true);
    // exit;

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
                    'fontSize' => '16px',
                    'fontFamily' => 'Prompt',
                    'backgroundColor' => '#ffffff'
                ],
            ],
            'title' => [
                'text' => Yii::t('app', 'รายงานระยะเวลาดำเนินการ : ') . $panelName->name . '<br><br>' . $date,
            ],
            'xAxis' => [
                'categories' => $categories,
            ],
            'yAxis' => [
                'title' => [
                    'text' => Yii::t('app', 'ระยะเวลาเฉลี่ย (วัน)'),
                ]
            ],
            'labels' => [
                'items' => [
                    [
                        //                        'html' => 'โครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาจากคณะกรรมการจำแนกตามกลุ่มสาขาวิชา',
                        'style' => [
                            'left' => '50px',
                            'top' => '18px',
                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "white"'),
                        ],
                    ],
                ],
            ],
            'series' => array_values($series),
        ]
    ]);
    ?>
</div>
