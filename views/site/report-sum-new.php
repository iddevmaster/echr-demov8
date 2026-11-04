<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'รายงานสถิติโครงการใหม่');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
$scoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, null, $searchModel->panel_id));
$scoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, null, $searchModel->panel_id));
$scoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, null, $searchModel->panel_id));
$scoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, null, $searchModel->panel_id));
$scoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, null, $searchModel->panel_id));

$total = $scoreValueC + $scoreValueS + $scoreValueExemption + $scoreValueExpedite + $scoreValueCrec;
?>

<div class="submission-index ">

    <div class="panel-body">
        <?php
        echo $this->renderFile('@app/views/site/_search-gp-new.php', ['searchModel' => $searchModel]);
        ?>
        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['site/report-sum-new', 'startDate' => $searchModel->startDate, 'endDate' => $searchModel->endDate, 'researcherOrg' => $searchModel->researcherOrg, 'researcherDep' => $searchModel->researcherDep, 'researcherDiv' => $searchModel->researcherDiv, 'searchModel' => $searchModel, 'pdf' => true, 'panelId' => $searchModel->panel_id], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
        <div class="row">
            <div class="col-md-12">
                <a id="create-plan-link" class="hidden" role="modal-remote" style="text-decoration: none"></a>
                <table style="border-collapse: collapse; border: 1px solid black; width: 100%" id="table-statistics-panel" class="table-statistics">
                    <thead>
                        <tr style="background-color: #878787; padding: 10px;">
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', '') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่ทางคลินิก') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่ทางสังคม') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'Expedited Review') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'Exemption Research') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่เข้าข่าย MOU-CREC') ?> </font> </td>
                            <td style="border: 1px solid black; width: 5%" class="text-center"><font style="color: #ffffff"><?= Yii::t('app', 'รวม') ?></font> </td>

                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td style="border: 1px solid black;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ยื่นขอพิจารณา') ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueC ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueS ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueExpedite ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueExemption ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueCrec ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"> <?= $total ?> </td>
                        </tr>
                        <tr>
                            <?php
                             
                            $YscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y, $searchModel->panel_id));
                            $YscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y, $searchModel->panel_id));
                            $YscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y, $searchModel->panel_id));
                            $YscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y, $searchModel->panel_id));
                            $YscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y, $searchModel->panel_id));
//                            $YscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $Ytotal = ($YscoreValueC) + ($YscoreValueS) + ($YscoreValueExemption) + ($YscoreValueExpedite) + ($YscoreValueCrec);
                            $YscoreC = $YscoreValueC;
                            $YscoreS = $YscoreValueS;
                            $YscoreExemption = $YscoreValueExemption;
                            $YscoreExpedite = $YscoreValueExpedite;
                            $YscoreCrec = $YscoreValueCrec;
                            ?>
                            <?php
                            $NscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N, $searchModel->panel_id));
                            $NscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N, $searchModel->panel_id));
                            $NscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N, $searchModel->panel_id));
                            $NscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N, $searchModel->panel_id));
                            $NscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N, $searchModel->panel_id));
//                            $NscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $Ntotal = ($NscoreValueC) + ($NscoreValueS) + ($NscoreValueExemption) + ($NscoreValueExpedite) + ($NscoreValueCrec);
                            $NscoreC = $NscoreValueC;
                            $NscoreS = $NscoreValueS;
                            $NscoreExemption = $NscoreValueExemption;
                            $NscoreExpedite = $NscoreValueExpedite;
                            $NscoreCrec = $NscoreValueCrec;
                            ?>
                            <td style="border: 1px solid black;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ได้รับการรับรอง/รับทราบ') ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreC ?> (<?= !empty($scoreValueC) ? $formatter->asDecimal(($YscoreC * 100) / $scoreValueC, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreS ?> (<?= !empty($scoreValueS) ? $formatter->asDecimal(($YscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreExpedite ?> (<?= !empty($scoreValueExpedite) ? $formatter->asDecimal(($YscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreExemption ?> (<?= !empty($scoreValueExemption) ? $formatter->asDecimal(($YscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreCrec ?> (<?= !empty($scoreValueCrec) ? $formatter->asDecimal(($YscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"> <?= $Ytotal ?> </td>
                        </tr>        
                        <tr>

                            <td style="border: 1px solid black;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ไม่รับรอง') ?> </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreC ?> (<?= !empty($scoreValueC) ? $formatter->asDecimal(($NscoreC * 100) / $scoreValueC, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreS ?> (<?= !empty($scoreValueS) ? $formatter->asDecimal(($NscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreExpedite ?> (<?= !empty($scoreValueExpedite) ? $formatter->asDecimal(($NscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreExemption ?> (<?= !empty($scoreValueExemption) ? $formatter->asDecimal(($NscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreCrec ?> (<?= !empty($scoreValueCrec) ? $formatter->asDecimal(($NscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"> <?= $Ntotal ?> </td>
                        </tr>      
                        <tr>
                            <?php
                            $WscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W, $searchModel->panel_id));
                            $WscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W, $searchModel->panel_id));
                            $WscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W, $searchModel->panel_id));
                            $WscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W, $searchModel->panel_id));
                            $WscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W, $searchModel->panel_id));
//                            $WscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $searchModel->startDate, $searchModel->endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $searchModel->researcherOrg, $searchModel->researcherDep, $searchModel->researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $Wtotal = ($WscoreValueC) + ($WscoreValueS) + ($WscoreValueExemption) + ($WscoreValueExpedite) + ($WscoreValueCrec);
                            $WscoreC = $WscoreValueC;
                            $WscoreS = $WscoreValueS;
                            $WscoreExemption = $WscoreValueExemption;
                            $WscoreExpedite = $WscoreValueExpedite;
                            $WscoreCrec = $WscoreValueCrec;                            
                            ?>
                            <td style="border: 1px solid black;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ถอนออกจากการพิจารณา') ?> </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreC ?> (<?= !empty($scoreValueC) ? $formatter->asDecimal(($WscoreC * 100) / $scoreValueC, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreS ?> (<?= !empty($scoreValueS) ? $formatter->asDecimal(($WscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreExpedite ?> (<?= !empty($scoreValueExpedite) ? $formatter->asDecimal(($WscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreExemption ?> (<?= !empty($scoreValueExemption) ? $formatter->asDecimal(($WscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreCrec ?> (<?= !empty($scoreValueCrec) ? $formatter->asDecimal(($WscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"> <?= $Wtotal ?> </td>
                        </tr>   
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>