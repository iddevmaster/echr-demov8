<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'รายงานสถิติโครงการใหม่');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
//$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
$title = yii::t('app', 'รายงานสถิติโครงการใหม่ <br>');
if (!empty($startDate)) {
    $title .= ' ตั้งแต่ วันที่ ' . Yii::$app->thaiFormatter->asDate($startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($endDate, 'php:d/m/Y');
}
$scoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv));
$scoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv));
$scoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv));
$scoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv));
$scoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv));

$total = $scoreValueC + $scoreValueS + $scoreValueExemption + $scoreValueExpedite + $scoreValueCrec;


$org = app\models\Organization::findOne($researcherOrg);
$dep = app\models\Department::findOne($researcherDep);
$div = app\models\Division::findOne($researcherDiv);
?>

<div class="submission-index ">

    <div class="panel-body">
        <div class="panel-header">
            <h3 class="panel-title text-center"><?= $title ?>
                <?= !empty($researcherOrg) ? '<br><font style="color: #0000CC">องค์กร/หน่วยงาน : </font>' . $org->name : "" ?>
                <?= !empty($researcherDep) ? '<font style="color: #0000CC">แผนก/คณะ :</font> ' . $dep->name : "" ?>
                <?= !empty($researcherDiv) ? '<font style="color: #0000CC">ฝ่าย/ภาควิชา  :</font> ' . $div->name : "" ?>
            </h3>

        </div><br>
        <div class="row">
            <div class="col-md-12">
                <a id="create-plan-link" class="hidden" role="modal-remote" style="text-decoration: none"></a>
                <table style="border-collapse: collapse; border: 1px solid black; width: 100%" id="table-statistics-panel" class="table-statistics">
                    <thead>
                        <tr style="background-color: #878787;">
                            <td rowspan="1" style="border: 1px solid black; padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', '') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;  padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่ทางคลินิก') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;  padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่ทางสังคม') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;  padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'Expedited Review') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;  padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'Exemption Research') ?> </font> </td>
                            <td rowspan="1" style="border: 1px solid black;  padding: 10px;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'โครงการใหม่เข้าข่าย MOU-CREC') ?> </font> </td>
                            <td style="border: 1px solid black; width: 5%;  padding: 10px;" class="text-center"><font style="color: #ffffff"><?= Yii::t('app', 'รวม') ?></font> </td>

                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td style="border: 1px solid black; padding: 10px;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ยื่นขอพิจารณา') ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueC ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueS ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueExpedite ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueExemption ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $scoreValueCrec ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"> <font style="color: #0000CC"><?= $total ?> </font></td>
                        </tr>
                        <tr>
                            <?php
                            $YscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $YscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $YscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $YscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $YscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
//                            $YscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_Y));
                            $Ytotal = ($YscoreValueC) + ($YscoreValueS) + ($YscoreValueExemption) + ($YscoreValueExpedite) + ($YscoreValueCrec);
                            $YscoreC = $YscoreValueC;
                            $YscoreS = $YscoreValueS;
                            $YscoreExemption = $YscoreValueExemption;
                            $YscoreExpedite = $YscoreValueExpedite;
                            $YscoreCrec = $YscoreValueCrec;
                            ?>
                            <td style="border: 1px solid black; padding: 10px;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ได้รับการรับรอง/รับทราบ') ?> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreC ?> (<?= !empty($YscoreC) ? $formatter->asDecimal(($YscoreC * 100) / $scoreValueC, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreS ?> (<?= !empty($YscoreS) ? $formatter->asDecimal(($YscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreExpedite ?> (<?= !empty($YscoreExpedite) ? $formatter->asDecimal(($YscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreExemption ?> (<?= !empty($YscoreExemption) ? $formatter->asDecimal(($YscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><?= $YscoreCrec ?> (<?= !empty($YscoreCrec) ? $formatter->asDecimal(($YscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #0000CC"> <?= $Ytotal ?></font> </td>
                        </tr>        
                        <tr>
                            <?php
                            $NscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $NscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $NscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $NscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $NscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
//                            $NscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_N));
                            $Ntotal = ($NscoreValueC) + ($NscoreValueS) + ($NscoreValueExemption) + ($NscoreValueExpedite) + ($NscoreValueCrec);
                            $NscoreC = $NscoreValueC;
                            $NscoreS = $NscoreValueS;
                            $NscoreExemption = $NscoreValueExemption;
                            $NscoreExpedite = $NscoreValueExpedite;
                            $NscoreCrec = $NscoreValueCrec;
                            ?>
                            <td style="border: 1px solid black; padding: 10px;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ไม่รับรอง') ?> </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreC ?> (<?= !empty($NscoreC) ? $formatter->asDecimal(($NscoreC * 100) / $scoreValueC, 2) : ""; ?>%) </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreS ?> (<?= !empty($NscoreS) ? $formatter->asDecimal(($NscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreExpedite ?> (<?= !empty($NscoreExpedite) ? $formatter->asDecimal(($NscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreExemption ?> (<?= !empty($NscoreExemption) ? $formatter->asDecimal(($NscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $NscoreCrec ?> (<?= !empty($NscoreCrec) ? $formatter->asDecimal(($NscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"> <font style="color: #0000CC"><?= $Ntotal ?> </font></td>
                        </tr>      
                        <tr>
                            <?php
                            $WscoreValueC = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $WscoreValueS = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $WscoreValueExemption = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $WscoreValueExpedite = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $WscoreValueCrec = intval(\Yii::$app->user->identity->getProjectCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueCRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 1, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueSRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 2, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueExemptionRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 3, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueExpediteRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 4, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
//                            $WscoreValueCrecRef = intval(\Yii::$app->user->identity->submissionReportNew(\app\models\SubmissionTypeGroup::GROUP_NEW, $startDate, $endDate, 19, \app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $researcherOrg, $researcherDep, $researcherDiv, \app\models\Submission::RESOLUTION_W));
                            $Wtotal = ($WscoreValueC) + ($WscoreValueS) + ($WscoreValueExemption) + ($WscoreValueExpedite) + ($WscoreValueCrec);
                            $WscoreC = $WscoreValueC;
                            $WscoreS = $WscoreValueS;
                            $WscoreExemption = $WscoreValueExemption;
                            $WscoreExpedite = $WscoreValueExpedite;
                            $WscoreCrec = $WscoreValueCrec;                        

                            ?>
                            <td style="border: 1px solid black; padding: 10px;" class="text-left padding-10"><?= Yii::t('app', 'จำนวนโครงการที่ถอนออกจากการพิจารณา') ?> </td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreC ?> (<?= !empty($WscoreC) ? $formatter->asDecimal(($WscoreC * 100) / $scoreValueC, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreS ?> (<?= !empty($WscoreS) ? $formatter->asDecimal(($WscoreS * 100) / $scoreValueS, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreExpedite ?> (<?= !empty($WscoreExpedite) ? $formatter->asDecimal(($WscoreExpedite * 100) / $scoreValueExpedite, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreExemption ?> (<?= !empty($WscoreExemption) ? $formatter->asDecimal(($WscoreExemption * 100) / $scoreValueExemption, 2) : ""; ?>%)</td>
                            <td style="border: 1px solid black;" class="text-center"><?= $WscoreCrec ?> (<?= !empty($WscoreCrec) ? $formatter->asDecimal(($WscoreCrec * 100) / $scoreValueCrec, 2) : ""; ?>%)</td>
                                    <td style="border: 1px solid black;" class="text-center"><font style="color: #0000CC"> <?= $Wtotal ?> </font> </td>
                        </tr>      
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>