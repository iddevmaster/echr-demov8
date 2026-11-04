<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

ini_set("pcre.backtrack_limit", "10000000");


$this->title = Yii::t('app', 'รายการโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาฯ');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = \app\models\Panel::find()->isDeleted(false)->all();

$title = yii::t('app', 'รายการโครงการวิจัยใหม่ที่เสนอขอรับการพิจารณาฯ');
if (!empty($startDate) || !empty($endDate)) {
    $title .= ' วันที่  ' . Yii::$app->thaiFormatter->asDate($startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($endDate, 'php:d/m/Y');
    if (!empty($panelId)) {
        $title .= ' Panel : ' . $panelId;
    }
}
if (isset($pdf)) {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=export.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
}
?>
<div class="site-about">
    <div class="panel-header">
        <h3 class="panel-title text-center"><?= $title ?></h3>

    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <a id="create-plan-link" class="hidden" role="modal-remote" style="text-decoration: none"></a>
                <table style="border-collapse: collapse; border: 1px solid black; width: 100%">
                    <thead>
                        <tr style="background-color: #878787">
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ลำดับ') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'เลขที่/รหัสคำขอ') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ชื่อโครงการวิจัยภาษาไทย') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ชื่อโครงการวิจัยภาษาอังกฤษ') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ประเภทการขอรับการพิจารณา') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'วาระ') ?> </font> </td>
                            <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'มติการพิจารณาครั้งแรก') ?> </font> </td>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        if (!empty($panelId)) {
                            $submissions = app\models\Submission::find()->joinWith(['submissionType','project', 'meetingAgendas'])->isDeleted(false)->noRef()->submissionTypeGroup(app\models\SubmissionTypeGroup::GROUP_NEW)->dateStatusBetween($startDate, $endDate, $status)->panel($panelId)->hasMeetingAgenda()->orderBy(['SUBSTRING(project.project_code, -5, 5)' => SORT_DESC])->all();
                        } else {
                            $submissions = app\models\Submission::find()->joinWith(['submissionType','project', 'meetingAgendas'])->isDeleted(false)->noRef()->submissionTypeGroup(app\models\SubmissionTypeGroup::GROUP_NEW)->dateStatusBetween($startDate, $endDate, $status)->hasMeetingAgenda()->orderBy(['SUBSTRING(project.project_code, -5, 5)' => SORT_DESC])->all();
                        }
                        $i = 1;
                        foreach ($submissions as $submission):
                            ?>
                            <tr>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $i++ ?></td>
                                <td style="border: 1px solid black; width: 10%" class="text-center padding-10"><?= $submission->project->project_code ?></td>
                                <td style="border: 1px solid black; width: 25%" class="text-left padding-10"><?= $submission->project->name_thai; ?></td>
                                <td style="border: 1px solid black; width: 25%" class="text-left padding-10"><?= $submission->project->name_eng ?></td>
                                <td style="border: 1px solid black; width: 20%" class="text-center padding-10"><?= $submission->submissionType->name ?></td>
                                <td style="border: 1px solid black; width: 10%" class="text-center padding-10"><?= $submission->meetingAgenda->sort_label; ?> <br> <?= $submission->meetingAgenda->meeting->yearNoWithDate ?></td>
                                <td style="border: 1px solid black; width: 7%" class="text-center padding-10"><?= isset($submission->resolution) ? app\models\Submission::getResolutionLables()[$submission->resolution] : ""?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

