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
    <?php
    echo $this->renderFile('@app/views/site/_search-report-summary-duration.php', ['searchModel' => $searchModel]);
    ?>
    
    <?= Html::a(Yii::t('app', "EXPORT PDF"), ['site/report-summary-duration', 'submissionTypeGroupId' => $searchModel->submission_type_group_id,'panelId'=>$searchModel->panel_id, 'startDate' => $searchModel->startDate, 'endDate' => $searchModel->endDate, 'pdf' => true], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <?php
                echo $this->renderFile('@app/views/site/_report-summary-duration.php', ['searchModel' => $searchModel, 'results' => $results]);
                ?>
            </div>
        </div>
    </div>
</div>