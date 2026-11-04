<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\SaeAssessForm;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use app\models\Resolution;
use yii\helpers\Url;
use app\models\SaeVolunteerEthics;
use app\models\ReviewChoice;

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
/* @var $form yii\widgets\ActiveForm */
$reviewChoicesByType = ArrayHelper::index($reviewChoices, null, 'type');

$currentRole = \Yii::$app->session->get('currentRole');
$resolutions = Resolution::find()->isDeleted(false)->orderBy('id')->all();
?>

<div class="sae-assess-form-form padding-15">
    <?php
    $form = ActiveForm::begin([
                'layout' => 'inline',
                'id' => 'submission-type-assess-form',
    ]);
    ?>
    <div class="border text-center" style="font-size: 20px; font-weight: bold;">
        <?= Yii::t('app', 'แบบประเมินต่อเนื่องของโครงการวิจัยที่ผ่านการรับรอง (Deviation)'); ?>
    </div>
    <div style="font-size: 18px;">

        <div>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'เลขที่โครงการ'); ?></span> <?= $model->submission->project->project_code ?>
        </div>
        <div>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'ชื่อโครงการ'); ?></span> <?= $model->submission->project->name_thai ?>
        </div>
        <div>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการ'); ?></span> <?= $model->submission->projectLeader->person->i18nFullName ?>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $model->submission->projectLeader->person->divisionName ?>
        </div>
        <div>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'ชื่อกรรมการประเมิน'); ?></span> <?= $model->submissionCommittee->person->i18nFullName ?>
            <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'กำหนดส่งคืน'); ?></span> <?= Yii::$app->formatter->asDate($model->submission->send_plan_date); ?>
        </div>
        <div class="font-weight-900" style="font-weight: bold;">
            <?= Yii::t('app', 'ชนิดของรายงานแยกตามชนิดของการพิจารณาของคณะกรรมการ : '); ?> <?= $model->reviewChoice->name; ?>
        </div>
        <div class="bl bb br text-left">
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

        <br>
        <?php
        foreach ($deviationEvent as $sv):
            $deviationEthicses = app\models\DeviationEventEthics::find()->isDeleted(false)->deviationEvent($sv->id)->all();
            ?>
        <br>
            <div>
                <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'หมายเลขเหตุการณ์ : '); ?></span> <?= $sv->submissionEvent->event_no; ?> <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', ' รายละเอียดประเภทโครงการวิจัยเบี่ยงเบน : '); ?></span><?= \app\models\DeviationEvent::violationLabels()[$sv->is_major_minor_com]; ?>
            </div>
            <table class="table table-bordered table-condensed table-striped">
                <tr style="background-color: #F5F5F5;">
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ประเด็นการพิจารณาทางด้านจริยธรรม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'เหมาะสม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ไม่เหมาะสม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ไม่เกี่ยวข้อง') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'หมายเหตุ') ?>
                </tr>
                <?php foreach ($deviationEthicses as $deviationEthic): ?>
                    <tr>
                        <td style="border: 1px solid black;">

                            <?= $deviationEthic->ethics->name; ?>
                            <?php if ($deviationEthic->ethics->need_text): ?>
                                <?= $deviationEthic->other; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">
                            <div class="radio-custom radio-primary">
                                <?php
                                if ($deviationEthic->is_appropriate == SaeVolunteerEthics::APPROPRIATE) {
                                    echo '/';
                                } else {
                                    '';
                                }
                                ?>

                            </div>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">
                            <div class="radio-custom radio-primary">
                                <?php
                                if ($deviationEthic->is_appropriate == SaeVolunteerEthics::INAPPROPRIATE) {
                                    echo '/';
                                } else {
                                    '';
                                }
                                ?>

                            </div>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">
                            <div class="radio-custom radio-primary">
                                <?php
                                if ($deviationEthic->is_appropriate == SaeVolunteerEthics::NOT_INVOLVED) {
                                    echo '/';
                                } else {
                                    '';
                                }
                                ?>


                            </div>
                        </td>
                        <td class="text-center" style="border: 1px solid black;"><span style=" text-align: center;"><?= $deviationEthic->remark; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div>
                <?php if (isset($sv->comment)) { ?>
                    <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม : '); ?></span> <?= $sv->comment; ?> 
                <?php } ?>
            </div>
        <?php endforeach; ?>
        <br><br>
        <div style="font-weight: bold;">
            <?= Yii::t('app', 'มติของกรรมการ'); ?> : <?= $model->resolution->getNameBySubmissionType($model->submission->submissionType); ?>
        </div>
        <?php if (isset($model->suggestion)): ?>
            <span style="font-weight: bold;"><?= Yii::t('app', 'ข้อเสนอแนะเพิ่มเติม '); ?> : </span> <?= $model->suggestion; ?>
        <?php endif; ?>
        <div>
            <?php
            $res = '<br>';
            if ($model->resolution_id == Submission::RESOLUTION_C) {
                $res .= $form->field($model, 'condition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                $res .= '<br>';
            } else if ($model->resolution_id == Submission::RESOLUTION_R || $model->resolution_id == Submission::RESOLUTION_N) {
                $res .= Yii::t('app', 'ในประเด็น') . '<br>';
                $res .= $form->field($model, 'addition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                $res .= '<br>';
            }
            echo $res;
            ?>
        </div>
    </div>
</div>
