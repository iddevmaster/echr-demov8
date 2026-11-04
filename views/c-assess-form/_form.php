<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\CAssessForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CAssessForm */
/* @var $form yii\widgets\ActiveForm */

$firstRef = $model->submission->getFirstRefSubmission();
$ref = $model->submission->refSubmission;
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="cassess-form-form">

    <?php
    $form = ActiveForm::begin([
                'layout' => 'inline',
                'id' => 'submission-type-assess-form',
                'action' => Url::to(['c-assess-form/create', 'submissionId' => $model->submission_id, 'submissionCommitteeId' => $model->submission_committee_id]),
    ]);
    ?>

    <div class="border text-center">
        <?= Yii::t('app', 'แบบประเมินจริยธรรมการวิจัยในมนุษย์สำหรับ') . (isset($firstRef) ? $firstRef->submissionType->name : ""); ?>
    </div>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'เลขที่โครงการ'); ?></span>
        <?= $model->submission->project->project_code ?>
        <span class="font-weight-900"><?=
            isset($ref) ? Yii::t('app', "(ฉบับแก้ไขมติ หลังประชุมครั้งที่ {meetingNo} วาระ {agenda})", [
                        'meetingNo' => $ref->meetingAgenda->meeting->yearNo,
                        'agenda' => $ref->meetingAgenda->fullTitle,
                    ]) : ""
            ?></span>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ'); ?></span> <?= $model->submission->project->name_thai ?>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการ'); ?></span> <?= $model->submission->projectLeader->person->i18nFullName ?>
        <span class="font-weight-900"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $model->submission->projectLeader->person->divisionName ?>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อกรรมการประเมิน'); ?></span> <?= $model->submissionCommittee->person->i18nFullName ?>
        <span class="font-weight-900"><?= Yii::t('app', 'กำหนดส่งคืน'); ?></span> <?= Yii::$app->formatter->asDate($model->submission->send_plan_date); ?>
    </div>
    <div>
        <?= Yii::t('app', 'สรุปความเห็นโดยรวม'); ?>
    </div>
    <div>
        <?php
        echo $form->field($model, 'opinion', ['options' => ['style' => 'width: 100%;']])->label(false)->radioList(CAssessForm::getOpinionLabels(), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "resolution_id-{$value}";
                $res = '';
                $style = '';
                $res .= Html::tag('div', Html::radio($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "radio-custom radio-primary",
                            'style' => $style,
                ]);
//                $res .= '<br>';
                if ($value == CAssessForm::OPINION_RC) {
                    $res .= Yii::t('app', 'คือ') . '<br>';
                    $res .= $form->field($model, 'opinion_remark', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                    $res .= Yii::t('app', 'เพื่อพิจารณา') . '<br>';
                } else {
                    $res .= '<br>';
                }
                return $res;
            }
        ]);
        ?>
    </div>

    <div>
        <?= Yii::t('app', 'ข้อเสนอแนะอื่นๆ') ?> :
    </div>
    <div>
        <?= $form->field($model, 'suggestion', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]) ?>
    </div>
    <?php if (($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $model->submissionCommittee->status == app\models\SubmissionCommittee::STATUS_ACCEPTED)) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-assess-form-save']) ?>
            <?php if (isset($model->id)): ?>
                <?= Html::a('<i class="icon wb-print" aria-hidden="true"></i> Export PDF', Url::to(['c-assess-form/print', 'id' => $model->id]), ['class' => 'btn btn-default', 'target' => '_blank', 'data-pjax' => 0]); ?>
            <?php endif; ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
