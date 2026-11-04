<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'รายงานผลการดำเนินโครงการวิจัยทางคลินิกเกี่ยวกับยา');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = \app\models\Panel::find()->isDeleted(false)->all();

$title = yii::t('app', 'รายงานผลการดำเนินงานคณะกรรมการพิจารณาจริยธรรมการวิจัยในคนที่พิจารณาโครงการวิจัยทางคลินิกเกี่ยวกับยา');
if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
    $title .= ' วันที่ ' . Yii::$app->thaiFormatter->asDate($searchModel->startDate,'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'php:d/m/Y');
if(!empty($searchModel->panel_id)){
    $title .= ' Panel : ' . $searchModel->panel_id;
}
}
?>
<div class="site-about">
    <?php
    echo $this->renderFile('@app/views/site/_search-report-fda.php', ['searchModel' => $searchModel]);
    ?>
    <div class="panel">
        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['site/submission-fda-report','startDate'=>$searchModel->startDate,'endDate'=>$searchModel->endDate,'status'=>$searchModel->status,'panelId'=>$searchModel->panel_id, 'pdf' => true], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
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
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ครั้งที่ประชุม วัน เดือน ปี') ?> </font> </td>
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'เลขที่/รหัสคำขอ อนุมัติโครงการ') ?> </font> </td>
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'รหัสโครงการวิจัย') ?> </font> </td>
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ชื่อโครงการวิจัย') ?> </font> </td>
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'การพิจารณา') ?> </font> </td>
                                <td style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'ผลการพิจารณา') ?> </font> </td>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php
                            if(!empty($searchModel->panel_id)){
                            $submissions = app\models\Submission::find()->joinWith(['project','meetingAgendas'])->isDeleted(false)->fda(1)->dateStatusBetween($searchModel->startDate, $searchModel->endDate, $searchModel->status)->panel($searchModel->panel_id)->hasMeetingAgenda()->orderBy('meeting_agenda.meeting_id desc')->all();
                            }else{
                            $submissions = app\models\Submission::find()->joinWith(['project','meetingAgendas'])->isDeleted(false)->fda(1)->dateStatusBetween($searchModel->startDate, $searchModel->endDate, $searchModel->status)->hasMeetingAgenda()->orderBy('meeting_agenda.meeting_id desc')->all();
                            }
                            foreach ($submissions as $submission):
                                ?>
                                <tr>
                                    <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->meeting->yearNoWithDate ?></td>
                                    <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->project->project_code ?></td>
                                    <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->project->fda_no ?></td>
                                    <td style="border: 1px solid black; width: 3%" class="text-left padding-10"><?= $submission->project->name_thai.'<Br>'.$submission->project->name_eng ?></td>
                                    <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->agenda->name ?></td>
                                    <td style="border: 1px solid black; width: 3%" class="text-center padding-10"><?= $submission->meetingAgenda->resolution ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>

</div>