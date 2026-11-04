<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$srd = app\models\SubmissionResultDocument::find()->isDeleted(false)->coaToken($searchModel->coa_token)->one();
$certifiedDate = isset($srd->submission->project->certified_date) ? \Yii::$app->formatter->asDate($srd->submission->project->certified_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($srd->submission->project->certified_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($srd->submission->project->certified_date, 'php:Y') + 543) : "N/A";
?>
<style>
    #download,
    #print,
    #secondaryDownload,
    #secondaryPrint {
        display: none !important;
    }
</style>
<?php if (isset($srd->coa_token)) { ?>
    <div class="panel">
        <div class="panel-heading" >
            <h3 class="panel-title"><?= Yii::t('app', 'รายละเอียดโครงการ') ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3"><?= yii::t('app', 'เลขที่โครงการ (HE)') ?> </div>
                <div class="col-md-9 text-left"><font class="blue-900"><?= isset($srd) ? $srd->submission->project->project_code : ""; ?></font></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= yii::t('app', 'ชื่อโครงการ (TH)') ?></div>
                <div class="col-md-9 text-left"><font class="blue-900"><?= isset($srd) ? $srd->submission->project->name_thai : ""; ?></font></div>
            </div>     
            <div class="row">
                <div class="col-md-3"><?= yii::t('app', 'ชื่อโครงการ (ENG)') ?></div>
                <div class="col-md-9 text-left"><font class="blue-900"><?= isset($srd) ? $srd->submission->project->name_eng : ""; ?></font></div>
            </div> 
            <div class="row">
                <div class="col-md-3"><?= yii::t('app', 'ชื่อเอกสาร') ?></div>
                <div class="col-md-9 text-left"><font class="blue-900"><?= isset($srd) ? $srd->name : ""; ?></font></div>
            </div> 
            <?php
            if (isset($srd->submission_ec_id)) {
                $certifiedDateLec = isset($srd->submissionEc->crec_certificate_at) ? \Yii::$app->formatter->asDate($srd->submissionEc->crec_certificate_at, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($srd->submissionEc->crec_certificate_at, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($srd->submissionEc->crec_certificate_at, 'php:Y') + 543) : "N/A";
                ?>
                <div class="row">
                    <div class="col-md-3"><?= yii::t('app', 'LEC') ?></div>
                    <div class="col-md-9 text-left"><font class="blue-900"><?= $srd->submissionEc->localEc->name; ?></font></div>
                </div> 
                <div class="row">
                    <div class="col-md-3"><?= yii::t('app', 'วันที่รับรองโครงการ (LEC)') ?></div>
                    <div class="col-md-9 text-left"><font class="blue-900"><?= $certifiedDateLec; ?></font></div>
                </div> 
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-3"><?= yii::t('app', 'วันที่รับรองโครงการ') ?></div>
                    <div class="col-md-9 text-left"><font class="blue-900"><?= $certifiedDate; ?></font></div>
                </div> 
            <?php } ?>
        </div>
        <div class="panel-footer">
            <?php if (isset($srd)) { ?>
                <iframe
                    src="<?= \yii\helpers\Url::to(['submission-result-document/view-file', 'id' => $srd->id]) ?>#toolbar=0&navpanes=0&scrollbar=0"
                    width="100%"
                    height="800px"
                    style="border:none;">
                </iframe>

            <?php } ?>
        </div>
    </div>
<?php } ?>
