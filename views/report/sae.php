<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'รายงาน Sae');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="deviation-index">
    <?php if ($pdf <> 1): ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10">
                <?= $this->render('_search-deviation', ['searchModel' => $searchModel]) ?>
            </div>
        </div>

        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['report/sae', 'isLeader' => $searchModel->is_leader, 'isOngoing' => $searchModel->isOngoing, 'name' => $searchModel->name, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'pdf' => true], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
    <?php endif; ?>
    <h3 class="panel-title text-center"><?= $this->title ?></h3><br>

    <?php
    foreach ($dataProvider->models as $m) :

        $saeIn = \app\models\Submission::find()->isDeleted(false)->submissionType(\app\models\SubmissionType::TYPE_INTERNAL_SAE)->project($m->project_id)->status(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();
        $saeEx = \app\models\Submission::find()->isDeleted(false)->submissionType(\app\models\SubmissionType::TYPE_EXTERNAL_SAE)->project($m->project_id)->status(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();
        $saeIdmc = \app\models\Submission::find()->isDeleted(false)->submissionType(\app\models\SubmissionType::TYPE_IDMC)->project($m->project_id)->status(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();

        $totalSae = $saeIn + $saeEx;

        $saeVols = $m->project->getSaeVolunteersReport();
        $volsByCode = ArrayHelper::index($saeVols, null, 'volunteer.code');
        ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10" style="padding: 10px;">
                <h3 class="card-title"><font style="font-weight: bold;" color="#073453;"><?= Yii::t('app', 'หมายเลขโครงการ : ') . $m->project->project_code ?> <?= Yii::t('app', 'หัวหน้าโครงการ : ') . $m->projectLeader->person->fullName ?> </font></h3>

                <div class="card-block">
                    <h4 class="card-title"> SAE ที่เกิดขึ้นทั้งสิ้น<font style="font-weight: bold;" color="#073453;"> <?= $totalSae ?> </font>รายการ</h4>
                    <ul>Internal <font style="font-weight: bold;" color="#073453;"><?= $saeIn ?></font> รายการ</ul>
                    <ul>External <font style="font-weight: bold;" color="#073453;"><?= $saeEx ?></font> รายการ </ul>
                    <ul>รายงานผลการประเมินจากคณะกรรมการติดตามข้อมูลและความปลอดภัยของโครงการ ( IDMC/DSMB) <font style="font-weight: bold;" color="#073453;"><?= $saeIdmc ?> </font> รายการ</ul>

                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th><?= Yii::t('app', 'เลขที่อาสาสมัคร') ?></th>
                                <th><?= Yii::t('app', 'ประเภทการติดตาม') ?></th>
                                <th><?= Yii::t('app', 'วันที่ยื่นเอกสาร') ?></th>
                                <th><?= Yii::t('app', 'การประชุม') ?></th>
                                <th><?= Yii::t('app', 'เสียชีวิตหรือไม่') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;

                            foreach ($volsByCode as $code => $vols):
                                $volsByType = ArrayHelper::index($vols, null, 'submissionVolunteer.id');
                                $totalVols = count($vols);
                                $count = 0;
                                $i++;
                                foreach ($volsByType as $typeVols):
                                    $typeCount = 0;
                                    $totalTypeVols = count($typeVols);
                                    foreach ($typeVols as $saeVol):
                                        $count++;

                                        $typeCount++;
                                        ?>
                                        <tr>
                                            <?php if ($count == 1): ?>
                                                <td class="text-center" rowspan="<?= $totalVols ?>"><?= $i; ?></td>
                                                <td rowspan="<?= $totalVols ?>"><?= $saeVol->volunteer->code ?></td>
                                            <?php endif; ?>
                                            <?php if ($typeCount == 1): ?>
                                                <td rowspan="<?= $totalTypeVols; ?>"><?= $saeVol->submissionVolunteer->typeLabel ?></td>
                                                <td rowspan="<?= $totalTypeVols; ?>"><?= Yii::$app->formatter->asDate($saeVol->submission->submittedAt) ?></td>
                                                <td rowspan="<?= $totalTypeVols; ?>"><?= $saeVol->submissionVolunteer->agendaTitle ?></td>
                                            <?php endif; ?>
                                            <td class="text-center"><?= $saeVol->dead ? Yii::$app->util->booleanIconFormat($saeVol->dead) : "" ?></td>

                                        </tr>
                                        <?php
                                    endforeach;
                                endforeach;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
