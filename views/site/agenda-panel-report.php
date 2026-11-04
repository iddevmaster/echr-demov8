<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\assets\TableExportAsset;
use kartik\export\ExportMenu;
TableExportAsset::register($this);

$this->title = Yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = \app\models\Panel::find()->isDeleted(false)->all();

$title = yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ และ Panel ');
if (!empty($searchModel->startDate)) {
    $title .= ' ตั้งแต่ วันที่ ' . Yii::$app->thaiFormatter->asDate($searchModel->startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($searchModel->endDate, 'php:d/m/Y');
}
?>
<div class="site-about">
    <?php
    echo $this->renderFile('@app/views/site/_search-agenda-year.php', ['searchModel' => $searchModel]);
    ?>
    <div class="panel">
        <?= Html::button(Yii::t('app', "EXPORT EXCEL"), ['class' => 'btn btn-success pull-right btn-lg btn-excel margin-10']) ?>
        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['site/agenda-panel-report', 'startDate' => $searchModel->startDate,'endDate'=>$searchModel->endDate,'searchModel'=>$searchModel, 'pdf' => true], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
       
        <div class="panel-header">
            <h3 class="panel-title text-center"><?= $title ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <a id="create-plan-link" class="hidden" role="modal-remote" style="text-decoration: none"></a>
                    <table style="border-collapse: collapse; border: 1px solid black; width: 100%" id="table-statistics-panel" class="table-statistics">
                        <thead>
                            <tr style="background-color: #878787">
                                <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'วาระ') ?> </font> </td>
                                <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'รายละเอียดวาระการประชุม') ?> </font> </td>
                                <?php foreach ($panels as $panel): ?>
                                    <td style="border: 1px solid black; width: 5%" class="text-center"><font style="color: #ffffff">panel <?= $panel->id; ?></font> </td>
                                <?php endforeach; ?>
                                <td style="border: 1px solid black; width: 5%" class="text-center"><font style="color: #ffffff"><?= Yii::t('app', 'รวม') ?></font> </td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($agendas as $agenda):
                                $total = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanelTotal($agenda->id)->dateStatus($searchModel->startDate,$searchModel->endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                ?> 
                                <tr>
                                    <td style="border: 1px solid black; width: 3%" class="text-center"> <?= $agenda->label; ?> </td>
                                    <td style="border: 1px solid black;" class="padding-5"><?= $agenda->name; ?></td>
                                    <?php
                                    foreach ($panels as $panel):
                                        $sm = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanel($agenda->id, $panel->id)->dateStatus($searchModel->startDate,$searchModel->endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                        ?>
                                        <td style="border: 1px solid black;" class="text-center"><?= isset($sm) ? number_format($sm, 0) : "" ?></td>
                                        <?php
                                    endforeach;
                                    ?>
                                    <td style="border: 1px solid black;" class="text-center"><?= isset($total) ? number_format($total, 0) : "" ?> </td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                            ?>
                            <tr style="background-color: #878787">
                                <td style="border: 1px solid black; width: 3%" class="text-center"> </td>
                                <td style="border: 1px solid black;" class="padding-5"><font style="color: #ffffff"><?= Yii::t('app', 'รวม') ?></font></td>
                                <?php
                                foreach ($panels as $panel):
                                    $smp = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanelSum($panel->id)->dateStatus($searchModel->startDate,$searchModel->endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                    $totalp = app\models\Submission::find()->isDeleted(false)->hasMeetingAgenda()->dateStatus($searchModel->startDate,$searchModel->endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                    ?>
                                    <td style="border: 1px solid black;" class="text-center"><font style="color: #ffffff"><?= isset($smp) ? number_format($smp, 0) : "" ?></font></td>
                                    <?php
                                endforeach;
                                ?>
                                <td style="border: 1px solid black;" class="text-center"><font style="color: #ffffff"><?= isset($totalp) ? number_format($totalp, 0) : "" ?></font> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>

</div>
<?php

$js = <<<js

    $('body').on('click', '.btn-excel', function() {
        var ExportButtons = document.getElementById('table-statistics-panel');
        // console.log(ExportButtons);
        var instance = new TableExport(ExportButtons, {
            formats: ['xlsx'],
            exportButtons: false
        });

        var exportData = instance.getExportData()['table-statistics-panel']['xlsx'];
        for (var i = 0; i < exportData.data.length; i++) {
            exportData.data[i][1].t = 's';
            exportData.data[i][2].t = 's';
            exportData.data[i][3].t = 's';
            exportData.data[i][4].t = 's';
            exportData.data[i][5].t = 's';
         }
        instance.export2file(exportData.data, exportData.mimeType, 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระตามpanel', exportData.fileExtension);
        
    });
js;
$this->registerJs($js);