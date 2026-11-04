<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

//$this->title = Yii::t('app', 'ตรวจสอบเอกสารรับรอง COA');
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="forget-password col-md-12 col-md-offset-2">
    <div class="panel" id="exampleWizardFormContainer"><br>
        <div class="panel-heading">
            <div class="text-center"><?= Html::img('@web/images/logo.png', ['height' => 80]); ?></div>
            <div class="brand-text font-size-18 text-center text-primary"><?= Yii::$app->name ?></div>
        </div>
        <div class="panel-body">
            <?php
            echo $this->renderFile('@app/views/site/_search-coa.php', ['searchModel' => $searchModel]);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo $this->renderFile('@app/views/site/_submissionCoa.php', ['searchModel' => $searchModel]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>