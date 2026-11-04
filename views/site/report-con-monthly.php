<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'สรุปจำนวนโครงการวิจัยต่อเนื่องที่เสนอขอรับการรับรองจากที่ประชุมคณะกรรมการฯ จำแนกตามเดือนและประจำสาขาวิชา');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="submission-index ">
    <?php
    echo $this->renderFile('@app/views/site/_search-gp-monthly-con.php', ['searchModel' => $searchModel]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->renderFile('@app/views/site/_submissionSumaryConByMonthly.php', ['searchModel' => $searchModel]);
            ?>
        </div>
    </div>
</div>