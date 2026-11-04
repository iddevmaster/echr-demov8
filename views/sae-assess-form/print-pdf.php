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

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
/* @var $form yii\widgets\ActiveForm */
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
        <?= Yii::t('app', 'แบบประเมินรายงานเหตุการณ์ไม่พึงประสงค์ (Serious Adverse Event: SAE)'); ?>
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
        <br>
        <div class="font-weight-900" style="font-weight: bold;">
            <?= Yii::t('app', 'สรุปผลการประเมินเหตุการณ์ไม่พึงประสงค์ในโครงการวิจัยที่ได้รับการรับรองจากสำนักงานคณะกรรมการจริยธรรมฯเพื่อนำเสนอในที่ประชุมพิจารณาโครงการฯ'); ?>
        </div>
        <div style="padding-left: 50px;"><span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'จำนวนอาสาสมัครเสียชีวิต : '); ?></span> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'dead', 1); ?> <?= Yii::t('app', ' ราย '); ?></div>
        <div style="padding-left: 50px;"><span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'จำนวนอาสาสมัครที่รักษาจนเป็นปกติ : '); ?> </span><?= Yii::t('app', 'ใช่ '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'cured', 1); ?> <?= Yii::t('app', ' ราย '); ?> <?= Yii::t('app', 'ไม่ใช่ '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'cured', 0); ?> <?= Yii::t('app', ' ราย '); ?>  <?= Yii::t('app', 'ไม่ทราบผล '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'cured', 2); ?><?= Yii::t('app', ' ราย '); ?></div>
        <div style="padding-left: 50px;"><span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'สัมพันธ์กับยาวิจัย : '); ?></span> <?= Yii::t('app', 'ใช่ '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'drug', 1); ?> <?= Yii::t('app', ' ราย '); ?> <?= Yii::t('app', 'ไม่ใช่ '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'drug', 0); ?><?= Yii::t('app', ' ราย '); ?> <?= Yii::t('app', 'ไม่ทราบผล '); ?> <?= app\models\SaeVolunteer::getVolunteerCount($model->submission_id, 'drug', 2); ?><?= Yii::t('app', ' ราย '); ?></div>
        <br>
        <?php
        foreach ($saeVolunteer as $sv):
            $saeEthicses = app\models\SaeVolunteerEthics::find()->isDeleted(false)->saeVolunteer($sv->id)->all();
            ?>
            <div>
                <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'หมายเลขอาสาสมัคร : '); ?></span> <?= $sv->volunteer->code; ?> <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', ' ประเภทติดตาม : '); ?></span> <?= $sv->submissionVolunteer->typeLabel; ?>
            </div>
            <div>
                <span class="font-weight-900" style="font-weight: bold; color:blue;"><?= Yii::t('app', 'เสียชีวิตหรือไม่ : '); ?></span><?= isset($sv->dead) ? Yii::$app->util->getYesNoLabels()[$sv->dead] : "" ?>
                <span class="font-weight-900" style="font-weight: bold;color:blue;"><?= Yii::t('app', 'รักษาจนเป็นปกติหรือไม่ : '); ?></span><?= isset($sv->cured) ? Yii::$app->util->getYesNoUnknownLabels()[$sv->cured] : "" ?> 
                <span class="font-weight-900" style="font-weight: bold;color:blue;"><?= Yii::t('app', 'สัมพันธ์กับยาวิจัยหรือไม่ : '); ?></span><?= isset($sv->drug) ? Yii::$app->util->getYesNoUnknownLabels()[$sv->drug] : "" ?>
            </div>
            <table class="table table-bordered table-condensed table-striped">
                <tr style="background-color: #F5F5F5;">
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ประเด็นการพิจารณาทางด้านจริยธรรม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'เหมาะสม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ไม่เหมาะสม') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'ไม่เกี่ยวข้อง') ?>
                    <td class="text-center font-weight-900" style="border: 1px solid black;"><?= Yii::t('app', 'หมายเหตุ') ?>
                </tr>
                <?php foreach ($saeEthicses as $saeEthics): ?>
                    <tr>
                        <td style="border: 1px solid black;">

                            <?= $saeEthics->ethics->name; ?>
                            <?php if ($saeEthics->ethics->need_text): ?>
                                <?= $saeEthics->other; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" style="border: 1px solid black;">
                            <div class="radio-custom radio-primary">
                                <?php
                                if ($saeEthics->is_appropriate == SaeVolunteerEthics::APPROPRIATE) {
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
                                if ($saeEthics->is_appropriate == SaeVolunteerEthics::INAPPROPRIATE) {
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
                                if ($saeEthics->is_appropriate == SaeVolunteerEthics::NOT_INVOLVED) {
                                    echo '/';
                                } else {
                                    '';
                                }
                                ?>


                            </div>
                        </td>
                        <td class="text-center" style="border: 1px solid black;"><span style=" text-align: center;"><?= $saeEthics->remark; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div>
                <?php if (isset($sv->comment)) { ?>
                    <span class="font-weight-900" style="font-weight: bold;"><?= Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม : '); ?></span> <?= $sv->comment; ?> 
                <?php } ?>
            </div>
        <?php endforeach; ?>
        <div style="font-weight: bold;">
            <?= Yii::t('app', 'มติของกรรมการ'); ?> : <?= $model->resolution->name; ?>
        </div>
        <div>
            <?php
            $res = '<br>';
            if ($model->resolution_id == 2) {
                $res .= $model->condition;
                $res .= '<br>';
            } else if ($model->resolution_id == 3) {
                $res .= Yii::t('app', 'ในประเด็น') . '<br>';
                $res .= $model->addition; 
                $res .= '<br>';
            }
            echo $res;
            ?>
        </div>
        <?php if (isset($model->suggestion)): ?>
            <span style="font-weight: bold;"><?= Yii::t('app', 'ข้อเสนอแนะเพิ่มเติม '); ?> : </span> <?= $model->suggestion; ?>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
