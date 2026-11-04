<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="submission-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
    ]);
    ?>

    <?php if ($model->submission->isFromCrec()) { ?>
        <?= $form->field($model, 'can_meeting')->label('ประสงค์เข้าร่วมประชุม CREC หรือไม่ ?')->radioList([0 => Yii::t('app', 'ไม่ประสงค์เข้าร่วมประชุม CREC'), 1 => Yii::t('app', 'ประสงค์เข้าร่วมประชุม CREC')]); ?>
    <?php } else { ?>
        <?= $form->field($model, 'is_meeting')->radioList([2 => Yii::t('app', 'ไม่ต้องเข้าประชุม'), 1 => Yii::t('app', 'ต้องเข้าประชุม')]); ?>
    <?php } ?>
    <?php 
        if(!$model->submission->is_submit_by_api && $model->submission->project->hasCrecNumber() && ($model->submission->submission_type_id == app\models\SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == app\models\SubmissionType::TYPE_DEVIATION)){
    //if (($model->submission->submission_type_id == app\models\SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == app\models\SubmissionType::TYPE_DEVIATION) && $model->submission->project->firstSubmission->submission_type_id == app\models\SubmissionType::TYPE_CREC) { ?>
        <?= $form->field($modelSubmission, 'send_to_crec')->radioList(Yii::$app->util->getYesNoLabels()) ?>
    <?php } ?>
    <?php if (!$model->submission->isFromCrec()) { ?>
        <?=
        $form->field($model, 'resolution')->label(Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ'))->widget(Select2::className(), [
            'data' => $model->submission->resolutionConsiderationLables,
            'options' => ['placeholder' => Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    <?php } else { ?>
        <?=
        $form->field($model, 'resolution')->label(Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ'))->widget(Select2::className(), [
            'data' => $model->submission->resolutionConsiderationLables,
            'options' => ['placeholder' => Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    <?php } ?>
    <?php
    echo $form->field($committeeRevise, 'remark')->widget(CKEditor::className(), [
        'options' => [
            'id' => uniqid(),
        ],
        'editorOptions' => [
            'preset' => 'standard',
            'inline' => false,
            'language' => Yii::$app->language,
        ],
    ]);
    ?>
    <?php ActiveForm::end(); ?>

</div>