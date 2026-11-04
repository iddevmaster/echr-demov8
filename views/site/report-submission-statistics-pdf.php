<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

?>

<div class="submission-index ">
    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <?php
                echo $this->renderFile('@app/views/site/_report-submission-statistics.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
                ?>
            </div>
        </div>
    </div>
</div>