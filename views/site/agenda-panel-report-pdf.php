<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel)');
$this->params['breadcrumbs'][] = $this->title;

$agendas = app\models\Agenda::find()->isDeleted(false)->hasParent()->all();
$panels = \app\models\Panel::find()->isDeleted(false)->all();

$title = yii::t('app', 'สรุปจำนวนโครงการวิจัยที่เสนอขอรับการพิจารณาตามวาระ (Panel) ');

if (!empty($searchModel->startDate)) {
    $title .= ' ตั้งแต่ วันที่ ' . Yii::$app->thaiFormatter->asDate($startDate, 'php:d/m/Y') . yii::t('app', ' ถึง ') . Yii::$app->thaiFormatter->asDate($endDate, 'php:d/m/Y');
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
                                <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'วาระ') ?> </font> </td>
                                <td rowspan="1" style="border: 1px solid black;" class="text-center padding-10"><font style="color: #ffffff"> <?= Yii::t('app', 'รายละเอียดวาระการประชุม') ?> </font> </td>
                                <?php foreach ($panels as $panel): ?>
                                    <td style="border: 1px solid black; width: 5%" class="text-center"><font style="color: #ffffff"><?= $panel->id; ?></font> </td>
                                <?php endforeach; ?>
                                <td style="border: 1px solid black; width: 5%" class="text-center"><font style="color: #ffffff"><?= Yii::t('app', 'รวม') ?></font> </td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($agendas as $agenda):
                                $total = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanelTotal($agenda->id)->dateStatus($startDate,$endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                ?> 
                                <tr>
                                    <td style="border: 1px solid black; width: 3%" class="text-center"> <?= $agenda->label; ?> </td>
                                    <td style="border: 1px solid black;" class="padding-5"><?= $agenda->name; ?></td>
                                    <?php
                                    foreach ($panels as $panel):
                                        $sm = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanel($agenda->id, $panel->id)->dateStatus($startDate,$endDate,  app\models\Submission::STATUS_AGENDA_ADDED)->count();
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
                                    $smp = app\models\Submission::find()->isDeleted(false)->hasMeetingAgendaPanelSum($panel->id)->dateStatus($startDate,$endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
                                    $totalp = app\models\Submission::find()->isDeleted(false)->hasMeetingAgenda()->dateStatus($startDate,$endDate, app\models\Submission::STATUS_AGENDA_ADDED)->count();
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

