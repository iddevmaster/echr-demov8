<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $submission app\models\Submission */
/* @var $index int */

$id = $submission->id;
$project = $submission->project;

// Get result documents for preview
$resultDocs = $submission->getResultDocuments();
?>

<?php // Main row ?>
<tr class="submission-row" data-submission-id="<?= $id ?>">
    <td>
        <strong><?= Html::encode($submission->project->project_code) ?> <?php if (isset($submission->submission_number)) { ?><br>(<?= Html::encode($submission->submission_number) ?>)<?php } ?></strong>
    </td>
    <td>
        <?= Html::encode($submission->submissionTypeName) ?>
    </td>
    <td>
        <?= Html::encode($submission->resolution) ?>
    </td>
    <td style="text-align:center;">
        <?=
        Html::a(
                '<i class="icon wb-eye"></i>',
                ['submission/project-submission', 'submissionId' => $id],
                ['target' => '_blank', 'data-pjax' => 0, 'title' => Yii::t('app', 'ดูรายละเอียดโครงการ')]
        )
        ?>
    </td>
    <td style="text-align:center;">
        <a href="javascript:void(0)" class="btn-toggle-detail" data-target="detail-<?= $id ?>">
            <i class="icon md-chevron-down"></i>
        </a>
    </td>
    <td style="text-align:center;">
        <label class="radio-inline" style="color:#5cb85c;">
            <input type="radio" name="decisions[<?= $id ?>][action]" value="approve" class="decision-radio" data-sid="<?= $id ?>">
            <?= Yii::t('app', 'อนุมัติ') ?>
        </label>


        <label class="radio-inline" style="color:#d9534f;">
            <input type="radio" name="decisions[<?= $id ?>][action]" value="reject" class="decision-radio" data-sid="<?= $id ?>">
            <?= Yii::t('app', 'ไม่') ?>
        </label>
        <label class="radio-inline" style="color:#999;">
            <input type="radio" name="decisions[<?= $id ?>][action]" value="pending" class="decision-radio" data-sid="<?= $id ?>" checked>
            <?= Yii::t('app', 'รอดำเนินการ') ?>
        </label>
    </td>
</tr>

<?php // Detail row (hidden by default)  ?>
<tr id="detail-<?= $id ?>" class="detail-row" style="display:none; background-color:#f9f9f9;">
    <td colspan="6">
        <div style="padding: 10px 15px;">
            <div class="row">
                <div class="col-md-12">
                    <span class="label label-primary">TH</span> <strong><?= Yii::t('app', 'ชื่อไทย') ?>:</strong>
                    <?= Html::encode($project->name_thai ?? '-') ?>
                </div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-md-12">
                    <span class="label label-info">GB</span> <strong>Eng:</strong>
                    <?= Html::encode($project->name_eng ?? '-') ?>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div class="col-md-12">
                    <span class="label label-warning"><?= Yii::t('app', 'รายการหนังสือแจ้งผล') ?></span>
                    <?php
                    $ardProvider = new \yii\data\ArrayDataProvider([
                        'allModels' => $submission->getResultDocuments()
                    ]);
                    echo $this->renderFile('@app/views/submission/letter-result-president.php', [
                        'submission' => $submission,
                        'pjaxId' => 'cer',
                        'dataProvider' => $ardProvider,
                    ]);
                    ?> 
                </div>
            </div>
            <div class="row" style="margin-top: 8px;">
                <div class="col-md-12">
                    <?=
                    Html::a(
                            '<i class="icon md-link"></i> ' . Yii::t('app', 'ไปยังหน้าโครงการ (Go to Project)'),
                            ['submission/project-submission', 'submissionId' => $id],
                            ['target' => '_blank', 'data-pjax' => 0, 'class' => 'text-primary']
                    )
                    ?>
                </div>
            </div>
        </div>
    </td>
</tr>

<?php // Comment row for reject (hidden by default)  ?>
<tr id="comment-<?= $id ?>" class="comment-row" style="display:none; background-color:#fff3f3;">
    <td colspan="6">
        <div style="padding: 10px 15px;">
            <label for="president_comment_<?= $id ?>">
                <span class="text-danger">*</span> <?= Yii::t('app', 'เหตุผลในการส่งคืนแก้ไข') ?>:
            </label>
            <textarea
                name="decisions[<?= $id ?>][president_comment]"
                id="president_comment_<?= $id ?>"
                class="form-control president_comment-input"
                data-sid="<?= $id ?>"
                rows="2"
                placeholder="<?= Yii::t('app', 'กรุณาระบุเหตุผล...') ?>"
                ></textarea>
        </div>
    </td>
</tr>
