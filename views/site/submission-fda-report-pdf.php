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


$this->title = Yii::t('app', 'รายงานผลการดำเนินโครงการวิจัยทางคลินิกเกี่ยวกับยา');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = \app\models\Panel::find()->isDeleted(false)->all();

$title = yii::t('app', 'รายงานผลการดำเนินงานคณะกรรมการพิจารณาจริยธรรมการวิจัยในคนที่พิจารณาโครงการวิจัยทางคลินิกเกี่ยวกับยา');
if (!empty($startDate) || !empty($endDate)) {
    $title .= ' วันที่  ' . Yii::$app->thaiFormatter->asDate($startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($endDate, 'php:d/m/Y');
    if(!empty($panelId)){
    $title .= ' Panel : ' . $panelId;
    }
}
?>
<div class="site-about">
    <div class="panel-header">
        <h3 class="panel-title text-center"><font style=" font-size: 18px;"><?= $title ?></font></h3>

    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <a id="create-plan-link" class="hidden" role="modal-remote" style="text-decoration: none"></a>
                <table style="border-collapse: collapse; border: 1px solid black; width:980px; overflow: wrap; " >
                    <thead>
                        <tr style="background-color: #878787">
                            <td style="border: 1px solid black; width: 80px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ครั้งที่ประชุม วัน เดือน ปี') ?> </font> </td>
                            <td style="border: 1px solid black;width: 80px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'เลขที่/รหัสคำขอ อนุมัติโครงการ') ?> </font> </td>
                            <td style="border: 1px solid black;width: 170px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'รหัสโครงการวิจัย') ?> </font> </td>
                            <td style="border: 1px solid black;width: 350px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ชื่อโครงการวิจัย') ?> </font> </td>
                            <td style="border: 1px solid black;width: 200px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'การพิจารณา') ?> </font> </td>
                            <td style="border: 1px solid black;width: 100px" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ผลพิจารณา') ?> </font> </td>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        if(!empty($panelId)){
                        $submissions = app\models\Submission::find()->joinWith(['project', 'meetingAgendas'])->isDeleted(false)->fda(1)->dateStatusBetween($startDate, $endDate, $status)->panel($panelId)->hasMeetingAgenda()->orderBy('meeting_agenda.meeting_id desc')->all();
                        }else{
                        $submissions = app\models\Submission::find()->joinWith(['project', 'meetingAgendas'])->isDeleted(false)->fda(1)->dateStatusBetween($startDate, $endDate, $status)->hasMeetingAgenda()->orderBy('meeting_agenda.meeting_id desc')->all();
                        }
                        foreach ($submissions as $submission):
                            ?>
                            <tr>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->meeting->yearNoWithDate ?></td>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->project->project_code ?></td>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->project->fda_no ?></td>
                                <td style="border: 1px solid black; width: 3%" class="text-left padding-10"><?= $submission->project->name_thai . '<Br>' . $submission->project->name_eng ?></td>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->agenda->name ?></td>
                                <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->resolution ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

