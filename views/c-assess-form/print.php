<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\CAssessForm;
use yii\helpers\ArrayHelper;
use app\models\ReviewChoice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ContinueAssessForm */
/* @var $form yii\widgets\ActiveForm */

$firstRef = $model->submission->getFirstRefSubmission();
$ref = $model->submission->refSubmission;
$currentRole = \Yii::$app->session->get('currentRole');
?>
<?php
$form = ActiveForm::begin([
            'layout' => 'inline',
            'id' => 'submission-type-assess-form',
        ]);
?>

<div class="border text-center" style="font-size: 20px; font-weight: bold;">
    <?= Yii::t('app', 'แบบประเมินจริยธรรมการวิจัยในมนุษย์สำหรับ') . (isset($firstRef) ? $firstRef->submissionType->name : ""); ?>
</div>
<?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

<div style="font-size: 16px;">
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;" ><?= Yii::t('app', 'เลขที่โครงการ'); ?></span>
    <?= $model->submission->project->project_code ?>
    <span class="font-weight-900"><?=
        isset($ref) ? Yii::t('app', "(ฉบับแก้ไขมติ หลังประชุมครั้งที่ {meetingNo} วาระ {agenda})", [
                    'meetingNo' => $ref->meetingAgenda->meeting->yearNo,
                    'agenda' => $ref->meetingAgenda->fullTitle,
                ]) : ""
        ?></span>
</div>
<div style="font-size: 16px;">
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;"><?= Yii::t('app', 'ชื่อโครงการ'); ?></span> <?= $model->submission->project->name_thai ?>
</div>
<div style="font-size: 16px;">
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการ'); ?></span> <?= $model->submission->projectLeader->person->i18nFullName ?>
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $model->submission->projectLeader->person->divisionName ?>
</div>
<div style="font-size: 16px;">
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;"><?= Yii::t('app', 'ชื่อกรรมการประเมิน'); ?></span> <?= $model->submissionCommittee->person->i18nFullName ?>
    <span class="font-weight-900" style="font-size: 16px; font-weight: bold;"><?= Yii::t('app', 'กำหนดส่งคืน'); ?></span> <?= Yii::$app->formatter->asDate($model->submission->send_plan_date); ?>
</div>
<div style="font-size: 16px; font-weight: bold;">
    <?= Yii::t('app', 'สรุปความเห็นโดยรวม'); ?>
</div>
<div style="font-size: 16px; margin-right: 20px">
    <?php
    foreach (CAssessForm::getOpinion() as $rc):
        if ($model->opinion == $rc) {
            echo '<span style="font-family: fontawesome;" class="fa"> &#xf14a; </span>' . CAssessForm::getOpinionLabels()[$rc]. '<br>';
//            if ($model->opinion == CAssessForm::OPINION_RC) {
                if (isset($model->opinion_remark)) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.Yii::t('app', ' คือ '). $model->opinion_remark . Yii::t('app', ' เพื่อพิจารณา').'<br>';
                }
//            }
        } else {
            echo '<span style="font-family: fontawesome;" class="fa"> &#xf0c8; </span>' . CAssessForm::getOpinionLabels()[$rc] . '<br>';
        }
    endforeach;
    ?>
   </div>

<div style="font-size: 16px; font-weight: bold;">
    <?= Yii::t('app', 'ข้อเสนอแนะอื่นๆ') ?> : 
</div>
<div style="font-size: 16px;">
    <?= $model->suggestion; ?> 
</div>