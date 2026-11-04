<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', '1.2. สถิติการเข้าประชุมของกรรมการ');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="committee-review-meeting-index">
    <?php if ($pdf <> 1): ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10">
                <?= $this->render('_search-committee-review-meeting', ['searchModel' => $searchModel]) ?>
            </div>
        </div>

        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['report/committee-review-meeting', 'pdf' => true, 'SubmissionCommitteeSearch[person_id]' => $searchModel->person_id, 'SubmissionCommitteeSearch[startMeetingDate]' => $searchModel->startMeetingDate, 'SubmissionCommitteeSearch[endMeetingDate]' => $searchModel->endMeetingDate], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
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
                            <th><?= Yii::t('app', 'วันที่เข้าประชุม') ?></th>
                            <th><?= Yii::t('app', 'คณะกรรมการ') ?></th>
                            <th><?= Yii::t('app', 'ครั้งที่ประชุม') ?></th>
                            <th><?= Yii::t('app', 'เวลาที่เข้าประชุม') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $d):
                        ?>
                            <tr>
                                <td><?= Yii::$app->formatter->asDate($d->start_date) ?? ''; ?></td>
                                <td><?= $d->panel->name ?? ''; ?></td>
                                <td><?= $d->yearNo ?? ''; ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>