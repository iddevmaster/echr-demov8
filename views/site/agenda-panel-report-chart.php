<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\assets\TableExportAsset;
use kartik\export\ExportMenu;
use miloschuman\highcharts\Highcharts;

TableExportAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;


$panels = \app\models\Panel::find()->isDeleted(false)->all();


if (!empty($searchModel->startDate)) {
    $title .= ' ตั้งแต่ วันที่ ' . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'php:d/m/Y');
}
?>
<div class="site-about">
    <?php
    echo $this->renderFile('@app/views/site/_search-agenda-year.php', ['searchModel' => $searchModel, 'url' => Url::to(['site/agenda-panel-report', 'title' => $title, 'chart' => 1, 'agendaIds' => implode(',', ArrayHelper::getColumn($agendas, 'id'))])]);
    ?>
    <div class="panel">

        <div class="panel-header">
            <h3 class="panel-title text-center"><?= $title ?></h3>
        </div>
        <div class="panel-body">
            <div class="panel">
                <?php
                $categories = ArrayHelper::getColumn($panels, 'name');
                $series = [];


                foreach ($agendas as $agenda) {
                    foreach ($panels as $panel) {
                        $sm = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanel($agenda->id, $panel->id)->dateStatus($searchModel->startDate, $searchModel->endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                        if (!key_exists($agenda->label, $series)) {
                            $series[$agenda->label] = [
                                'type' => 'column',
                                'name' => $agenda->label,
                                'data' => [floatval($sm)]
                            ];
                        } else {
                            $series[$agenda->label]['data'][] = floatval($sm);
                        }
                    }
                }

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
                                'fontSize' => '13px',
                                'fontFamily' => 'Prompt',
                            ],
                        ],
                        'title' => [
                            'text' => $title,
                        ],
                        'xAxis' => [
                            'categories' => $categories,
                        ],
                        'yAxis' => [
                            'title' => [
                                'text' => Yii::t('app', 'จำนวน'),
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
                        'series' => array_values($series),
                    ]
                ]);
                ?>
            </div>

        </div>
        <div class="panel-footer">
        </div>
    </div>

</div>