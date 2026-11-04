<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Ethics;
use yii\helpers\ArrayHelper;
use app\models\ReviewChoice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ContinueAssessForm */
/* @var $form yii\widgets\ActiveForm */
$reviewChoicesByType = ArrayHelper::index($reviewChoices, null, 'type');

$currentRole = \Yii::$app->session->get('currentRole');
?>
<table class="table table-condensed table-bordered margin-bottom-0" style="margin-bottom: 0;">
    <tbody>
        <tr>
            <td class="text-center"><?= Yii::t('app', 'หมายเลขโครงการ') ?></td>
            <td class="text-center"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการวิจัย') ?></td>
            <td class="text-center"><?= Yii::t('app', 'หน่วยงานที่สังกัด') ?></td>
        </tr>
        <tr>
            <td class="text-center"><?= $model->submission->project->project_code ?></td>
            <td class="text-center"><?= $model->submission->projectLeader->person->i18nFullName ?></td>
            <td class="text-center"><?= $model->submission->projectLeader->person->divisionName ?></td>
        </tr>
    </tbody>
</table>

<div class="bl bb br text-center">
    <?= Yii::t('app', 'ชนิดของรายงานแยกตามชนิดของการพิจารณาของคณะกรรมการ') ?>
</div>

<div class="bl br">
    <div style="width: 49%; float: left" class="br">
        <div style="padding: 2px;">
            <div class="font-weight-900">Expedited Review</div>
            <?php foreach ($reviewChoicesByType[ReviewChoice::TYPE_EXPEDITED] as $rc): ?>
                <div>
                    <span style="font-family: fontawesome;" class="fa"><?= $rc->id == $model->review_choice_id ? "&#xf14a;" : "&#xf0c8;" ?></span> <?= $rc->name ?>
                </div>
            <?php endforeach ?>
            <?php if ($rc->need_text): ?>
                <?php if (empty($model->review_choice_text)): ?>
                    <div class="underline">&nbsp;</div>
                    <div class="underline">&nbsp;</div>
                <?php else: ?>
                    <div class="text-underline"><?= $model->review_choice_text ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <?php foreach ($rc->children as $child): ?>
                <div style="padding-left: 20px">
                    <span style="font-family: fontawesome;" class="fa"><?= in_array($child->id, $model->reviewIds) ? "&#xf14a;" : "&#xf0c8;" ?></span> <?= $child->name ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="width: 50%; float: left">
        <div style="padding: 2px;">
            <div class="font-weight-900">Full Board Review</div>
            <?php foreach ($reviewChoicesByType[ReviewChoice::TYPE_FULL_BOARD] as $rc): ?>
                <div>
                    <span style="font-family: fontawesome;" class="fa"><?= $rc->id == $model->review_choice_id ? "&#xf14a;" : "&#xf0c8;" ?></span> <?= $rc->name ?>
                </div>
            <?php endforeach ?>
            <?php if ($rc->need_text): ?>
                <?php if (empty($model->review_choice_text)): ?>
                    <div class="underline">&nbsp;</div>
                    <div class="underline">&nbsp;</div>
                <?php else: ?>
                    <div class="underline"><?= $model->review_choice_text ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <?php foreach ($rc->children as $child): ?>
                <div style="padding-left: 20px">
                    <span style="font-family: fontawesome;" class="fa"><?= in_array($child->id, $model->reviewIds) ? "&#xf14a;" : "&#xf0c8;" ?></span> <?= $child->name ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<table class="table table-condensed table-bordered" border="1" style="border-collapse: collapse;width: 100%;">
    <tbody>
        <tr>
            <td class="text-center font-weight-900" style="width: 49.1%"><?= Yii::t('app', 'ประเด็นการพิจารณาทางด้านจริยธรรม') ?>
            <td class="text-center font-weight-900" style="width: 12%;"><?= Yii::t('app', 'เหมาะสม') ?>
            <td class="text-center font-weight-900" style="width: 12%;"><?= Yii::t('app', 'ไม่เหมาะสม') ?>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'หมายเหตุ') ?>
        </tr>
        <?php foreach ($conEthicses as $conEthics): ?>
            <tr>
                <td>
                    <?= $conEthics->ethics->name; ?>
                    <?php if ($conEthics->ethics->need_text): ?>
                        <?= $conEthics->other; ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <span style="font-family: fa-solid"><?= !$conEthics->is_appropriate ? "&#xf00c;" : "" ?></span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <span style="font-family: fa-solid"><?= $conEthics->is_appropriate ? "&#xf00c;" : "" ?></span>
                    </div>
                </td>
                <td><?= $conEthics->getAttribute("[{$conEthics->ethics_id}]remark") ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'ข้อคิดเห็นของกรรมการ') ?>
            <td colspan="3" class="text-center font-weight-900"><?= Yii::t('app', 'ข้อเสนอแนะเพิ่มเติม') ?>
        </tr>
        <tr>
            <td>
                <?php foreach ($resolutions as $r): ?>
                    <div>
                        <span style="font-family: fontawesome;" class="fa"><?= $r->id == $model->resolution_id ? "&#xf14a;" : "&#xf0c8;" ?></span> <?= $r->name ?>
                    </div>
                <?php endforeach; ?>
            </td>
            <td colspan="3">
                <?= $model->suggestion ?>
            </td>
        </tr>
    </tbody>
</table>
<table style="width: 30%; margin-left: 70%; margin-top: 100px;">
    <tbody>
        <tr>
            <td class="text-right">ลงชื่อ</td>
            <td>........................................................</td>
            <td></td>
        </tr>
        <tr>
            <td class="text-right">(</td>
            <td>........................................................</td>
            <td>)</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">กรรมการผู้ประเมิน</td>
            <td></td>
        </tr>
        <tr>
            <td class="text-right">วันที่</td>
            <td>........................................................</td>
            <td></td>
        </tr>
    </tbody>
</table>
