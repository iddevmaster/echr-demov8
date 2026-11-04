<?php

use app\models\Panel;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'รายงานประเภทโครงการวิจัยจากหน้ารายงานประชุม');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;

if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $date = yii::t('app', 'รายงานประเภทโครงการวิจัยจากหน้ารายงานประชุม <Br>(ตั้งแต่วันที่ ') . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'long') . yii::t('app', 'ถึง') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'long') . yii::t('app', ')');
} else {
    $date = yii::t('app', 'รายงานประเภทโครงการวิจัยจากหน้ารายงานประชุม');
}
$panels = Panel::find()->isDeleted(false)->orderBy('id')->all();
$sumPanels = [];
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
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'ประเภทโครงการวิจัยใหม่') ?></th>
                                <?php foreach ($panels as $p):
                                    $sumPanels[$p->id] = 0;
                                ?>
                                    <th><?= $p->name_eng ?></th>
                                <?php endforeach; ?>
                                <th><?= Yii::t('app', 'รวม') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dataByType = ArrayHelper::index($data, null, 'name');
                            $sum = 0;
                            foreach ($dataByType as $type => $dataTypes):
                                $sumRow = 0;
                            ?>
                                <tr>
                                    <td><?= $type ?></td>
                                    <?php foreach ($panels as $p):
                                        $res = array_filter($dataTypes, function ($v, $k) use ($p) {
                                            return $v['name_eng'] == $p->name_eng;
                                        }, ARRAY_FILTER_USE_BOTH);
                                        $sum += isset($res[0]) ? $res[0]['c'] : 0;
                                        $sumRow += isset($res[0]) ? $res[0]['c'] : 0;
                                        $sumPanels[$p->id] += isset($res[0]) ? $res[0]['c'] : 0;
                                    ?>
                                        <td class="text-right"><?= isset($res[0]) ? number_format($res[0]['c']) : "" ?></td>
                                    <?php endforeach; ?>
                                    <td class="text-right"><?= number_format($sumRow) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><?= Yii::t('app', 'รวม') ?></td>
                                <?php foreach ($panels as $p):
                                ?>
                                    <td class="text-right"><?= number_format($sumPanels[$p->id]) ?></td>
                                <?php endforeach; ?>
                                <td class="text-right"><?= number_format(array_sum($sumPanels)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                </div>
            </div>

        </div>
    </div>
</div>