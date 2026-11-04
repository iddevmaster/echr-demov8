<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', '1.1.รายงานสถิติการดำเนินการอ่านโครงการวิจัยและการเข้าร่วมประชุมของกรรมการแต่ละท่าน');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="committee-review-count-index">
    <?php if ($pdf <> 1): ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10">
                <?= $this->render('_search-committee-review-count', ['searchModel' => $searchModel]) ?>
            </div>
        </div>

        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['report/committee-review-count', 'pdf' => true, 'SubmissionCommitteeSearch[person_id]' => $searchModel->person_id, 'SubmissionCommitteeSearch[startMeetingDate]' => $searchModel->startMeetingDate, 'SubmissionCommitteeSearch[endMeetingDate]' => $searchModel->endMeetingDate], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
    <?php endif; ?>
    <h3 class="panel-title text-center"><?= $this->title ?></h3><br>

    <div class="panel panel-bordered margin-bottom-10">
        <div class="padding-10" style="padding: 10px;">
            <?php if (isset($searchModel->person)): ?>
            <h3 class="card-title">
                <font style="font-weight: bold;" color="#073453;">
                    <?= Yii::t('app', 'กรรมการ') ?> : <?= $searchModel->person->fullName ?>
                    <?= Yii::t('app', 'จากวันที่') ?> : <?= Yii::$app->formatter->asDate($searchModel->startMeetingDate) ?>
                    <?= Yii::t('app', 'ถึงวันที่') ?> : <?= Yii::$app->formatter->asDate($searchModel->endMeetingDate) ?>

                </font>
            </h3>
            <?php endif; ?>
            <div class="card-block">

                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th><?= Yii::t('app', 'ประเภทโครงการที่พิจารณา') ?></th>
                            <th><?= Yii::t('app', 'จำนวน') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $index => $d):
                        ?>
                            <tr>
                                <td><?= $index+1; ?></td>
                                <td><?= $d['name']; ?></td>
                                <td class="text-right"><?= number_format($d['c']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2"><?= Yii::t('app', 'รวม') ?></td>
                            <td class="text-right"><?= number_format(array_sum(array_column($data, 'c'))) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>