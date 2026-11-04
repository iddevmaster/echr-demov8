<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'สรุประยะเวลาที่ใช้ในการพิจารณาโครงการวิจัยแต่ละประเภท');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="submission-index ">
    <div class="text-center"><h4>สรุประยะเวลาที่ใช้ในการพิจารณาโครงการวิจัยแต่ละประเภท</h4></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
            <?php
            echo $this->renderFile('@app/views/site/_report-summary-duration-pdf.php', [ 'results' => $results]);
            ?>
            </div>
        </div>
    </div>
</div>