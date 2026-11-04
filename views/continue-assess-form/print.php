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
<h3 class="text-center"><?= $model->submission->submissionType->name ?></h3>
<table class="table table-condensed table-bordered margin-bottom-0" style="margin-bottom: 0;">
    <tbody>
        <tr>
            <td class="text-center" style="background-color: #DCDCDC;"><?= Yii::t('app', 'หมายเลขโครงการ') ?></td>
            <td class="text-center" style="background-color: #DCDCDC;"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการวิจัย') ?></td>
            <td class="text-center" style="background-color: #DCDCDC;"><?= Yii::t('app', 'หน่วยงานที่สังกัด') ?></td>
        </tr>
        <tr>
            <td class="text-center"><?= $model->submission->project->project_code ?></td>
            <td class="text-center"><?= $model->submission->projectLeader->person->i18nFullName ?></td>
            <td class="text-center"><?= $model->submission->projectLeader->person->divisionName ?></td>
        </tr>
    </tbody>
</table>
<table class="table table-condensed table-bordered" border="1" style="border-collapse: collapse;width: 100%;">
    <tbody>
        <tr>
            <td class="text-center font-weight-900" style="width: 49.1%; background-color: #DCDCDC;"><?= Yii::t('app', 'ประเด็นการพิจารณาทางด้านจริยธรรม'); ?></td>
            <td class="text-center font-weight-900" style="width: 12%;background-color: #DCDCDC;"><?= Yii::t('app', 'เหมาะสม'); ?></td>
            <td class="text-center font-weight-900" style="width: 12%;background-color: #DCDCDC;"><?= Yii::t('app', 'ไม่เหมาะสม'); ?></td>
            <td class="text-center font-weight-900" style="width: 12%;background-color: #DCDCDC;"><?= Yii::t('app', 'ไม่เกี่ยวข้อง'); ?></td>
            <td class="text-center font-weight-900" style="background-color: #DCDCDC;"><?= Yii::t('app', 'หมายเหตุ') ?>
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
                        <span style="font-family: fa-solid;"><?php
                            if (isset($conEthics->is_appropriate) && $conEthics->is_appropriate == \app\models\ContinueAssessFormEthics::APPROPRIATE) {
                                echo "&#xf00c;";
                            } else {
                                echo "";
                            }
                            ?></span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <span style="font-family: fa-solid;"><?php
                            if (isset($conEthics->is_appropriate) && $conEthics->is_appropriate == \app\models\ContinueAssessFormEthics::INAPPROPRIATE) {
                                echo "&#xf00c;";
                            } else {
                                echo "";
                            }
                            ?></span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <span style="font-family: fa-solid"><?php
                            if (isset($conEthics->is_appropriate) && $conEthics->is_appropriate == \app\models\ContinueAssessFormEthics::NOT_INVOLVED) {
                                echo "&#xf00c;";
                            } else {
                                echo "";
                            }
                            ?></span>
                    </div>
                </td>
                <td><?= $conEthics->remark ;?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="5" class="text-left font-weight-900"><font style="color: blue"><?= Yii::t('app', 'ข้อสรุปของกรรมการ: ') ;?> </font><?= $model->suggestion ;?></td>
        </tr>
    </tbody>
</table>
<div class="bl bb br text-center">
    <?= Yii::t('app', 'ข้อคิดเห็นของกรรมการ') ?>
</div>

<div class="bl br">
    <div style="width: 100%; float: left" class="br">
        <div style="padding: 2px;">
            <?php foreach ($reviewChoicesByType[$model->submission->submission_type_id] as $rc): ?>
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
    
</div>


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
