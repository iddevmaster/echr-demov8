<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="submission-index ">
    <div class="table-responsive" >
        <table id="table-statistics" class="table-statistics table table-condensed table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= Yii::t('app', 'เลขที่โครงการ') ?></th>
                    <th><?= Yii::t('app', 'หัวหน้าโครงการ') ?></th>
                    <th><?= Yii::t('app', 'สังกัด') ?></th>
                    <th><?= Yii::t('app', 'แหล่งทุน') ?></th>
                    <th><?= Yii::t('app', 'วาระ') ?></th>
                    <th><?= Yii::t('app', 'วันที่ลงรับเอกสาร') ?></th>
                    <th><?= Yii::t('app', 'วันที่แจ้งเลขที่ HE') ?></th>
                    <th><?= Yii::t('app', 'วันที่เสนอเลขาฯ assign กก') ?></th>
                    <th><?= Yii::t('app', 'วันที่ได้รับชื่อกรรมการ') ?></th>
                    <th><?= Yii::t('app', 'วันที่ส่งประเมิน') ?></th>
                    <th><?= Yii::t('app', 'วันที่รับผลประเมิน') ?></th>
                    <th><?= Yii::t('app', 'ครั้งที่ประชุม') ?></th>
                    <th><?= Yii::t('app', 'วันที่ประชุม') ?></th>
                    <th><?= Yii::t('app', 'วันที่ส่งหนังสือแจ้งผลหลังประชุม') ?></th>
                    <th><?= Yii::t('app', 'มติที่ประชุม') ?></th>
                    <th><?= Yii::t('app', 'วันที่รับรอง') ?></th>
                    <th><?= Yii::t('app', 'วันที่ส่งออกหนังสือรับรอง') ?></th>
                    <th><?= Yii::t('app', 'รายงานความก้าวหน้าทุก') ?></th>
                    <th><?= Yii::t('app', 'จน.วันแจ้งHE') ?></th>
                    <th><?= Yii::t('app', 'จน.วันเข้าประชุม') ?></th>
                    <th><?= Yii::t('app', 'จน.วันแจ้งผลประชุม') ?></th>
                    <th><?= Yii::t('app', 'จน.วัน process') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dataProvider->pagination = false;
                $submissions = $dataProvider->models;
                foreach ($submissions as $index => $submission):
                    ?>
                <tr class="">
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?> text-center"><?= Yii::$app->formatter->asDecimal($index+1) ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= $submission->project->projectCodeWithHistory ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->i18nFullName : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->divisionName : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->project->fundingSource) ? $submission->project->fundingSource->name : "" ?></td>
                    <td class=""><?= isset($submission->meetingAgenda) ? $submission->meetingAgenda->sort_label : "" ?></td>
                    <td class=""><?= isset($submission->submittedAt) ? Yii::$app->formatter->asDate($submission->submittedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($submission->codeGeneratedAt) ? Yii::$app->formatter->asDate($submission->codeGeneratedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($submission->secretarySelectedAt) ? Yii::$app->formatter->asDate($submission->secretarySelectedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($submission->committeeSelectedAt) ? Yii::$app->formatter->asDate($submission->committeeSelectedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= $submission->getCommitteePersonSubmit() ?></td>
                    <td class=""><?= $submission->getCommitteePersonReturn() ?></td>
                    <td class=""><?= isset($submission->meetingAgenda) ? $submission->meetingAgenda->meeting->yearNo : "" ?></td>
                    <td class=""><?= isset($submission->meetingAt) ? Yii::$app->formatter->asDate($submission->meetingAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($submission->currentUploadResultAt) ? Yii::$app->formatter->asDate($submission->currentUploadResultAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= $submission->resolution ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->endorseDate) ? Yii::$app->formatter->asDate($submission->endorseDate, 'php:d/m/Y') : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->yResultAt) ? Yii::$app->formatter->asDate($submission->yResultAt, 'php:d/m/Y') : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= $submission->latestProgressPeriod ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->codeGeneratedDays) ? Yii::$app->formatter->asDecimal($submission->codeGeneratedDays) : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->meetingDays) ? Yii::$app->formatter->asDecimal($submission->meetingDays) : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->resultDays) ? Yii::$app->formatter->asDecimal($submission->resultDays) : "" ?></td>
                    <td rowspan="<?= $submission->totalSubmissionCount ?>" class="<?= $index % 2 == 0 ? 'active' : '' ?>"><?= isset($submission->totalYUploadResultDays) ? Yii::$app->formatter->asDecimal($submission->totalYUploadResultDays) : "" ?></td>
                </tr>
                <?php 
                $nextReSubmission = $submission->nextReSubmission;
                while (isset($nextReSubmission)):
                ?>
                <tr>
                    <td class=""><?= isset($nextReSubmission->meetingAgenda) ? $nextReSubmission->meetingAgenda->sort_label : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->submittedAt) ? Yii::$app->formatter->asDate($nextReSubmission->submittedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->codeGeneratedAt) ? Yii::$app->formatter->asDate($nextReSubmission->codeGeneratedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->secretarySelectedAt) ? Yii::$app->formatter->asDate($nextReSubmission->secretarySelectedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->committeeSelectedAt) ? Yii::$app->formatter->asDate($nextReSubmission->committeeSelectedAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= $nextReSubmission->getCommitteePersonSubmit() ?></td>
                    <td class=""><?= $nextReSubmission->getCommitteePersonReturn() ?></td>
                    <td class=""><?= isset($nextReSubmission->meetingAgenda) ? $nextReSubmission->meetingAgenda->meeting->yearNo : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->meetingAt) ? Yii::$app->formatter->asDate($nextReSubmission->meetingAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= isset($nextReSubmission->currentUploadResultAt) ? Yii::$app->formatter->asDate($nextReSubmission->currentUploadResultAt, 'php:d/m/Y') : "" ?></td>
                    <td class=""><?= $nextReSubmission->resolution ?></td>
                    </tr>
                <?php 
                $nextReSubmission = $nextReSubmission->nextReSubmission;
                endwhile; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
       
    </div>
</div>