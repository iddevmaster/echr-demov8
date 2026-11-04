<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'จำนวนโครงการโดยแยกตามประเภทโครงการใหม่ และโครงการต่อเนื่อง');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="submission-index ">
    <?php
    echo $this->renderFile('@app/views/site/_search-gp.php', ['searchModel' => $searchModel]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo $this->renderFile('@app/views/site/_submissionSumary.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
            ?>
        </div>
    </div>
</div>