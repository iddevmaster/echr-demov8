<?php

use app\models\Submission;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if (!$model->is_submit_by_api && $model->project->hasCrecNumber() && $model->resolution == Submission::RESOLUTION_Y && ($model->submission_type_id == app\models\SubmissionType::TYPE_INTERNAL_SAE || $model->submission_type_id == app\models\SubmissionType::TYPE_DEVIATION)): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'send_to_crec')->radioList(Yii::$app->util->getYesNoLabels()) ?>
        </div>
    <?php else: ?>
        <div>
            <?php echo Yii::t('app', 'ยืนยันการแจ้งการ Upload เอกสาร'); ?>
            <?= $form->field($model, 'send_to_crec')->label(false)->hiddenInput(); ?>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>

</div>